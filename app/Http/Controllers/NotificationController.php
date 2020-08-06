<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;


class NotificationController extends Controller
{
    public function getIndex()
    {
        return view('notification');
    }

    public function postNotify(Request $request)
    {
      $notifyText = e($request->input('notify_text'));
        // Get Pusher instance from service container
        $pusher = App::make('pusher');
        // Trigger `new-notification` event on `notifications` channel
        $pusher->trigger('notifications', 'new-notification', array('text' => $notifyText));
    }

    public function notificationmaintenance(){

      $me = (new CommonController)->get_current_user();

      $notification = DB::table('notificationtype')
      ->select('notificationtype.Id','notificationtype.Notification_Name' ,'notificationtype.Description','notificationtype.Notification_Subject','notificationtype.Notification_Content', 'users.Name')
      ->leftjoin('notificationsubscriber','notificationsubscriber.NotificationTypeId','=','notificationtype.Id')
      ->leftjoin('users','users.Id','=','notificationsubscriber.UserId')
      ->get();

      $users = DB::table('users')
      ->where('Active', '=','1')
      ->where('Approved', '=','1')
  		->get();

      return view('notificationmaintenance',['me'=>$me, 'notification'=>$notification, 'users'=>$users]);

    }
}
