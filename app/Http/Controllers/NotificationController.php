<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        Message::whereHas('ticket', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->where('sender', 'admin')
        ->where('is_read', false)
        ->update(['is_read' => true]);

        return response()->json(['status' => 'success']);
    }
}
