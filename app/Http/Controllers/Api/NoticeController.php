<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Input;
use DateTime;

class NoticeController extends Controller {

    public function getgenset()
    {
        $me = JWTAuth::parseToken()->authenticate();

        $gen=DB::table('serviceticket')
        ->select('serviceticket.Id','serviceticket.technicianId','gensetservice.ServiceId as ServiceId','gensetservice.Status')
        // ->leftJoin('gensetservice','serviceticket.Id','=','gensetservice.ServiceId')
        ->leftjoin(DB::raw('(SELECT Max(Id) as maxid,ServiceId from gensetservice group by ServiceId) as max'),'max.ServiceId','=','serviceticket.Id')
        ->leftjoin('gensetservice','gensetservice.Id','=','max.maxid')
        ->where('serviceticket.technicianId','=',$me->Id)
        // ->where('gensetservice.Status','=',"In-Progress")
        ->where('gensetservice.Status','!=',"Completed")
        ->get();

        return json_encode(['gen'=> $gen, 'count' => count($gen)]);

    }

  public function getnotice()
  {
    $me = JWTAuth::parseToken()->authenticate();

    $Date=date("d-M-Y", strtotime('+2 weeks'));
    $Today=date("d-M-Y");

    $notice = DB::table('noticeboards')
    ->select('noticeboards.Id','noticeboards.Title','noticeboards.Content','noticeboards.Start_Date','noticeboards.End_Date','users.Name as Created_By','noticeboards.created_at','f.FileName','f.Attachment','files.Web_Path')
    ->where(DB::raw('str_to_date(noticeboards.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$Date.'","%d-%M-%Y")'))
    ->where(DB::raw('str_to_date(noticeboards.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$Today.'","%d-%M-%Y")'))
    ->leftJoin('users', 'users.Id', '=', 'noticeboards.UserId')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')

    ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))

    ->leftJoin(DB::raw('(SELECT TargetId, GROUP_CONCAT( Web_Path SEPARATOR "|") as Attachment,GROUP_CONCAT( File_Name SEPARATOR "|") as FileName FROM files WHERE Type="Notice" GROUP BY TargetId) as f'),'f.TargetId','=','noticeboards.Id')
    ->orderBy(DB::raw('str_to_date(noticeboards.Start_Date,"%d-%M-%Y")'),'desc')
    ->get();

    return json_encode($notice);
  }

    public function getalllist()
    {
        $me = JWTAuth::parseToken()->authenticate();
        $today = date("d-M-Y");

        $list = DB::table('tasks')
        ->select('tasks.Id','tasks.Current_Task','tasks.Previous_Task','tasks.Previous_Task_Date','tasks.Project_Code','tasks.Site_Name','tasks.Threshold','tasks.ProjectId','tasks.UserId','tasks.complete_date','tasks.target_date','tasks.assign_by','tasks.type','tasks.assign_date','taskstatuses.Status','users.Name as AssignedName','tasks.target_time')
        ->leftJoin('users','tasks.assign_by','=','users.Id')
        ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
        ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
        // ->leftJoin('taskstatuses','tasks.Id','=','taskstatuses.TaskId')
        ->where('tasks.UserId','=',$me->Id)
        ->where('tasks.type','=','Todo')
        ->whereIn('taskstatuses.Status',['Assigned','In Progress'])
     //    ->whereRaw('
     //        ((target_date != "" AND complete_date = "" AND type = "Todo" AND taskstatuses.Status = "In Progress" AND str_to_date("'.$today.'","%d-%M-%Y") > str_to_date(target_date,"%d-%M-%Y"))
     //              OR taskstatuses.Status IN ("Assigned","In Progress")) AND
     //               tasks.UserId='.$me->Id.'
     // ')
        ->get();

        return json_encode(['list' => $list, 'count' => count($list)]);
    }

  public function getlistassigned()
  {
    $me = JWTAuth::parseToken()->authenticate();

    $list = DB::table('tasks')
    ->select('tasks.Id','tasks.Current_Task','tasks.Previous_Task','tasks.Previous_Task_Date','tasks.Project_Code','tasks.Site_Name','tasks.Threshold','tasks.ProjectId','tasks.UserId','tasks.complete_date','tasks.target_date','tasks.assign_by','tasks.type','tasks.assign_date','taskstatuses.Status','users.Name as AssignedName','tasks.target_time','tasks.complete_time')
    ->leftJoin('users','tasks.assign_by','=','users.Id')
    ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
    ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
    // ->leftJoin('taskstatuses','tasks.Id','=','taskstatuses.TaskId')
    ->where('tasks.UserId','=',$me->Id)
    ->where('tasks.type','=','Todo')
    ->where('taskstatuses.Status','=','Assigned')
    ->get();

    return json_encode(['list' => $list, 'count' => count($list)]);

  }

  public function getlistacknowledge()
  {
    $me = JWTAuth::parseToken()->authenticate();


    $listack = DB::table('tasks')
    ->select('tasks.Id','tasks.Current_Task','tasks.Previous_Task','tasks.Previous_Task_Date','tasks.Project_Code','tasks.Site_Name','tasks.Threshold','tasks.ProjectId','tasks.UserId','tasks.complete_date','tasks.target_date','tasks.assign_by','tasks.type','tasks.assign_date','taskstatuses.Status','users.Name as AssignedName','tasks.target_time','tasks.complete_time')
    ->leftJoin('users','tasks.assign_by','=','users.Id')
    ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
    ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
    // ->leftJoin('taskstatuses','tasks.Id','=','taskstatuses.TaskId')
    ->where('tasks.UserId','=',$me->Id)
    ->where('tasks.type','=','Todo')
    ->where('taskstatuses.Status','=','In Progress')
    ->get();

    return json_encode(['list' => $listack, 'count' => count($listack)]);

  }

  public function getlistrejected()
  {
    $me = JWTAuth::parseToken()->authenticate();

    $listack = DB::table('tasks')
    ->select('tasks.Id','tasks.Current_Task','tasks.Previous_Task','tasks.Previous_Task_Date','tasks.Project_Code','tasks.Site_Name','tasks.Threshold','tasks.ProjectId','tasks.UserId','tasks.complete_date','tasks.target_date','tasks.assign_by','tasks.type','tasks.assign_date','tasks.target_time','tasks.complete_time','taskstatuses.Status','users.Name as AssignedName','taskstatuses.Comment')
    ->leftJoin('users','tasks.assign_by','=','users.Id')
    ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
    ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
    // ->leftJoin('taskstatuses','tasks.Id','=','taskstatuses.TaskId')
    ->where('tasks.UserId','=',$me->Id)
    ->where('tasks.type','=','Todo')
    ->where('taskstatuses.Status','=','Rejected')
    ->get();

    return json_encode(['list' => $listack, 'count' => count($listack)]);
  }

  public function getlistcompleted()
  {
    $me = JWTAuth::parseToken()->authenticate();

    $Date=date("d-M-Y", strtotime('+2 weeks'));
    $Today=date("d-M-Y");

    $listack = DB::table('tasks')
    ->select('tasks.Id','tasks.Current_Task','tasks.Previous_Task','tasks.Previous_Task_Date','tasks.Project_Code','tasks.Site_Name','tasks.Threshold','tasks.ProjectId','tasks.UserId','tasks.complete_date','tasks.target_date','tasks.assign_by','tasks.type','tasks.assign_date','tasks.complete_time','tasks.target_time','taskstatuses.Status','users.Name as AssignedName')
    ->leftJoin('users','tasks.assign_by','=','users.Id')
    ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
    ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
    // ->leftJoin('taskstatuses','tasks.Id','=','taskstatuses.TaskId')
    ->where('tasks.UserId','=',$me->Id)
    ->where('tasks.type','=','Todo')
    ->where('taskstatuses.Status','=','Completed')
    ->get();

    return json_encode(['list' => $listack, 'count' => count($listack)]);
  }

  public function getlistoverdue()
  {
    //list overdue completed
    $me = JWTAuth::parseToken()->authenticate();


    $listack = DB::table('tasks')
    ->select('tasks.Id','tasks.Current_Task','tasks.Previous_Task','tasks.Previous_Task_Date','tasks.Project_Code','tasks.Site_Name','tasks.Threshold','tasks.ProjectId','tasks.UserId','tasks.complete_date','tasks.target_date','tasks.assign_by','tasks.type','tasks.assign_date','tasks.complete_time','tasks.target_time','taskstatuses.Status','users.Name as AssignedName')
    ->leftJoin('users','tasks.assign_by','=','users.Id')
    ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
    ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
    // ->whereRaw('complete_date !="" AND type = "Todo" AND str_to_date(complete_date,"%d-%M-%Y") > str_to_date(target_date,"%d-%M-%Y")')
    // ->ORwhereRaw('complete_date !="" AND type = "Todo" AND tasks.complete_time > tasks.target_time')
    ->whereRaw('tasks.complete_date !="" AND type="Todo" AND (str_to_date(concat(complete_date," ",complete_time),"%d-%M-%Y %H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))')

    // ->leftJoin('taskstatuses','tasks.Id','=','taskstatuses.TaskId')
    ->where('tasks.UserId','=',$me->Id)
    // ->where('tasks.type','=','Todo')
    ->where('taskstatuses.Status','=','Completed')
    ->get();

    return json_encode(['list' => $listack, 'count' => count($listack)]);

  }

  public function getoverduetodo(){
    //list overdue
    $me = JWTAuth::parseToken()->authenticate();
        $today = date("d-M-Y");
        $time = date('H:i:s');

    // $overduecompleted = DB::table('tasks')
    // ->select(DB::raw('COUNT(Id) as overduecompleted'))
    // ->whereRaw('complete_date !="" AND type != "Todo" AND str_to_date(complete_date,"%d-%M-%Y") > str_to_date(target_date,"%d-%M-%Y")')
    // ->first();
// dd($time);
    $overdue = DB::table('tasks')
    ->select('tasks.Id','tasks.Current_Task','tasks.Previous_Task','tasks.Previous_Task_Date','tasks.Project_Code','tasks.Site_Name','tasks.Threshold','tasks.ProjectId','tasks.UserId','tasks.complete_date','tasks.target_date','tasks.assign_by','tasks.type','tasks.assign_date','tasks.target_time','taskstatuses.Status','users.Name as AssignedName')
    ->leftJoin('users','tasks.assign_by','=','users.Id')
    ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
    ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
    ->whereRaw('tasks.UserId="'.$me->Id.'" AND target_date != "" AND complete_date = "" AND type = "Todo" AND str_to_date("'.$today.'","%d-%M-%Y") > str_to_date(target_date,"%d-%M-%Y")')
    ->ORwhereRaw('tasks.UserId="'.$me->Id.'" AND target_date != "" AND complete_date = "" AND type = "Todo" AND str_to_date("'.$today.'","%d-%M-%Y") > str_to_date(target_date,"%d-%M-%Y") AND "'.$time.'" > time_format(target_time,"%H:%i:%s")')
    ->where('tasks.UserId','=',$me->Id)
    // ->where('tasks.type','=','Todo')
    ->where('taskstatuses.Status','=','In Progress')

    ->get();

    return json_encode(['overdue' =>$overdue, 'count'=>count($overdue)]);
  }

  public function listchangetaskstatus(Request $request)
    {
        $me = JWTAuth::parseToken()->authenticate();
        $statuses = ['Assigned', 'In Progress', 'Accepted', 'Rejected'];

        $input = $request->all();
        // $input['target_date'] = date('d-M-Y',strtotime($input['target_date']));

        if (in_array($input['Status'], $statuses)) {

            if ($input['Status'] == 'Rejected') {

                $current = DB::table('tasks')
                ->select('tasks.Previous_Task','tasks.Project_Code','tasks.Site_Name','tasks.ProjectId','tasks.target_date')
                ->where('Id', $input['ListId'])
                ->where('UserId', $me->Id)
                // ->where('Status', '<>', 'Rejected')
                ->first();

                // DB::table('tasks')
                // ->where('Id', $input['ListId'])
                // ->update([
                //     'target_date'=>$input['target_date'],
                // ]);

                DB::table('taskstatuses')->insert([
                    'TaskId' => $input['ListId'],
                    'Status' => $input['Status'],
                    'Comment' => $input['Reason'],
                    'UserId' => $me->Id,
                ]);

                $a = DB::table('tasks')
                ->select('users.Id','users.Player_Id','tasks.UserId')
                ->leftJoin('users','users.Id','=','tasks.assign_by')
                ->where('tasks.UserId','=',$me->Id)
                ->where('tasks.Id','=', $input['ListId'])
                ->first();

                if ($a->Player_Id){
                    $playerids = [$a->Player_Id];
                    $title      = "Task Rejected Notification";
                    $message    = 'The task ' . $input['Current_Task'] . ' has been rejected. Reason : ' . $input['Reason'];
                    $type       = 'ToDo';

                    $this->sendNotification($playerids, $title, $message, $type);
                }
                else{
                    return 1;
                }
            }
        }

        return 1;
    }

    public function listchangeack(Request $request)
    {
        $me = JWTAuth::parseToken()->authenticate();
        $statuses = ['Assigned', 'In Progress', 'Accepted', 'Rejected'];

        $input = $request->all();
        $input['target_date'] = date('d-M-Y',strtotime($input['target_date']));

        if (in_array($input['Status'], $statuses)) {

            if ($input['Status'] == 'In Progress') {

                $current = DB::table('tasks')
                ->select('tasks.Previous_Task','tasks.Project_Code','tasks.Site_Name','tasks.ProjectId','tasks.target_date','tasks.target_time')
                ->where('Id', $input['ListId'])
                ->where('UserId', $me->Id)
                // ->where('Status', '<>', 'Rejected')
                ->first();

                DB::table('tasks')
                ->where('Id', $input['ListId'])
                ->update([
                    'target_date'=>$input['target_date'],
                    'target_time'=>$input['target_time']
                ]);

                DB::table('taskstatuses')->insert([
                    'TaskId' => $input['ListId'],
                    'Status' => $input['Status'],
                    'UserId' => $me->Id,
                ]);

                $a = DB::table('tasks')
                ->select('users.Id','users.Player_Id','tasks.UserId')
                ->leftJoin('users','users.Id','=','tasks.assign_by')
                ->where('tasks.UserId','=',$me->Id)
                ->where('tasks.Id','=', $input['ListId'])
                ->first();

                if ($a->Player_Id){
                    $playerids = [$a->Player_Id];
                    $title      = "Task Accepted Notification";
                    $message    = 'The task ' . $input['Current_Task'] . ' has been In Progress';
                    $type       = 'ToDo';

                    $this->sendNotification($playerids, $title, $message, $type);
                }
                else{
                    return 1;
                }
            }
        }

        return 1;
    }

    public function listchangecomplete(Request $request)
    {
        $me = JWTAuth::parseToken()->authenticate();
        $statuses = ['Assigned', 'In Progress', 'Completed', 'Rejected'];

        $input = $request->all();
        // $input['target_date'] = date('d-M-Y',strtotime($input['target_date']));
        $Date = date('d-M-Y');
        $completetime = date('H:i:s');


        if (in_array($input['Status'], $statuses)) {

            if ($input['Status'] == 'Completed') {

                $current = DB::table('tasks')
                ->select('tasks.Previous_Task','tasks.Project_Code','tasks.Site_Name','tasks.ProjectId','tasks.target_date','tasks.target_time')
                ->where('Id', $input['ListId'])
                ->where('UserId', $me->Id)
                // ->where('Status', '<>', 'Rejected')
                ->first();

                DB::table('tasks')
                ->where('Id', $input['ListId'])
                ->update([
                    'complete_date'=>$Date,
                    'complete_time' => $completetime,
                ]);

                // if (strtotime($Date) > strtotime($current->target_date)){
                //     DB::table('taskstatuses')->insert([
                //     'TaskId' => $input['ListId'],
                //     'Status' => 'Overdue',
                //     'UserId' => $me->Id,
                //     ]);
                // } else

                DB::table('taskstatuses')->insert([
                    'TaskId' => $input['ListId'],
                    'Status' => $input['Status'],
                    'UserId' => $me->Id,
                ]);

                $a = DB::table('tasks')
                ->select('users.Id','users.Player_Id','tasks.UserId')
                ->leftJoin('users','users.Id','=','tasks.assign_by')
                ->where('tasks.UserId','=',$me->Id)
                ->where('tasks.Id','=', $input['ListId'])
                ->first();

                if ($a->Player_Id){

                    $playerids = [$a->Player_Id];
                    $title      = "Task Completed Notification";
                    $message    = 'The task ' . $input['Current_Task'] . ' has been completed';
                    $type       = 'ToDo';

                    $this->sendNotification($playerids, $title, $message, $type);
                }
                else{
                    return 1;
                }

            }
        }

        return 1;
    }

    function sendNotification(array $playerids, $title, $message, $type){

        $heading = array(
            "en" => $title
        );

        $content = array(
            "en" => $message
        );


        $fields = array(
            'app_id'                => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            'include_player_ids'    => $playerids,
            'data'                  => array("type" => $type),
            'headings'              => $heading,
            'contents'              => $content,
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic NWU1YjI2ZmYtOTM3NS00NWRkLTk2YTYtOTM5N2Y3NGJhNDY4'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
    }

}
