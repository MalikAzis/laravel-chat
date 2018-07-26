<?php

namespace App\Http\Controllers;

use App\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    public function __construct ()
    {
      $this->middleware('auth');
    }

    //show chats
    public function index ()
    {
      return view('chat');
    }

    //fetch all message
    public function fetchMessages()
    {
      return Message::with('user')->get();
    }

    //store message to database
    public function sendMessage(Request $request)
    {
      $user = Auth::user();

      $message = $user->messages()->create([
        'message'=> $request->input('message')
      ]);

      broadcast(new MessageSent($user, $message))->toOthers();

      return ['status' => 'Message Sent!'];
    }
}
