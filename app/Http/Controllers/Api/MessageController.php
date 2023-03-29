<?php

namespace App\Http\Controllers\Api;

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

    public function message_by_user_id($userId, $authUserId)
    {
        $messages = Message::where(function ($q) use ($userId, $authUserId) {
            $q->where('from', $authUserId);
            $q->where('to', $userId);
            // $q->where('type', 0);
        })->orWhere(function ($q) use ($userId, $authUserId) {
            $q->where('from', $userId);
            $q->where('to', $authUserId);
            // $q->where('type', 1);
        })->with('user')->get();

        return $messages;
    }
}
