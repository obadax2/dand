<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Added for logging
use App\Models\Blog;
use App\Models\PaymentLog;

class PaymentController extends Controller
{
    private $paypalBaseUrl = 'https://api.sandbox.paypal.com';

    private function getAccessToken()
    {
        try {
           $clientId = 'AfckPd40pNpHd7GtPG9tTN_JsTSO_p4ufWbQI4MU3JvmbQRuRbFQsQl3dtZFW5dwmkgFGeV4RwUR80ez'; // Replace with your actual sandbox client id
        $clientSecret = 'EJo7bV6t8IYyd7VkxBu9dqv2S9lE5GtjaUpDg5VrNCd4n6Tdj-6LJUD-VGDX9gTDtSQ5JaHt9CRvqOAx';

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

        Log::info('Fetching order details', ['order_id' => $token]);

        $orderResponse = Http::withToken($accessToken)
            ->withOptions(['verify' => false])
            ->get($this->paypalBaseUrl . "/v2/checkout/orders/{$token}");

        Log::info('Order fetch response', ['status' => $orderResponse->status(), 'body' => $orderResponse->body()]);

        if (!$orderResponse->successful()) {
            Log::error('Failed to fetch order details', ['response' => $orderResponse->body()]);
            return redirect()->route('dashboard')->withErrors('Failed to fetch order details.');
        }

        $orderDetails = $orderResponse->json();
        Log::info('Order details', ['details' => $orderDetails]);

        if ($orderDetails['status'] !== 'APPROVED') {
            Log::warning('Order not approved', ['status' => $orderDetails['status']]);
            return redirect()->route('dashboard')->withErrors('Order not approved.');
        }

        // Check if already captured
        if ($orderDetails['status'] === 'COMPLETED') {
            Log::info('Order already completed, skipping capture');

            $blog = Blog::with('story.user')->findOrFail($blogId);
            PaymentLog::create([
                'user_id' => Auth::id(),
                'amount' => $blog->price,
                'payment_method' => 'PayPal',
                'transaction_id' => $orderDetails['id'],
                'status' => 'completed',
            ]);
            return redirect()->route('dashboard')->with('success', 'Payment already processed.');
        }

        // Proceed to capture
        $captureUrl = $this->paypalBaseUrl . "/v2/checkout/orders/{$token}/capture";
        Log::info('Capturing payment', ['url' => $captureUrl]);

       $captureResponse = Http::withToken($accessToken)
    ->withOptions(['verify' => false])
    ->withHeaders(['Content-Type' => 'application/json'])
    ->post($captureUrl, json_encode([]));

        Log::info('Capture response', ['status' => $captureResponse->status(), 'body' => $captureResponse->body()]);

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
        Log::info('Capture result', ['result' => $captureResult]);

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

        // Save payment
        $blog = Blog::with('story.user')->findOrFail($blogId);
        PaymentLog::create([
            'user_id' => Auth::id(),
            'amount' => $blog->price,
            'payment_method' => 'PayPal',
            'transaction_id' => $captureResult['id'],
            'status' => 'completed',
        ]);
        return redirect()->route('dashboard')->with('success', 'Payment successful!');
    } catch (\Exception $e) {
        Log::error('Exception in execute', ['message' => $e->getMessage()]);
        return redirect()->route('dashboard')->withErrors('An error occurred during payment execution.');
    }
}
    public function cancel()
    {
        return redirect()->route('dashboard')->with('error', 'Payment canceled.');
    }
}