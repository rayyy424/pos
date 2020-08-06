<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrackerController extends Controller {

    public function taskchecking()
    {
        $tasklist = DB::table('tasks')
        ->leftJoin(DB::raw('(SELECT Max(Id) as maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'),'tasks.Id','=','max.TaskId')
        ->leftJoin('taskstatuses','taskstatuses.Id','=','max.maxid')
        ->select('tasks.Id','tasks.created_at')
        ->where('taskstatuses.Status','=','Assigned')
        ->where('tasks.type','<>','Todo')
        ->get();

        foreach ($tasklist as $key => $value) {
            $now = Carbon::now();
            $diff = (strtotime($now) - strtotime($value->created_at))/ (3600);

            $options= DB::table('options')
    				->where('Field','=', 'Task Accept Threhold(Hours)')
    				->first();

            $hour=$options->Option;

            if($diff > $hour)
            {
                DB::table('taskstatuses')
                ->insert([
                    'TaskId' => $value->Id,
                    'Status' => "Rejected",
                    'Comment' => "No Response within ".$hour." hour(s)",
                    'UserId' => 562,
                    'created_at' => Carbon::now()
                ]);
            }
        }
    }

    public function accepttask(Request $request)
    {
        $me = JWTAuth::parseToken()->authenticate();
        $me = (new AuthController)->get_current_user($me->Id);

        $input = $request->all();

        $tasks = DB::table('tasks')
        ->where('tasks.UserId', '=',$me->UserId)
        ->where('tasks.Status', '=','')
        ->where('tasks.Project_Name', '=',$input["Project_Name"])
        ->where('tasks.Project_Code', '=',$input["Project_Code"])
        ->where('tasks.Threshold', '=',$input["Threshold"])
        ->where('tasks.Current_Task', '=',$input["Current_Task"])
        ->where('tasks.Previous_Task', '=',$input["Previous_Task"])
        ->where('tasks.Previous_Task_Date', '=',$input["Previous_Task_Date"])
        ->get();

        if(!$tasks)
        {
          $insertid=DB::table('tasks')->insertGetId([
              'UserId' => $me->UserId,
              'Status' => 'Acknowledge',
              'Project_Name' => $input["Project_Name"],
              'Project_Code' => $input["Project_Code"],
              'Threshold' => $input["Threshold"],
              'Current_Task' => $input["Current_Task"],
              'Previous_Task' => $input["Previous_Task"],
              'Previous_Task_Date' => $input["Previous_Task_Date"],
              'created_by' => $me->UserId,
              'created_at' => DB::raw('now()')
          ]);
        }

        return $insertid;
    }

    public function rejecttask(Request $request)
    {
        $me = JWTAuth::parseToken()->authenticate();
        $me = (new AuthController)->get_current_user($me->Id);

        $input = $request->all();

        $tasks = DB::table('tasks')
        ->where('tasks.UserId', '=',$me->UserId)
        ->where('tasks.Status', '=','')
        ->where('tasks.Project_Name', '=',$input["Project_Name"])
        ->where('tasks.Project_Code', '=',$input["Project_Code"])
        ->where('tasks.Threshold', '=',$input["Threshold"])
        ->where('tasks.Current_Task', '=',$input["Current_Task"])
        ->where('tasks.Previous_Task', '=',$input["Previous_Task"])
        ->where('tasks.Previous_Task_Date', '=',$input["Previous_Task_Date"])
        ->get();

        if(!$tasks)
        {
          $insertid=DB::table('tasks')->insertGetId([
              'UserId' => $me->UserId,
              'Status' => 'Reject',
              'Project_Name' => $input["Project_Name"],
              'Project_Code' => $input["Project_Code"],
              'Threshold' => $input["Threshold"],
              'Current_Task' => $input["Current_Task"],
              'Previous_Task' => $input["Previous_Task"],
              'Previous_Task_Date' => $input["Previous_Task_Date"],
              'Remarks' => $input["Remarks"],
              'created_by' => $me->UserId,
              'created_at' => DB::raw('now()')
          ]);
        }

        return $insertid;
    }

    public function getmypendingtask()
    {
        $me = JWTAuth::parseToken()->authenticate();

        // $agings = DB::table('agings')
        // ->select('agings.Start_Date', 'agings.End_Date')
        // ->join('agingsubscribers', 'agingsubscribers.AgingId', '=', 'agings.Id')
        // ->where('agingsubscribers.UserId', $me->Id)
        // ->distinct()
        // ->get();

        // $columns = [];
        // $pendingtasks = [];

        // if (count($agings)) {
        //     foreach ($agings as $aging) {
        //         array_push($columns, "`tracker`.`{$aging->Start_Date}`", "`tracker`.`{$aging->End_Date}`");
        //     }

        //     $columns = array_unique($columns);

        //     $rows = DB::table('agings')
        //     ->select('agings.ProjectId', DB::raw("CASE WHEN tracker.Site_Name='' THEN tracker.`Site Name` ELSE tracker.Site_Name END as 'Site_Name'"),'projects.Project_Name','agings.Start_Date', 'agings.Threshold','agings.End_Date', 'agings.Sequence','tracker.Project_Code', 'agingsubscribers.UserId', DB::raw(implode(",",$columns)))
        //     ->join('agingsubscribers', 'agingsubscribers.AgingId', '=', 'agings.Id')
        //     ->join('tracker', 'tracker.ProjectId', '=', 'agings.ProjectId')
        //     ->leftJoin('projects', 'projects.Id', '=', 'agings.ProjectId')
        //     ->where('agingsubscribers.UserId', $me->Id)
        //     ->get();

        //     foreach($rows as $row) {
        //         if ($row->{$row->Start_Date} != '' && $row->{$row->End_Date} == '') {
        //             array_push($pendingtasks, [
        //                 'Project_Name'  => $row->Project_Name,
        //                 'Project_Code'     => $row->Project_Code,
        //                 'Threshold'     => $row->Threshold,
        //                 'Site_Name'     => $row->Site_Name,
        //                 'Current_Task'  => $row->End_Date,
        //                 'Previous_Task' => $row->Start_Date,
        //                 'Previous_Task_Date' => $row->{$row->Start_Date}
        //             ]);
        //         }
        //     }

        // }

        $tasks = DB::table('tasks as task')
                    ->select('task.Id as TaskId', 'projects.Project_Name', 'task.Project_Code','task.Threshold', 'task.Site_Name', 'task.Current_Task', 'task.Previous_Task','task.Previous_Task_Date','status.Status','task.target_time','task.complete_time','task.UserId')
                    ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'task.Id')
                    ->leftJoin('taskstatuses as status', 'status.Id', '=', 'max.maxid')
                    ->leftJoin('projects', 'projects.Id', '=', 'task.ProjectId')
                    // ->whereRaw("(status.Status = 'Assigned' OR status.Status = 'In Progress')")
                    ->whereRaw('status.Id In (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by tasks.Id)')
        ->whereRaw("(status.Status = 'Assigned')")

                //     ->whereRaw("(status.Status = 'Assigned') AND
                //         (task.Project_Code NOT IN (Select task.Project_Code from tasks
                // left join (SELECT Max(Id) maxid,TaskId from taskstatuses group by TaskId) as max1 on tasks.Id = max1.TaskId left join taskstatuses on taskstatuses.Id=max1.maxid
                //   where tasks.Previous_Task = task.Previous_Task AND tasks.Project_Code = task.Project_Code AND tasks.Current_Task=task.Current_Task AND (taskstatuses.Status='In Progress' OR taskstatuses.Status='Completed') group by tasks.Project_Code AND tasks.Current_Task))
                //   AND
                //   (task.Current_Task NOT IN (Select task.Current_Task from tasks
                //                   left join (SELECT Max(Id) maxid,TaskId from taskstatuses group by TaskId) as max1 on tasks.Id = max1.TaskId left join taskstatuses on taskstatuses.Id=max1.maxid
                //                     where tasks.Previous_Task = task.Previous_Task AND tasks.Project_Code = task.Project_Code AND tasks.Current_Task=task.Current_Task AND (taskstatuses.Status='In Progress' OR taskstatuses.Status='Completed') group by tasks.Project_Code AND tasks.Current_Task))")

                    // ->whereRaw('status.Id In (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by concat(Project_Code,Current_Task))')

                    ->where('task.UserId','=', $me->Id)


                    ->where('task.type','=','')
->get();


//         $t=DB::table('tasks')->where('Project_Code',$tasks->Project_Code)
//         ->where('Current_Task',$tasks->Current_Task)
// // ->groupby('Current_Task','Previous_Task')
//         ->get();
//         return json_encode($t);
        return json_encode(['tasks' => $tasks, 'count' => count($tasks)]);
    }


    public function getmyacknowledgedtask()
    {
        $me = JWTAuth::parseToken()->authenticate();

        $tasks = DB::table('tasks')
        ->select('tasks.Id as TaskId', 'projects.Project_Name', 'tasks.Project_Code','tasks.Threshold', 'tasks.Site_Name', 'tasks.Current_Task', 'tasks.Previous_Task','tasks.Previous_Task_Date','taskstatuses.Status','tasks.complete_date','tasks.target_date','tasks.assign_by','tasks.assign_date','tasks.target_time','tasks.complete_time')
        ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
        ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
        ->leftJoin('projects', 'projects.Id', '=', 'tasks.ProjectId')
        ->whereRaw("(taskstatuses.Status = 'In Progress')")
        ->where('tasks.UserId', $me->Id)
        ->where('tasks.type','=','')
        ->get();

        foreach($tasks as &$t){

            $t->startdate = date("d-M-Y",strtotime($t->Previous_Task_Date));

            $t->enddate = $t->Threshold;

            $t->all = date("d-M-Y", strtotime($t->startdate.'+'.$t->enddate.'day'));
        }

        return json_encode(['tasks' => $tasks, 'count' => count($tasks)]);
    }


    public function getmycompletedtask()
    {
        $me = JWTAuth::parseToken()->authenticate();

        $tasks = DB::table('tasks')
        ->select('tasks.Id as TaskId', 'projects.Project_Name', 'tasks.Project_Code','tasks.Threshold', 'tasks.Site_Name', 'tasks.Current_Task', 'tasks.Previous_Task','tasks.Previous_Task_Date','taskstatuses.Status','tasks.complete_date','tasks.target_date','tasks.assign_by','tasks.assign_date','tasks.target_time','tasks.complete_time')
        ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
        ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
        ->leftJoin('projects', 'projects.Id', '=', 'tasks.ProjectId')
        ->whereRaw("(taskstatuses.Status = 'Completed')")
        ->where('tasks.UserId', $me->Id)
        ->where('tasks.type','=','')
        ->get();
        // ->first();

        foreach($tasks as &$t){

            $t->startdate = date("d-M-Y",strtotime($t->Previous_Task_Date));

            $t->enddate = $t->Threshold;

            $t->all = date("d-M-Y", strtotime($t->startdate.'+'.$t->enddate.'day'));
        }


        return json_encode(['tasks' => $tasks, 'count' => count($tasks)]);
    }


    public function getmypendingtaskcount($value='')
    {
        $me = JWTAuth::parseToken()->authenticate();

        // $agings = DB::table('agings')
        // ->select('agings.Start_Date', 'agings.End_Date')
        // ->join('agingsubscribers', 'agingsubscribers.AgingId', '=', 'agings.Id')
        // ->where('agingsubscribers.UserId', $me->Id)
        // ->distinct()
        // ->get();

        // $columns = [];
        // $pendingtasks = 0;

        // if (count($agings)) {
        //     foreach ($agings as $aging) {
        //         array_push($columns, "`tracker`.`{$aging->Start_Date}`", "`tracker`.`{$aging->End_Date}`");
        //     }

        //     $columns = array_unique($columns);

        //     $rows = DB::table('agings')
        //     ->select('agings.Start_Date', 'agings.End_Date', DB::raw(implode(",",$columns)))
        //     ->join('agingsubscribers', 'agingsubscribers.AgingId', '=', 'agings.Id')
        //     ->join('tracker', 'tracker.ProjectId', '=', 'agings.ProjectId')
        //     ->where('agingsubscribers.UserId', $me->Id)
        //     ->get();


        //     foreach($rows as $row) {
        //         if ($row->{$row->Start_Date} != '' && $row->{$row->End_Date} == '') {
        //             $pendingtasks = $pendingtasks + 1;
        //         }
        //     }
        // }
        //
        $tasks = DB::table('tasks')

                    ->select(DB::raw('COUNT(tasks.Id) as TaskCount'))
                    ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
                    ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
                    // ->whereRaw("(taskstatuses.Status = 'Assigned' OR taskstatuses.Status = 'In Progress')")
                    ->whereRaw("(taskstatuses.Status = 'Assigned')")

                    ->groupBy('tasks.UserId')
                    ->where('tasks.UserId', $me->Id)
                    ->where('tasks.type','=','')
                    ->first();

        if($tasks)
        {
          return json_encode(['badge_count' => $tasks->TaskCount]);
        }
        else {
          // code...
          return json_encode(['badge_count' => 0]);
        }
    }

    public function getmyoverduetask()
    {
        //task overdue complete
        $me = JWTAuth::parseToken()->authenticate();

        $tasks = DB::table('tasks')
        ->select('tasks.Id as TaskId', 'projects.Project_Name', 'tasks.Project_Code','tasks.Threshold', 'tasks.Site_Name', 'tasks.Current_Task', 'tasks.Previous_Task','tasks.Previous_Task_Date','taskstatuses.Status','tasks.complete_date','tasks.target_date','tasks.assign_by','tasks.assign_date','tasks.target_time','tasks.complete_time','tasks.UserId')
        ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
        ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
        ->leftJoin('projects', 'projects.Id', '=', 'tasks.ProjectId')
        ->whereRaw("(taskstatuses.Status = 'Completed')")
        ->whereRaw('tasks.UserId="'.$me->Id.'" AND complete_date !="" AND str_to_date(complete_date,"%d-%M-%Y") > str_to_date(target_date,"%d-%M-%Y")')
        ->ORwhereRaw('tasks.UserId="'.$me->Id.'" AND complete_date !="" AND tasks.complete_time > tasks.target_time')
        ->where('tasks.UserId', $me->Id)
        ->where('tasks.type','=','')
        ->get();
        // ->first();

        // foreach($tasks as &$t){

        //     $t->startdate = date("d-M-Y",strtotime($t->Previous_Task_Date));

        //     $t->enddate = $t->Threshold;

        //     $t->all = date("d-M-Y", strtotime($t->startdate.'+'.$t->enddate.'day'));
        // }


        return json_encode(['tasks' => $tasks, 'count' => count($tasks)]);
    }

    public function getmyoverduetask2()
    {
        //task overdue
        $me = JWTAuth::parseToken()->authenticate();
        $today = date("d-M-Y");
        $time = date('H:i:s');

        $tasks = DB::table('tasks')
        ->select('tasks.Id as TaskId', 'projects.Project_Name', 'tasks.Project_Code','tasks.Threshold', 'tasks.Site_Name', 'tasks.Current_Task', 'tasks.Previous_Task','tasks.Previous_Task_Date','taskstatuses.Status','tasks.complete_date','tasks.target_date','tasks.assign_by','tasks.assign_date','tasks.target_time','tasks.complete_time','tasks.UserId')
        ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
        ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
        ->leftJoin('projects', 'projects.Id', '=', 'tasks.ProjectId')
        // ->whereRaw("(taskstatuses.Status = 'Overdue')")
        ->whereRaw('tasks.UserId="'.$me->Id.'" AND target_date != "" AND complete_date = "" AND str_to_date("'.$today.'","%d-%M-%Y") > str_to_date(target_date,"%d-%M-%Y")')
        ->ORwhereRaw('tasks.UserId="'.$me->Id.'" AND target_date != "" AND complete_date = "" AND str_to_date("'.$today.'","%d-%M-%Y") > str_to_date(target_date,"%d-%M-%Y") AND "'.$time.'" > time_format(target_time,"%H:%i:%s")')
        ->where('tasks.UserId', $me->Id)
        ->where('tasks.type','=','')
        ->get();
        // ->first();

        // foreach($tasks as &$t){

        //     $t->startdate = date("d-M-Y",strtotime($t->Previous_Task_Date));

        //     $t->enddate = $t->Threshold;

        //     $t->all = date("d-M-Y", strtotime($t->startdate.'+'.$t->enddate.'day'));
        // }


        return json_encode(['tasks' => $tasks, 'count' => count($tasks)]);
    }

    public function notifypendingtasks()
    {
        // $agings = DB::table('agings')
        // ->select('agings.Start_Date', 'agings.End_Date')
        // ->join('agingsubscribers', 'agingsubscribers.AgingId', '=', 'agings.Id')
        // ->distinct()
        // ->get();

        // $columns = [];

        // if (count($agings)) {
        //     $condition = "1 AND (";

        //     foreach ($agings as $aging) {
        //         $condition .= "(`tracker`.`{$aging->Start_Date}` <> '' AND `tracker`.`{$aging->Start_Date}` IS NOT NULL AND (`tracker`.`{$aging->End_Date}` = '' OR `tracker`.`{$aging->End_Date}` IS NULL)) OR ";
        //         array_push($columns, "`tracker`.`{$aging->Start_Date}`", "`tracker`.`{$aging->End_Date}`");
        //     }

        //     $condition = substr($condition,0,strlen($condition)-4) . ")";

        //     $columns = array_unique($columns);
        //     $rows = DB::table('agings')
        //     ->select('agings.Id as AgingId', 'agings.ProjectId', 'agings.Start_Date', 'agings.End_Date', 'agings.Sequence', 'agingsubscribers.UserId', DB::raw(implode(",",$columns)),'users.Player_Id')
        //     ->join('agingsubscribers', 'agingsubscribers.AgingId', '=', 'agings.Id')
        //     ->join('users', 'users.Id', '=', 'agingsubscribers.UserId')
        //     ->join('tracker', 'tracker.ProjectId', '=', 'agings.ProjectId')
        //     ->whereRaw($condition)
        //     ->get();

        //     $pendingtasks = [];
        //     $groups = [];

        //     foreach($rows as $row) {
        //         if ($row->{$row->Start_Date} != '' && $row->{$row->End_Date} == '' && $row->Player_Id) {
        //             if (isset($groups[$row->UserId]['taskcount'])) {
        //                 $groups[$row->UserId]['taskcount'] = $groups[$row->UserId]['taskcount'] + 1;
        //             } else {
        //                 $groups[$row->UserId]['taskcount'] = 1;
        //                 $groups[$row->UserId]['playerids'] = $row->Player_Id;
        //             }
        //         }
        //     }

        //     foreach($groups as $group) {

        //         $playerids  = array($group['playerids']);
        //         $title      = "Pending Task Notification";
        //         $message    = 'You have ' . $group['taskcount'] . ' pending tasks today.';
        //         $type       = 'Task';

        //         $this->sendNotification($playerids, $title, $message, $type);
        //     }
        // }


        $tasks = DB::table('tasks')
                    ->select(DB::raw('COUNT(tasks.Id) as TaskCount'), 'users.Player_Id')
                    ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
                    ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
                    ->leftJoin('users','users.Id', '=','tasks.UserId')
                    ->leftJoin('projects', 'projects.Id', '=', 'tasks.ProjectId')
                    ->whereRaw("(taskstatuses.Status = 'Assigned' OR taskstatuses.Status = 'In Progress')")
                    ->groupBy('tasks.UserId')
                    ->get();

            foreach($tasks as $task) {

                $playerids  = [$task->Player_Id];
                $title      = "Pending Task Notification";
                $message    = 'You have ' . $task->TaskCount . ' pending tasks today.';
                $type       = 'Task';

                $this->sendNotification($playerids, $title, $message, $type);
            }

        return 1;

    }

    public function todonotification()
    {
        $daily = array();
        $weekly = array();
        $monthly = array();

        $tasks = DB::table('tasks')
            ->select('tasks.Id','tasks.reminder','users.Player_Id')
            ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
            ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
            ->leftJoin('users','users.Id', '=','tasks.UserId')
            ->whereRaw("(taskstatuses.Status = 'Assigned' OR taskstatuses.Status = 'In Progress')")
            ->where('type','=','Todo')
            ->get();

        foreach($tasks as $task) {
            if($task->reminder == "Daily")
            {
                array_push($daily, $task->Id);
            }
            else if($task->reminder == "Weekly")
            {
                array_push($weekly, $task->Id);
            }
            else
            {
                array_push($monthly, $task->Id);
            }
        }

        $this->notifytodo($daily,'Daily');
        $this->notifytodo($weekly,'Weekly');
        $this->notifytodo($monthly,'Monthly');

        return 1;

    }

    public function notifytodo($id,$type)
    {
        $tasks = DB::table('tasks')
                    ->select('tasks.Current_Task','tasks.assign_date','users.Player_Id')
                    ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
                    ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
                    ->leftJoin('users','users.Id', '=','tasks.UserId')
                    ->whereRaw("(taskstatuses.Status = 'Assigned' OR taskstatuses.Status = 'In Progress')")
                    ->whereIn('tasks.Id',$id)
                    ->get();

        $today = Carbon::now()->format('d-M-Y');

            foreach($tasks as $task) {
                if($type == "Daily")
                {
                    $playerids  = [$task->Player_Id];
                    $title      = "Pending Task Notification";
                    $message    = $task->Current_Task . ' is still Pending';
                    $type       = 'To-do List';

                    $this->sendNotification($playerids, $title, $message, $type);

                }
                else if($type == "Weekly")
                {
                    $day = date('t',strtotime($task->assign_date));
                    $check = date('d-M-Y',strtotime($task->assign_date.'+ 7 day'));
                    $playerids  = [$task->Player_Id];
                    $title      = "Pending Task Notification";
                    $message    = $task->Current_Task . ' is still Pending';
                    $type       = 'To-do List';
                    if(strtotime($today) == strtotime($check))
                    {
                        $this->sendNotification($playerids, $title, $message, $type);
                    }

                }
                else
                {
                    $day = date('t',strtotime($task->assign_date));
                    $check = date('d-M-Y',strtotime($task->assign_date.'+'.$day.'day'));
                    $playerids  = [$task->Player_Id];
                    $title      = "Pending Task Notification";
                    $message    = $task->Current_Task . ' is still Pending';
                    $type       = 'To-do List';
                    if($today == $check)
                    {
                        $this->sendNotification($playerids, $title, $message, $type);
                    }

                }

            }

    }

    public function taskchangeack(Request $request)
    {
        $me = JWTAuth::parseToken()->authenticate();
        $statuses = ['Assigned', 'In Progress', 'Accepted', 'Rejected'];

        $input = $request->all();
        $input['target_date'] = date('d-M-Y',strtotime($input['target_date']));

        if (in_array($input['Status'], $statuses)) {

            if ($input['Status'] == 'In Progress') {

                $current = DB::table('tasks')
                ->select('tasks.Previous_Task','tasks.Project_Code','tasks.Site_Name','tasks.ProjectId','tasks.target_date')
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
                ->select('users.Id','users.Player_Id','tasks.UserId','users.Name')
                ->leftJoin('users','users.Id','=','tasks.assign_by')
                ->where('tasks.UserId','=',$me->Id)
                ->where('tasks.Id','=', $input['ListId'])
                ->first();

                $b = DB::table('users')
                ->select('Id','Player_Id')
                ->whereIn('Id',[655,645,855])
                    // ->whereIn('Id',[1193,562])
                    ->get();

                $test = array();

                foreach ($b as $key => $value) {
                    // dd($value);
                    array_push($test, $value->Player_Id);
                }

// dd($a);

                if ($a->Player_Id){
                    // dd($a);
                    $playerids = $test;
                    $title      = "Task Accepted Notification";
                    $message    = 'The task ' . $input['Current_Task'] . ' has been accepted by ' .$a->Name . ' are In Progress';
                    $type       = 'Task';

                    $this->sendNotification($playerids, $title, $message, $type);
                }
                else{
                    return 1;
                }
            }
        }

        return 1;
    }

    public function taskchangecomplete(Request $request)
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
                ->select('tasks.Previous_Task','tasks.Project_Code','tasks.Site_Name','tasks.ProjectId','tasks.target_date')
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

                //     $a = DB::table('tasks')
                //     ->select('users.Id','users.Player_Id','tasks.UserId')
                //     ->leftJoin('users','users.Id','=','tasks.assign_by')
                //     ->where('tasks.UserId','=',$me->Id)
                //     ->where('tasks.Id','=', $input['ListId'])
                //     ->first();

                //     if ($a->Player_Id){

                //         $playerids = [$a->Player_Id];
                //         $title      = "Task Overdue Notification";
                //         $message    = 'The task ' . $input['Current_Task'] . ' has been Overdue';
                //         $type       = 'Task';

                //         $this->sendNotification($playerids, $title, $message, $type);
                //     }
                //     else{
                //         return 1;
                //     }
                // } else

                DB::table('taskstatuses')->insert([
                    'TaskId' => $input['ListId'],
                    'Status' => $input['Status'],
                    'UserId' => $me->Id,
                ]);

                $a = DB::table('tasks')
                ->select('users.Id','users.Player_Id','tasks.UserId','users.Name')
                ->leftJoin('users','users.Id','=','tasks.assign_by')
                ->where('tasks.UserId','=',$me->Id)
                ->where('tasks.Id','=', $input['ListId'])
                ->first();

                $b = DB::table('users')
                ->select('Id','Player_Id')
                ->whereIn('Id',[655,645,855])
                    // ->whereIn('Id',[1193,562])
                    ->get();

                $test = array();

                foreach ($b as $key => $value) {
                    // dd($value);
                    array_push($test, $value->Player_Id);
                }

                if ($a->Player_Id){

                    $playerids = $test;
                    $title      = "Task Completed Notification";
                    $message    = 'The task ' . $input['Current_Task'] . ' by ' . $a->Name .' has been completed';
                    $type       = 'Task';

                    $this->sendNotification($playerids, $title, $message, $type);
                }
                else{
                    return 1;
                }

            }
        }

        return 1;
    }

    public function changetaskstatus(Request $request)
    {
        $me = JWTAuth::parseToken()->authenticate();

        $statuses = ['Assigned', 'In Progress', 'Accepted', 'Rejected'];

        $input = $request->all();

        if (in_array($input['Status'], $statuses)) {

            if ($input['Status'] == 'Rejected') {

                $current = DB::table('tasks')
                ->select('tasks.Previous_Task','tasks.Project_Code','tasks.Site_Name','tasks.ProjectId')
                ->where('Id', $input['TaskId'])
                ->where('UserId', $me->Id)
                // ->where('Status', '<>', 'Rejected')
                ->first();

                if ($current) {

                    DB::table('taskstatuses')->insert([
                        'TaskId' => $input['TaskId'],
                        'Comment' => $input['Reason'],
                        'Status' => $input['Status'],
                        'UserId' => $me->Id
                    ]);

                    $stat = DB::table('taskstatuses')
                    ->select('Id','TaskId','UserId')
                    ->where('TaskId','=',$input['TaskId'])
                    ->first();


                    $previous = DB::table('tasks')
                    ->select('tasks.Id','users.Id as UserId','users.Player_Id')
                    ->leftJoin('users', 'users.Id', '=', 'tasks.UserId')
                    ->where('Current_Task', $current->Previous_Task)
                    ->where('Project_Code', $current->Project_Code)
                    ->where('Site_Name', $current->Site_Name)
                    ->where('ProjectId', $current->ProjectId)
                    ->where('Status', '<>', 'Rejected')
                    ->get();

                    $b = DB::table('users')
                    ->select('Id','Player_Id')
                    ->whereIn('Id',[655,645,855])
                    // ->whereIn('Id',[1193])
                    ->get();

                    $test = array();
                    $userids = array();
                    foreach ($b as $key => $value) {
                        // dd($value);
                        array_push($test, $value->Player_Id);
                        array_push($userids, $value->Id);
                    }

                    foreach ($previous as $keys => $values) {
                        array_push($test, $values->Player_Id);
                        array_push($userids, $values->UserId);

                    }

                    // if (count($previous)) {

                    //     foreach($previous as $p) {

                    //         if ($p->Player_Id) {
                                $playerids  = $test;
                                $title      = "Task Rejected Notification";
                                $message    = 'The task '.$current->Previous_Task.' was rejected by '.$me->Name.'. Reason: ' . $input['Reason'];
                                $type       = 'Task';

                                $this->sendNotification($playerids, $title, $message, $type);
                            // }
                                foreach ($userids as $k => $v) {
                                    # code...
                                    DB::table('notificationstatus')->insert([
                                        'userid' => $v,
                                        'type' => 'Rejected Task',
                                        'seen' => 0,
                                        'TargetId' => $stat->Id,//taskstatuses Id
                                    ]);
                                }
                    //     }

                    // }
                }

            } else {

                $current = DB::table('tasks')
                ->select('tasks.Previous_Task','tasks.Project_Code','tasks.Site_Name','tasks.ProjectId')
                // ->where('Status', '<>', 'Rejected')
                ->where('Id', $input['TaskId'])
                ->where('UserId', $me->Id)
                ->first();

                DB::table('tasks')
                ->where('Id', $input['TaskId'])
                ->update([
                    'target_date'=>$input['target_date'],
                ]);

                if ($current) {
                    DB::table('taskstatuses')->insert([
                        'TaskId' => $input['TaskId'],
                        'Status' => $input['Status'],
                        'UserId' => $me->Id
                    ]);
                }
            }
        }

        return 1;
    }

    public function getnextrejectedtask()
    {
        $me = JWTAuth::parseToken()->authenticate();

        $rejectedtasks = DB::table('tasks')
        ->select('tasks.Id as TaskId', 'projects.Project_Name', 'tasks.Project_Code','tasks.Threshold', 'tasks.Site_Name', 'tasks.Current_Task', 'tasks.Previous_Task','tasks.Previous_Task_Date','taskstatuses.Status','tasks.complete_date','tasks.target_date','tasks.assign_by','tasks.assign_date','taskstatuses.Comment')
        ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'tasks.Id')
        ->leftJoin('users', 'users.Id', '=', 'max.maxid')

        ->leftJoin('taskstatuses', 'taskstatuses.Id', '=', 'max.maxid')
        ->leftJoin('projects', 'projects.Id', '=', 'tasks.ProjectId')
        ->join(
            DB::raw("(SELECT rejected.Id,rejected.ProjectId,rejected.Project_Code,rejected.Site_Name,rejected.Current_Task,rejected.Previous_Task,rejected.Previous_Task_Date,taskstatuses.Comment FROM tasks as rejected
                JOIN (SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max ON max.TaskId = rejected.Id
                JOIN taskstatuses ON taskstatuses.Id = max.maxid
                WHERE taskstatuses.Status = 'Rejected'
            ) AS rejected"), 'rejected.ProjectId', '=', DB::raw("tasks.ProjectId AND rejected.Project_Code = rejected.Project_Code AND rejected.Site_Name = rejected.Site_Name AND rejected.Previous_Task = tasks.Current_Task"))
        ->where('tasks.UserId', $me->Id)
        ->where('taskstatuses.Status','=','Rejected')
        ->where('taskstatuses.Comment','!=','Accepted by others')
        ->get();

        return json_encode($rejectedtasks);
    }

    function sendNotification(array $playerids, $title, $message, $type)
    {
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
