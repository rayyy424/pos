<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class InventoryController extends Controller {

	public function tresholdnotification()
	{
		$item = DB::table('speedfreakinventory')
		->select('name','machinery_no','qty_balance','balance_treshold')
		->whereRaw('qty_balance < balance_treshold')
		->groupby('speedfreakinventory.Id')
		->get();

		$count = DB::table('speedfreakinventory')
		->select(DB::raw('COUNT(Id) as count'))
		->whereRaw('qty_balance < balance_treshold')
		->first();

		if($item != "" or $item != null)
		{
			$subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',85)
            ->get();

            $emails = array();

            foreach ($subscribers as $subscriber) {
                $NotificationSubject=$subscriber->Notification_Subject;
                if ($subscriber->Company_Email!="")
                {
                    array_push($emails,$subscriber->Company_Email);
                }

                else
                {
                    array_push($emails,$subscriber->Personal_Email);
                }
            }

            // Mail::send('emails.tresholdnotification', ['item' => $item , 'count'=>$count], function($message) use ($emails,$NotificationSubject)
            // {
            //     array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
            //     $emails = array_filter($emails);
            //     $message->to($emails)->subject($NotificationSubject.' [System: TresholdNotification]');
            // });
		}

		return 1;
	}
}
