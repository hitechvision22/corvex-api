<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function getConversations(Request $request)
    {
        $conversations = Conversation::with('sender', 'reservation', 'user1', 'user2','trajet')->where('user1_id', Auth::user()->id)
            ->orWhere('user2_id', Auth::user()->id)
            ->get();

        return response()->json($conversations);
    }



    public function getConversationMessages(Request $request, $reservationId)
    {
        $conversation = Conversation::where("reservation_id",$reservationId)->first();

        if ($conversation && ($conversation->user1_id === Auth::user()->id || $conversation->user2_id === Auth::user()->id)) {
            $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();

            return response()->json(['messages' => $messages]);
        }

        return response()->json(['error' => 'Conversation not found or not authorized to access'], 404);
    }

    public function sendMessage(Request $request, $conversationId)
    {
        $conversation = Conversation::find($conversationId);
        if ($conversation && ($conversation->user1_id === Auth::user()->id || $conversation->user2_id === Auth::user()->id)) {
            $message = Message::create([
                'conversation_id' => $conversationId,
                'sender_id' => Auth::user()->id,
                'recipient_id' => $request->recipient_id,
                'message_text' => $request->message,
            ]);

            Conversation::find($conversationId)->update([
                'last_message' => $request->message,
                'sender_id'=>Auth::user()->id,
                'created_at'=> Carbon::now(),
            ]);

            return response()->json([$message,$conversation]);
        }

        return response()->json(['error' => 'Conversation not found or not authorized to send messages'], 404);
    }

    // marquer comme lu
    public function markMessageAsRead(Request $request, $messageId)
    {
        $message = Message::find($messageId);
        if ($message && $message->recipient_id === Auth::user()->id && !$message->read_at) {
            $message->read_at = Carbon::now();
            $message->update();
            Conversation::find($message->conversation_id)->update(['read_at', Carbon::now()]);

            return response()->json(['message' => $message]);
        }

        return response()->json(['error' => 'Message not found or already read'], 404);
    }

    public function deleteMessage($messageId)
    {
        $message = Message::find($messageId);
        if ($message && ($message->sender_id === Auth::user()->id || $message->recipient_id === Auth::user()->id)) {
            $message->message_text = 'message supprime';
            $message->update();

            $conversation = Conversation::find($message->conversation_id);
            $message = array_slice($conversation->messages()->orderBy('created_at', 'asc')->get(), -2, 1);
            $conversation->last_message = $message->message_text;
            $conversation->update();

            return response()->json(['message' => 'Message deleted']);
        }

        return response()->json(['error' => 'Message not found or not authorized to delete'], 404);
    }


    public function GetConversationAdmin($reserId)
    {
        $conversation = Conversation::with('messages','trajet')->where('reservation_id',$reserId)->get();
        return response()->json($conversation);
    }
}
