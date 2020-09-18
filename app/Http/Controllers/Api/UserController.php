<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use DateTime;
use Carbon\Carbon;
use Input;
use Hash;
use Storage;
use File;

class UserController extends Controller {

  public function probationnotification()
	{

    $stafflist = DB::table('users')
    ->select('StaffId','Name','Department','Position','Joining_Date as Join_Date')
    ->whereRaw("DATEDIFF(now(),str_to_date(users.Joining_Date,'%d-%M-%Y'))>180")
    ->where('Confirmation_Date', '=','')
    ->get();

    $subscribers = DB::table('notificationtype')
    ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
    ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
    ->where('notificationtype.Id','=',56)
    ->get();

    $emails = array();

    foreach ($subscribers as $subscriber)
    {
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

    if($stafflist)
    {
      // Mail::send('emails.probationnotification', ['stafflist'=>$stafflist], function($message) use ($emails,$NotificationSubject)
      // {
      //   $emails = array_filter($emails);
      //   array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
      //   $message->to($emails)->subject($NotificationSubject.' ['.date('d-M-Y').']');
      // });
    }
	}

  public function confirmationnotification()
  {
    $startDate = date("d-M-Y", strtotime("+20 day", strtotime("first day of last month")));
    $endDate = date("d-M-Y", strtotime("+19 day", strtotime("first day of this month")));

    $stafflist = DB::table('users')
    ->select('StaffId','Name','Department','Position','Joining_Date as Join_Date','Confirmation_Date')
    ->whereRaw("str_to_date(users.Confirmation_Date,'%d-%M-%Y') BETWEEN str_to_date('$startDate','%d-%M-%Y') AND str_to_date('$endDate','%d-%M-%Y')")
    ->get();

    $subscribers = DB::table('notificationtype')
    ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
    ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
    ->where('notificationtype.Id','=',67)
    ->get();

    $emails = array();

    foreach ($subscribers as $subscriber)
    {
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

    if($stafflist)
    {
      // Mail::send('emails.confirmationnotification', ['stafflist'=>$stafflist, 'startDate' => $startDate, 'endDate' => $endDate], function($message) use ($emails,$NotificationSubject, $startDate, $endDate)
      // {
      //   array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
      //   $emails = array_filter($emails);
      //   $message->to($emails)->subject($NotificationSubject.' ['.$startDate.' - '. $endDate .']');
      // });

      return 1;
    }

    return 0;
  }

  public function updatemonthlypresavings()
  {

    $today = date('d-M-Y', strtotime('today'));

    $presavings = DB::table('presaving')
    ->leftJoin('users','users.Id', '=', 'presaving.UserId')
    ->whereRaw('(str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y") OR users.Resignation_Date = "") AND (presaving.Presaving_End_Date = "" OR (str_to_date(presaving.Presaving_End_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y")))')
    ->select('users.Id as UserId','presaving.Id','presaving.Presaving_Monthly_Amount','presaving.created_by')
    ->get();

    $arrNewRecords = [];
    $arrNewDeductionRecords = [];

    foreach($presavings as $presaving) {

      // array_push($arrNewRecords, [
      //   'PresavingId'  => $presaving->Id,
      //   'Type'          => 'Saving',
      //   'Payment_Date'  => $today,
      //   'Amount'        => $presaving->Presaving_Monthly_Amount,
      //   'created_at'    => date('Y-m-d h:i:s')
      // ]);
      $recordId = DB::table('presavingrecords')->insertGetId([
        'PresavingId'  => $presaving->Id,
        'Type'          => 'Saving',
        'Payment_Date'  => $today,
        'created_by'    => 562,
        'Amount'        => $presaving->Presaving_Monthly_Amount,
        'created_at'    => date('Y-m-d h:i:s')
      ]);

      array_push($arrNewDeductionRecords, [
        'UserId' => $presaving->UserId,
        'Type' => 'PRE-SAVING SCHEME',
        'Month' => date('F Y'),
        'Date' => date('d-M-Y'),
        'Amount' => $presaving->Presaving_Monthly_Amount,
        'FinalAmount' => $presaving->Presaving_Monthly_Amount,
        'Description' => '[AUTO FROM PRE-SAVING RECORD]',
        'TableRowId' => $recordId,
        'created_by' => 562,
        'created_at' => date('Y-m-d h:i:s')
      ]);

    }

    // DB::table('presavingrecords')->insert($arrNewRecords);
    DB::table('staffdeductions')->insert($arrNewDeductionRecords);
    return 1;

  }

  public function authenticatePayslip(Request $request)
  {
      $me = JWTAuth::parseToken()->authenticate();

      if ($request->password == $me->Payslip_Password) {
          return response()->json(['success' => 'success']);
      }

      return response()->json(['error' => 'Not authorized.'],403);
  }

    public function downloadpayslip(Request $request)
    {
      $me = JWTAuth::parseToken()->authenticate();

      if ($request->password == $me->Payslip_Password) {
          $month = $request->month;
          $year = $request->year;
          $staffid = $me->StaffId;

          $exist = Storage::has("payslips/$year/$month/$staffid.pdf");

          if ($exist) {
              $file = storage_path("app/payslips/$year/$month/$staffid.pdf");

              $headers = [
                  'Content-Type' => 'application/pdf',
              ];

              return response()->download($file, 'Payslip_'. $staffid . '_' . $month . '_' . $year . '.pdf', $headers);
          }

          return response()->json(['error' => 'File not found.'],404);

      }

      return response()->json(['error' => 'Not authorized.'],403);

    }

    public function getbalance()
  	{
  			$auth = JWTAuth::parseToken()->authenticate();
  			$me = (new AuthController)->get_current_user($auth->Id);

        $ewallet = DB::table('users')
  			->select('users.Id',
  			DB::raw('(select IF(SUM(Amount),SUM(Amount),0) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id) as `Total_TopUp`'),
  			DB::raw('(select IF(SUM(Amount),SUM(Amount),0) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id) as `Total_Expenses`'),
  			DB::raw('(select IF(SUM(Amount),SUM(Amount),0) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id)-(select IF(SUM(Amount),SUM(Amount),0) FROM ewallet Where Type!="Top-up" and Expenses_Type!="Shell Card" and ewallet.UserId=users.Id) as Balance'))
  			->where('users.Id', '=',$me->UserId)
  			->orderBy('users.Name')
  			->first();

  			return json_encode($ewallet);

  	}

    public function getrecord(Request $request)
  	{
  			$auth = JWTAuth::parseToken()->authenticate();
  			$me = (new AuthController)->get_current_user($auth->Id);

        $input = $request->all();
        $d=date('d');

        $start=$input["Start_Date"];
        $end=$input["End_Date"];

        $ewalletrecord = DB::table('ewallet')
  			->select('ewallet.Id','ewallet.Date','projects.Project_Name','tracker.Project_Code','ewallet.Type','ewallet.Expenses_Type','ewallet.Amount','ewallet.Remarks','ewallet.created_at','ewallet.updated_at','creator.Name as Created_By', DB::raw('GROUP_CONCAT(files.Web_Path) as Web_Path'))
  			->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
  			->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
  			->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
        ->leftJoin('files', 'files.TargetId', '=', DB::raw('ewallet.Id and files.`Type`="eWallet"'))
  			->where('ewallet.UserId', '=',$me->UserId)
        ->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
  			->orderByRaw('str_to_date(ewallet.Date,"%d-%M-%Y") DESC')
        ->groupBy('ewallet.Id')
  			->get();

  			return json_encode($ewalletrecord);

  	}

    public function insertexpenses(Request $request)
    {

        $auth = JWTAuth::parseToken()->authenticate();
        $me = (new AuthController)->get_current_user($auth->Id);
        $time = Carbon::now();
        $input = $request->all();

        //check edi created
        $ewalletrecord = DB::table('ewallet')
  			->where('ewallet.UserId', '=',$me->UserId)
        ->where('ewallet.ProjectId', '=',$input["ProjectId"])
        ->where('ewallet.TrackerId', '=',$input["TrackerId"])
        ->where('ewallet.Expenses_Type', '=',$input["Expenses_Type"])
        ->where('ewallet.Date', '=',$input["Date"])
        ->where('ewallet.Amount', '=',$input["Amount"])
  			->get();

        if(1==1)//hau, temporary turn off the checking
        {
          $insertid=DB::table('ewallet')->insertGetId([
              'ProjectId' => $input["ProjectId"],
              'TrackerId' => $input["TrackerId"],
              'Type' => 'Expenses',
              'Expenses_Type' => $input["Expenses_Type"],
              'Date' => $input["Date"],
              'Amount' => $input["Amount"],
              'Remarks' => $input["Remarks"],
              'UserId' => $me->UserId,
              'created_by' => $me->UserId,
              'created_at' => DB::raw('now()')
          ]);

          $project = DB::table('projects')
          ->where('projects.Id', '=',$input["ProjectId"])
    			->first();

          $tracker = DB::table('tracker')
          ->where('tracker.Id', '=',$input["TrackerId"])
    			->first();

          if (!file_exists('/private/upload/Site Document/'.$project->Project_Name.'/'.$tracker->Project_Code."/eWallet")) {

  				  File::makeDirectory('/private/upload/Site Document/'.$project->Project_Name.'/'.$tracker->Project_Code."/eWallet", 0777, true, true);

  		    	File::makeDirectory('/private/upload/Site Document/'.$project->Project_Name.'/'.$tracker->Project_Code."/eWallet", 0777, true, true);

          }

          $destinationPath="private/upload/Site Document/".$project->Project_Name."/".$tracker->Project_Code."/eWallet/";

          $type="eWallet";
          $uploadcount=count($request->file('attachment'));

            if ($request->hasFile('attachment')) {

                for ($i=0; $i <$uploadcount ; $i++) {
                    # code...
                    $file = $request->file('attachment')[$i];
                    $extension = $file->getClientOriginalExtension();
                    $originalName=$file->getClientOriginalName();
                    $fileSize=$file->getSize();
                    $fileName=$originalName.$time;
                    $upload_success = $file->move($destinationPath, $fileName);

                    $insert=DB::table('files')->insertGetId(
                        ['Type' => $type,
                         'TargetId' => $insertid,
                         'File_Name' => $originalName.$time,
                         'File_Size' => $fileSize,
                         'UserId'=>$me->UserId,
                         'Web_Path' => $destinationPath.$fileName
                        ]
                    );


                }

          }
          return $insertid;
        }
        else {
          // code...

          return -999;//edi exist
        }


    }

    public function deletehistory(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();

    $me = (new AuthController)->get_current_user($auth->Id);

    $input = $request->all();

    $id =  DB::table('ewallet')
    ->where('Id', '=', $input["HistoryId"])
    ->delete();

    return $id;

  }

  public function staffloans(Request $request)
  {
        $input = $request->all();


    $me = JWTAuth::parseToken()->authenticate();
    $loans = DB::table('staffloan')
    ->select('staffloan.Id','users.StaffId as Staff_ID','users.Name','users.Department','staffloan.Reason','staffloan.Date_Approved','staffloan.Repayment_Start_On','staffloan.Amount',DB::raw('"" AS Total_Paid'),DB::raw('"" AS Total_Paid_Month'),DB::raw('"" AS Outstanding_Balance'),'staffloan.created_at','staffloan.updated_at')
    ->leftJoin('users','staffloan.UserId','=','users.Id')
    ->where('staffloan.UserId',747)
    ->orderBy('users.Name')
    ->get();

    $users = DB::table('users')
    ->get();

    return json_encode($loans);

  }

  public function loanrecord($id)
  {
    $me = (new AuthController)->get_current_user();

    $staffloan = DB::table('staffloan')
    ->select('staffloan.Id','users.StaffId as Staff_ID','users.Name','users.Department','staffloan.Reason','staffloan.Date_Approved','staffloan.Repayment_Start_On','staffloan.Amount',DB::raw('"" AS Total_Paid'),DB::raw('"" AS Outstanding_Balance'),'staffloan.created_at','staffloan.updated_at')
    ->leftJoin('users','staffloan.UserId','=','users.Id')
    ->where('staffloan.Id', '=',$id)
    ->orderBy('users.Name')
    ->first();

    $loans = DB::table('repaymentrecords')
    ->select('repaymentrecords.Id','repaymentrecords.StaffLoanId','repaymentrecords.Payment_Date','repaymentrecords.Amount','repaymentrecords.created_at','repaymentrecords.updated_at')
    ->where('repaymentrecords.StaffLoanId', '=',$id)
    ->orderBy('repaymentrecords.Payment_Date')
    ->get();

    return view('loanrecord', ['me' => $me,'staffloan'=>$staffloan,'loans' => $loans,'id'=>$id]);

  }

  public function allloans(Request $request){

    $me = JWTAuth::parseToken()->authenticate();

    $loans = DB::table('staffloan')
    ->select('staffloan.Id','users.StaffId as Staff_ID','users.Name','users.Department','staffloan.Reason','staffloan.Date_Approved','staffloan.Repayment_Start_On','staffloan.Amount','staffloanstatusses.Status',DB::raw('"" AS Total_Paid'),DB::raw('"" AS Total_Paid_Month'),DB::raw('"" AS Outstanding_Balance'),'staffloan.created_at','staffloan.updated_at')
    ->leftJoin('users','staffloan.UserId','=','users.Id')
    ->leftJoin('staffloanstatusses','staffloan.Id','staffloanstatusses.StaffLoanId')
    ->where('staffloan.UserId',747)
    ->orderBy('users.Name')
    ->get();
  }

  public function myloan()
    {
    $me = JWTAuth::parseToken()->authenticate();

      $date=date('d-M-Y', strtotime('first day of last month'));
      $date = date('d-M-Y', strtotime($date . " +20 days"));

      $user = DB::table('users')
      ->select('Id','Name','Bank_Account_No','Position')
      ->where('users.Id','=',$me->UserId)
      ->first();

      $projects = DB::table('projects')
         ->get();

      $options= DB::table('options')
      ->whereIn('Table', ["users"])
      ->orderBy('Table','asc')
      ->orderBy('Option','asc')
      ->get();

      $myloan = DB::table('staffloans')
      ->select('staffloans.Id','staffloanstatuses.Status','staffloans.Type','staffloans.Date',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'),DB::raw('Format((staffloans.Total_Approved),2) as Total_Approved'),'projects.Project_Name','approver.Name as Approver')
      ->leftJoin('users','users.Id','=','staffloans.UserId')
      ->leftJoin('projects','projects.Id','=','staffloans.ProjectId')
      ->leftJoin( DB::raw('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max'), 'max.StaffLoanId', '=', 'staffloans.Id')
      ->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', DB::raw('max.`maxid`'))
      ->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
      ->where('staffloans.UserId', '=', $me->Id)
      ->where('staffloanstatuses.Status','!=','null')
      ->get();

      $myloan2=DB::table('staffloans')
      ->select('staffloans.Id','staffloanstatuses.Status','staffloans.UserId','staffloans.Purpose','staffloans.Type')
      ->leftJoin('staffloanstatuses','staffloans.Id','=','staffloanstatuses.StaffLoanId')
      // ->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', DB::raw('max.`maxid`'))
      ->where('staffloans.UserId','=',$me->Id)
      ->where('staffloanstatuses.Status','!=','')
      ->get();

      return json_encode($myloan);

    }

    public function getbank(){
      $me = JWTAuth::parseToken()->authenticate();

      $user = DB::table('users')
      ->select('Id','Name','Bank_Account_No','Position')
      ->where('users.Id','=',$me->Id)
      ->first();

      return json_encode($user);

    }


}
