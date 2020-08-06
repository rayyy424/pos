<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class NotificationController extends Controller {

  // Task
   public function getalltask(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();
    

    // $notifications = DB::table('notificationstatus')
    // ->where('UserId', $auth->Id)
    // ->where('Seen', 0)
    // ->where(function ($q) {
    //   $q->where('Type', 'Assigned');
    //   $q->orWhere('Type', 'Rejected Task');
      
    // })

    // ->get();

    
        $tasks = DB::table('tasks as task')
                    ->select('task.Id as TaskId', 'projects.Project_Name', 'task.Project_Code','task.Threshold', 'task.Site_Name', 'task.Current_Task', 'task.Previous_Task','task.Previous_Task_Date','status.Status')
                  ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'task.Id')
                    // ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'task.Id')
                    ->leftJoin('taskstatuses as status', 'status.Id', '=', 'max.maxid')
                    ->leftJoin('projects', 'projects.Id', '=', 'task.ProjectId')
                    // ->whereRaw("(taskstatuses.Status = 'Assigned' OR taskstatuses.Status = 'In Progress')")
                  //   ->whereRaw("(status.Status = 'Assigned') AND ('In Progress') NOT IN (Select taskstatuses.Status from tasks 
                  // left join (SELECT Max(Id) maxid,TaskId from taskstatuses group by TaskId) as max1 on tasks.Id = max1.TaskId left join taskstatuses on taskstatuses.Id=max1.maxid
                  // where tasks.Previous_Task = task.Previous_Task AND tasks.Project_Code = task.Project_Code AND tasks.Current_Task=task.Current_Task AND taskstatuses.Status='In Progress')")

                    ->where('task.UserId','=',$auth->Id)
                    ->where('task.type','=','')
                    ->whereIn('status.Status',['Assigned','In Progress'])

                    ->get();

    // return json_encode(['badge_count'=>count($notifications)+$tasks->TaskCount, 'notifications'=> $notifications]);
        return json_encode(['tasks' => $tasks, 'count' => count($tasks)]);

  }

  public function getalllist(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();
    

    // $notifications = DB::table('notificationstatus')
    // ->where('UserId', $auth->Id)
    // ->where('Seen', 0)
    // ->where(function ($q) {
    //   $q->where('Type', 'Assigned');
    //   $q->orWhere('Type', 'Rejected Task');
      
    // })

    // ->get();

    
        $tasks = DB::table('tasks as task')
                    ->select('task.Id as TaskId', 'projects.Project_Name', 'task.Project_Code','task.Threshold', 'task.Site_Name', 'task.Current_Task', 'task.Previous_Task','task.Previous_Task_Date','status.Status')
                    ->leftJoin(DB::raw('(SELECT MAX(Id) maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'), 'max.TaskId', '=', 'task.Id')
                    ->leftJoin('taskstatuses as status', 'status.Id', '=', 'max.maxid')
                    ->leftJoin('projects', 'projects.Id', '=', 'task.ProjectId')
                    // ->whereRaw("(taskstatuses.Status = 'Assigned' OR taskstatuses.Status = 'In Progress')")
                    ->whereRaw("(status.Status = 'Assigned') AND ('In Progress') NOT IN (Select taskstatuses.Status from tasks 
                  left join (SELECT Max(Id) maxid,TaskId from taskstatuses group by TaskId) as max1 on tasks.Id = max1.TaskId left join taskstatuses on taskstatuses.Id=max1.maxid
                  where tasks.Previous_Task = task.Previous_Task AND tasks.Project_Code = task.Project_Code AND tasks.Current_Task=task.Current_Task AND taskstatuses.Status='In Progress')")
                    ->where('task.UserId', $auth->Id)
                    ->where('task.type','=','ToDo')
                    ->get();

    // return json_encode(['badge_count'=>count($notifications)+$tasks->TaskCount, 'notifications'=> $notifications]);
        return json_encode(['tasks' => $tasks, 'count' => count($tasks)]);

  }

  public function getopentripbadge(Request $request)
  {
    $me = JWTAuth::parseToken()->authenticate();

    $input = $request->all();
    $isDriver = DB::table('users')
    ->select('Id')
    ->where('Position','LIKE','%Driver%')
    ->where('Id', $me->Id)
    ->count();


    // $mydelivery = DB::table('deliveryform')
    // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
    // ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
    // ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
    // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
    // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
    // ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
    // ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
    // ->leftJoin(DB::raw('(SELECT GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse, deliveryform.Id as deliveryformId FROM deliveryform LEFT JOIN deliveryitem ON deliveryitem.formId = deliveryform.Id LEFT JOIN inventories ON inventories.Id = deliveryitem.inventoryId GROUP BY deliveryform.Id) as wh'), 'wh.deliveryformId', '=', 'deliveryform.Id')
    // ->leftJoin('options','options.Id','=','deliveryform.Purpose')
    // // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
    // ->leftJoin('radius','radius.Id','=','deliveryform.Location')
    // ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at', 'wh.Warehouse','deliveryform.delivery_time','deliveryform.pick_up_time','deliveryform.pickup_date')
    // ->whereRaw("( (deliveryform.DriverId IS NULL OR deliveryform.DriverId = 0) AND ".$isDriver." ")
    // // ->whereRaw("((deliveryform.DriverId = $me->Id OR (deliveryform.DriverId IS NULL AND (roadtax.UserId = $me->Id OR roadtax.UserId2 = $me->Id OR roadtax.UserId3 = $me->Id))) OR (deliveryform.DriverId = 0 AND $isDriver))")
    // ->where('delivery_status','=','Processing')
    // ->orderBy(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),'asc')
    // ->get();

          $mydelivery = DB::table('deliveryform')
          ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
          ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
          ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
          ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
          ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
          ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
          ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
          ->leftJoin(DB::raw('(SELECT GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse, deliveryform.Id as deliveryformId FROM deliveryform LEFT JOIN deliveryitem ON deliveryitem.formId = deliveryform.Id LEFT JOIN inventories ON inventories.Id = deliveryitem.inventoryId GROUP BY deliveryform.Id) as wh'), 'wh.deliveryformId', '=', 'deliveryform.Id')
          ->leftJoin('options','options.Id','=','deliveryform.Purpose')
          ->leftJoin('radius','radius.Id','=','deliveryform.Location')
          ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at', 'wh.Warehouse','deliveryform.delivery_time','deliveryform.pick_up_time','deliveryform.pickup_date')
          // ->whereRaw("(deliveryform.DriverId IS NULL OR deliveryform.DriverId = 0) AND ".$isDriver." ") // logistics 1
          ->whereRaw("((deliveryform.DriverId = $me->Id OR ( (deliveryform.DriverId IS NULL OR deliveryform.DriverId = 0) AND (roadtax.UserId = $me->Id OR roadtax.UserId2 = $me->Id OR roadtax.UserId3 = $me->Id))) OR (deliveryform.DriverId = 0 AND ".$isDriver."))") // logistics 2
          ->whereRaw('deliverystatuses.delivery_status = "Processing"')
          ->get();


    return json_encode(['badge_count'=>count($mydelivery), 'mydelivery'=> $mydelivery]);
  }

  // Task
  public function gettaskbadge(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();

    $notifications = DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('Seen', 0)
    ->where(function ($q) {
      $q->where('Type', 'New Task');
    })

    ->get();

    return json_encode(['badge_count'=>count($notifications), 'notifications'=> $notifications]);
  }

  // Rejected Task
  public function getrejectedtaskbadge(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();

    $notifications = DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('Seen', 0)
    ->where(function ($q) {
      $q->where('Type', 'Rejected Task');
    })
    ->get();

    return json_encode(['badge_count'=>count($notifications), 'notifications'=> $notifications]);
  }

  public function updaterejectedtaskbadge(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();
    $targetid = $request->get('TargetId');
    DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('TargetId', $targetid)
    ->where('Type', 'Rejected Task')
    ->update([
      'Seen' => 1
    ]);

    return 1;
  }

  // Notice
  public function getnoticebadge(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();

    $notifications = DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('Seen', 0)
    ->where(function ($q) {
      $q->where('Type', 'New Notice');
    })

    ->get();

    return json_encode(['badge_count'=>count($notifications), 'notifications'=> $notifications]);
  }

  public function updatenoticebadge(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();
    $targetid = $request->get('TargetId');
    DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('TargetId', $targetid)
    ->where('Type', 'New Notice')
    ->update([
      'Seen' => 1
    ]);


    return 1;
  }

  // Leave
   public function getallleave(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();

    $notifications = DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('Seen', 0)
    ->where(function ($q) {
      $q->where('Type', 'Pending Leave');
      $q->orWhere('Type', 'Leave Approved');
      $q->orWhere('Type', 'Leave Rejected');
      $q->orWhere('Type', 'Leave Cancelled');
    })

    ->get();

    return json_encode(['badge_count'=>count($notifications), 'notifications'=> $notifications]);
  }

  public function getleaveall(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();

    $notifications = DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('Seen', 0)
    ->where(function ($q) {
      $q->where('Type', 'Leave Approved');
      $q->orWhere('Type', 'Leave Rejected');
      $q->orWhere('Type', 'Leave Cancelled');

    })

    ->get();

    return json_encode(['badge_count'=>count($notifications), 'notifications'=> $notifications]);
  }

  public function getleavepending(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();

    $notifications = DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('Seen', 0)
    ->where('Type', 'Pending Leave')
    ->get();


    return json_encode(['badge_count'=>count($notifications), 'notifications'=> $notifications]);
  }

  public function updateleavepending(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();
    $targetid = $request->get('TargetId');
    DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('TargetId', $targetid)
    ->where('Type', 'Pending Leave')
    ->update([
      'Seen' => 1
    ]);


    return 1;
  }

  public function getleaveapproved(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();

    $notifications = DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('Seen', 0)
    ->where('Type', 'Leave Approved')
    ->get();


    return json_encode(['badge_count'=>count($notifications), 'notifications'=> $notifications]);
  }

  public function updateleaveapproved(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();
    $targetid = $request->get('TargetId');
    DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('TargetId', $targetid)
    ->where('Type', 'Leave Approved')
    ->update([
      'Seen' => 1
    ]);


    return 1;
  }

  public function getleaverejected(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();

    $notifications = DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('Seen', 0)
    ->where('Type', 'Leave Rejected')
    ->get();


    return json_encode(['badge_count'=>count($notifications), 'notifications'=> $notifications]);
  }

  public function updateleaverejected(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();
    $targetid = $request->get('TargetId');
    DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('TargetId', $targetid)
    ->where('Type', 'Leave Rejected')
    ->update([
      'Seen' => 1
    ]);


    return 1;
  }

  public function getleavecancelled(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();

    $notifications = DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('Seen', 0)
    ->where('Type', 'Leave Cancelled')
    ->get();


    return json_encode(['badge_count'=>count($notifications), 'notifications'=> $notifications]);
  }

  public function updateleavecancelled(Request $request)
  {
    $auth = JWTAuth::parseToken()->authenticate();
    $targetid = $request->get('TargetId');
    DB::table('notificationstatus')
    ->where('UserId', $auth->Id)
    ->where('TargetId', $targetid)
    ->where('Type', 'Leave Cancelled')
    ->update([
      'Seen' => 1
    ]);


    return 1;
  }

}
