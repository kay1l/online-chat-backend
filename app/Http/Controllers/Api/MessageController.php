<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Get chat history between auth user and contact
    public function index($contact_id)
    {
        $user = Auth::user();

        $messages = Message::where(function ($query) use ($user, $contact_id) {
                $query->where('sender_id', $user->id)->where('receiver_id', $contact_id);
            })->orWhere(function ($query) use ($user, $contact_id) {
                $query->where('sender_id', $contact_id)->where('receiver_id', $user->id);
            })
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }

    // Send a new message
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id|not_in:' . Auth::id(),
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'content' => $validated['content'],
            'is_read' => false,
        ]);

        return response()->json($message);
    }
    public function markAsRead($id)
    {
        $message = Message::where('id', $id)
            ->where('receiver_id', Auth::id())
            ->firstOrFail();

        $message->read_at = now();
        $message->save();

        return response()->json(['message' => 'Message marked as read']);
    }
}
