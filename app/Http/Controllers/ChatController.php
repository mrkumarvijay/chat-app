<?php

namespace App\Http\Controllers;

use App\Events\MessageNotification;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Get all users except the authenticated user
     */
    public function users(): JsonResponse
    {
        $users = User::where('id', '!=', Auth::id())
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    /**
     * Get conversation between authenticated user and another user
     */
    public function messages(User $user): JsonResponse
    {
        $messages = Message::where(function ($query) use ($user) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', Auth::id());
            })
            ->with(['sender:id,name', 'receiver:id,name'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('receiver_id', Auth::id())
            ->where('sender_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    /**
     * Send a new message
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        // Broadcast the message to the receiver
        event(new MessageSent($message));

        // Send notification to receiver (for unread count)
        event(new MessageNotification([
            'sender_name' => Auth::user()->name,
            'message_snippet' => substr($request->message, 0, 30) . (strlen($request->message) > 30 ? '...' : ''),
            'conversation_id' => Auth::id(),
            'receiver_id' => $request->receiver_id
        ]));

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message->load(['sender:id,name', 'receiver:id,name'])
        ], 201);
    }
}
