<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Blog;
use App\Models\PaymentLog;
use App\Models\Story;

class PaymentController extends Controller
{
    private $paypalBaseUrl = 'https://api.sandbox.paypal.com';

    private function getAccessToken()
    {
        try {
            $clientId = 'AdOzBhc5t_QnWkTh1h9svI60vPAH_698nL6IBSWwWoKqdMsWYfTaOXeFdLxPCU3qArUNgJEUV3Yfiyed';
            $clientSecret = 'EEFkktdIE2Uucdj7PtaG_uDdeNH5XH039DZSBENmzRBncCaDeEIAAEAg5Tsor9uHGW6UmVj0SYcj606C';

            Log::info('Requesting PayPal access token...');
            $response = Http::withBasicAuth($clientId, $clientSecret)
                ->asForm()
                ->withOptions(['verify' => false])
                ->post($this->paypalBaseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            Log::info('Access token response', ['status' => $response->status(), 'body' => $response->body()]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            } else {
                Log::error('Failed to get access token', ['response' => $response->body()]);
                abort(500, 'Failed to obtain PayPal access token.');
            }
        } catch (\Exception $e) {
            Log::error('Exception in getAccessToken', ['message' => $e->getMessage()]);
            abort(500, 'Error obtaining PayPal access token.');
        }
    }

    public function createPayment(Request $request)
    {
        try {
            $request->validate(['blog_id' => 'required|exists:blogs,id']);

            $blog = Blog::with('story.user')->findOrFail($request->blog_id);
            $user = Auth::user();

            if ($blog->user_id == $user->id) {
                return redirect()->back()->with('error', 'You cannot purchase your own blog.');
            }

            // ✅ Check for duplicate purchase
            $alreadyPurchased = Story::where('user_id', $user->id)
                ->where('status', 'purchased')
                ->where('title', $blog->story->title)
                ->exists();

            if ($alreadyPurchased) {
                return redirect()->route('dashboard')->withErrors('You have already purchased this story.');
            }

            $amount = $blog->price;
            $orderId = uniqid();

            $accessToken = $this->getAccessToken();

            Log::info('Creating PayPal order', [
                'blog_id' => $blog->id,
                'amount' => $amount,
                'order_id' => $orderId,
            ]);

            $body = [
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.execute'),
                    "cancel_url" => route('paypal.cancel'),
                ],
                "purchase_units" => [
                    [
                        "reference_id" => $orderId,
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($amount, 2, '.', ''),
                            "breakdown" => [
                                "item_total" => [
                                    "currency_code" => "USD",
                                    "value" => number_format($amount, 2, '.', ''),
                                ],
                            ],
                        ],
                        "items" => [
                            [
                                "name" => $blog->story->title,
                                "unit_amount" => [
                                    "currency_code" => "USD",
                                    "value" => number_format($blog->price, 2, '.', ''),
                                ],
                                "quantity" => "1",
                            ],
                        ],
                    ],
                ],
            ];

            $response = Http::withToken($accessToken)
                ->withOptions(['verify' => false])
                ->post($this->paypalBaseUrl . '/v2/checkout/orders', $body);

            Log::info('Create order response', ['status' => $response->status(), 'body' => $response->body()]);

            if ($response->successful()) {
                $order = $response->json();
                $approveLink = collect($order['links'])->firstWhere('rel', 'approve')['href'];

                Session::put('blog_id', $blog->id);
                Session::put('order_id', $order['id']);

                return redirect($approveLink);
            } else {
                Log::error('Error creating order', ['response' => $response->body()]);
                return redirect()->back()->with('error', 'Error creating PayPal order.');
            }
        } catch (\Exception $e) {
            Log::error('Exception in createPayment', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while creating payment.');
        }
    }

    public function execute(Request $request)
    {
        try {
            $token = $request->get('token');
            Log::info('Executing payment', ['token' => $token]);

            if (!$token || !Session::has('blog_id')) {
                Log::warning('Missing token or session data', ['token' => $token, 'session' => Session::all()]);
                return redirect()->route('dashboard')->withErrors('Invalid session or token.');
            }

            $blogId = Session::get('blog_id');
            $accessToken = $this->getAccessToken();

            $orderResponse = Http::withToken($accessToken)
                ->withOptions(['verify' => false])
                ->get($this->paypalBaseUrl . "/v2/checkout/orders/{$token}");

            if (!$orderResponse->successful()) {
                Log::error('Failed to fetch order details', ['response' => $orderResponse->body()]);
                return redirect()->route('dashboard')->withErrors('Failed to fetch order details.');
            }

            $orderDetails = $orderResponse->json();
            if ($orderDetails['status'] !== 'APPROVED') {
                Log::warning('Order not approved', ['status' => $orderDetails['status']]);
                return redirect()->route('dashboard')->withErrors('Order not approved.');
            }

            $captureUrl = $this->paypalBaseUrl . "/v2/checkout/orders/{$token}/capture";

            $captureResponse = Http::withToken($accessToken)
                ->withOptions(['verify' => false])
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Content-Length' => '0',
                ])
                ->post($captureUrl, null);

            if (!$captureResponse->successful()) {
                Log::error('Payment capture failed', ['response' => $captureResponse->body()]);
                PaymentLog::create([
                    'user_id' => Auth::id(),
                    'amount' => $orderDetails['purchase_units'][0]['amount']['value'],
                    'payment_method' => 'PayPal',
                    'transaction_id' => null,
                    'status' => 'failed',
                ]);
                return redirect()->route('dashboard')->withErrors('Payment capture failed.');
            }

            $captureResult = $captureResponse->json();
            if (($captureResult['status'] ?? '') !== 'COMPLETED') {
                Log::warning('Capture not completed', ['status' => $captureResult['status']]);
                PaymentLog::create([
                    'user_id' => Auth::id(),
                    'amount' => $orderDetails['purchase_units'][0]['amount']['value'],
                    'payment_method' => 'PayPal',
                    'transaction_id' => null,
                    'status' => 'failed',
                ]);
                return redirect()->route('dashboard')->withErrors('Payment was not completed.');
            }

            $blog = Blog::with('story.user')->findOrFail($blogId);
            PaymentLog::create([
                'user_id' => Auth::id(),
                'amount' => $blog->price,
                'payment_method' => 'PayPal',
                'transaction_id' => $captureResult['id'],
                'status' => 'completed',
            ]);

            // ✅ Duplicate the story
            $originalStory = $blog->story;

            $copiedStory = $originalStory->replicate();
            $copiedStory->user_id = Auth::id();
            $copiedStory->status = 'purchased';
            $copiedStory->save();

            Log::info('Story copied for user', [
                'original_story_id' => $originalStory->id,
                'new_story_id' => $copiedStory->id,
                'buyer_id' => Auth::id()
            ]);

            return redirect()->route('dashboard')->with('success', 'Payment successful! The story has been saved to your library.');
        } catch (\Exception $e) {
            Log::error('Exception in execute', ['message' => $e->getMessage()]);
            return redirect()->route('dashboard')->withErrors('An error occurred during payment execution.');
        }
    }

    public function cancel()
    {
        return redirect()->route('dashboard')->with('error', 'Payment canceled.');
    }
public function checkoutAndExecuteCart(Request $request)
{
    try {
        $token = $request->get('token');

        if (!$token) {
            // Step 1: Create the PayPal order

            $user = Auth::user();
            $cartItems = $user->cartItems()->with('item.story')->get();

            if ($cartItems->isEmpty()) {
                dd('3: Cart is empty');
            }

            $items = [];
            $total = 0;

            foreach ($cartItems as $item) {
                $blog = $item->item;

                if (!$blog) {
                    Log::warning("Cart item ID {$item->id} has no associated blog.");
                    continue;
                }

                $alreadyPurchased = Story::where('user_id', $user->id)
                    ->where('status', 'purchased')
                    ->where('title', $blog->story->title)
                    ->exists();

                if ($alreadyPurchased) {
                    dd("5: Already purchased - {$blog->story->title}");
                }

                $items[] = [
                    'name' => $blog->story->title,
                    'unit_amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($blog->price, 2, '.', ''),
                    ],
                    'quantity' => (string) $item->quantity,
                ];

                $total += $blog->price * $item->quantity;
            }

            if ($total <= 0) {
                dd('6: Total amount is zero or invalid.');
            }

            $accessToken = $this->getAccessToken();

            $orderId = uniqid();
            $body = [
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.cart.execute'),
                    "cancel_url" => route('paypal.cancel'),
                ],
                "purchase_units" => [
                    [
                        "reference_id" => $orderId,
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($total, 2, '.', ''),
                            "breakdown" => [
                                "item_total" => [
                                    "currency_code" => "USD",
                                    "value" => number_format($total, 2, '.', ''),
                                ],
                            ],
                        ],
                        "items" => $items,
                    ],
                ],
            ];

            $response = Http::withToken($accessToken)
                ->withOptions(['verify' => false])
                ->post($this->paypalBaseUrl . '/v2/checkout/orders', $body);

            if (!$response->successful()) {
                dd('7: PayPal order failed', $response->body());
            }

            $order = $response->json();
            $approveLink = collect($order['links'])->firstWhere('rel', 'approve')['href'] ?? null;

            if (!$approveLink) {
                dd('8: Approval link not found', $order);
            }

            Session::put('cart_checkout', true);
            Session::put('order_id', $order['id']);

            return redirect($approveLink);
        }

        // Step 2: Execute the order after returning from PayPal

        if (!Session::has('cart_checkout')) {
            dd('10: Session lost');
        }

        $user = Auth::user();
        $cartItems = $user->cartItems()->with('item.story')->get();

        $accessToken = $this->getAccessToken();

        $orderResponse = Http::withToken($accessToken)
            ->withOptions(['verify' => false])
            ->get($this->paypalBaseUrl . "/v2/checkout/orders/{$token}");

        if (!$orderResponse->successful()) {
            dd('12: Order fetch failed', $orderResponse->body());
        }

        $orderDetails = $orderResponse->json();
        if ($orderDetails['status'] !== 'APPROVED') {
            dd('13: Order not approved', $orderDetails);
        }

      $captureResponse = Http::withToken($accessToken)
    ->withOptions(['verify' => false])
    ->withHeaders([
        'Content-Type' => 'application/json',
    ])
    ->send('POST', $this->paypalBaseUrl . "/v2/checkout/orders/{$token}/capture", [
        'body' => '{}',
    ]);
        if (!$captureResponse->successful()) {
            dd('14: Capture failed', $captureResponse->body());
        }

        $captureResult = $captureResponse->json();
        if (($captureResult['status'] ?? '') !== 'COMPLETED') {
            dd('15: Capture not completed', $captureResult);
        }

    foreach ($cartItems as $item) {
    $blog = $item->item;
    $originalStory = $blog->story;

    // Copy the story for the user
    $copiedStory = $originalStory->replicate();
    $copiedStory->user_id = $user->id;
    $copiedStory->status = 'purchased';
    $copiedStory->save();

    // Avoid duplicate logs
    $existingPayment = PaymentLog::where('transaction_id', $captureResult['id'])->first();
    if (!$existingPayment) {
        PaymentLog::create([
            'user_id' => $user->id,
            'amount' => $blog->price,
            'payment_method' => 'PayPal',
            'transaction_id' => $captureResult['id'],
            'status' => 'completed',
        ]);
    }
}

        $user->cartItems()->delete();
        Session::forget(['cart_checkout', 'order_id']);

        return redirect()->route('dashboard')->with('success', 'Cart purchase successful!');
    } catch (\Exception $e) {
        dd('17: Exception occurred', $e->getMessage());
    }
}



}
