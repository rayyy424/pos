<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Mail;


class AssetController extends Controller {

	// public function __construct()
	// {
	// 	$this->middleware('auth');
	// }

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */


	public function index($type)
	{
  		$auth = JWTAuth::parseToken()->authenticate();

	    // $assets = DB::table('assets')
	    // ->select('assettrackings.AssetId','assets.Item_Name','assets.Availability','assets.Description','assets.Serial_No','assets.Model_No','assets.Car_No','assets.Software_License','assets.Rental_Company','assets.Remarks','assets.Ownership','assets.Rental_Start_Date','assets.Rental_End_Date')
	    // ->orderBy('assets.Item_Name','asc')
	    // ->get();

		$assettrackings = DB::table('assets')
		->select('assets.Id','assets.Label','assets.Type','assets.Serial_No','assets.IMEI','assets.Brand','assets.Model_No','assets.Car_No','assets.Replacement_Car_No','assets.Color','assets.Availability','projects.Project_Name','users.Name as Holder','transfer.Name as Transfer_To','assettrackings.Transfer_Date_Time','assettrackings.Acknowledge_Date_Time','assets.Ownership','assets.Rental_Company','assets.Rental_Start_Date','assets.Asset_Type','assets.Rental_End_Date','assets.Remarks','assets.Extra_Detail_1','assets.Extra_Detail_2','assets.Extra_Detail_3','assets.Extra_Detail_4','assets.Extra_Detail_5','files.Web_Path','assets.Supplier_Name',DB::raw('Format((assets.Price),2) as Price'), 'assets.Description','assets.Date_of_Purchase','assets.Kitchen_Appliances','assets.Location','assets.Company','assets.Rental_Fees','assets.Registered_Fees','assets.Agreenment_Start_Date','assets.Agreenment_End_Date','assets.Termination_of_Agreenment','assets.Asset_Listed','assets.Rental_Date','assets.Rental_Deposit','assets.Service_Provided','assets.APA_Registration_No','assets.Expired_Date','assets.Quantity','assets.Contact_No')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Asset" Group By TargetId) as max2'), 'max2.TargetId', '=', 'assets.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Asset"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,AssetId from assettrackings Group By AssetId) as max'), 'max.AssetId', '=', 'assets.Id')
		->leftJoin('assettrackings', 'assettrackings.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('projects', 'assettrackings.ProjectId', '=', 'projects.Id')
		->leftJoin('users', 'assettrackings.UserId', '=', 'users.Id')
		->leftJoin(DB::raw('users as transfer'), 'assettrackings.Transfer_To', '=', 'transfer.Id')
		->orderBy('assets.Label','asc')
		->where('assets.Type', '=',$type)
		->get();

	    $projects = DB::table('projects')
	    ->get();

	    $users = DB::table('users')
	    ->get();

		$usersasset = DB::table('users')
		->select('users.Id','users.Status','users.Name',DB::raw('Count(DISTINCT assettrackings.AssetId) as Total'))
		->leftJoin('assettrackings', function($join)
		{
			$join->on('assettrackings.UserId', '=', 'users.Id');
			$join->on('assettrackings.Id','in',DB::raw('(select Max(Id) from assettrackings where Status in ("Taken","Returned")Group By AssetId)'));
			$join->on('assettrackings.Status','=',DB::raw('"Taken"'));
		})
		->orderBy('users.Name','asc')
		->groupBy('users.Name')
	    ->get();

    	$category=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','assets')
		->where('options.Field', '=','Type')
		->orderBy('options.Option')
		->get();

	    $options= DB::table('options')
	    ->whereIn('Table', ["assets"])
	    ->orderBy('Table','asc')
	    ->orderBy('Option','asc')
	    ->get();

		$company=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','users')
		->where('options.Field', '=','Company')
		->get();

		$departments = DB::table('projects')
		->where('projects.Project_Name','like','%Department%')
    	->get();

		$items = DB::table('options')
		->distinct('options.Option')
		->select('options.Option', 'options.Field')
		->where('options.Table', '=','assets')
		->orderBy('options.Option')
		->get();

    	return json_encode(['me' => $me,'type' =>$type,'projects' =>$projects, 'users' =>$users,'usersasset' =>$usersasset, 'assettrackings' =>$assettrackings,'category' =>$category,'options' =>$options,'departments'=>$departments,'company'=>$company, 'items'=>$items]);
	}

	public function assign(Request $request)
	{

	    $me = JWTAuth::parseToken()->authenticate();

		$input = $request->all();
		$emaillist=array();
		array_push($emaillist,$me->UserId);
		array_push($emaillist,$input["UserId"]);

		$notify = DB::table('users')
		->whereIn('Id', $emaillist)
		->get();

		DB::table('assettrackings')->insert([
			'AssetId' => $input["AssetId"],
			'UserId' => $input["UserId"],
			'Date' => $input["Date"],
			'ProjectId' => $input["ProjectId"],
			'Status' => 'Taken'
		]);

		DB::table('assets')
		->where('Id', $input["AssetId"])
		->update(array(
			'Availability' =>  'No'
		));

		$emails = array();

		foreach ($notify as $user) {
			if ($user->Company_Email!="")
			{
				array_push($emails,$user->Company_Email);
			}

			else
			{
				array_push($emails,$user->Personal_Email);
			}
		}

		// Mail::send('emails.accountapproved', ['me'=>$me,'user'=>$approveduser], function($message) use ($emails)
		// {
		// 		$message->to($emails)->subject('Your Account Approved.');
		// });

		return 1;
	}

	public function transfer(Request $request)
	{

	    $me = JWTAuth::parseToken()->authenticate();

		$input = $request->all();
		$emaillist=array();
		array_push($emaillist,$me->UserId);
		array_push($emaillist,$input["UserId"]);

		$notify = DB::table('users')
		->whereIn('Id', $emaillist)
		->get();

		// DB::table('assettrackings')->insert([
		// 	'AssetId' => $input["AssetId"],
		// 	'UserId' => $input["UserId"],
		// 	'Date' => $input["Date"],
		// 	'ProjectId' => $input["ProjectId"],
		// 	'Status' => 'Taken'
		// ]);

		DB::table('assettrackings')
			->where('Id', $input["TrackingId"])
			->update(array(
			'Transfer_To' =>  $input["UserId"],
			'Transfer_Date_Time' =>  DB::raw('now()'),
		));

		$emails = array();

		foreach ($notify as $user) {
			if ($user->Company_Email!="")
			{
				array_push($emails,$user->Company_Email);
			}

			else
			{
				array_push($emails,$user->Personal_Email);
			}
		}

		// Mail::send('emails.accountapproved', ['me'=>$me,'user'=>$approveduser], function($message) use ($emails)
		// {
		// 		$message->to($emails)->subject('Your Account Approved.');
		// });

		return 1;
	}


	public function report(Request $request)
	{

    	$me = JWTAuth::parseToken()->authenticate();
		
		$input = $request->all();

		$subscribers = DB::table('notificationsubscriber')
		->leftJoin('notificationtype','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('NotificationTypeId','=',43)
		->get();

		$report = DB::table('assettrackings')
		->select('assets.Label','assets.Type','assets.Serial_No','assets.IMEI','assets.Brand','assets.Model_No','assets.Car_No','projects.Project_Name','users.Name As Holder','assettrackings.Issue','assettrackings.Replacement')
		->leftJoin('assets','assettrackings.AssetId','=','assets.Id')
		->leftJoin('projects','projects.Id','=','assettrackings.ProjectId')
		->leftJoin('users','users.Id','=','assettrackings.UserId')
		->where('assettrackings.Id','=',$input["TrackingId"])
		->get();

		$emails = array();


		$NotificationSubject="";

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

		$emaillist=array();
		array_push($emaillist,$me->UserId);

		$notify = DB::table('users')
		->whereIn('Id', $emaillist)
		->get();

		// DB::table('assettrackings')->insert(
			// 'AssetId' => $input["AssetId"],
			// 'UserId' => $input["UserId"],
			// 'Date' => $input["Date"],
			// 'ProjectId' => $input["ProjectId"],
			// 'Status' => 'Taken'
		// ]);

		DB::table('assettrackings')
		->where('Id', $input["TrackingId"])
		->update(array(
			'Issue' =>  $input["Issue"],
			'Replacement' =>  $input["Replacement"],
		));

		foreach ($notify as $user) {
			if ($user->Company_Email!="")
			{
				array_push($emails,$user->Company_Email);
			}

			else
			{
				array_push($emails,$user->Personal_Email);
			}
		}

		// Mail::send('emails.assetissuereport', ['me'=>$me,'report'=>$report], function($message) use ($emails,$NotificationSubject)
		// {
		// 	$emails = array_filter($emails);
		// 	array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
		// 	$message->to($emails)->subject($NotificationSubject);
		// });

		return 1;
	}


	public function returned(Request $request)
	{

		$me = JWTAuth::parseToken()->authenticate();

		$input = $request->all();
		$emaillist=array();
		array_push($emaillist,$me->UserId);

		$notify = DB::table('users')
		->whereIn('Id', $emaillist)
		->get();

		DB::table('assettrackings')->insert(
		['AssetId' => $input["AssetId"],
		'UserId' => $input["UserId"],
		'ProjectId' => $input["ProjectId"],
		'Date' => Date("d-M-Y"),
		'Status' => "Returned"
		]);

		DB::table('assettrackings')->insert(
			['AssetId' => $input["AssetId"],
			'UserId' => 0,
			'ProjectId' => 0
		]);

		// DB::table('assettrackings')
		// 	->where('Id', $input["TrackingId"])
		// 	->update(array(
		// 	'Status' =>  'Returned'
		// ));

		DB::table('assets')
			->where('Id', $input["AssetId"])
			->update(array(
			'Availability' =>  'Yes'
		));

		$emails = array();

		foreach ($notify as $user) {
			if ($user->Company_Email!="")
			{
				array_push($emails,$user->Company_Email);
			}

			else
			{
				array_push($emails,$user->Personal_Email);
			}
		}

		// Mail::send('emails.accountapproved', ['me'=>$me,'user'=>$approveduser], function($message) use ($emails)
		// {
		// 		$message->to($emails)->subject('Your Account Approved.');
		// });

		return 1;
	}

	public function acknowledge(Request $request)
	{

    	$me = JWTAuth::parseToken()->authenticate();

		$input = $request->all();
		$emaillist=array();
		array_push($emaillist,$me->UserId);

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',42)
		->get();

		$notify = DB::table('users')
		->whereIn('Id', $emaillist)
		->get();

		$assettrackings = DB::table('assets')
		->select('assets.Label','assets.Type','assets.Serial_No','assets.IMEI','assets.Brand','assets.Model_No','assets.Car_No','projects.Project_Name','users.Name as Holder','transfer.Name as Transfer_To','assettrackings.Transfer_Date_Time')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Asset" Group By TargetId) as max2'), 'max2.TargetId', '=', 'assets.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Asset"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,AssetId from assettrackings Group By AssetId) as max'), 'max.AssetId', '=', 'assets.Id')
		->leftJoin('assettrackings', 'assettrackings.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('projects', 'assettrackings.ProjectId', '=', 'projects.Id')
		->leftJoin('users', 'assettrackings.UserId', '=', 'users.Id')
		->leftJoin(DB::raw('users as transfer'), 'assettrackings.Transfer_To', '=', 'transfer.Id')
		->orderBy('assets.Label','asc')
		->where('assets.Id', '=',$input["AssetId"])
		->first();

		DB::table('assettrackings')->insert([
			'AssetId' => $input["AssetId"],
			'UserId' => $input["UserId"],
			'Date' => Date("d-M-Y"),
			'Status' => "Taken"
		]);

		DB::table('assettrackings')
		->where('Id', $input["TrackingId"])
		->update(array(
			'Acknowledge_Date_Time' =>  DB::raw('now()')
		));

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

		foreach ($notify as $user) {
			if ($user->Company_Email!="")
			{
				array_push($emails,$user->Company_Email);
			}

			else
			{
				array_push($emails,$user->Personal_Email);
			}
		}

		// Mail::send('emails.assetacknowledgement', ['me'=>$me,'assettrackings'=>$assettrackings], function($message) use ($emails)
		// {
		// 		$message->to($emails)->subject('Asset Acknowledged');
		// });

		return 1;
	}


	public function updatecarno(Request $request)
	{

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		DB::table('assettrackings')->insert(
		['AssetId' => $input["AssetId"],
		'Date' => Date("d-M-Y"),
		'UserId' => $me->UserId,
		'Car_No' => $input["Car_No"],
		'Status' => "Change Car"
		]);

		return 1;
	}


	public function history(Request $request)
	{

		$input = $request->all();

		if ($input["Type"]=="Car")
		{
			$assettrackings = DB::table('assettrackings')
			->select('assettrackings.Date','projects.Project_Name','users.Name','assettrackings.Car_No','assettrackings.Status')
			->leftJoin('projects', 'assettrackings.ProjectId', '=', 'projects.Id')
			->leftJoin('users', 'assettrackings.UserId', '=', 'users.Id')
			->orderBy('assettrackings.Id','Desc')
			->where('assettrackings.AssetId', '=', $input["AssetId"])
			->get();
		}

		else 
		{
			$assettrackings = DB::table('assettrackings')
			->select('assettrackings.Date','projects.Project_Name','users.Name','assettrackings.Status')
			->leftJoin('projects', 'assettrackings.ProjectId', '=', 'projects.Id')
			->leftJoin('users', 'assettrackings.UserId', '=', 'users.Id')
			->orderBy('assettrackings.Id','Desc')
			->where('assettrackings.AssetId', '=', $input["AssetId"])
			->get();
		}


		return json_encode($assettrackings);
	}


	public function assethistory($type)
	{

    	$me = JWTAuth::parseToken()->authenticate();

		$history = DB::table('assettrackings')
		->select('assettrackings.Date','assets.Type','assets.Rental_End_Date','users.Name','assets.Label','assets.Serial_No','projects.Project_Name','assettrackings.Status')
		->leftJoin('assets', 'assets.Id', '=', 'assettrackings.AssetId')
		->leftJoin('projects', 'assettrackings.ProjectId', '=', 'projects.Id')
		->leftJoin('users', 'assettrackings.UserId', '=', 'users.Id')
		->leftJoin(DB::raw('users as transfer'), 'assettrackings.Transfer_To', '=', 'transfer.Id')
		->orderBy('assets.Type','asc')
		->orderBy('assets.Label','asc')
		->orderBy('assettrackings.Date','Desc')
		->where('assets.Type', '=',$type)
		->where('assettrackings.Date', '!=','')
		->get();

		return json_encode(['me' => $me, 'history'=>$history]);

	}


	public function assetlist(Request $request)
	{

		$input = $request->all();

		$assetlist = DB::table('assets')
		->select('assets.Id','assets.Label','assets.Type','assets.Serial_No','assets.IMEI','assets.Brand','assets.Model_No','assets.Car_No',
		'assets.Color','assets.Availability')
		->leftJoin( DB::raw('(select Max(Id) as maxid,AssetId from assettrackings Group By AssetId) as max'), 'max.AssetId', '=', 'assets.Id')
		->leftJoin('assettrackings', 'assettrackings.Id', '=', DB::raw('max.`maxid`'))
		->where('assettrackings.UserId', '=', $input["Id"])
		->get();

		//dd($assetlist);

		return json_encode($assetlist);

	}


	public function rentalNotify()
	{

		$notifydate = date('d-M-Y', strtotime('today'. " -7 days"));

		$endNotify = DB::table('assets')
		->select('assets.Type','assets.Rental_Start_Date','assets.Rental_End_Date','users.Name as Holder', 'users.Company_Email', 'users.Personal_Email','assets.Label','assets.Type','assets.Serial_No','assets.IMEI','assets.Brand','assets.Model_No','assets.Car_No','assets.Color')
		->leftJoin( DB::raw('(select Max(Id) as maxid,AssetId from assettrackings Group By AssetId) as max'), 'max.AssetId', '=', 'assets.Id')
		->leftJoin('assettrackings', 'assettrackings.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('projects', 'assettrackings.ProjectId', '=', 'projects.Id')
		->leftJoin('users', 'assettrackings.UserId', '=', 'users.Id')
		->leftJoin(DB::raw('users as transfer'), 'assettrackings.Transfer_To', '=', 'transfer.Id')
		->orderBy('assets.Label','asc')
		->where('assets.Rental_End_Date', '=', $notifydate)
		->get();

		if ($endNotify)
		{

			$subscribers = DB::table('notificationtype')
			->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
			->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
			->where('notificationtype.Id','=',20)
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

			foreach ($endNotify as $notify) {
				$rentalEndDate = $notify->Rental_End_Date;
				$notifyType = $notify->Type;

				if ($notify->Company_Email!="")
				{
					if (filter_var($notify->Company_Email, FILTER_VALIDATE_EMAIL)) {
						array_push($emails,$notify->Company_Email);
					}
				}
				else
				{
					if (filter_var($notify->Personal_Email, FILTER_VALIDATE_EMAIL)) {
						array_push($emails,$notify->Personal_Email);
					}
				}
			}

				// Mail::send('emails.rentalNotification', ['endNotify'=>$endNotify], function($message) use ($emails, $rentalEndDate,$notifyType,$NotificationSubject)
				// {
				// 		$emails = array_filter($emails);
				// 		array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
				// 		$message->to($emails)->subject($NotificationSubject.' '.$rentalEndDate. '! ['.$notifyType.']');

				// });
				return 1;
		}
		else {
			return 0;
		}
	}


	public function phonebilltracker($type,$start = null, $end = null)
	{

   		$me = JWTAuth::parseToken()->authenticate();

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of previous month'));

		}

		if ($end==null)
		{
			// $end=date('d-M-Y', strtotime('today'));
			// $end=date('d-M-Y', strtotime($end . " +1 days"));
			$end=date('d-M-Y', strtotime('last day of this month'));
		}

		$list = DB::table('phones')
		->select('phones.Id','phones.Type','phones.Registered_Name','phones.Account_No','phones.Phone_No','phones.Current_Holder','phones.Department as depart','users.Name','users.Department','phones.Package','phones.Remarks')
		->leftJoin('users','phones.UserId','=','users.Id')
		->where('phones.Type','=',$type)
		->get();

		$bills = DB::table('phonebills')
		->select('phonebills.Id','phonebills.Type','phonebills.Registered_Name','phonebills.Account_No','phonebills.Bill_No','phonebills.Phone_No','phonebills.Current_Holder','phonebills.Department as depart','users.Name','users.Department','phonebills.Package','phonebills.Amount','phonebills.GST',DB::raw('"" as Total'),'phonebills.Bill_Date','phonebills.Due_Date','phonebills.Credit_Card_No','phonebills.Transaction_Date','phonebills.Transfer_Amount','phonebills.Remarks')
		->leftJoin('users','phonebills.UserId','=','users.Id')
		->where('phonebills.Type','=',$type)
		->get();

		$sims = DB::table('assets')
		->where('Type','like','%Sim%')
		->get();

		$startmonth = strtotime($start);
		$endmonth = strtotime($end);
		$daterange =[];

		$query ="";

		while ($startmonth <= $endmonth){
			$query.="Format(SUM(CASE WHEN LEFT(str_to_date('". date('M-Y', $startmonth) ."','%M-%Y'),7) = LEFT(str_to_date(phonebills.Bill_Date,'%d-%M-%Y'),7) THEN phonebills.Amount ELSE 0 END),2) AS '". date('M', $startmonth) ."',";

			$daterange[].= date('M', $startmonth);
			$startmonth=strtotime("+1 months",$startmonth);
		}

	 	$query=substr($query,0,strlen($query)-1);

		$rangebill = DB::select("
			select phonebills.Phone_No,
			".$query."
			from phonebills
			GROUP BY phonebills.Phone_No
		");

		$projects = DB::table('projects')
    	->get();

		$category=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','utility')
		->where('options.Field', '=','Phone_Type')
		->orderBy('options.Option')
		->get();

		$company=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','users')
		->where('options.Field', '=','Company')
		->get();

		$departments = DB::table('projects')
		->where('projects.Project_Name','like','%department%')
		->get();

		// dd($department);

		// $phone_no=DB::table('options')
		// 	->distinct('options.Option')
		// 	->select('options.Option','options.Field', 'options.Extra')
		// 	->where('options.Table', '=','utility')
		// 	->where('options.Field', '=', $type.'_No')
		// 	->get();

		$phone_no=DB::table('phones')
		->distinct('phones.Phone_No')
		->select('phones.Phone_No')
		->where('phones.Type', '=', $type)
		->get();

		$users = DB::table('users')
		->where('users.Name','not like', '%admin%')
    	->get();

    	if ($type == "Maxis" || $type == "Celcom") {
    		$users = collect($users)->groupBy('Department');
    	}

		return json_encode(['me'=>$me,'list'=>$list, 'type'=>$type,'rangebill'=>$rangebill,'daterange'=>$daterange, 'start'=>$start, 'end'=>$end,'sims'=>$sims,'projects'=>$projects,'category'=>$category,'users'=>$users,'company'=>$company,'phone_no'=>$phone_no,'departments'=>$departments, 'bills' => $bills]);

		// return view('phonebill',['me'=>$me,'list'=>$list, 'type'=>$type,'rangebill'=>$rangebill,'daterange'=>$daterange, 'start'=>$start, 'end'=>$end,'sims'=>$sims,'projects'=>$projects,'category'=>$category,'users'=>$users,'company'=>$company,'phone_no'=>$phone_no,'departments'=>$departments]);
	}

	public function shellcardtracker($start = null, $end = null)
	{
    	
    	$me = JWTAuth::parseToken()->authenticate();

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of previous month'));
		}

		if ($end==null)
		{
			// $end=date('d-M-Y', strtotime('today'));
			// $end=date('d-M-Y', strtotime($end . " +1 days"));
			$end=date('d-M-Y', strtotime('last day of this month'));
		}

		$list = DB::table('shellcards')
		->select('shellcards.Id','shellcards.Company','shellcards.Vehicle_No','shellcards.Account_No','shellcards.Card_No','shellcards.Pin_Code','shellcards.Type','shellcards.Limit_Month','shellcards.Expiry_Date','users.Name','users.Department','shellcards.Remarks')
		->leftJoin('users','users.Id','=','shellcards.UserId')
		->get();

		$expenses = DB::table('shellcardexpenses')
		->select('shellcardexpenses.Id','shellcards.Card_No','shellcards.Vehicle_No','shellcardexpenses.Payment_Month','shellcardexpenses.Amount')
		->leftJoin('shellcards','shellcards.Id','=','shellcardexpenses.ShellCardId')
		->get();

		$deductions = DB::table('deductions')
		->select('deductions.Id','deductions.Name','deductions.Company','deductions.Remarks','deductions.Status','submitter.Name as SubmitterName','deductions.created_at as Created_Date','approver_HRA.Name as AdminApprover','deductions.Admin_Status','approver_CME.Name as CMEApprover','deductions.CME_Status','approver_GENSET.Name as GENSETApprover','deductions.GENSET_Status','MD.Name as MD_Name','deductions.MD_Status')
		->leftJoin('deductionitems', 'deductions.Id', '=', 'deductionitems.DeductionId')
		->leftJoin('users as submitter', 'deductions.UserId', '=', 'submitter.Id')
		->leftJoin('users as approver_HRA', 'deductions.Admin_HOD', '=', 'approver_HRA.Id')
		->leftJoin('users as approver_CME', 'deductions.CME_HOD', '=', 'approver_CME.Id')
		->leftJoin('users as approver_GENSET', 'deductions.GENSET_HOD', '=', 'approver_GENSET.Id')
		->leftJoin('users as MD', 'deductions.MD', '=', 'MD.Id')
		->where('deductions.Type', '=', 'shellcards')
		->orderBy('deductions.Id','desc')
		->groupBy('deductions.Id','deductions.UserId')
		->get();

		$approverlevel = DB::table('approvalsettings')
		->select('users.Id','users.Name','approvalsettings.UserId','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
		->where('approvalsettings.Type', '=', 'Deduction')
		->where('approvalsettings.Level', '=', 'Final Approval')
		->get();

		$HOD_LOG=0;
		$HOD_CME=0;
		$HOD_GST=0;
		$MD=855;

		foreach($approverlevel as $level)
		{
			if($level->Project_Name == "MY_Department_LOG")
			{
				$HOD_LOG=$level->UserId;
			}
			elseif($level->Project_Name == "MY_Department_CME")
			{
				$HOD_CME=$level->UserId;
			}
			elseif($level->Project_Name == "MY_Department_GST")
			{
				$HOD_GST=$level->UserId;
			}
		}

		$paymentmonth = DB::table('cutoff')
		->orderBy(DB::raw('str_to_date(cutoff.Payment_Month,"%M %Y")'))
		->get();

		$current=date('M Y');

		$cars = DB::table('assets')
		->where('Type','like','%Car%')
		->get();

		$company=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','users')
		->where('options.Field', '=','Company')
		->orderBy('options.Option')
		->get();

		$users = DB::table('users')
		->where('users.Name','not like', '%admin%')
    	->get();

    	$users = collect($users)->groupBy('Department')->all();

    	$vehiclelist = DB::table('roadtax')->select('Vehicle_No')->where('Option','=', 'VEHICLE LIST')->get();

		$account_no=DB::table('shellcards')
		->select('shellcards.*')
		->get();

		return json_encode(['me'=>$me, 'list'=>$list, 'company'=>$company,'start'=>$start,'end'=>$end,'cars'=>$cars,'users'=>$users,'deductions'=>$deductions,'paymentmonth'=>$paymentmonth,'current'=>$current,'approverlevel'=>$approverlevel,'HOD_LOG'=>$HOD_LOG,'HOD_CME'=>$HOD_CME,'HOD_GST'=>$HOD_GST,'MD'=>$MD,'expenses'=>$expenses,'account_no'=>$account_no, 'vehiclelist'=>$vehiclelist]);
	}

	public function shelldeduction($deductionid)
	{

    	$me = JWTAuth::parseToken()->authenticate();

		$shelldeductions = DB::table('deductionitems')
		->select('deductionitems.Id','deductionitems.DeductionId','deductionitems.Date','deductionitems.Time','deductionitems.Invoice_No','deductionitems.Invoice_Date','deductionitems.Due_Date','deductionitems.Account_No','deductionitems.Company','deductionitems.Project_Code','users.Name','users.Department','deductionitems.Petrol_Station','deductionitems.Amount','deductionitems.Total_Deduction','deductionitems.Remarks')
		->leftJoin('users','users.Id','=','deductionitems.UserId')
		->leftJoin('deductions','deductions.Id','=','deductionitems.DeductionId')
		->where('deductionitems.DeductionId','=',$deductionid)
		->get();

		// $deductions = DB::table('deductions')
		// ->select('deductions.Id','deductions.Name','deductions.Company','deductions.Remarks','deductions.Status','submitter.Name as SubmitterName','deductions.created_at as Created_Date','approver_HRA.Name as AdminApprover','deductions.Admin_Status','approver_CME.Name as CMEApprover','deductions.CME_Status','approver_GENSET.Name as GENSETApprover','deductions.GENSET_Status','MD.Name as MD_Name','deductions.MD_Status')
		// ->leftJoin('deductionitems', 'deductions.Id', '=', 'deductionitems.DeductionId')
		// ->leftJoin('users as submitter', 'deductions.UserId', '=', 'submitter.Id')
		// ->leftJoin('users as approver_HRA', 'deductions.Admin_HOD', '=', 'approver_HRA.Id')
		// ->leftJoin('users as approver_CME', 'deductions.CME_HOD', '=', 'approver_CME.Id')
		// ->leftJoin('users as approver_GENSET', 'deductions.GENSET_HOD', '=', 'approver_GENSET.Id')
		// ->leftJoin('users as MD', 'deductions.MD', '=', 'MD.Id')
		// ->where('deductions.Type', '=', 'shellcards')
		// ->orderBy('deductions.Id','desc')
		// ->groupBy('deductions.Id','deductions.UserId')
		// ->get();

		$deductions = DB::table('deductions')
		->select()
		->where('deductions.Id',$deductionid)
		->first();

		// dd($deductions);

		$company=DB::table('options')
		
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','users')
		->where('options.Field', '=','Company')
		->orderBy('options.Option')
		->get();

		$cars = DB::table('roadtax')
		->select('Vehicle_No')
		->where('Option','=','VEHICLE LIST')
		->get();


		$users = DB::table('users')
		->where('users.Name','not like', '%admin%')
		->get();

		return json_encode(['me'=>$me,'deductionid'=>$deductionid,'shelldeductions'=>$shelldeductions,'users'=>$users,'deductions'=>$deductions,'company'=>$company,'cars'=>$cars]);
	}


	public function deductionapproval(Request $request)
	{
    	$me = JWTAuth::parseToken()->authenticate();

		$input = $request->all();

		if($input["Role"]=="UserId"){

			DB::table('deductions')
			->where('Id', $input["DeductionId"])
			->update(array(
				'Status'	=> "Submitted for approval"
			));

		}
		elseif($input["Role"]=="HRA"){

			DB::table('deductions')
			->where('Id', $input["DeductionId"])
			->update(array(
				'Admin_Status'	=> "Approved"
			));

		}
		elseif($input["Role"]=="CME"){
			DB::table('deductions')
			->where('Id', $input["DeductionId"])
			->update(array(
				'CME_Status'	=> "Approved"
			));

		}
		elseif($input["Role"]=="GENSET"){
			DB::table('deductions')
			->where('Id', $input["DeductionId"])
			->update(array(
				'GENSET_Status'	=> "Approved",
			));
		}

		$deductions = DB::table('deductions')
		->select()
		->where('deductions.Id',$input["DeductionId"])
		->first();

		if($deductions->CME_Status=="Approved" && $deductions->GENSET_Status=="Approved" && $deductions->Admin_Status=="Approved"){
			DB::table('deductions')
			->where('Id', $input["DeductionId"])
			->update(array(
				'Status'	=> "Final Approved",
			));
		}

		return 1;
	}

	public function summontracker()
	{
    	
    	$me = JWTAuth::parseToken()->authenticate();

		$all = DB::table('summons')
		->select('summons.Id','summons.Vehicle_No','summons.Company','summons.Place','summons.Summon_No','summons.Date','summons.Time','summons.Offense','summons.Amount','users.Name','users.Department','summons.Company_Deduction','summons.Total_Deduction','summons.Employer_Bare','summons.Settlement_Date','summons.Remarks')
		->leftJoin('users','users.Id','=','summons.UserId')
		->orderBy('summons.Id','asc')
		->get();

		$cars = DB::table('roadtax')
		->select('Vehicle_No')
		->where('Option','=','VEHICLE LIST')
		->get();

		$deductions = DB::table('deductions')
		->select('deductions.Id','deductions.Name','deductions.Company','deductions.Remarks','deductions.Status','submitter.Name as SubmitterName','deductions.created_at as Created_Date','approver_HRA.Name as AdminApprover','deductions.Admin_Status','approver_CME.Name as CMEApprover','deductions.CME_Status','approver_GENSET.Name as GENSETApprover','deductions.GENSET_Status','MD.Name as MD_Name','deductions.MD_Status')
		->leftJoin('summons', 'deductions.Id', '=', 'summons.DeductionId')
		->leftJoin('users as submitter', 'deductions.UserId', '=', 'submitter.Id')
		->leftJoin('users as approver_HRA', 'deductions.Admin_HOD', '=', 'approver_HRA.Id')
		->leftJoin('users as approver_CME', 'deductions.CME_HOD', '=', 'approver_CME.Id')
		->leftJoin('users as approver_GENSET', 'deductions.GENSET_HOD', '=', 'approver_GENSET.Id')
		->leftJoin('users as MD', 'deductions.MD', '=', 'MD.Id')
		->where('deductions.Type', '=', 'summon')
		->orderBy('deductions.Id','desc')
		->groupBy('deductions.Id','deductions.UserId')
		->get();

		// dd($deductions);
		$accidents = DB::table('deductions')
		->select('deductions.Id','deductions.Name','deductions.Company','deductions.Remarks','deductions.Status','submitter.Name as SubmitterName','deductions.created_at as Created_Date','approver_LOG.Name as LOGApprover','deductions.LOG_Status','approver_CME.Name as CMEApprover','deductions.CME_Status','approver_GENSET.Name as GENSETApprover','deductions.GENSET_Status','MD.Name as MD_Name','deductions.MD_Status')
		->leftJoin('summons', 'deductions.Id', '=', 'summons.DeductionId')
		->leftJoin('users as submitter', 'deductions.UserId', '=', 'submitter.Id')
		->leftJoin('users as approver_LOG', 'deductions.LOG_HOD', '=', 'approver_LOG.Id')
		->leftJoin('users as approver_CME', 'deductions.CME_HOD', '=', 'approver_CME.Id')
		->leftJoin('users as approver_GENSET', 'deductions.GENSET_HOD', '=', 'approver_GENSET.Id')
		->leftJoin('users as MD', 'deductions.MD', '=', 'MD.Id')
		->where('deductions.Type', '=', 'accident')
		->orderBy('deductions.Id','desc')
		->groupBy('deductions.Id','deductions.UserId')
		->get();

		// dd($accidents);
		$approverlevel = DB::table('approvalsettings')
		->select('users.Id','users.Name','approvalsettings.UserId','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
		->where('approvalsettings.Type', '=', 'Deduction')
		->where('approvalsettings.Level', '=', 'Final Approval')
		->get();

		$HOD_LOG=0;
		$HOD_CME=0;
		$HOD_GST=0;
		$MD=855;

		foreach($approverlevel as $level)
		{
			if($level->Project_Name == "MY_Department_LOG")
			{
				$HOD_LOG=$level->UserId;
			}
			elseif($level->Project_Name == "MY_Department_CME")
			{
				$HOD_CME=$level->UserId;
			}
			elseif($level->Project_Name == "MY_Department_GST")
			{
				$HOD_GST=$level->UserId;
			}

		}

		// dd($HOD_LOG);
		$paymentmonth = DB::table('cutoff')
		->orderBy(DB::raw('str_to_date(cutoff.Payment_Month,"%M %Y")'))
		->get();

		$current=date('M Y');

		$company=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','users')
		->where('options.Field', '=','Company')
		->orderBy('options.Option')
		->get();

		$users = DB::table('users')
		->where('users.Name','not like', '%admin%')
    	->get();

		// $departments = DB::table('projects')
		// ->where('projects.Project_Name','like','%Department%')
    	// ->get();

		// $departments = ["GST","LOG"];

		// dd($departments);

		return json_encode(['me'=>$me, 'all'=>$all, 'users'=>$users,'cars'=>$cars,'company'=>$company,'deductions'=>$deductions,'accidents'=>$accidents,'paymentmonth'=>$paymentmonth,'current'=>$current,'approverlevel'=>$approverlevel,'HOD_LOG'=>$HOD_LOG,'HOD_CME'=>$HOD_CME,'HOD_GST'=>$HOD_GST,'MD'=>$MD]);
	}


	public function summondeduction($deductionid)
	{

    	$me = JWTAuth::parseToken()->authenticate();

		$summondeductions = DB::table('summons')
		->select('summons.Id','summons.DeductionId','summons.Vehicle_No','summons.Company','summons.Place','summons.Summon_No','summons.Date','summons.Time','summons.Offense','summons.Amount','users.Name','users.Department','summons.Company_Deduction','summons.Total_Deduction','summons.Employer_Bare','summons.Settlement_Date','summons.Remarks')
		->leftJoin('deductions','deductions.Id','=','summons.DeductionId')
		->leftJoin('users','users.Id','=','summons.UserId')
		->where('summons.DeductionId','=',$deductionid)
		->get();

		$deductions = DB::table('deductions')
		->select()
		->where('deductions.Id',$deductionid)
		->first();

		$company=DB::table('options')
			->distinct('options.Option')
			->select('options.Option')
			->where('options.Table', '=','users')
			->where('options.Field', '=','Company')
			->orderBy('options.Option')
			->get();

		$cars = DB::table('roadtax')
		->select('Vehicle_No')
		->where('Option','=','VEHICLE LIST')
		->get();

		// dd($cars);
		$users = DB::table('users')
		->where('users.Name','not like', '%admin%')
		->get();

		return json_encode(['me'=>$me,'deductionid'=>$deductionid,'summondeductions'=>$summondeductions,'users'=>$users,'deductions'=>$deductions,'company'=>$company,'cars'=>$cars]);
	}

	public function accidentdeduction($deductionid)
	{

   		$me = JWTAuth::parseToken()->authenticate();

		$accidentdeduction = DB::table('deductionitems')
		->select('deductionitems.Id','deductionitems.DeductionId','deductionitems.Date','deductionitems.Time','deductionitems.Car_No','users.Name','users.Department','deductionitems.Victim','deductionitems.Amount','deductionitems.Total_Deduction')
		->leftJoin('deductions','deductions.Id','=','deductionitems.DeductionId')
		->leftJoin('users','users.Id','=','deductionitems.UserId')
		->where('deductionitems.DeductionId','=',$deductionid)
		->get();

		$deductions = DB::table('deductions')
		->select()
		->where('deductions.Id',$deductionid)
		->first();

		$company=DB::table('options')
			->distinct('options.Option')
			->select('options.Option')
			->where('options.Table', '=','users')
			->where('options.Field', '=','Company')
			->orderBy('options.Option')
			->get();

		$cars = DB::table('assets')
		->where('Type','like','%Car%')
		->get();

		$users = DB::table('users')
		->where('users.Name','not like', '%admin%')
		->get();

    	$vehiclelists = DB::table('roadtax')->select('Vehicle_No')->where('Option','=', 'VEHICLE LIST')->get();


		return json_encode(['me'=>$me,'deductionid'=>$deductionid,'accidentdeduction'=>$accidentdeduction,'users'=>$users,'deductions'=>$deductions,'company'=>$company, 'vehiclelists' => $vehiclelists]);
	}


	public function touchngoforvehicle($vehicleno)
	{
		$touchngocards = DB::table('touchngo')->select('touchngo.Card_No')->where('touchngo.Vehicle_No', '=', $vehicleno)->get();
		return collect($touchngocards)->pluck('Card_No');
	}


	public function touchngo()
	{
    	
    	$me = JWTAuth::parseToken()->authenticate();

		$list = DB::table('touchngo')
		->select('touchngo.Id','touchngo.Username','touchngo.User_ID','touchngo.Card_No','touchngo.Vehicle_No','touchngo.Card_Type','touchngo.Registered_Name','touchngo.Plusmiles_Register','users.Name','users.Department','touchngo.Date_Provide','touchngo.Date_Return','touchngo.Date_Terminate','touchngo.Remarks')
		->leftJoin('users','users.Id','=','touchngo.UserId')
		->get();

		$deductions = DB::table('deductions')
		->select('deductions.Id','deductions.Name','deductions.Company','deductions.Remarks','deductions.Status','submitter.Name as SubmitterName','deductions.created_at as Created_Date','approver_HRA.Name as AdminApprover','deductions.Admin_Status','approver_CME.Name as CMEApprover','deductions.CME_Status','approver_GENSET.Name as GENSETApprover','deductions.GENSET_Status','MD.Name as MD_Name','deductions.MD_Status')
		->leftJoin('summons', 'deductions.Id', '=', 'summons.DeductionId')
		->leftJoin('users as submitter', 'deductions.UserId', '=', 'submitter.Id')
		->leftJoin('users as approver_HRA', 'deductions.Admin_HOD', '=', 'approver_HRA.Id')
		->leftJoin('users as approver_CME', 'deductions.CME_HOD', '=', 'approver_CME.Id')
		->leftJoin('users as approver_GENSET', 'deductions.GENSET_HOD', '=', 'approver_GENSET.Id')
		->leftJoin('users as MD', 'deductions.MD', '=', 'MD.Id')
		->where('deductions.Type', '=', 'touchngo')
		->orderBy('deductions.Id','desc')
		->groupBy('deductions.Id','deductions.UserId')
		->get();

		$reload = DB::table('reload')
		->select('reload.Id','reload.Card_No','users.Name','reload.Project_Code','users.Department','reloadBy.Name as reloader','reloadBy.Department as depart','reload.Date_Reload','reload.Balance_Before','reload.Total_Reload','reload.Topup','reload.Balance','reload.Remarks','reload2.Balance as PreviousBalance')
		->leftJoin('users','users.Id','=','reload.Request_By')
		->leftJoin('users as reloadBy','reloadBy.Id','=','reload.Reload_By')
		->leftJoin('reload as reload2','reload.Id','=',DB::raw('reload2.Id+1'))
		->get();

		$approverlevel = DB::table('approvalsettings')
		->select('users.Id','users.Name','approvalsettings.UserId','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
		->where('approvalsettings.Type', '=', 'Deduction')
		->where('approvalsettings.Level', '=', 'Final Approval')
		->get();

		$HOD_LOG=0;
		$HOD_CME=0;
		$HOD_GST=0;
		$MD=855;

		foreach($approverlevel as $level)
		{
			if($level->Project_Name == "MY_Department_LOG")
			{
				$HOD_LOG=$level->UserId;
			}
			elseif($level->Project_Name == "MY_Department_CME")
			{
				$HOD_CME=$level->UserId;
			}
			elseif($level->Project_Name == "MY_Department_GST")
			{
				$HOD_GST=$level->UserId;
			}
		}

		$paymentmonth = DB::table('cutoff')
		->orderBy(DB::raw('str_to_date(cutoff.Payment_Month,"%M %Y")'))
		->get();

		$current=date('M Y');

		$cars = DB::table('assets')
		->where('Type','like','%Car%')
		->get();

		$company=DB::table('options')
			->distinct('options.Option')
			->select('options.Option')
			->where('options.Table', '=','users')
			->where('options.Field', '=','Company')
			->orderBy('options.Option')
			->get();

		$touchngocards = DB::table('touchngo')->select('Card_No')->get();

		$cardtypes=DB::table('options')
			->distinct('options.Option')
			->select('options.Option')
			->where('options.Table', '=','assets')
			->where('options.Field', '=','TouchNGo_Card_Type')
			->orderBy('options.Option')
			->get();

		$users = DB::table('users')
		->where('users.Name','not like', '%admin%')
    	->get();

		$vehicle_no = DB::table("roadtax")
		->DISTINCT('Vehicle_No')
		->get();

		return json_encode(['me'=>$me, 'list'=>$list, 'company'=>$company,'cars'=>$cars,'users'=>$users,'deductions'=>$deductions,'paymentmonth'=>$paymentmonth,'current'=>$current,'reload'=>$reload,'approverlevel'=>$approverlevel,'HOD_LOG'=>$HOD_LOG,'HOD_CME'=>$HOD_CME,'HOD_GST'=>$HOD_GST,'MD'=>$MD,'vehicle_no'=>$vehicle_no, 'cardtypes' => $cardtypes, 'touchngocards' => $touchngocards]);

	}


	public function touchngodeduction($deductionid)
	{

	    $me = JWTAuth::parseToken()->authenticate();

		$touchdeductions = DB::table('deductionitems')
		->select('deductionitems.Id','deductionitems.DeductionId','deductionitems.Date','deductionitems.Time','deductionitems.Card_Serial','users.Name','deductionitems.Entry_location','deductionitems.Amount','deductionitems.Amount','deductionitems.Total_Deduction','deductionitems.Penalty')
		->leftJoin('users','users.Id','=','deductionitems.UserId')
		->leftJoin('deductions','deductions.Id','=','deductionitems.DeductionId')
		->where('deductionitems.DeductionId','=',$deductionid)
		->get();

		$deductions = DB::table('deductions')
		->select()
		->where('deductions.Id',$deductionid)
		->first();

		$users = DB::table('users')
		->where('users.Name','not like', '%admin%')
		->get();

		$touchngocards = DB::table('touchngo')->select('Card_No')->get();

		return json_encode(['me'=>$me,'deductionid'=>$deductionid,'touchdeductions'=>$touchdeductions,'users'=>$users,'deductions'=>$deductions, 'touchngocards' => $touchngocards]);
	}


	public function phonebillsnewentry(Request $request, $type)
	{

		$input = $request->input();
		$phonebills = [];
		for ($i=0; $i < count($input['Phone_No']); $i++) {
			if ($input['Amount'][$i] && $input['Total'][$i]) {
				array_push($phonebills, [
					'Amount' => $input['Amount'][$i],
					'GST' => 0,
					'Total' => $input['Total'][$i],
					'Registered_Name' => $input['Registered_Name'][$i],
					'Account_No' => $input['Account_No'][$i],
					'Phone_No' => $input['Phone_No'][$i],
					'Current_Holder' => $input['Current_Holder'][$i],
					'Department' => $input['Department'][$i],
					'UserId' => $input['UserId'][$i],
					'Package' => $input['Package'][$i],

					// 'Credit_Card_No' => $input['Credit_Card_No'][$i],
					// 'Package' => $input['Package'][$i],
					// 'Transaction_Date' => $input['Transaction_Date'][$i],
					// 'Transfer_Amount' => $input['Transfer_Amount'][$i],
					// 'Remarks' => $input['Remarks'][$i],

					'Bill_Date' => $input['Bill_Date'],
					'Due_Date' => $input['Due_Date'],
					'Bill_No' => $input['Bill_No'],
					'Prorate_Monthly' => NULL,
					'Type' => $type,
					'ProjectId' => 0,
				]);
			}
		}

		DB::table('phonebills')->insert($phonebills);

		return 1;
	}


	public function phonebillsupdateentry(Request $request, $type)
	{

		$input = $request->input();
		for ($i=0; $i < count($input['Id']); $i++) {

			DB::table('phonebills')
			->where('Id', $input['Id'][$i])
			->update([
				'Credit_Card_No' => $input['Credit_Card_No'][$i],
				'Transaction_Date' => $input['Transaction_Date'][$i],
				'Transfer_Amount' => $input['Transfer_Amount'][$i],
				'Amount' => $input['Amount'][$i],
				// 'Total' => $input['Total'][$i],
				'Bill_Date' => $input['Bill_Date'][$i],
				'Due_Date' => $input['Due_Date'][$i],
				'Bill_No' => $input['Bill_No'][$i],
				'Amount' => $input['Amount'][$i],
				'Remarks' => $input['Remarks'][$i],
			]);
		}

		return 1;
	}


	public function newphonebill(Request $request)
	{

	    $me = JWTAuth::parseToken()->authenticate();

		$input = $request->all();

		DB::table('phonebills')->insert([
			'Phone_Number' => $input["Phone_Number"],
			'UserId' => $input["UserId"],
			'Bill_Number' => $input["Bill_Number"],
			'Bill_Date' => $input["Bill_Date"],
			'Amount' => $input["Amount"]
		]);

		return 1;
	}


	public function newsummon(Request $request)
	{

	    $me = JWTAuth::parseToken()->authenticate();

		$input = $request->all();

		DB::table('summons')->insert([
			'Vehicle_No' => $input["Vehicle_No"],
			'UserId' => $input["UserId"],
			'Summon_No' => $input["Summon_No"],
			'Time' => $input["Time"],
			'Date' => $input["Date"],
			'Summon_No' => $input["Summon_No"],
			'Offense' => $input["Offense"],
			'Payment_Date' => $input["Payment_Date"],
			'Remarks' => $input["Remarks"],
			'Amount' => $input["Amount"]
		]);

		return 1;
	}


	public function newshellbill(Request $request)
	{
	    $me = JWTAuth::parseToken()->authenticate();

		$input = $request->all();

		DB::table('shellcards')->insert([
			'Vehicle_No' => $input["Vehicle_No"],
			'UserId' => $input["UserId"],
			'Time' => $input["Time"],
			'Date' => $input["Date"],
			'Identity' => $input["Identity"],
			'Quantity' => $input["Quantity"],
			'Usage_RM_ltr' => $input["Usage_RM_ltr"]
		]);


		return 1;
	}


	public function utilitybill($type)
	{

        $me = JWTAuth::parseToken()->authenticate();

		$category=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','utility')
		->where('options.Field', '=','Type')
		->orderBy('options.Option')
		->get();

		$branch=DB::table('options')
		->distinct('options.Option')
		->where('options.Table', '=','utility')
		->where('options.Field','=','Branch')
		->orderBy('options.Option')
		->get();

		$company=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','users')
		->where('options.Field', '=','Company')
		->orderBy('options.Option')
		->get();

		$payment_type=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','utility')
		->where('options.Field', '=','Payment_Type')
		->orderBy('options.Option')
		->get();

		switch ($type) {
			case 'Water':
			case 'WATER':
				$bill_account_no_type = 'Utility_Account_WATER';
			break;

			case 'COWAY':
				$bill_account_no_type = 'Utility_Account_COWAY';
			break;

			case 'BOMBA':
				$bill_account_no_type = 'Utility_Account_BOMBA';
			break;
			case 'ELECTRICITY':
				$bill_account_no_type = 'Utility_Account_ELECTRICITY';
			break;
			case 'INDAH WATER':
				$bill_account_no_type = 'Utility_Account_INDAH_WATER';
			break;
			case 'MPKJ':
				$bill_account_no_type = 'Utility_Account_MPKJ';
			break;
			case 'TELEKOM':
				$bill_account_no_type = 'Utility_Account_TELEKOM';
			break;
			case 'UMOBILE':
				$bill_account_no_type = 'Utility_Account_UMobile';
			break;
			case 'UNIFI':
				$bill_account_no_type = 'Utility_Account_Unifi';
			break;
			default:
				$bill_account_no_type = 'Utility_Account_%';
			break;
		}

		$bill_account_no=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','utility')
		->where('options.Field', 'LIKE',$bill_account_no_type)
		->orderBy('options.Option')
		->get();

		// $account_no = array
	  // (
		// 	["Type"=>"Water1", "Branch"=>"Kuantan", "Account_No"=>"1234"],
	  // 	["Type"=>"Water", "Branch"=>"Menglembu", "Account_No"=>"12345"]
	  // );

		$list = DB::table('utility')
		->select('utility.Id','utility.Type','utility.Branch','utility.Bill_Account_No','utility.Bill_Date','utility.Bill_Due_Date',DB::raw('Format((utility.Monthly_Charges),2) as Monthly_Charges'),DB::raw('Format((utility.GST_Charges),2) as GST_Charges'),DB::raw('Format((utility.Total_Amount),2) as Total_Amount'),'utility.Payment_Type','utility.Date_Paid','utility.Paid_Amount','utility.Remarks')
		->where('utility.Type', '=', $type)
		->get();

		return json_encode(['me'=>$me,'category'=>$category,'list'=>$list,'type'=>$type,'company'=>$company,'branch'=>$branch, 'payment_type' => $payment_type, 'bill_account_no' => $bill_account_no]);
	}


	public function roadtaxmanagement($option)
	{

	    $me = JWTAuth::parseToken()->authenticate();

		$roadtax = DB::table('roadtax')
		->select('roadtax.Id','roadtax.Option','roadtax.Vehicle_No','users.Name','users.Department','roadtax.RoadTax_Expire_Date','roadtax.Insurance_Expiry_Date','roadtax.Insurance_Company','roadtax.Asset_Listed','roadtax.With_ShellCard','roadtax.Maker','roadtax.Model','roadtax.Year','roadtax.Type','roadtax.Owner','roadtax.Original_Reg_Card','roadtax.Purchase_Date','roadtax.Financier','roadtax.Account_No','roadtax.Hire_Purchase','roadtax.First_Installment','roadtax.Monthly_Installment','roadtax.Personal_Accident','roadtax.Puspakom_Expiry','roadtax.PMA_Expiry','roadtax.SPAD_Expiry','roadtax.NCD','roadtax.Loading','roadtax.Sum_Insured','roadtax.Windscreen','roadtax.Remarks', 'shellcards.Card_No', 'shellcards.Id as ShellCardId')
		->leftJoin('users','users.Id','=','roadtax.UserId')
		->leftJoin('shellcards','shellcards.Vehicle_No','=','roadtax.Vehicle_No')
		->get();

		$noticedate=date("d-M-Y", strtotime('+2 weeks'));
		$today=date("d-M-Y");

		$paymentmonth = DB::table('cutoff')
		->orderBy(DB::raw('str_to_date(cutoff.Payment_Month,"%M %Y")'))
		->get();

		$category=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','assets')
		->where('options.Field', '=','Motorvehicle_Type')
		->get();

		$users = DB::table('users')
		->where('users.Name','not like', '%admin%')
		->get();

		$users = collect($users)->groupBy('Department');

		$company=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','users')
		->where('options.Field', '=','Company')
		->get();

		$insurance_company=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','assets')
		->where('options.Field', '=','Insurance_Company')
		->get();

		$summondeductions = DB::table('deductions')
		->select('deductions.Id','deductions.Name')
		->where('deductions.Type', '=', 'summon')
		->get();

		$accidentdeductions = DB::table('deductions')
		->select('deductions.Id','deductions.Name')
		->where('deductions.Type', '=', 'accident')
		->get();

		$shellcarddeductions = DB::table('deductions')
		->select('deductions.Id','deductions.Name')
		->where('deductions.Type', '=', 'shellcards')
		->get();

		$touchngodeductions = DB::table('deductions')
		->select('deductions.Id','deductions.Name')
		->where('deductions.Type', '=', 'touchngo')
		->get();

		$tngcardtypes=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','assets')
		->where('options.Field', '=','TouchNGo_Card_Type')
		->orderBy('options.Option')
		->get();

		return json_encode(['me'=>$me,'roadtax'=>$roadtax, 'noticedate'=>$noticedate, 'category'=>$category,'option'=>$option,'today'=>$today, 'users'=>$users, 'company'=>$company, 'insurance_company' => $insurance_company, 'summondeductions' => $summondeductions, 'shellcarddeductions' => $shellcarddeductions, 'paymentmonth' => $paymentmonth, 'accidentdeductions' => $accidentdeductions, 'touchngodeductions' => $touchngodeductions, 'tngcardtypes' => $tngcardtypes]);
	}

	public function creditcardnewentry(Request $request, $owner)
	{

		$input = $request->input();
		$cards = [];
		for ($i=0; $i < count($input['Card_Type']); $i++) {
			if ($input['Statement_Date'][$i] && $input['Statement_Due'][$i] && $input['Current_Balance'][$i]) {
				array_push($cards, [
					'Type' => $input['Card_Type'][$i],
					'Statement_Due' => $input['Statement_Due'][$i],
					'Statement_Date' => $input['Statement_Date'][$i],
					'Current_Balance' => $input['Current_Balance'][$i],
					'Owner' => $owner
				]);
			}
		}

		DB::table('creditcards')->insert($cards);

		return 1;
	}


	public function creditcardupdateentry(Request $request, $owner)
	{

		$input = $request->input();
		for ($i=0; $i < count($input['Id']); $i++) {
			if ($input['Statement_Date'][$i] && $input['Statement_Due'][$i] && $input['Current_Balance'][$i] && $input['Payment_Date'][$i] && $input['Payment_Type'][$i] && $input['Amount'][$i]) {

				DB::table('creditcards')
				->where('Id', $input['Id'][$i])
				->update([
					'Statement_Due' => $input['Statement_Due'][$i],
					'Statement_Date' => $input['Statement_Date'][$i],
					'Current_Balance' => $input['Current_Balance'][$i],
					'Payment_Type' => $input['Payment_Type'][$i],
					'Payment_Date' => $input['Payment_Date'][$i],
					'Amount' => $input['Amount'][$i]
				]);
			}
		}

		return 1;
	}


	public function creditcardtracker($owner){

    	$me = JWTAuth::parseToken()->authenticate();


		$lists = DB::table('creditcards')
		->select('creditcards.Id','creditcards.Type','creditcards.Owner','creditcards.Statement_Date','creditcards.Statement_Due',DB::raw('Format((creditcards.Current_Balance),2)'),'creditcards.Payment_Date','creditcards.Payment_Type',DB::raw('Format(creditcards.Amount,2)'))
		->where('creditcards.Owner','=',$owner)
		->get();

		$category=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','utility')
		->where('options.Field', '=','CreditCard_Owner')
		->orderBy('options.Option')
		->get();

		$typeoptions=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','utility')
		->where('options.Field', '=','CreditCard_Type')
		->orderBy('options.Option')
		->get();

		$payment_type=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','utility')
		->where('options.Field', '=','Payment_Type')
		->orderBy('options.Option')
		->get();

		return json_encode(['me'=>$me,'owner'=>$owner,'lists'=>$lists,'category'=>$category,'typeoptions'=>$typeoptions, 'payment_type' => $payment_type]);

	}
	public function insurancetracker($type)
	{

    	$me = JWTAuth::parseToken()->authenticate();

		$list = DB::table('insurances')
		->select('insurances.Id','insurances.Type','insurances.Insured_Name','insurances.Situation','insurances.Address','insurances.Insured_Person','insurances.Insurance_Expiry','insurances.Insurance_Company','insurances.Company','insurances.Account_No','insurances.Policy_No','insurances.Type_of_Policy','insurances.Plan_Covered','insurances.Class','insurances.Benefits','insurances.Ratings','insurances.Sum_Insured','insurances.Total_Premium','insurances.Business','insurances.Area','insurances.Insurance_Type','insurances.Status','insurances.Installment_Rental','insurances.Start_Date','insurances.End_Date','insurances.Contact_Person','insurances.Contact_No','insurances.Section','insurances.Client','insurances.Purchase_Date','insurances.Brand','insurances.Serial_No','insurances.Engine_Model','insurances.Engine_No','insurances.Financier','insurances.Capacity','insurances.Remarks','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Insurance" Group By TargetId) as max'), 'max.TargetId', '=', 'insurances.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Insurance"'))
		->where('insurances.Type','=',$type)
		->get();



		$projects = DB::table('projects')
		->get();

		$category=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','utility')
		->where('options.Field', '=','Insurance_Type')
		->orderBy('options.Option')
		->get();

		$company=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','users')
		->where('options.Field', '=','Company')
		->get();

		return json_encode(['me'=>$me,'list'=>$list, 'type'=>$type,'projects'=>$projects,'category'=>$category,'company'=>$company]);

	}

	public function licensetracker($type)
	{

	    $me = JWTAuth::parseToken()->authenticate();

		$licenses = DB::table('licenses')
		->select('licenses.Id','licenses.Type','users.Name','users.Department','licenses.License_Type','licenses.Description','licenses.Identity_No','licenses.Expiry_Date','licenses.Remarks','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="License" Group By TargetId) as max'), 'max.TargetId', '=', 'licenses.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="License"'))
		->leftJoin('users','users.Id','=','licenses.UserId')
		->where('licenses.Type','=',$type)
		->orderBy('licenses.Id','Asc')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users","employmenthistories","experiences","qualifications","skills","licenses","trainings","references","languages"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$category=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','licenses')
		->where('options.Field', '=','Type')
		->orderBy('options.Option')
		->get();

		$projects = DB::table('projects')
    	->get();

		$users = DB::table('users')
		->where('users.StaffId','<>','admin')
    	->get();


		return json_encode(['me'=>$me, 'type'=>$type, 'category'=>$category,'licenses'=>$licenses,'options'=>$options,'projects'=>$projects,'users'=>$users]);

	}

	public function ITservice($domain=null)
	{

	    $me = JWTAuth::parseToken()->authenticate();


		$vpnusers = DB::table('vpn')
		->select('vpn.Id','users.Name','users.Position','users.Department','vpn.User_ID','vpn.Password')
		->leftJoin('users','users.Id','=','vpn.UserId')
		->get();

		$nasusers = DB::table('nas')
		->select('nas.Id','users.Name','users.Position','users.Department','nas.PC_Name','nas.User_ID','nas.Password','nas.Share_Folders')
		->leftJoin('users','users.Id','=','nas.UserId')
		->get();

		$domains = DB::table('domain')
		->select('domain.Id','domain.Company','domain.Email','domain.Password','domain.NestFrom_Password','domain.Created_On','domain.Request_By')
		->get();

		$emailauto = DB::table('emailautoforwarder')
		->select('emailautoforwarder.Id','emailautoforwarder.Domain','emailautoforwarder.Group_Email','emailautoforwarder.User')
		->get();

		$users = DB::table('users')
		->where('users.Id','<>','562')
		->get();

		$typeoptions = DB::table('options')
		->where('options.Field','=','Domain')
		->get();

		$folderoptions = DB::table('options')
		->where('options.Field','=','Share_Folder')
		->get();

		$pcoptions = DB::table('options')
		->where('options.Field','=','PC_Name')
		->get();


		return json_encode(['me'=>$me,'domain'=>$domain,'users'=>$users,'vpnusers'=>$vpnusers,'nasusers'=>$nasusers, 'domains'=>$domains, 'emailauto'=>$emailauto,'typeoptions'=>$typeoptions, 'pcoptions' => $pcoptions, 'folderoptions' => $folderoptions]);
	}


	public function servicecontact()
	{

    	$me = JWTAuth::parseToken()->authenticate();

		$servicecontact = DB::table('servicecontact')
		->select('Id','Company','Services','Contact_Person','Contact_No')
		->get();

		return json_encode(['me'=>$me,'servicecontact'=>$servicecontact]);
	}


	public function printer($bill_month=null)
	{

    	$me = JWTAuth::parseToken()->authenticate();

		if ($bill_month==null)
		{
			$bill_month="All";

		}

		$printer = DB::table('printer')
		->select('printer.Id','printer.Name','printer.Floor','printer.Printer_ID','printer.Password')
		->where('printer.Type','=','Pin')
		->get();

		if($bill_month=="All"){
			$report = DB::table('printer')
			->select('Id','Floor','Printer_Model','Bill_Month','Start_Date','End_Date','Quantity','UP','Total_Without_GST','Total')
			->where('Type','=','Report')
			->orderBy('Floor')
			->get();
		}

		else{
			$report = DB::table('printer')
			->select('Id','Floor','Printer_Model','Bill_Month','Start_Date','End_Date','Quantity','UP','Total_Without_GST','Total')
			->where('Type','=','Report')
			->where('Bill_Month','=',$bill_month)
			->orderBy('Floor')
			->get();
		}

		$months = DB::table('cutoff')
		->orderBy(DB::raw('str_to_date(cutoff.Payment_Month,"%M %Y")'))
		->get();

		$printer_usage =DB::select("
			SELECT printer.Bill_Month,
			SUM(printer.Total) As 'Total_Value'
			FROM printer
			WHERE printer.Bill_Month <> ''
			GROUP BY printer.Bill_Month
			ORDER BY FIELD(printer.Bill_Month , 'Jan 2017', 'Feb 2017', 'Mar 2017', 'Apr 2017', 'May 2017', 'Jun 2017', 'Jul 2017', 'Aug 2017', 'Sep 2017', 'Oct 2017', 'Nov 2017', 'Dec 2017') ASC
		");

		foreach($printer_usage as $key => $quote){
			$aa[]=$quote->Bill_Month;
			$printer1 = implode(',', $aa);
		}

		foreach($printer_usage as $key => $quote){
			$bb[]=$quote->Total_Value;
			$printer2= implode(',', $bb);
		}

		// dd($printer_usage);

		$first_color1 = "";
    	$first_color2 = "";
		$first_bw1 = "";
		$first_bw2 = "";
		$ground_bw1 = "";
		$ground_bw2 = "";
		$ground_color1 = "";
		$ground_color2 = "";

		$first_color = DB::table("printer")
		->select('printer.Bill_Month','printer.Total_Without_GST')
		->where('printer.Floor','=','First Floor')
		->where('printer.Printer_Model','like','%color%')
		->groupBy('printer.Bill_Month')
		->orderByRaw("FIELD(printer.Bill_Month , 'Jan 2017', 'Feb 2017', 'Mar 2017', 'Apr 2017', 'May 2017', 'Jun 2017', 'Jul 2017', 'Aug 2017', 'Sep 2017', 'Oct 2017', 'Nov 2017', 'Dec 2017') ASC")
		->get();

		foreach($first_color as $key => $quote){
			$a[]=$quote->Bill_Month;
			$first_color1 = implode(',', $a);
		}

		foreach($first_color as $key => $quote){
			$b[]=$quote->Total_Without_GST;
			$first_color2= implode(',', $b);
		}

		$first_BW = DB::table("printer")
		->select('printer.Bill_Month','printer.Total_Without_GST')
		->where('printer.Floor','=','First Floor')
		->where('printer.Printer_Model','like','%B/W%')
		->groupBy('printer.Bill_Month')
		->orderByRaw("FIELD(printer.Bill_Month , 'Jan 2017', 'Feb 2017', 'Mar 2017', 'Apr 2017', 'May 2017', 'Jun 2017', 'Jul 2017', 'Aug 2017', 'Sep 2017', 'Oct 2017', 'Nov 2017', 'Dec 2017') ASC")
		->get();

		foreach($first_BW as $key => $quote){
			$c[]=$quote->Bill_Month;
			$first_bw1 = implode(',', $c);
		}

		foreach($first_BW as $key => $quote){
			$d[]=$quote->Total_Without_GST;
			$first_bw2= implode(',', $d);
		}

		$ground_BW = DB::table("printer")
		->select('printer.Bill_Month','printer.Total_Without_GST')
		->where('printer.Floor','=','Ground Floor')
		->where('printer.Printer_Model','like','%B/W%')
		->groupBy('printer.Bill_Month')
		->orderByRaw("FIELD(printer.Bill_Month , 'Jan 2017', 'Feb 2017', 'Mar 2017', 'Apr 2017', 'May 2017', 'Jun 2017', 'Jul 2017', 'Aug 2017', 'Sep 2017', 'Oct 2017', 'Nov 2017', 'Dec 2017') ASC")
		->get();

		foreach($ground_BW as $key => $quote){
			$e[]=$quote->Bill_Month;
			$ground_bw1 = implode(',', $e);
		}

		foreach($ground_BW as $key => $quote){
			$f[]=$quote->Total_Without_GST;
			$ground_bw2= implode(',', $f);
		}

		$ground_color = DB::table("printer")
		->select('printer.Bill_Month','printer.Total_Without_GST')
		->where('printer.Floor','=','First Floor')
		->where('printer.Printer_Model','like','%color%')
		->groupBy('printer.Bill_Month')
		->orderByRaw("FIELD(printer.Bill_Month , 'Jan 2017', 'Feb 2017', 'Mar 2017', 'Apr 2017', 'May 2017', 'Jun 2017', 'Jul 2017', 'Aug 2017', 'Sep 2017', 'Oct 2017', 'Nov 2017', 'Dec 2017') ASC")
		->get();

		foreach($ground_color as $key => $quote){
			$g[]=$quote->Bill_Month;
			$ground_color1 = implode(',', $g);
		}

		foreach($ground_color as $key => $quote){
			$h[]=$quote->Total_Without_GST;
			$ground_color2= implode(',', $h);
		}

		$users = DB::table('users')->select('users.Name', 'users.Nick_Name')->where('Id','<>', '562')->get();

		return json_encode(['me'=>$me,'bill_month'=>$bill_month,'printer'=>$printer,'report'=>$report,'months'=>$months,'first_color1'=>$first_color1,'first_color'=>$first_color,'first_color2'=>$first_color2,'first_BW'=>$first_BW,'first_bw1'=>$first_bw1,'first_bw2'=>$first_bw2,'ground_BW'=>$ground_BW,'ground_bw1'=>$ground_bw1,'ground_bw2'=>$ground_bw2,'ground_color'=>$ground_color,'ground_color1'=>$ground_color1,'ground_color2'=>$ground_color2,'printer_usage'=>$printer_usage,'printer1'=>$printer1,'printer2'=>$printer2, 'users' => $users]);
	}


	public function utilitysummary($year=null,$type=null,$branch=null)
	{
    
    	$me = JWTAuth::parseToken()->authenticate();

		if ($year==null)
		{
			$year=Date("Y");

		}

		if ($type==null)
		{
			$type="BOMBA";

		}

		$bill = DB::table("utility")
		->select(DB::raw('RIGHT(utility.Date_Paid,8) as Paid_Month'),DB::raw('SUM(utility.Paid_Amount) as Paid_Amount'))
		->where('utility.Type', '=',$type)
		->where('utility.Branch', '=',$branch)
		->where(DB::raw('RIGHT(utility.Date_Paid,4)'), '=',$year)
		->groupBy(DB::raw('RIGHT(utility.Date_Paid,8)'))
		->orderByRaw(DB::raw('LEFT(str_to_date(utility.Date_Paid,"%d-%M-%Y"),7)'))
		->get();

		$bills = "";
		$amount="";
		$months=array();

		foreach($bill as $key => $quote){
			$g[]=$quote->Paid_Month;
			$bills = implode(',', $g);

			$h[]=$quote->Paid_Amount;
			$amount = implode(',', $h);
		}

		$category=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','utility')
		->where('options.Field', '=','Type')
		->orderBy('options.Option')
		->get();

		$branches=DB::table('utility')
		->distinct('utility.Branch')
		->select('utility.Branch')
		->get();

		return json_encode(['me'=>$me,'year'=>$year,'type'=>$type,'bill'=>$bill,'bills'=>$bills,'branches'=>$branches,'branch'=>$branch,'amount'=>$amount,'category'=>$category]);
	}


	public function phonebillsummary($year=null,$operator=null,$number=null)
	{
    
    	$me = JWTAuth::parseToken()->authenticate();

		$filter="1";

		if ($year==null)
		{
			$year=Date("Y");
			// $year=2017;
		}

		if ($operator==null)
		{
			$numbers=array();
		}

		else {
			# code...
			$filter.=" AND phonebills.Type='".$operator."'";

			$numbers=DB::table('phonebills')
			->distinct('phonebills.Phone_No')
			->select('phonebills.Phone_No')
			->where('type', '=',$operator)
			->orderBy('phonebills.Phone_No')
			->get();
		}

		if ($number==null)
		{

		}

		else {
			$filter.=" AND phonebills.Phone_No='".$number."'";
		}

		# code...
		$bill = DB::table("phonebills")
		->select(DB::raw('RIGHT(phonebills.Transaction_Date,8) as Paid_Month'),DB::raw('SUM(phonebills.Transfer_Amount) AS Transfer_Amount'))
		->whereRaw($filter)
		->where(DB::raw('RIGHT(phonebills.Transaction_Date,4)'), '=',$year)
		->groupBy(DB::raw('RIGHT(phonebills.Transaction_Date,8)'))
		->orderByRaw(DB::raw('LEFT(str_to_date(phonebills.Transaction_Date,"%d-%M-%Y"),7)'))
		->get();

		$bills = "";
		$amount="";
		$months=array();

		foreach($bill as $key => $quote){
			$g[]=$quote->Paid_Month;
			$bills = implode(',', $g);

			$h[]=$quote->Transfer_Amount;
			$amount = implode(',', $h);
		}

		$category=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','utility')
		->where('options.Field', '=','Phone_Type')
		->orderBy('options.Option')
		->get();

		return json_encode(['me'=>$me,'year'=>$year,'operator'=>$operator,'number'=>$number,'bill'=>$bill,'bills'=>$bills,'amount'=>$amount,'numbers'=>$numbers,'category'=>$category]);
	}

	public function creditcardsummary($year=null,$owner=null,$type=null)
	{

    	$me = JWTAuth::parseToken()->authenticate();

		$filter="1";

		if ($year==null)
		{
			$year=Date("Y");
			// $year=2017;
		}

		if ($owner==null)
		{
			$types=array();
		}

		else {
			# code...
			$filter.=" AND creditcards.Owner='".$owner."'";

			if ($owner == 'ADELINE') {
			   $types = [
				   "SCCB - 2162",
	               "SCCB - 4464",
	               "HSBC - 2411",
	               "HLBB - 8200",
	               "PBB - 9111",
	               "UOB - 1922",
	               "CIMB - 3289",
	               "RHB - 9312",
	           ];
	        } else {
	        	$types = [
	               "SCCB - 8079",
	               "SCCB - 9600",
	               "RHB - 1010N",
	               "CITI - 9228",
	               "HLBB - 8200",
	               "PBB - 5108",
	               "UOB - 4539",
	               "CIMB - 7900",
	               "MBB - 2687 & 2679",
	               "MBB - 3803 & 3779"
	            ];
        	}
		}

		if ($type==null)
		{


		}
		else {
			$filter.=" AND creditcards.Type='".$type."'";
		}

		# code...
		$bill = DB::table("creditcards")
		->select(DB::raw('RIGHT(creditcards.Payment_Date,8) as Paid_Month'),DB::raw('SUM(creditcards.Amount) AS Amount'))
		->whereRaw($filter)
		->where(DB::raw('RIGHT(creditcards.Payment_Date,4)'), '=',$year)
		->groupBy(DB::raw('RIGHT(creditcards.Payment_Date,8)'))
		->orderByRaw(DB::raw('LEFT(str_to_date(creditcards.Payment_Date,"%d-%M-%Y"),7)'))
		->get();

		$bills = "";
		$amount="";
		$months=array();

		foreach($bill as $key => $quote){
			$g[]=$quote->Paid_Month;
			$bills = implode(',', $g);

			$h[]=$quote->Amount;
			$amount = implode(',', $h);
		}

		return json_encode(['me'=>$me,'year'=>$year,'owner'=>$owner,'type'=>$type,'bill'=>$bill,'bills'=>$bills,'amount'=>$amount, 'types' => $types]);
	}


	public function vehicleexpensessummary($year=null,$carnumber=null)
	{
	           
    	$me = JWTAuth::parseToken()->authenticate();
		$companyselected = request()->get('company');
		$filter="1";

		if ($year==null)
		{
			$year=Date("Y");
			$year=2017;
		}

		$monthTable = DB::raw("(
			select 'Jan $year' as Payment_Month union all
			select 'Feb $year' union all
			select 'Mar $year' union all
			select 'Apr $year' union all
			select 'May $year' union all
			select 'Jun $year' union all
			select 'Jul $year' union all
			select 'Aug $year' union all
			select 'Sep $year' union all
			select 'Oct $year' union all
			select 'Nov $year' union all
			select 'Dec $year') as Payment_Month_Table 
		");

		if ($carnumber==null)
		{
			if (! empty($companyselected)) {
				$summary = DB::table($monthTable)
				->select('Payment_Month_Table.Payment_Month as Paid_Month',DB::raw('coalesce(shellcardexpenses.Amount, 0) AS Shellcard'),DB::raw('coalesce(tng.Total_Reload,0) AS TouchNGo'),DB::raw('coalesce(summons.Amount, 0) AS Summon'),DB::raw('(SELECT Shellcard + TouchNGo + Summon) as Amount'))
				->leftJoin(DB::raw("(
					SELECT SUM(shellcardexpenses.Amount) as Amount, shellcardexpenses.Payment_Month
					FROM shellcardexpenses
					RIGHT JOIN shellcards ON shellcards.Id = shellcardexpenses.ShellCardId
					RIGHT JOIN roadtax ON roadtax.Vehicle_No = shellcards.Vehicle_No
					WHERE roadtax.Option = 'Vehicle List' AND roadtax.Owner = '$companyselected' AND
					RIGHT(shellcardexpenses.Payment_Month,4) = $year
					GROUP BY shellcardexpenses.Payment_Month
				) as shellcardexpenses"),'shellcardexpenses.Payment_Month','=','Payment_Month_Table.Payment_Month')
				->leftJoin(DB::raw("(
					SELECT REPLACE(RIGHT(reload.Date_Reload,8),'-',' ') as Date_Reload,
					SUM(reload.Total_Reload) as Total_Reload
					FROM reload
					RIGHT JOIN touchngo ON touchngo.Card_No = reload.Card_No
					RIGHT JOIN roadtax ON roadtax.Vehicle_No = touchngo.Vehicle_No
					WHERE RIGHT(Date_Reload,4) = $year AND roadtax.Owner = '$companyselected'
					GROUP BY RIGHT(Date_Reload,8)
				) as tng"), 'tng.Date_Reload', '=', 'Payment_Month_Table.Payment_Month')
				->leftJoin(DB::raw("(
					SELECT REPLACE(RIGHT(summons.Settlement_Date,8),'-',' ') as Settlement_Date,
					SUM(summons.Amount) as Amount
					FROM summons
					RIGHT JOIN roadtax ON roadtax.Vehicle_No = summons.Vehicle_No
					WHERE RIGHT(Settlement_Date,4) = $year AND roadtax.Owner = '$companyselected'
					GROUP BY RIGHT(Settlement_Date,8)
				) as summons"), 'summons.Settlement_Date', '=', 'Payment_Month_Table.Payment_Month')
				->groupBy('Payment_Month_Table.Payment_Month')
				->orderByRaw(DB::raw('str_to_date(Payment_Month_Table.Payment_Month,"%b %Y")'))
				->get();
			} else {
				$summary = DB::table($monthTable)
				->select('Payment_Month_Table.Payment_Month as Paid_Month',DB::raw('coalesce(shellcardexpenses.Amount, 0) AS Shellcard'),DB::raw('coalesce(tng.Total_Reload,0) AS TouchNGo'),DB::raw('coalesce(summons.Amount, 0) AS Summon'),DB::raw('(SELECT Shellcard + TouchNGo + Summon) as Amount'))
				->leftJoin(DB::raw("(SELECT SUM(shellcardexpenses.Amount) as Amount, shellcardexpenses.Payment_Month FROM shellcardexpenses WHERE RIGHT(Payment_Month,4) = $year GROUP BY shellcardexpenses.Payment_Month) as shellcardexpenses"),'shellcardexpenses.Payment_Month','=','Payment_Month_Table.Payment_Month')
				->leftJoin(DB::raw("(SELECT REPLACE(RIGHT(reload.Date_Reload,8),'-',' ') as Date_Reload, SUM(reload.Total_Reload) as Total_Reload FROM reload WHERE RIGHT(Date_Reload,4) = $year GROUP BY RIGHT(Date_Reload,8)) as tng"), 'tng.Date_Reload', '=', 'Payment_Month_Table.Payment_Month')
				->leftJoin(DB::raw("(SELECT REPLACE(RIGHT(summons.Settlement_Date,8),'-',' ') as Settlement_Date, SUM(summons.Amount) as Amount FROM summons WHERE RIGHT(Settlement_Date,4) = $year GROUP BY RIGHT(Settlement_Date,8)) as summons"), 'summons.Settlement_Date', '=', 'Payment_Month_Table.Payment_Month')
				// ->whereRaw($filter)
				->groupBy('Payment_Month_Table.Payment_Month')
				->orderByRaw(DB::raw('str_to_date(Payment_Month_Table.Payment_Month,"%b %Y")'))
				->get();
			}
		}
		else {

			$filter ="roadtax.Vehicle_No='".$carnumber."'";

			$summary = DB::table($monthTable)
			->select('Payment_Month_Table.Payment_Month as Paid_Month',DB::raw('coalesce(shellcardexpenses.Amount, 0) AS Shellcard'),DB::raw('coalesce(tng.Total_Reload,0) AS TouchNGo'),DB::raw('coalesce(summons.Amount, 0) AS Summon'),DB::raw('(SELECT Shellcard + TouchNGo + Summon) as Amount'))
			->leftJoin(DB::raw("(
				SELECT SUM(shellcardexpenses.Amount) as Amount, shellcardexpenses.Payment_Month
				FROM shellcardexpenses
				RIGHT JOIN shellcards ON shellcards.Id = shellcardexpenses.ShellCardId
				RIGHT JOIN roadtax ON roadtax.Vehicle_No = shellcards.Vehicle_No
				WHERE roadtax.Option = 'Vehicle List' AND $filter AND
				RIGHT(shellcardexpenses.Payment_Month,4) = $year
				GROUP BY shellcardexpenses.Payment_Month
			) as shellcardexpenses"),'shellcardexpenses.Payment_Month','=','Payment_Month_Table.Payment_Month')
			->leftJoin(DB::raw("(
				SELECT REPLACE(RIGHT(reload.Date_Reload,8),'-',' ') as Date_Reload,
				SUM(reload.Total_Reload) as Total_Reload
				FROM reload
				RIGHT JOIN touchngo ON touchngo.Card_No = reload.Card_No
				RIGHT JOIN roadtax ON roadtax.Vehicle_No = touchngo.Vehicle_No
				WHERE RIGHT(Date_Reload,4) = $year AND $filter
				GROUP BY RIGHT(Date_Reload,8)
			) as tng"), 'tng.Date_Reload', '=', 'Payment_Month_Table.Payment_Month')
			->leftJoin(DB::raw("(
				SELECT REPLACE(RIGHT(summons.Settlement_Date,8),'-',' ') as Settlement_Date,
				SUM(summons.Amount) as Amount
				FROM summons
				RIGHT JOIN roadtax ON roadtax.Vehicle_No = summons.Vehicle_No
				WHERE RIGHT(Settlement_Date,4) = $year AND $filter
				GROUP BY RIGHT(Settlement_Date,8)
			) as summons"), 'summons.Settlement_Date', '=', 'Payment_Month_Table.Payment_Month')

			->groupBy('Payment_Month_Table.Payment_Month')
			->orderByRaw(DB::raw('str_to_date(Payment_Month_Table.Payment_Month,"%b %Y")'))
			->get();
		}

		$company=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','users')
		->where('options.Field', '=','Company')
		->orderBy('options.Option')
		->get();
		// $summary = DB::table("roadtax")
		// 	->select(DB::raw('shellcardexpenses.Payment_Month as Paid_Month'),DB::raw('SUM(IF(shellcardexpenses.Amount,shellcardexpenses.Amount,0)+IF(reload.Total_Reload,reload.Total_Reload,0)) AS Amount'),DB::raw('SUM(IF(shellcardexpenses.Amount,shellcardexpenses.Amount,0)) AS Shellcard'),DB::raw('SUM(IF(reload.Total_Reload,reload.Total_Reload,0)) AS TouchNGo'))
		// 	->leftJoin('shellcards','roadtax.Vehicle_No','=','shellcards.Vehicle_No')
		// 	->leftJoin('shellcardexpenses','shellcardexpenses.ShellCardId','=',DB::raw('shellcards.Id AND RIGHT(shellcardexpenses.Payment_Month,4)='.$year))
		// 	->leftJoin('touchngo','roadtax.Vehicle_No','=','touchngo.Vehicle_No')
		// 	->leftJoin('reload','reload.Card_No','=',DB::raw('touchngo.Card_No AND LEFT(str_to_date(reload.Date_Reload,"%d-%M-%Y"),7) = LEFT(str_to_date(shellcardexpenses.Payment_Month,"%b %Y"),7)'))
		// 	->whereRaw($filter)
		// 	->where('roadtax.Option', '=','Vehicle List')
		// 	->groupBy(DB::raw('shellcardexpenses.Payment_Month'))
		// 	->orderByRaw(DB::raw('str_to_date(shellcardexpenses.Payment_Month,"%b %Y")'))
		// 	->get();

		$titles = "";
		$amount="";
		$months=array();
		$shellcard="";
		$touchngo="";

		foreach($summary as $key => $quote)
		{

			$g[]=$quote->Paid_Month;
			$titles = implode(',', $g);

			$h[]=$quote->Amount;
			$amount = implode(',', $h);

			$i[]=$quote->Shellcard;
			$shellcard = implode(',', $i);

			$j[]=$quote->TouchNGo;
			$touchngo = implode(',', $j);

			$k[]=$quote->Summon;
			$summon = implode(',', $k);
		}

		if (! empty($companyselected)) 
		{

			$vehiclenumbers=DB::table('roadtax')
			->distinct('Vehicle_No')
			->select('Vehicle_No','Owner')
			->where('roadtax.Option', '=','Vehicle List')
			->where('roadtax.Owner', '=', $companyselected)
			->orderBy('Vehicle_No')
			->get();
		} else 
		{

			$vehiclenumbers=DB::table('roadtax')
			->distinct('Vehicle_No')
			->select('Vehicle_No','Owner')
			->where('roadtax.Option', '=','Vehicle List')
			->orderBy('Vehicle_No')
			->get();
		}


		return json_encode(['me'=>$me,'year'=>$year,'carnumber'=>$carnumber,'summary'=>$summary,'titles'=>$titles,'amount'=>$amount,'shellcard'=>$shellcard,'touchngo'=>$touchngo,'vehiclenumbers'=>$vehiclenumbers, 'company' => $company, 'companyselected' => $companyselected, 'summon'=> $summon]);
	}

	public function agreement($type)
	{

    	$me = JWTAuth::parseToken()->authenticate();

		$agreement = DB::table('agreement')
		->select('agreement.Id','agreement.Type','agreement.Department','agreement.Date_of_Agreement','agreement.Description_of_Agreement','agreement.Expiry_Date','agreement.Remarks','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Agreement" Group By TargetId) as max'), 'max.TargetId', '=', 'agreement.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Agreement"'))
		->get();

		$category=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','assets')
		->where('options.Field', '=','Agreement_Type')
		->orderBy('options.Option')
		->get();

		return json_encode(['me'=>$me,'agreement'=>$agreement,'type'=>$type,'category'=>$category]);
	}


	public function property($type)
	{
    
    	$me = JWTAuth::parseToken()->authenticate();

		$property = DB::table('property')
		->select('property.Id','property.Type','property.Address','property.Landlord','property.Tenant','property.Company','property.Department','property.Business','property.Area','property.Property_Type','property.Status','property.Rental','property.TNB','property.Water','property.IWK','property.Start','property.End','property.Security_Deposit','property.Utility_Deposit','property.Termination_Notice','property.Agreement','property.Keys','property.Owner','property.Contact_Person','property.Remarks','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Property" Group By TargetId) as max'), 'max.TargetId', '=', 'property.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Property"'))
		->get();

		$category=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','assets')
		->where('options.Field', '=','Property_Type')
		->orderBy('options.Option')
		->get();

		$company=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','users')
		->where('options.Field', '=','Company')
		->orderBy('options.Option')
		->get();

		$area = DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','assets')
		->where('options.Field', '=','Area')
		->orderBy('options.Option')
		->get();

		return json_encode(['me'=>$me,'property'=>$property,'type'=>$type,'category'=>$category,'company'=>$company, 'area' => $area]);
	}


	public function filingsystem($type)
	{
    
    	$me = JWTAuth::parseToken()->authenticate();

		$filingsystem = DB::table('filingsystem')
		->select('filingsystem.Id','filingsystem.Type','filingsystem.Box_No','filingsystem.Box_File','filingsystem.File_Type','filingsystem.Company','filingsystem.Description','filingsystem.File_No','filingsystem.Date','filingsystem.Year','filingsystem.Prepared_By','filingsystem.Destruction_Date','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Filing System" Group By TargetId) as max'), 'max.TargetId', '=', 'filingsystem.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Filing System"'))
		->get();

		$company=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','users')
		->where('options.Field', '=','Company')
		->orderBy('options.Option')
		->get();

		$category=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','assets')
		->where('options.Field', '=','Filing_Type')
		->orderBy('options.Option')
		->get();

		return json_encode(['me'=>$me,'filingsystem'=>$filingsystem,'type'=>$type,'category'=>$category, 'company' => $company]);
	}
	
}
