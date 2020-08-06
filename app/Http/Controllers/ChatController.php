<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CommonController;

class ChatController extends Controller
{
    var $pusher;
    var $user;
    var $chatChannel;

    const DEFAULT_CHAT_CHANNEL = 'chat';

    public function __construct()
    {
        $this->pusher = App::make('pusher');
        $this->user= (new CommonController)->get_current_user();
        $this->chatChannel = self::DEFAULT_CHAT_CHANNEL;
    }

    public function getIndex()
    {


        return view('chat', ['chatChannel' => $this->chatChannel]);
    }

    public function postMessage(Request $request)
    {
        $message = [
            'text' => e($request->input('chat_text')),
            'username' => $this->user->Name,
            'avatar' => $this->user->Web_Path,
            'timestamp' => (time()*1000)
        ];
        $this->pusher->trigger($this->chatChannel, 'new-message', $message);
    }

    /**
     * Authenticate a subscription request.
     *
     * @param Request $request
     * @return Response
     */
    public function postAuth(Request $request)
    {
        if($this->user)
        {
            // TODO: should check if the $channelName has a 'private-' prefix.
            $channelName = $request->input('channel_name');
            $socketId = $request->input('socket_id');
            $auth = $this->pusher->socket_auth($channelName, $socketId);
            return $auth;
        }
        else
        {
            return (new Response('Not Authorized', 401));
        }
    }
}
