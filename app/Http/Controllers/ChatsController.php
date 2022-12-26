<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class ChatsController extends Controller
{
    //Add the below functions


    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->token);
        $user = $token->tokenable;

//        return response()->json($user);
        $message = Message::create([
            'user_id' => $user->id,
            'message' => $request->message
        ]);
        broadcast(new MessageSent($user, $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}
