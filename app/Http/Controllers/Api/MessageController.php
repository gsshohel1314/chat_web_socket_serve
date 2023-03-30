<?php

namespace App\Http\Controllers\Api;

use App\Events\Hello;
use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\AlumniCollection;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    public function userMessage($userId = null, $authUserId = null)
    {
        $user = User::findOrFail($userId);
        $messages = $this->message_by_user_id($userId, $authUserId);
        return response()->json([
            'messages' => $messages,
            'user' => $user,
        ]);
    }


    public function sendMessage(Request $request)
    {
        $messages = Message::create([
            'message' => $request->message,
            'from' => $request->auth_user_id,
            'to' => $request->user_id,
            'type' => 0
        ]);

        $messages = Message::create([
            'message' => $request->message,
            'from' => $request->auth_user_id,
            'to' => $request->user_id,
            'type' => 1
        ]);

        broadcast(new Hello($messages));

        return response()->json($messages, 201);
    }

    public function deleteSingleMessage($messageId = null)
    {
        Message::findOrFail($messageId)->delete();

        return response()->json('deleted', 200);
    }

    public function deleteAllMessage($userId = null, $authUserId = null)
    {
        $messages = $this->message_by_user_id($userId, $authUserId);
        foreach ($messages as $value) {
            Message::findOrFail($value->id)->delete();
        }

        return response()->json('all deleted', 200);
    }

    public function message_by_user_id($userId, $authUserId)
    {
        $messages = Message::where(function ($q) use ($userId, $authUserId) {
            $q->where('from', $authUserId);
            $q->where('to', $userId);
            $q->where('type', 0);
        })->orWhere(function ($q) use ($userId, $authUserId) {
            $q->where('from', $userId);
            $q->where('to', $authUserId);
            $q->where('type', 1);
        })->with('user', 'user.alumni.alumni')->get();

        return $messages;
    }
}

