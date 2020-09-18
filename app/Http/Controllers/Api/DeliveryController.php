<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Input;
use DateTime;
use File;
use Dompdf\Dompdf;
use Dompdf\Options;

class DeliveryController extends Controller {

        public function todaydelivery(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();
                $genset = DB::table('projects')
                ->select('Id','Project_Name')
                ->where('Project_Name','LIKE',"%Genset%")
                ->get();

                $gensetid = json_decode(json_encode($genset),true);
                $excludeid = [142,143,144];
                for($i=0; $i<Count($gensetid); $i++)
                {
                 array_push($excludeid,$gensetid[$i]['Id']);
                }
                $Today=date("d-M-Y");
                $driver = DB::table('deliveryform')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as driver','driver.Id','=',DB::raw('((roadtax.UserId) OR (roadtax.UserId2))'))
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                // ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // ->select('deliveryform.Id as DeliveryId','roadtax.Id as lorry','roadtax.UserId','roadtax.UserId2','deliveryform.DO_No','roadtax.Vehicle_No','deliveryform.delivery_date','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at','deliveryform.delivery_time','deliveryform.pick_up_time','deliveryform.pickup_date','deliverystatuses.Remarks')
                // ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'), '=', $Today)

                // ->where('roadtax.UserId','=', $me->Id)
                // ->orWhere('roadtax.UserId2','=', $me->Id)
                // ->where('deliveryform.DriverId','=', $me->Id)
                // ->whereRaw("(deliveryform.DriverId = $me->Id OR (deliveryform.DriverId IS NULL AND (roadtax.UserId = $me->Id OR roadtax.UserId2 = $me->Id OR roadtax.UserId3 = $me->Id)))")
                ->whereRaw("(deliveryform.DriverId = $me->Id)")
                ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Collection") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Delivery" AND deliveryform.ProjectId = "142") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Delivery" AND deliveryform.ProjectId = "143") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Delivery" AND deliveryform.ProjectId = "144") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse" AND options.Option = "Delivery") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver") OR (deliverystatuses.delivery_status = "Incomplete" AND deliverystatuses.delivery_status_details = "Task Completed by Driver") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Driver Release Trip"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                ->orderBy('deliveryform.delivery_date', 'asc')
                ->where('deliveryform.delivery_date', '=', $Today)

                ->get();

                // dd($driver);


                $mydelivery = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                // ->leftJoin('users as driver','roadtax.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // ->leftJoin('users as driver','driver.Id','=',DB::raw('((roadtax.UserId) OR (roadtax.UserId2))'))

                // ->leftJoin('deliveryform as lala','roadtax.UserId','=','lala.DriverId')

                // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at','deliveryform.delivery_time','deliveryform.pick_up_time','deliveryform.pickup_date')
                // ->whereRaw('((roadtax.UserId', '=', $me->Id) )
                // ->orWhere('roadtax.UserId2', '=', $me->Id)


                // ->whereRaw('((roadtax.UserId = 105) OR (roadtax.UserId2 = 105  ))')
                ->where('roadtax.UserId','=', $me->Id)
                ->orWhere('roadtax.UserId2','=', $me->Id)


                ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'), '=', $Today)
                // ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%Y-%m-%d")'),"=",DB::raw('str_to_date("'.$Today.'","%d-%m-%Y")'))
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Collection") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Delivery" AND deliveryform.ProjectId = "142") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Delivery" AND deliveryform.ProjectId = "143") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Delivery" AND deliveryform.ProjectId = "144") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse" AND options.Option = "Delivery") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                ->orderBy('deliverystatuses.delivery_status','desc')
                ->orderBy('deliveryform.delivery_date', 'asc')
                ->get();

                // $mydelivery = DB::table('deliveryform')
                // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                // ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                // // ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                // ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                // // ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
                // ->select('deliveryform.Id','deliveryform.DO_No','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','driver.Name as Driver','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name','projects.Project_Name','deliveryform.Purpose','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks')
                // ->where('deliveryform.DriverId', '=', $me->Id)
                // ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"=",DB::raw('str_to_date("'.$Today.'","%d-%M-%Y")'))
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // // ->where(function ($q) {
                // //         $q->where('deliverystatuses.delivery_status_details', 'Accepted by Warehouse');
                // //         $q->orWhere('deliverystatuses.delivery_status_details', 'Accepted by Driver');
                // //         $q->orWhere('deliverystatuses.delivery_status_details', 'Pick Up by Driver');
                // //         $q->orWhere('deliverystatuses.delivery_status_details', 'Start Delivery by Driver');
                // //         $q->orWhere('deliverystatuses.delivery_status_details', 'Task Completed by Driver');
                // // })
                // // ->where('deliverystatuses.delivery_status_details', '!=', 'Driver Transfer Trip')
                // // ->where('deliverystatuses.delivery_status_details', '!=', 'Accepted by Admin')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                // ->get();

                // dd($Today);

                return json_encode($driver);
        }

	public function myalldelivery(Request $request)
	{
		$me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();

                $genset = DB::table('projects')
                ->select('Id','Project_Name')
                ->where('Project_Name','LIKE',"%Genset%")
                ->get();

                $gensetid = json_decode(json_encode($genset),true);
                $excludeid = [142,143,144];
                for($i=0; $i<Count($gensetid); $i++)
                {
                 array_push($excludeid,$gensetid[$i]['Id']);
                }
                // $warehouse = DB::table('deliveryitem')
                // ->leftJoin('deliveryform', 'deliveryform.Id', '=', 'deliveryitem.formId')
                // ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                // ->select('inventories.Warehouse', 'deliveryitem.formId', DB::raw('GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse'))
                // ->distinct('inventories.Warehouse')
                // ->where('deliveryform.DriverId', '=', $me->Id)
                // ->groupBy('deliveryitem.formId')
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
                // ->leftJoin('deliveryitem', 'deliveryitem.formId', '=', 'deliveryform.Id')
                // ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at', 'wh.Warehouse','deliveryform.delivery_time','deliveryform.pick_up_time','deliveryform.pickup_date','deliverystatuses.Remarks')
                ->where('deliveryform.DriverId', '=', $me->Id)
                ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Collection") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Delivery" AND deliveryform.ProjectId = "142") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Delivery" AND deliveryform.ProjectId = "143") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Delivery" AND deliveryform.ProjectId = "144") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse" AND options.Option = "Delivery") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details != "-") OR (deliverystatuses.delivery_status = "Incomplete" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                ->orderBy(DB::raw('str_to_date(deliverystatuses.delivery_status,"%d-%M-%Y")'),'desc')
                ->orderBy(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),'asc')
                ->get();



                // return($mydelivery);

                // $mydelivery = DB::table('deliveryform')
                // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                // ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                // // ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                // ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                // // ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
                // ->select('deliveryform.Id','deliveryform.DO_No','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','driver.Name as Driver','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name','projects.Project_Name','deliveryform.Purpose','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks')
                // ->where('deliveryform.DriverId', '=', $me->Id)
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // // ->where(function ($q) {
                // //         $q->where('deliverystatuses.delivery_status_details', 'Accepted by Warehouse');
                // //         $q->orWhere('deliverystatuses.delivery_status_details', 'Accepted by Driver');
                // //         $q->orWhere('deliverystatuses.delivery_status_details', 'Pick Up by Driver');
                // //         $q->orWhere('deliverystatuses.delivery_status_details', 'Start Delivery by Driver');
                // //         $q->orWhere('deliverystatuses.delivery_status_details', 'Task Completed by Driver');
                // // })
                // // ->where('deliverystatuses.delivery_status_details', '!=', 'Driver Transfer Trip')
                // // ->where('deliverystatuses.delivery_status_details', '!=', 'Accepted by Admin')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                // ->get();

                // dd($mydelivery);
                $calendarEvent = [];

                foreach($mydelivery as $md)
                {
                        $md->start = date(DATE_ISO8601, strtotime($md->delivery_date));
                        $md->end = date(DATE_ISO8601, strtotime("+1 day", strtotime($md->delivery_date)));
                        array_push($calendarEvent, $md);
                }

                return json_encode($calendarEvent);
	}

	public function myprocessingdelivery(Request $request)
	{
		$auth = JWTAuth::parseToken()->authenticate();

                $me = (new AuthController)->get_current_user($auth->Id);

                $input = $request->all();

                $mydelivery = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                ->leftJoin(DB::raw('(SELECT GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse, deliveryform.Id as deliveryformId FROM deliveryform LEFT JOIN deliveryitem ON deliveryitem.formId = deliveryform.Id LEFT JOIN inventories ON inventories.Id = deliveryitem.inventoryId GROUP BY deliveryform.Id) as wh'), 'wh.deliveryformId', '=', 'deliveryform.Id')
                // ->leftJoin('deliveryitem', 'deliveryitem.formId', '=', 'deliveryform.Id')
                // ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at', 'wh.Warehouse','deliveryform.delivery_time','deliveryform.pick_up_time')
                ->where('deliveryform.DriverId', '=', $me->Id)
                ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") )')
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')

                ->orderBy(DB::raw('str_to_date(deliverystatuses.delivery_status,"%d-%M-%Y")'),'desc')
                ->orderBy(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),'asc')
                ->get();



                // $mydelivery = DB::table('deliveryform')
                // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                // ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                // ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                // ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                // ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                // ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                // ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at','deliveryform.delivery_time')
                // ->where('deliveryform.DriverId', '=', $auth->Id)
                // // ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%Y-%m-%d")'),"=",DB::raw('str_to_date("'.$Today.'","%Y-%m-%d")'))
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") )')
                // ->get();

                return json_encode($mydelivery);
	}

	public function myacceptdelivery(Request $request)
	{
		$me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();

                $mydelivery = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                ->leftJoin(DB::raw('(SELECT GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse, deliveryform.Id as deliveryformId FROM deliveryform LEFT JOIN deliveryitem ON deliveryitem.formId = deliveryform.Id LEFT JOIN inventories ON inventories.Id = deliveryitem.inventoryId GROUP BY deliveryform.Id) as wh'), 'wh.deliveryformId', '=', 'deliveryform.Id')
                // ->leftJoin('deliveryitem', 'deliveryitem.formId', '=', 'deliveryform.Id')
                // ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at', 'wh.Warehouse','deliveryform.delivery_time','deliveryform.pick_up_time')
                ->where('deliveryform.DriverId', '=', $me->Id)
                ->whereRaw('((deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver"))')
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')

                ->orderBy(DB::raw('str_to_date(deliverystatuses.delivery_status,"%d-%M-%Y")'),'desc')
                ->orderBy(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),'asc')
                ->get();

                // $mydelivery = DB::table('deliveryform')
                // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                // ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                // ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                // ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                // ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                // ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                // ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at','deliveryform.delivery_time')
                // ->where('deliveryform.DriverId', '=', $me->Id)
                // // ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%Y-%m-%d")'),"=",DB::raw('str_to_date("'.$Today.'","%Y-%m-%d")'))
                // ->whereRaw('((deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                // ->get();

                return json_encode($mydelivery);
	}

	public function mycompletedelivery(Request $request)
	{
		$me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();

                $mydelivery = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                ->leftJoin(DB::raw('(SELECT GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse, deliveryform.Id as deliveryformId FROM deliveryform LEFT JOIN deliveryitem ON deliveryitem.formId = deliveryform.Id LEFT JOIN inventories ON inventories.Id = deliveryitem.inventoryId GROUP BY deliveryform.Id) as wh'), 'wh.deliveryformId', '=', 'deliveryform.Id')
                // ->leftJoin('deliveryitem', 'deliveryitem.formId', '=', 'deliveryform.Id')
                // ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at', 'wh.Warehouse','deliveryform.delivery_time','deliveryform.pick_up_time')
                ->where('deliveryform.DriverId', '=', $me->Id)
                ->whereRaw('((deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details != "-"))')
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')

                ->orderBy(DB::raw('str_to_date(deliverystatuses.delivery_status,"%d-%M-%Y")'),'desc')
                ->orderBy(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),'asc')
                ->get();

                // $mydelivery = DB::table('deliveryform')
                // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                // ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                // ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                // ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                // ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                // ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                // ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at','deliveryform.delivery_time')
                // ->where('deliveryform.DriverId', '=', $me->Id)
                // // ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%Y-%m-%d")'),"=",DB::raw('str_to_date("'.$Today.'","%Y-%m-%d")'))
                // ->whereRaw('((deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                // ->get();

                return json_encode($mydelivery);
	}

        public function opentrip(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();
                $genset = DB::table('projects')
                ->select('Id','Project_Name')
                ->where('Project_Name','LIKE',"%Genset%")
                ->get();

                $gensetid = json_decode(json_encode($genset),true);
                $excludeid = [142,143,144];
                for($i=0; $i<Count($gensetid); $i++)
                {
                 array_push($excludeid,$gensetid[$i]['Id']);
                }

                $exclude = implode( ',', $excludeid );
                $isDriver = DB::table('users')
                ->select('Id')
                ->where('Position','LIKE','%Driver%')
                ->where('Id', $me->Id)
                ->count();

                // $driverid = json_decode(json_encode($driver),true);

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
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Collection") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Delivery" AND (deliveryform.ProjectId IN ('.$exclude.') )) OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse" AND options.Option = "Delivery") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Driver Release Trip"))')
                ->orderBy(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),'ASC')
                ->get();

                // $mydelivery = DB::table('deliveryform')
                // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                // ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                // ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                // ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                // ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                // ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                // ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at','deliveryform.delivery_time')
                // ->where('deliveryform.DriverId', '!=', $me->Id)
                // // ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%Y-%m-%d")'),"=",DB::raw('str_to_date("'.$Today.'","%Y-%m-%d")'))
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Driver Transfer Trip"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                // ->get();

                return json_encode( $mydelivery);
        }

        public function opentripdetails(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();

                $Id = explode(",", $input["Id"]);

                // $mydelivery = DB::table('deliveryform')
                // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                // ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                // ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                // ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // ->leftJoin(DB::raw('(SELECT GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse, deliveryform.Id as deliveryformId FROM deliveryform LEFT JOIN deliveryitem ON deliveryitem.formId = deliveryform.Id LEFT JOIN inventories ON inventories.Id = deliveryitem.inventoryId GROUP BY deliveryform.Id) as wh'), 'wh.deliveryformId', '=', 'deliveryform.Id')
                // // ->leftJoin('deliveryitem', 'deliveryitem.formId', '=', 'deliveryform.Id')
                // // ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                // // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                // ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                // ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                // ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at', 'wh.Warehouse','deliveryform.delivery_time','deliveryform.pick_up_time')
                // ->where('deliveryform.DriverId', '=', $me->Id)
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Driver Transfer Trip"))')
                // // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                // ->get();

                $mydelivery = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time')
                ->where('deliverystatuses.deliveryform_Id', '=', $Id)
                // ->where('deliveryform.DriverId', '=', $me->Id)
                // ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%Y-%m-%d")'),"=",DB::raw('str_to_date("'.$Today.'","%Y-%m-%d")'))
                ->whereRaw('((deliverystatuses.delivery_status = "Processing" OR deliverystatuses.delivery_status_details = "Driver Transfer Trip"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                ->get();

                $id=0;
                foreach ($mydelivery as $delivery)
                {


                        // if ( $delivery->DriverId != $me->Id)
                        // {
                                // $insertid=$delivery->DeliveryId;

                                // DB::table('deliveryform')
                                // ->where('Id', $insertid)
                                // ->update(array(
                                //         'DriverId' => $me->Id,
                                // ));


                                DB::table('deliveryform')
                                ->where('Id','=',$delivery->DeliveryId)
                                ->update([

                                        'DriverId' => $me->Id,
                                ]);

                                $id=DB::table('deliverystatuses')
                                ->insertGetId([
                                        'deliveryform_Id' => $delivery->DeliveryId,
                                        'user_Id' => $me->Id,
                                        'delivery_status' => "Accepted",
                                        'delivery_status_details' => "Accepted by Driver",
                                        'remarks' => "New Driver Accept Trip",
                                        'updated_at' => DB::raw('now()')
                                ]);

                                $deliverydetail = DB::table('deliveryform')
                                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                                ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                                ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                                ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
                                ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                                ->select('requestor.Name as requestorName','roadtax.Vehicle_No','roadtax.Lorry_Size','projects.Project_Name','options.Option','driver.Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','deliveryitem.Qty_received','options.Option','approver.Name as approverName','deliverystatuses.delivery_status','requestor.Company_Email as requestorCompanyEmail','approver.Company_Email as approverEmail','deliverystatuses.remarks','radius.Location_Name','deliverystatuses.delivery_status_details','deliverystatuses.remarks as deliveryremarks','tracker.Site_Name','deliveryform.DO_No','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pick_up_time','deliveryform.pickup_date','requestor.Player_Id')
                                ->where('deliveryform.Id', '=',$delivery->DeliveryId)
                                ->first();

                                $pushnotificationtitle = $deliverydetail->DO_No." Accepted by ".$deliverydetail->Name;
                                $notifyplayerid = array();
                                array_push($notifyplayerid,$deliverydetail->Player_Id);
                                $this->sendpushnotifcation($notifyplayerid, $pushnotificationtitle);

                        // }

                        $id = $id;
                }

                $NotificationSubject="";
                $emails = array();
                $notifylist=array();

                if($id>0)
                {
                        $emails=array();

                        $subscribers = DB::table('notificationtype')
                        ->leftJoin('notificationsubscriber', 'notificationtype.Id', '=', 'notificationsubscriber.NotificationTypeId')
                        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                        ->where('notificationtype.Id','=',76)
                        ->get();

                        foreach ($subscribers as $subscriber)
                        {
                                $NotificationSubject = $subscriber->Notification_Subject;

                                if ($subscriber->Company_Email!="")
                                {
                                        array_push($emails,$subscriber->Company_Email);
                                }
                                else
                                {
                                        array_push($emails,$subscriber->Personal_Email);
                                }
                        }
                        array_push($emails,$deliverydetail->requestorCompanyEmail);

                        $NotificationSubject == "" ? $NotificationSubject="New Driver Accepted Delivery Trip":$NotificationSubject;

                        // Mail::send('emails.deliveryemailapp', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                        // {
                        //         array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        //         $emails = array_filter($emails);
                        //         $message->to($emails)->subject($NotificationSubject);
                        // });
                }
                return json_encode($deliverydetail);
        }

        public function transferdelivery(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $arrDeliveryId = array();

                $input = $request->all();

                $Id = explode(",", $input["Id"]);
// dd($Id);


                $mydelivery = DB::table('deliveryform')
                ->where('Id',$Id)
                ->get();

                // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                // ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                // ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                // ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // ->leftJoin(DB::raw('(SELECT GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse, deliveryform.Id as deliveryformId FROM deliveryform LEFT JOIN deliveryitem ON deliveryitem.formId = deliveryform.Id LEFT JOIN inventories ON inventories.Id = deliveryitem.inventoryId GROUP BY deliveryform.Id) as wh'), 'wh.deliveryformId', '=', 'deliveryform.Id')
                // // ->leftJoin('deliveryitem', 'deliveryitem.formId', '=', 'deliveryform.Id')
                // // ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                // // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                // ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                // ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                // ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at', 'wh.Warehouse','deliveryform.delivery_time','deliveryform.pick_up_time','deliveryform.pickup_date')
                // // ->whereIn('deliverystatuses.deliveryform_Id', $Id)
                // ->whereRaw("(deliveryform.DriverId = $me->Id OR (deliveryform.DriverId IS NULL AND (roadtax.UserId = $me->Id OR roadtax.UserId2 = $me->Id OR roadtax.UserId3 = $me->Id)))")
                // // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Driver Transfer Trip"))')
                // // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // // ->orderBy('deliverystatuses.delivery_status','desc')
                // // ->orderBy('deliveryform.delivery_date', 'asc')
                // ->orderBy(DB::raw('str_to_date(deliverystatuses.delivery_status,"%d-%M-%Y")'),'desc')
                // ->orderBy(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),'asc')
                // ->get();

                // $mydelivery = DB::table('deliveryform')
                // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                // ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                // ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                // // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                // ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                // ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                // ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                // ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at','deliveryform.delivery_time')
                // // ->where('deliveryform.Id', '=', $Id)
                // ->whereIn('deliverystatuses.deliveryform_Id', $Id)
                // // ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%Y-%m-%d")'),"=",DB::raw('str_to_date("'.$Today.'","%Y-%m-%d")'))
                // // ->whereRaw('((deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                // ->get();
// dd($mydelivery);
                $id=0;

                foreach ($mydelivery as $item)
                {
                        // if(str_contains($item->delivery_status,"Accepted")==false)
                        // {
                                // DB::table('deliveryform')
                                // ->where('Id','=',$item->Id)
                                // ->update([
                                //         'DriverId'=>0
                                //         ]);
                                $id=DB::table('deliverystatuses')
                                ->insertGetId([
                                        'deliveryform_Id' => $item->Id,
                                        'user_Id' => $me->Id,
                                        'remarks' => $input["remarks"],
                                        'delivery_status' => "Processing",
                                        'delivery_status_details' => "Driver Release Trip"
                                ]);
                                array_push($arrDeliveryId,$item->Id);
                        // }

                                DB::table('deliveryform')
                                ->where('Id','=',$item->Id)
                                ->update([

                                        'DriverId' => 0,
                                ]);

                        $id = $id;
                }

                $deliverydetail = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
                ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->select('requestor.Name as requestorName','roadtax.Vehicle_No','roadtax.Lorry_Size','projects.Project_Name','options.Option','driver.Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','deliveryitem.Qty_received','options.Option','approver.Name as approverName','deliverystatuses.delivery_status','requestor.Company_Email as requestorCompanyEmail','approver.Company_Email as approverEmail','deliverystatuses.remarks','radius.Location_Name','deliverystatuses.delivery_status_details','deliverystatuses.remarks as deliveryremarks','tracker.Site_Name','deliveryform.DO_No','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pick_up_time','deliveryform.pickup_date')
                ->where('deliveryform.Id', '=',$Id)
                ->first();

                // $driver = DB::table('users')
                // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                // ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                // ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                // ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                // ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                // // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
                // ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
                // ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                // ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                // ->select('requestor.Name as requestorName','deliveryform.delivery_date','roadtax.Vehicle_No','roadtax.Lorry_Size','projects.Project_Name','options.Option','driver.Name','visitstatus.visit_status','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','deliveryitem.Qty_send','deliveryitem.Qty_received','options.Option','approver.Name as approverName','deliverystatuses.delivery_status',
                //     'requestor.Company_Email as requestorCompanyEmail','approver.Company_Email as approverEmail','deliverystatuses.remarks','deliverystatuses.delivery_status_details','tracker.Site_Name','deliveryform.DO_No','driver.Id as driverId')
                // ->where('deliveryform.Id', '=',$id)
                // ->first();

                // $driver = DB::table('deliveryform')
                // ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // ->leftJoin('users','users.Id','=','roadtax.UserId')
                // ->leftJoin('users as driver1','driver1.Id','=','roadtax.UserId2')
                // ->leftJoin('users as driver2','driver2.Id','=','roadtax.UserId3')
                // ->select('deliveryform.Id','roadtax.UserId','roadtax.UserId2','roadtax.UserId3', 'users.Player_Id', 'driver1.Player_Id as Player_Id1', 'driver2.Player_Id as Player_Id2', 'users.StaffId', 'driver1.StaffId as StaffId1', 'driver2.StaffId as StaffId2')
                // ->where('deliveryform.Id','=',$Id)
                // // ->where('Position', '=', 'Lorry Driver')
                // // ->leftJoin('users as driver','driver.Id','=','roadtax.UserId2')
                // // ->where('Id', '!=', $me->Id)
                // ->get();
                $driver = DB::table('users')
                ->select('Id')
                ->where('Position','LIKE','%Lorry%')
                ->get();
                // dd($driver);

                $NotificationSubject="";
                $emails = array();
                $notifylist=array();
                $notifyplayerid=array();

                if($id>0)
                {
                        array_push($emails,$deliverydetail->requestorCompanyEmail);
                        array_push($emails,$deliverydetail->approverEmail);

                        $subscribers = DB::table('notificationtype')
                        ->leftJoin('notificationsubscriber', 'notificationtype.Id', '=', 'notificationsubscriber.NotificationTypeId')
                        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                        ->where('notificationtype.Id','=',77)
                        ->get();

                        $NotificationSubject == "" ? $NotificationSubject="Driver Transfer Delivery Trip":$NotificationSubject;

                        // Mail::send('emails.deliveryemailapp', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                        // {
                        //         array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        //         $emails = array_filter($emails);
                        //         $message->to($emails)->subject($NotificationSubject);
                        // });

                        $emails=array();
                        foreach ($subscribers as $subscriber)
                        {
                                $NotificationSubject = $subscriber->Notification_Subject;

                                if ($subscriber->Company_Email!="")
                                {
                                        array_push($emails,$subscriber->Company_Email);
                                }
                                else
                                {
                                        array_push($emails,$subscriber->Personal_Email);
                                }
                        }

                        $NotificationSubject == "" ? $NotificationSubject="Driver Transfer Delivery Trip":$NotificationSubject;

                        // Mail::send('emails.warehouse2', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                        // {
                        //         array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        //         $emails = array_filter($emails);
                        //         $message->to($emails)
                        //         ->subject($NotificationSubject);
                        // });

                        foreach ($driver as $d)
                        {
                                array_push($notifylist, $d->Id);

                                // if ($d->Player_Id)
                                // {

                                array_push($notifyplayerid,$d->Player_Id);
                                $notifyplayerid = array_filter($notifyplayerid);

                                // }
                        }


                        if($notifyplayerid)
                        {
                                $this->sendOpenTrip($notifyplayerid);
                        }
                }

                return json_encode($deliverydetail);
        }

        public function accepttrip(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();

                $Id = explode(",", $input["Id"]);

                $mydelivery = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                // ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftJoin(DB::raw('(SELECT GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse, deliveryform.Id as deliveryformId FROM deliveryform LEFT JOIN deliveryitem ON deliveryitem.formId = deliveryform.Id LEFT JOIN inventories ON inventories.Id = deliveryitem.inventoryId GROUP BY deliveryform.Id) as wh'), 'wh.deliveryformId', '=', 'deliveryform.Id')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at','deliveryform.delivery_time','deliveryform.pick_up_time','wh.Warehouse','deliveryform.pickup_date')
                ->where('deliverystatuses.deliveryform_Id', '=', $Id)
                // ->where('deliveryform.DriverId', '=', $me->Id)
                // ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%Y-%m-%d")'),"=",DB::raw('str_to_date("'.$Today.'","%Y-%m-%d")'))
                ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Proceed to Delivery") OR (deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Driver Release Trip"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                ->get();



                $id = 0;
// dd($mydelivery);
                foreach ($mydelivery as $delivery)
                {
                        // if ( $delivery->DriverId == $me->Id)
                        // {

                                $id=DB::table('deliverystatuses')
                                ->insertGetId([
                                        'deliveryform_Id' => $delivery->DeliveryId,
                                        'user_Id' => $me->Id,
                                        'delivery_status' => "Accepted",
                                        'delivery_status_details' => "Accepted by Driver",
                                        // 'updated_at' => DB::raw('now()')
                                ]);

                                DB::table('deliveryform')
                                ->where('Id','=',$delivery->DeliveryId)
                                ->update([

                                        'DriverId' => $me->Id,
                                ]);



                                $id = $id;
                        // }
                }

                $deliverydetail = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
                ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->select('requestor.Name as requestorName','roadtax.Vehicle_No','roadtax.Lorry_Size','projects.Project_Name','options.Option','driver.Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','deliveryitem.Qty_received','options.Option','approver.Name as approverName','deliverystatuses.delivery_status','requestor.Company_Email as requestorCompanyEmail','approver.Company_Email as approverEmail','deliverystatuses.remarks','radius.Location_Name','deliverystatuses.delivery_status_details','deliverystatuses.remarks as deliveryremarks','tracker.Site_Name','deliveryform.DO_No','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','driver.Id','requestor.Player_Id')
                ->where('deliveryform.Id', '=',$Id)
                ->first();


                $NotificationSubject="";
                $emails = array();

                if($id>0)
                {
                        $pushnotificationtitle = $deliverydetail->DO_No." Accepted by ".$deliverydetail->Name;
                        $notifyplayerid = array();
                        array_push($notifyplayerid,$deliverydetail->Player_Id);

                        array_push($emails,$deliverydetail->requestorCompanyEmail);
                        array_push($emails,$deliverydetail->approverEmail);

                        $subscribers = DB::table('notificationtype')
                        ->leftJoin('notificationsubscriber', 'notificationtype.Id', '=', 'notificationsubscriber.NotificationTypeId')
                        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                        ->where('notificationtype.Id','=',68)
                        ->get();

                        $NotificationSubject == "" ? $NotificationSubject="Driver Accepted Delivery Trip":$NotificationSubject;

                        // Mail::send('emails.deliveryemailapp', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                        // {
                        //         array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        //         $emails = array_filter($emails);
                        //         $message->to($emails)->subject($NotificationSubject);
                        // });

                        $emails=array();
                        foreach ($subscribers as $subscriber)
                        {
                                $NotificationSubject = $subscriber->Notification_Subject;

                                if ($subscriber->Company_Email!="")
                                {
                                        array_push($emails,$subscriber->Company_Email);
                                }
                                else
                                {
                                        array_push($emails,$subscriber->Personal_Email);
                                }
                                array_push($notifyplayerid, $subscriber->Player_Id);
                        }
                        $this->sendpushnotifcation($notifyplayerid, $pushnotificationtitle);

                        $NotificationSubject == "" ? $NotificationSubject="Driver Accepted Delivery Trip":$NotificationSubject;

                        // Mail::send('emails.warehouse2', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                        // {
                        //         array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        //         $emails = array_filter($emails);
                        //         $message->to($emails)
                        //         ->subject($NotificationSubject);
                        // });

                        return 1;
                }

                return json_encode($deliverydetail);
        }

        public function checkpickup(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();

                // $Id = explode(",", $input["Id"]);
                $Id = $input["Id"];

                $exist = DB::table('deliverytracking')
                ->where('created_by', '=', $me->Id)
                ->where('deliveryform_Id', '=', $Id)
                // ->where('created_at', '=', $input["Date"])
                // ->where('created_at','<>','')
                ->where('type', '=', 'Pick Up')
                ->first();


                if ($exist)
                {
                        return 1;
                }

                return 0;
        }

        public function checkstartdelivery(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();

                // $Id = explode(",", $input["Id"]);
                $Id = $input["Id"];

                $exist = DB::table('deliverytracking')
                ->where('created_by', '=', $me->Id)
                ->where('deliveryform_Id', '=', $Id)
                // ->where('created_at', '=', $input["Date"])
                // ->where('created_at','<>','')
                ->where('type', '=', 'Start Delivery')
                ->first();


                if ($exist)
                {
                        return 1;
                }

                return 0;
        }

        public function checkcompletedelivery(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();

                // $Id = explode(",", $input["Id"]);
                $Id = $input["Id"];

                $exist = DB::table('deliverytracking')
                ->where('created_by', '=', $me->Id)
                ->where('deliveryform_Id', '=', $Id)
                // ->where('created_at', '=', $input["Date"])
                // ->where('created_at','<>','')
                ->where('type', '=', 'Completed Delivery')
                ->first();


                if ($exist)
                {
                        return 1;
                }

                return 0;
        }

        public function pickup(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();

                // $Id = explode(",", $input["Id"]);
                $Id = $input["Id"];

                $Remarks = $input["Remarks"];
                // dd($input);

                if (! $request->hasFile('attachment')) {
                        return json_encode([
                            'Required' => [
                                'Attachment is required for time in.'
                            ]
                        ]);
                }

                $mydelivery = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at','deliveryform.delivery_time','deliveryform.pickup_date')
                ->where('deliverystatuses.deliveryform_Id', '=', $Id)
                ->where('deliveryform.DriverId', '=', $me->Id)
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                ->get();

                // check for already time in with the date
                $exist = DB::table('deliverytracking')
                ->where('created_by', '=', $me->Id)
                ->where('deliveryform_Id', '=', $Id)
                // ->where('created_at', '=', $input["Date"])
                ->where('created_at','<>','')
                ->where('type', '=', 'Pick Up')
                ->first();

                if(! $exist)
                {
                        foreach ($mydelivery as $delivery)
                        {

                                $id=DB::table('deliverystatuses')
                                ->insertGetId([
                                        'deliveryform_Id' => $delivery->DeliveryId,
                                        'user_Id' => $me->Id,
                                        'delivery_status' => 'Accepted',
                                        'delivery_status_details' => 'Pick Up by Driver',
                                        'created_at' => DB::raw('now()'),
                                        'remarks' => $Remarks,
                                ]);

                                $insertid=DB::table('deliverytracking')
                                ->insertGetId([
                                        'deliveryform_id'  => $delivery->DeliveryId,
                                        'deliverystatus_id' => $id,
                                        'latitude1' => $input["Latitude_In"],
                                        'Longitude1' => $input["Longitude_In"],
                                        'created_at' => DB::raw('now()'),
                                        'created_by' => $me->Id,
                                        'type' => 'Pick Up',
                                        // 'remarks' => $Remarks,
                                        // 'Scope' => $input["Scope"],
                                        // 'Project_Code' => $input["Project_Code"],
                                        // 'Deduction' => $deduction
                                ]);
                        }

                        $filenames="";
                        $attachmentUrl = null;
                        $type="Delivery";
                        $uploadcount=count($request->file('attachment'));

                        if ($request->hasFile('attachment')) {

                            for ($i=0; $i <$uploadcount ; $i++) {
                                # code...
                                $file = $request->file('attachment')[$i];
                                $destinationPath=public_path()."/private/upload/Delivery";
                                $extension = $file->getClientOriginalExtension();
                                $originalName=$file->getClientOriginalName();
                                $fileSize=$file->getSize();
                                $fileName=time()."_".$i.".".$extension;
                                $upload_success = $file->move($destinationPath, $fileName);
                                $insert=DB::table('files')->insertGetId(
                                    ['Type' => $type,
                                     'TargetId' => $id,
                                     'UserId' => $me->Id,
                                     'File_Name' => $originalName,
                                     'File_Size' => $fileSize,
                                     'Web_Path' => '/private/upload/Delivery/'.$fileName
                                    ]
                                );
                                $attachmentUrl = url('/private/upload/Delivery/'.$fileName);
                                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
                            }

                            $filenames=substr($filenames, 0, strlen($filenames)-1);

                            //return '/private/upload/'.$fileName;
                        }

                        return 1;
                }
                else
                {
                        return 0;
                        // return json_encode($exist);
                }
        }

        public function startdelivery(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();
                $Id = explode(",", $input["Id"]);
                // return json_encode($input);
                // dd($input);
                if (! $request->hasFile('attachment')) {
                        return json_encode([
                            'Required' => [
                                'Attachment is required for time in.'
                            ]
                        ]);
                }

                // Kp coded for logistics 2 enable later
                // if($request->vehicle_qr == "")
                // {
                //         return json_encode([
                //                 'Required' => [
                //                     'Lorry QR scanning is required for time in'
                //                 ]
                //             ]);
                // }

                $mydelivery = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftJoin(DB::raw('(SELECT GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse, deliveryform.Id as deliveryformId FROM deliveryform LEFT JOIN deliveryitem ON deliveryitem.formId = deliveryform.Id LEFT JOIN inventories ON inventories.Id = deliveryitem.inventoryId GROUP BY deliveryform.Id) as wh'), 'wh.deliveryformId', '=', 'deliveryform.Id')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.created_at','deliveryform.delivery_time','deliveryform.pick_up_time','wh.Warehouse','deliveryform.pickup_date')
                ->where('deliverystatuses.deliveryform_Id', '=', $Id)
                ->where('deliveryform.DriverId', '=', $me->Id)
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                ->get();

                // check for already time in with the date
                $exist = DB::table('deliverytracking')
                ->where('created_by', '=', $me->Id)
                ->where('deliveryform_Id', '=', $Id)
                // ->where('created_at', '=', $input["Date"])
                ->where('created_at','<>','')
                ->where('type', '=', 'Start Delivery')
                ->first();

                if(! $exist)
                {
                        foreach ($mydelivery as $delivery)
                        {
                                $sitelocation = DB::table('deliveryform')
                                ->where('deliveryform.Id',$request->Id)
                                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                                ->select('Latitude','Longitude')
                                ->first();

                                DB::table('driverlocation')
                                ->insert([
                                        'formId' => $request->Id,
                                        'lat_from' => $request->Latitude_In,
                                        'long_from' => $request->Longitude_In,
                                        'lat_to' => $sitelocation->Latitude,
                                        'long_to' => $sitelocation->Longitude
                                ]);

                                $id=DB::table('deliverystatuses')
                                ->insertGetId([
                                        'deliveryform_Id' => $delivery->DeliveryId,
                                        'user_Id' => $me->Id,
                                        'delivery_status' => 'Accepted',
                                        // 'ontime' => $input['onTime'],
                                        'delivery_status_details' => 'Start Delivery by Driver',
                                        'created_at' => DB::raw('now()'),
                                        'remarks' => $input["Remarks"],
                                ]);


                                $insertid=DB::table('deliverytracking')
                                ->insertGetId([
                                        'deliveryform_id'  => $delivery->DeliveryId,
                                        'deliverystatus_id' => $id,
                                        'latitude1' => $input["Latitude_In"],
                                        'Longitude1' => $input["Longitude_In"],
                                        'created_at' => DB::raw('now()'),
                                        'created_by' => $me->Id,
                                        'type' => 'Start Delivery',
                                        // 'Scope' => $input["Scope"],
                                        // 'Project_Code' => $input["Project_Code"],
                                        // 'Deduction' => $deduction
                                ]);
                        }

                        $filenames="";
                        $attachmentUrl = null;
                        $type="Delivery";
                        $uploadcount=count($request->file('attachment'));

                        if ($request->hasFile('attachment')) {

                            for ($i=0; $i <$uploadcount ; $i++) {
                                # code...
                                $file = $request->file('attachment')[$i];
                                $destinationPath=public_path()."/private/upload/Delivery";
                                $extension = $file->getClientOriginalExtension();
                                $originalName=$file->getClientOriginalName();
                                $fileSize=$file->getSize();
                                $fileName=time()."_".$i.".".$extension;
                                $upload_success = $file->move($destinationPath, $fileName);
                                $insert=DB::table('files')->insertGetId(
                                    ['Type' => $type,
                                     'TargetId' => $id,
                                     'UserId' => $me->Id,
                                     'File_Name' => $originalName,
                                     'File_Size' => $fileSize,
                                     'Web_Path' => '/private/upload/Delivery/'.$fileName
                                    ]
                                );
                                $attachmentUrl = url('/private/upload/Delivery/'.$fileName);
                                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
                            }

                            $filenames=substr($filenames, 0, strlen($filenames)-1);

                            //return '/private/upload/'.$fileName;
                        }

                        return 1;
                }
                else
                {
                        return 0;
                }
        }

        public function completedelivery2(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();
                $input = $request->all();
                // dd($request->Id);

                $Id = $input["Id"];
                // dd($input);

                $check = DB::table('deliveryitem')
                // ->select('*')
                ->where('Qty_received', null)
                ->where('deliveryitem.formId','=', $Id)
                ->get();

                if($check)
                {
                        return json_encode([
                                'Required' => [
                                        'Please check item first.'
                                ]
                        ]);
                }

                // if (! $request->hasFile('attachment')) {
                //         return json_encode([
                //             'Required' => [
                //                 'Attachment is required for time in.'
                //             ]
                //         ]);
                // }

                $mydelivery = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftJoin(DB::raw('(SELECT GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse, deliveryform.Id as deliveryformId FROM deliveryform LEFT JOIN deliveryitem ON deliveryitem.formId = deliveryform.Id LEFT JOIN inventories ON inventories.Id = deliveryitem.inventoryId GROUP BY deliveryform.Id) as wh'), 'wh.deliveryformId', '=', 'deliveryform.Id')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Id as radiusId','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.created_at','deliveryform.delivery_time','deliveryform.pick_up_time','wh.Warehouse','deliveryform.pickup_date')
                ->where('deliverystatuses.deliveryform_Id', '=', $Id)
                ->where('deliveryform.DriverId', '=', $me->Id)
                ->get();

                // check for already time in with the date
                $exist = DB::table('deliverytracking')
                ->where('created_by', '=', $me->Id)
                ->where('deliveryform_Id', '=', $Id)
                // ->where('created_at', '=', $input["Date"])
                ->where('created_at','<>','')
                ->where('type', '=', 'Completed Delivery')
                ->first();

                $id=0;

                if(! $exist)
                {
                        // $did=4;
                        foreach ($mydelivery as $delivery)
                        {
                                //digital signature
                            if ($request->signature) {
                                $encoded_image = explode(",", $request->signature)[1];
                                $decoded_image = base64_decode($encoded_image);
                                $folderPath = public_path('private/upload/signature');
                                File::makeDirectory($folderPath, 0777, true, true);
                
                                $image_parts = explode(";base64,", $request->signature);
                                        
                                $image_type_aux = explode("image/", $image_parts[0]);
                                  
                                $image_type = $image_type_aux[1];
                                  
                                $image_base64 = base64_decode($image_parts[1]);
                                  
                                $file = $folderPath."/".$delivery->DO_No.'.'.$image_type;
                                file_put_contents($file, $image_base64);

                                $insert=DB::table('files')->insertGetId(
                                    ['Type' => 'Signature',
                                     'TargetId' => $Id,
                                     'UserId' => $me->Id,
                                     'File_Name' => 'Signature'.$delivery->DO_No,
                                     'Web_Path' => explode('/usr/local/apache/htdocs/totg/public',$file)[1]
                                    ]
                                );
                            }

                            DB::table('deliverysignature')
                            ->insert([
                                'formId' => $request->Id,
                                'qr' => $request->qr ? $request->qr : null,
                                'signature' => isset($file) ? $file : null 
                            ]);


                                $ontime = 0;
                                $totalincentive = 0;
                                $totalcharges = 0;
                                //Update 28/5/2020
                                $checkcomplete = DB::table('deliverystatuses')
                                ->leftjoin('deliveryform','deliveryform.Id','=','deliverystatuses.deliveryform_Id')
                                ->leftjoin('radius','radius.Id','=','deliveryform.Location')
                                ->select('deliverystatuses.Id','deliverystatuses.deliveryform_Id','radius.Latitude','radius.Longitude')
                                ->whereRaw('deliverystatuses.Id = (SELECT Max(deliverystatuses.Id) from deliveryform left join (select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max on `max`.`deliveryform_Id` = `deliveryform`.`Id` left join `deliverystatuses` on `deliverystatuses`.`Id` = max.`maxid` WHERE delivery_date = "'.$delivery->delivery_date.'" AND DriverId = "'.$delivery->DriverId.'" AND deliverystatuses.delivery_status = "Completed")')
                                ->first();

                                $checksamesite = DB::table('deliveryform')
                                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                                ->whereRaw('deliveryform.Location = "'.$delivery->radiusId.'" AND delivery_date = "'.$delivery->delivery_date.'" AND DriverId = "'.$delivery->DriverId.'" AND deliverystatuses.delivery_status = "Completed" AND deliveryform.DO_No NOT LIKE BINARY "%\_R%"')
                                ->count();

                                // if($delivery->Option == "Collection")
                                // {
                                //     DB::table('driverlocation')
                                //     ->where('formId',$delivery->DeliveryId)
                                //     ->update([
                                //         'lat_to' => $delivery->Latitude,
                                //         'long_to' => $delivery->Longitude,
                                //         'lat_from' => $request->Latitude_In,
                                //         'long_from' => $request->Longitude_In
                                //     ]);
                                // }

                                if($checkcomplete)
                                {
                                    DB::table('driverlocation')
                                    ->where('formId',$delivery->DeliveryId)
                                    ->update([
                                        'lat_from' => $checkcomplete->Latitude,
                                        'long_from' => $checkcomplete->Longitude,
                                    ]);
                                }

                                // Transport Charges & Incentive for logistics2 TESTED by KP 18/3/2020
                                $coordinate = DB::table('driverlocation')
                                ->where('formId',$delivery->DeliveryId)
                                ->first();

                                if($checksamesite)
                                {
                                    $distance = 0;
                                }
                                else
                                {
                                    $distance = $this->googleapi($coordinate->lat_from, $coordinate->long_from, $coordinate->lat_to, $coordinate->long_to);
                                }

                                $incentiverate = DB::table('logistics')
                                ->select('rate')
                                ->where('Lorry_Type',$delivery->Lorry_Size)
                                ->whereRAW('"'.$distance.'" BETWEEN distance1 AND distance2')
                                ->where('type','Incentive')
                                ->first();

                                if($incentiverate)
                                {
                                    $totalincentive = $incentiverate->rate * $distance;
                                }

                                $transportchargesrate = DB::table('logistics')
                                ->select('rate')
                                ->where('Lorry_Type', $delivery->Lorry_Size)
                                ->whereRAW('"'.$distance.'" BETWEEN distance1 AND distance2')
                                ->where('type','Charges')
                                ->first();

                                if($transportchargesrate)
                                {
                                    $totalcharges = $transportchargesrate->rate * $distance;
                                }

                                //On time incentive calculation 30mins buffer
                                // $starttime = DB::table('deliverystatuses')
                                // ->where('deliveryform_Id', $delivery->DeliveryId)
                                // ->select('created_at')
                                // ->orderby('Id')
                                // ->first();

                                // $plusthirty = date('Y-m-d H:i:s',strtotime($starttime->created_at."+30 minutes"));
                                // $now = DB::raw('Now()');

                                // if($now <= $plusthirty)
                                // {
                                //         $ontimerate = DB::table('logistics')
                                //         ->select('rate')
                                //         ->where('Lorry_Type',$delivery->Lorry_Size)
                                //         ->whereRAW('"'.$distance.'" BETWEEN distance1 AND distance2')
                                //         ->where('type','Ontime')
                                //         ->first();

                                //         $ontime = $ontimerate->rate;
                                // }

                                //On time decided by PIC
                                if($request->onTime == "Yes")
                                {
                                    $ontimerate = DB::table('logistics')
                                    ->select('rate')
                                    ->where('Lorry_Type',$delivery->Lorry_Size)
                                    ->whereRAW('"'.$distance.'" BETWEEN distance1 AND distance2')
                                    ->where('type','Ontime')
                                    ->first();

                                    $ontime = isset($ontimerate) ? $ontimerate->rate : 0;
                                }

                                DB::table('deliveryform')
                                ->where('Id',$delivery->DeliveryId)
                                ->update([
                                        'basicincentive' => $totalincentive,
                                        'distance_km' => $distance,
                                        'incentive_rate' => $incentiverate ? $incentiverate->rate : 0,
                                        'charges_rate' => $transportchargesrate ? $transportchargesrate->rate : 0,
                                        'charges' => $totalcharges,
                                        'ontime' => $ontime
                                ]);

                                $id=DB::table('deliverystatuses')
                                ->insertGetId([
                                        'deliveryform_Id' => $delivery->DeliveryId,
                                        'user_Id' => $me->Id,
                                        'delivery_status' => 'Completed',
                                        'delivery_status_details' => 'Task Completed by Driver',
                                        'created_at' => DB::raw('now()'),
                                        'remarks' => $input["Remarks"],
                                ]);


                                $insertid=DB::table('deliverytracking')
                                ->insertGetId([
                                        'deliveryform_id'  => $delivery->DeliveryId,
                                        'deliverystatus_id' => $id,
                                        'latitude1' => $input["Latitude_In"],
                                        'Longitude1' => $input["Longitude_In"],
                                        'created_at' => DB::raw('now()'),
                                        'created_by' => $me->Id,
                                        'type' => 'Completed Delivery',
                                        // 'Scope' => $input["Scope"],
                                        // 'Project_Code' => $input["Project_Code"],
                                        // 'Deduction' => $deduction
                                ]);


                                $did=$delivery->DeliveryId;
                                $id = $id;
                        }

                        $pic = DB::table('deliveryreview')
                        ->insertGetId([
                                'pic_staff_id' => $input["Staff_id"],
                                'pic_name' => $input["pic_name"],
                                'created_at' => DB::raw('now()'),
                                'created_by' => $me->Id,
                                'deliveryform_Id' => $did,
                                // 'onTime' => $input["onTime"],
                        ]);

                        $filenames="";
                        $attachmentUrl = null;
                        $type="Delivery";
                        $uploadcount=count($request->file('attachment'));

                        if ($request->hasFile('attachment')) {

                            for ($i=0; $i <$uploadcount ; $i++) {
                                # code...
                                $file = $request->file('attachment')[$i];
                                $destinationPath=public_path()."/private/upload/Delivery";
                                $extension = $file->getClientOriginalExtension();
                                $originalName=$file->getClientOriginalName();
                                $fileSize=$file->getSize();
                                $fileName=time()."_".$i.".".$extension;
                                $upload_success = $file->move($destinationPath, $fileName);
                                $insert=DB::table('files')->insertGetId(
                                    ['Type' => $type,
                                     'TargetId' => $id,
                                     'UserId' => $me->Id,
                                     'File_Name' => $originalName,
                                     'File_Size' => $fileSize,
                                     'Web_Path' => '/private/upload/Delivery/'.$fileName
                                    ]
                                );
                                $attachmentUrl = url('/private/upload/Delivery/'.$fileName);
                                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
                            }

                            $filenames=substr($filenames, 0, strlen($filenames)-1);

                            //return '/private/upload/'.$fileName;
                        }
                }

                $deliverydetail = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
                ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->select('requestor.Name as requestorName','roadtax.Vehicle_No','roadtax.Lorry_Size','projects.Project_Name','options.Option','driver.Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','deliveryitem.Qty_received','options.Option','approver.Name as approverName','deliverystatuses.delivery_status','requestor.Company_Email as requestorCompanyEmail','approver.Company_Email as approverEmail','deliverystatuses.remarks','radius.Location_Name','deliverystatuses.delivery_status_details','deliverystatuses.remarks as deliveryremarks','tracker.Site_Name','deliveryform.DO_No','deliveryform.delivery_date','deliveryform.delivery_time',
                'deliveryform.pickup_date','salesorderid','requestor.Player_Id')
                ->where('deliveryform.Id', '=',$Id)
                ->first();
                if($deliverydetail->salesorderid != 0){
                        $temp=DB::table('deliveryitem')
                        ->select('deliveryitem.Id')
                        ->join('inventories','inventories.Id','=','deliveryitem.inventoryId')
                        ->where('deliveryitem.formId',$Id)
                        ->where('inventories.Item_Code','LIKE','SC%')
                        ->get();
                        foreach($temp as $t){
                                $serId=DB::table('serviceticket')
                                ->insertGetId([
                                        'service_type'=>"PM(O)",
                                        'genset_no'=>$t->Id,
                                        'service_id'=>$this->generateServiceNo(),
                                        'DeliveryId'=>$Id

                                ]);
                                $today=date("d-M-Y");
                                DB::table('speedfreakservice')
                                ->insert([
                                        'ServiceId'=>$serId,
                                        'last_service'=>$today,
                                        'upcoming_service'=>date('d-M-Y', strtotime($today."+45 days")),
                                        'Status'=>"In-Progress"
                                ]);
                        }
                }
                $NotificationSubject="";
                $emails = array();
                $notifyplayerid = array();

                if($id>0)
                {
                        array_push($emails,$deliverydetail->requestorCompanyEmail);
                        array_push($emails,$deliverydetail->approverEmail);
                        array_push($notifyplayerid,$deliverydetail->Player_Id);

                        $subscribers = DB::table('notificationtype')
                        ->leftJoin('notificationsubscriber', 'notificationtype.Id', '=', 'notificationsubscriber.NotificationTypeId')
                        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                        ->where('notificationtype.Id','=',78)
                        ->get();

                        $NotificationSubject == "" ? $NotificationSubject="Task Completed by Driver":$NotificationSubject;

                        // Mail::send('emails.deliveryemailapp', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                        // {
                        //         array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        //         $emails = array_filter($emails);
                        //         $message->to($emails)->subject($NotificationSubject);
                        // });

                        $emails=array();
                        foreach ($subscribers as $subscriber)
                        {
                                $NotificationSubject = $subscriber->Notification_Subject;

                                if ($subscriber->Company_Email!="")
                                {
                                        array_push($emails,$subscriber->Company_Email);
                                }
                                else
                                {
                                        array_push($emails,$subscriber->Personal_Email);
                                }
                                array_push($notifyplayerid,$subscriber->Player_Id);
                        }

                        $NotificationSubject == "" ? $NotificationSubject="Task Completed by Driver":$NotificationSubject;
                        $this->sendpushnotifcation($notifyplayerid, $NotificationSubject);

                        // Mail::send('emails.warehouse2', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                        // {
                        //         array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        //         $emails = array_filter($emails);
                        //         $message->to($emails)
                        //         ->subject($NotificationSubject);
                        // });

                        return 1;
                }

                return json_encode($deliverydetail);
        }

        public function completedelivery(Request $request)
        {
           $me = JWTAuth::parseToken()->authenticate();
                $input = $request->all();
                // dd($request->Id);
                $response = 0;
                $Id = $input["Id"];
                // dd($input);

                $check = DB::table('deliveryitem')
                // ->select('*')
                ->where('Qty_received', null)
                ->where('deliveryitem.formId','=', $Id)
                ->get();

                if($check)
                {
                        return json_encode([
                                'Required' => [
                                        'Please check item first.'
                                ]
                        ]);
                }

                // if (! $request->hasFile('attachment')) {
                //         return json_encode([
                //             'Required' => [
                //                 'Attachment is required for time in.'
                //             ]
                //         ]);
                // }

                $mydelivery = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftJoin(DB::raw('(SELECT GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse, deliveryform.Id as deliveryformId FROM deliveryform LEFT JOIN deliveryitem ON deliveryitem.formId = deliveryform.Id LEFT JOIN inventories ON inventories.Id = deliveryitem.inventoryId GROUP BY deliveryform.Id) as wh'), 'wh.deliveryformId', '=', 'deliveryform.Id')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Id as radiusId','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.created_at','deliveryform.delivery_time','deliveryform.pick_up_time','wh.Warehouse','deliveryform.pickup_date')
                ->where('deliverystatuses.deliveryform_Id', '=', $Id)
                ->where('deliveryform.DriverId', '=', $me->Id)
                ->get();

                // check for already time in with the date
                $exist = DB::table('deliverytracking')
                ->where('created_by', '=', $me->Id)
                ->where('deliveryform_Id', '=', $Id)
                // ->where('created_at', '=', $input["Date"])
                ->where('created_at','<>','')
                ->where('type', '=', 'Completed Delivery')
                ->first();

                $id=0;

                if(! $exist)
                {
                        // $did=4;
                        foreach ($mydelivery as $delivery)
                        {
                                //digital signature
                            if ($request->signature) {
                                $encoded_image = explode(",", $request->signature)[1];
                                $decoded_image = base64_decode($encoded_image);
                                $folderPath = public_path('private/upload/signature');
                                File::makeDirectory($folderPath, 0777, true, true);
                
                                $image_parts = explode(";base64,", $request->signature);
                                        
                                $image_type_aux = explode("image/", $image_parts[0]);
                                  
                                $image_type = $image_type_aux[1];
                                  
                                $image_base64 = base64_decode($image_parts[1]);
                                  
                                $file = $folderPath."/".$delivery->DO_No.'.'.$image_type;
                                file_put_contents($file, $image_base64);

                                $insert=DB::table('files')->insertGetId(
                                    ['Type' => 'Signature',
                                     'TargetId' => $Id,
                                     'UserId' => $me->Id,
                                     'File_Name' => 'Signature'.$delivery->DO_No,
                                     'Web_Path' => explode('/usr/local/apache/htdocs/totg/public',$file)[1]
                                    ]
                                );
                            }

                            DB::table('deliverysignature')
                            ->insert([
                                'formId' => $request->Id,
                                'qr' => $request->qr ? $request->qr : null,
                                'signature' => isset($file) ? $file : null 
                            ]);


                                $ontime = 0;
                                $totalincentive = 0;
                                $totalcharges = 0;
                                //Update 28/5/2020
                                $checkcomplete = DB::table('deliverystatuses')
                                ->leftjoin('deliveryform','deliveryform.Id','=','deliverystatuses.deliveryform_Id')
                                ->leftjoin('radius','radius.Id','=','deliveryform.Location')
                                ->select('deliverystatuses.Id','deliverystatuses.deliveryform_Id','radius.Latitude','radius.Longitude')
                                ->whereRaw('deliverystatuses.Id = (SELECT Max(deliverystatuses.Id) from deliveryform left join (select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max on `max`.`deliveryform_Id` = `deliveryform`.`Id` left join `deliverystatuses` on `deliverystatuses`.`Id` = max.`maxid` WHERE delivery_date = "'.$delivery->delivery_date.'" AND DriverId = "'.$delivery->DriverId.'" AND deliverystatuses.delivery_status = "Completed")')
                                ->first();

                                $checksamesite = DB::table('deliveryform')
                                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                                ->whereRaw('deliveryform.Location = "'.$delivery->radiusId.'" AND delivery_date = "'.$delivery->delivery_date.'" AND DriverId = "'.$delivery->DriverId.'" AND deliverystatuses.delivery_status = "Completed" AND deliveryform.DO_No NOT LIKE BINARY "%\_R%"')
                                ->count();

                                // if($delivery->Option == "Collection")
                                // {
                                //     DB::table('driverlocation')
                                //     ->where('formId',$delivery->DeliveryId)
                                //     ->update([
                                //         'lat_to' => $delivery->Latitude,
                                //         'long_to' => $delivery->Longitude,
                                //         'lat_from' => $request->Latitude_In,
                                //         'long_from' => $request->Longitude_In
                                //     ]);
                                // }

                                if($checkcomplete)
                                {
                                    DB::table('driverlocation')
                                    ->where('formId',$delivery->DeliveryId)
                                    ->update([
                                        'lat_from' => $checkcomplete->Latitude,
                                        'long_from' => $checkcomplete->Longitude,
                                    ]);
                                }

                                // Transport Charges & Incentive for logistics2 TESTED by KP 18/3/2020
                                $coordinate = DB::table('driverlocation')
                                ->where('formId',$delivery->DeliveryId)
                                ->first();

                                if($checksamesite)
                                {
                                    $distance = 0;
                                }
                                else
                                {
                                    $distance = $this->googleapi($coordinate->lat_from, $coordinate->long_from, $coordinate->lat_to, $coordinate->long_to);
                                }

                                $incentiverate = DB::table('logistics')
                                ->select('rate')
                                ->where('Lorry_Type',$delivery->Lorry_Size)
                                ->whereRAW('"'.$distance.'" BETWEEN distance1 AND distance2')
                                ->where('type','Incentive')
                                ->first();

                                if($incentiverate)
                                {
                                    $totalincentive = $incentiverate->rate * $distance;
                                }

                                $transportchargesrate = DB::table('logistics')
                                ->select('rate')
                                ->where('Lorry_Type', $delivery->Lorry_Size)
                                ->whereRAW('"'.$distance.'" BETWEEN distance1 AND distance2')
                                ->where('type','Charges')
                                ->first();

                                if($transportchargesrate)
                                {
                                    $totalcharges = $transportchargesrate->rate * $distance;
                                }

                                //On time incentive calculation 30mins buffer
                                // $starttime = DB::table('deliverystatuses')
                                // ->where('deliveryform_Id', $delivery->DeliveryId)
                                // ->select('created_at')
                                // ->orderby('Id')
                                // ->first();

                                // $plusthirty = date('Y-m-d H:i:s',strtotime($starttime->created_at."+30 minutes"));
                                // $now = DB::raw('Now()');

                                // if($now <= $plusthirty)
                                // {
                                //         $ontimerate = DB::table('logistics')
                                //         ->select('rate')
                                //         ->where('Lorry_Type',$delivery->Lorry_Size)
                                //         ->whereRAW('"'.$distance.'" BETWEEN distance1 AND distance2')
                                //         ->where('type','Ontime')
                                //         ->first();

                                //         $ontime = $ontimerate->rate;
                                // }

                                //On time decided by PIC
                                if($request->onTime == "Yes")
                                {
                                    $ontimerate = DB::table('logistics')
                                    ->select('rate')
                                    ->where('Lorry_Type',$delivery->Lorry_Size)
                                    ->whereRAW('"'.$distance.'" BETWEEN distance1 AND distance2')
                                    ->where('type','Ontime')
                                    ->first();

                                    $ontime = isset($ontimerate) ? $ontimerate->rate : 0;
                                }

                                DB::table('deliveryform')
                                ->where('Id',$delivery->DeliveryId)
                                ->update([
                                        'basicincentive' => $totalincentive,
                                        'distance_km' => $distance,
                                        'incentive_rate' => $incentiverate ? $incentiverate->rate : 0,
                                        'charges_rate' => $transportchargesrate ? $transportchargesrate->rate : 0,
                                        'charges' => $totalcharges,
                                        'ontime' => $ontime
                                ]);

                                $id=DB::table('deliverystatuses')
                                ->insertGetId([
                                        'deliveryform_Id' => $delivery->DeliveryId,
                                        'user_Id' => $me->Id,
                                        'delivery_status' => 'Completed',
                                        'delivery_status_details' => 'Task Completed by Driver',
                                        'created_at' => DB::raw('now()'),
                                        'remarks' => $input["Remarks"],
                                ]);


                                $insertid=DB::table('deliverytracking')
                                ->insertGetId([
                                        'deliveryform_id'  => $delivery->DeliveryId,
                                        'deliverystatus_id' => $id,
                                        'latitude1' => $input["Latitude_In"],
                                        'Longitude1' => $input["Longitude_In"],
                                        'created_at' => DB::raw('now()'),
                                        'created_by' => $me->Id,
                                        'type' => 'Completed Delivery',
                                        // 'Scope' => $input["Scope"],
                                        // 'Project_Code' => $input["Project_Code"],
                                        // 'Deduction' => $deduction
                                ]);


                                $did=$delivery->DeliveryId;
                                $id = $id;
                        }

                        $pic = DB::table('deliveryreview')
                        ->insertGetId([
                                'pic_staff_id' => $input["Staff_id"],
                                'pic_name' => $input["pic_name"],
                                'created_at' => DB::raw('now()'),
                                'created_by' => $me->Id,
                                'deliveryform_Id' => $did,
                                // 'onTime' => $input["onTime"],
                        ]);

                        $filenames="";
                        $attachmentUrl = null;
                        $type="Delivery";
                        $uploadcount=count($request->file('attachment'));

                        if ($request->hasFile('attachment')) {

                            for ($i=0; $i <$uploadcount ; $i++) {
                                # code...
                                $file = $request->file('attachment')[$i];
                                $destinationPath=public_path()."/private/upload/Delivery";
                                $extension = $file->getClientOriginalExtension();
                                $originalName=$file->getClientOriginalName();
                                $fileSize=$file->getSize();
                                $fileName=time()."_".$i.".".$extension;
                                $upload_success = $file->move($destinationPath, $fileName);
                                $insert=DB::table('files')->insertGetId(
                                    ['Type' => $type,
                                     'TargetId' => $id,
                                     'UserId' => $me->Id,
                                     'File_Name' => $originalName,
                                     'File_Size' => $fileSize,
                                     'Web_Path' => '/private/upload/Delivery/'.$fileName
                                    ]
                                );
                                $attachmentUrl = url('/private/upload/Delivery/'.$fileName);
                                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
                            }

                            $filenames=substr($filenames, 0, strlen($filenames)-1);

                            //return '/private/upload/'.$fileName;
                        }
                }

                $deliverydetail = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
                ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->select('requestor.Name as requestorName','roadtax.Vehicle_No','roadtax.Lorry_Size','projects.Project_Name','options.Option','driver.Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','deliveryitem.Qty_received','options.Option','approver.Name as approverName','deliverystatuses.delivery_status','requestor.Company_Email as requestorCompanyEmail','approver.Company_Email as approverEmail','deliverystatuses.remarks','radius.Location_Name','deliverystatuses.delivery_status_details','deliverystatuses.remarks as deliveryremarks','tracker.Site_Name','deliveryform.DO_No','deliveryform.delivery_date','deliveryform.delivery_time',
                'deliveryform.pickup_date','salesorderid','requestor.Player_Id')
                ->where('deliveryform.Id', '=',$Id)
                ->first();
                if($deliverydetail->salesorderid != 0){
                        $temp=DB::table('deliveryitem')
                        ->select('deliveryitem.Id')
                        ->join('inventories','inventories.Id','=','deliveryitem.inventoryId')
                        ->where('deliveryitem.formId',$Id)
                        ->where('inventories.Item_Code','LIKE','SC%')
                        ->get();
                        foreach($temp as $t){
                                $serId=DB::table('serviceticket')
                                ->insertGetId([
                                        'service_type'=>"PM(O)",
                                        'genset_no'=>$t->Id,
                                        'service_id'=>$this->generateServiceNo(),
                                        'DeliveryId'=>$Id

                                ]);
                                $today=date("d-M-Y");
                                DB::table('speedfreakservice')
                                ->insert([
                                        'ServiceId'=>$serId,
                                        'last_service'=>$today,
                                        'upcoming_service'=>date('d-M-Y', strtotime($today."+45 days")),
                                        'Status'=>"In-Progress"
                                ]);
                        }
                }
                $NotificationSubject="";
                $emails = array();
                $notifyplayerid = array();

                if($id>0)
                {
                        array_push($emails,$deliverydetail->requestorCompanyEmail);
                        array_push($emails,$deliverydetail->approverEmail);
                        array_push($notifyplayerid,$deliverydetail->Player_Id);

                        $subscribers = DB::table('notificationtype')
                        ->leftJoin('notificationsubscriber', 'notificationtype.Id', '=', 'notificationsubscriber.NotificationTypeId')
                        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                        ->where('notificationtype.Id','=',78)
                        ->get();

                        $NotificationSubject == "" ? $NotificationSubject="Task Completed by Driver":$NotificationSubject;

                        // Mail::send('emails.deliveryemailapp', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                        // {
                        //         array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        //         $emails = array_filter($emails);
                        //         $message->to($emails)->subject($NotificationSubject);
                        // });

                        $emails=array();
                        foreach ($subscribers as $subscriber)
                        {
                                $NotificationSubject = $subscriber->Notification_Subject;

                                if ($subscriber->Company_Email!="")
                                {
                                        array_push($emails,$subscriber->Company_Email);
                                }
                                else
                                {
                                        array_push($emails,$subscriber->Personal_Email);
                                }
                                array_push($notifyplayerid,$subscriber->Player_Id);
                        }

                        $NotificationSubject == "" ? $NotificationSubject="Task Completed by Driver":$NotificationSubject;
                        $this->sendpushnotifcation($notifyplayerid, $NotificationSubject);

                        // Mail::send('emails.warehouse2', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                        // {
                        //         array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        //         $emails = array_filter($emails);
                        //         $message->to($emails)
                        //         ->subject($NotificationSubject);
                        // });

                        $response = 1;

                }

                $link = $this->deliveryorderpdf($Id);

                return json_encode(['link'=>$link,'response'=>$response]);

        }

        public function deliveryorderpdf($id)
    {
        $order=DB::table('deliveryform')
        ->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->leftjoin('companies','deliveryform.company_id','=','companies.Id')
        ->leftjoin('companies as client','deliveryform.client','=','client.Id')
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Company Logo" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'companies.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid`'))
        ->leftjoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
        ->leftjoin(DB::raw('(SELECT Id,deliveryform_id FROM deliverytracking WHERE type="Completed Delivery") as track'),'track.deliveryform_id','=','deliveryform.Id')
        ->leftJoin('deliverytracking','deliverytracking.Id','=','track.Id')
        ->leftJoin('radius as realtimelocation', function($join)
        {
            $join->on('realtimelocation.Latitude','LIKE',DB::raw('CONCAT("%",SUBSTRING(deliverytracking.latitude1,1,8),"%")') );
            $join->on('realtimelocation.Longitude','LIKE',DB::raw('CONCAT("%",SUBSTRING(deliverytracking.longitude1,1,8),"%")') );
        })
        ->leftJoin('users as driver','driver.Id','=','deliveryform.DriverId')
        ->leftjoin('users as requestor','requestor.Id','=','deliveryform.RequestorId')
        ->leftjoin('deliverysignature','deliverysignature.formId','=','deliveryform.Id')
        ->select('roadtax.Type','files.Web_Path','companies.Company_Name','deliveryform.DO_No','deliveryform.delivery_date','companies.address','companies.Contact_No as Company_No','companies.Fax_No','radius.Latitude','radius.Longitude','client.Company_Name as clientName','radius.Location_Name','client.Contact_No as clientNum','client.Fax_No as clientFax','client.Address as clientAddress','companies.Contact_No','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.po','deliveryform.term','deliveryform.Remarks','deliverytracking.latitude1','deliverytracking.longitude1','realtimelocation.Location_Name as driverlocation','driver.StaffId as driverId','driver.Name as driver','requestor.Name as requestor',DB::raw('DATE_FORMAT(STR_TO_DATE(deliveryform.created_at,"%Y-%m-%d"),"%d-%M-%Y") as requestdate'),'requestor.StaffId as requestorId','deliverysignature.qr','deliverysignature.signature')
        ->where('deliveryform.Id','=',$id)
        ->first();

        $location1 = "";
        $location2 = "";
        if(strlen($order->Location_Name) >= 21)
        {
            $location1 = substr($order->Location_Name, 0,20);
            $location2 = substr($order->Location_Name, 20);
        }

        $cond=DB::table('deliverycondition')
        ->select('options.Option')
        ->leftjoin('options','options.Id','=','deliverycondition.options_Id')
        ->where('deliveryform_Id','=',$id)
        ->get();

        // $additional = DB::table('deliveryitem')
        // ->select('Ad')
        $items=DB::table('deliveryitem')
        ->leftjoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
        ->select('deliveryitem.Qty_request','inventories.Item_Code','inventories.Description','deliveryitem.add_desc','inventories.Unit')
        ->where('deliveryitem.formId','=',$id)
        ->get();

        $note=DB::table('deliverynote')
        ->select('deliverynote.options_Id','options.Option','deliverynote.note')
        ->leftjoin('options','deliverynote.options_Id','=','options.Id')
        ->where('deliverynote.deliveryform_Id','=',$id)
        ->get();

        $checkmr=DB::table('materialrequest')
        ->where('materialrequest.DeliveryId',$id)
        ->groupBy('materialrequest.DeliveryId')
        ->get();
        $comp=[];
        if($checkmr){
            $comp=DB::table('companies')
            ->select('Company_Name','Address','Office_No','Fax_No')
            ->whereIn('Id',[1,3])
            ->get();
        }

        $signature = DB::table('files')
        ->where('TargetId',14329)
        ->where('Type','Signature')
        ->first();

        $html = view('deliveryorderpdf', ['order'=>$order,'items'=>$items,'cond'=>$cond,'note'=>$note,'comp'=>$comp,'location1'=>$location1,'location2'=>$location2,'signature'=>$signature]);
        // return $html;
        $link = $this->DOPdf($html,$id,$order->DO_No);

        return $link;
    }

     public function GeneralExport($html)
  {
    set_time_limit(3000);
    $options = new Options();
    $options->set('isRemoteEnabled', TRUE);
    $options->set('logOutputFile',__DIR__ . '/dompdf.log.html');
    $dompdf = new Dompdf($options);
    $dompdf ->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $content = $dompdf->output();
    return $content;
  }

  public function DOPdf($html,$id,$donum)
  { 
    $content = $this->GeneralExport($html);
    $path = "/private/upload/E-DO";

    File::makeDirectory(public_path().$path, 0777, true, true);
    $fileName = $donum.".pdf";
    file_put_contents(public_path().$path."/".$fileName, $content);

     $insert=DB::table('files')->insertGetId(
          ['Type' => "Delivery",
           'TargetId' => $id,
           'File_Name' => $fileName,
           'Document_Type' => "E-DO",
           'Web_Path' => $path."/".$fileName
          ]
      );

     return $path."/".$fileName;
  }

        private function generateServiceNo(){
		$temp=DB::Table('serviceticket')
		->select('service_id')
		->orderBy('service_id','desc')
		->first();
		$temp=$temp ? explode('-',$temp->service_id):1;
		return $temp ? "SVT-".sprintf('%05s',(int)$temp[1]+1):"SVT-".sprintf("%05s",1);
	}
        public function incompletedelivery2(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();

// dd($input);
                $Id = $input["Id"];


                // $check = DB::table('deliveryitem')
                // ->select('*')
                // ->where('Qty_received', null)
                // ->where('deliveryitem.formId','=', $Id)
                // ->get();

                // if($check)
                // {
                //         return json_encode([
                //                 'Required' => [
                //                         'Please check item first.'
                //                 ]
                //         ]);
                // }

                // // dd($input);
                // if (! $request->hasFile('attachment')) {
                //         return json_encode([
                //             'Required' => [
                //                 'Attachment is required for time in.'
                //             ]
                //         ]);
                // }

                $mydelivery = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
                // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftJoin(DB::raw('(SELECT GROUP_CONCAT(DISTINCT inventories.Warehouse SEPARATOR ", ") as Warehouse, deliveryform.Id as deliveryformId FROM deliveryform LEFT JOIN deliveryitem ON deliveryitem.formId = deliveryform.Id LEFT JOIN inventories ON inventories.Id = deliveryitem.inventoryId GROUP BY deliveryform.Id) as wh'), 'wh.deliveryformId', '=', 'deliveryform.Id')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location','radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.created_at','deliveryform.delivery_time','deliveryform.pick_up_time','wh.Warehouse','deliveryform.pickup_date','deliveryform.salesorderid')
                ->where('deliverystatuses.deliveryform_Id', '=', $Id)
                ->where('deliveryform.DriverId', '=', $me->Id)
                // ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Accepted by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Pick Up by Driver") OR (deliverystatuses.delivery_status = "Accepted" AND deliverystatuses.delivery_status_details = "Start Delivery by Driver") OR (deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details = "Task Completed by Driver"))')
                // ->orderBy('deliverystatuses.delivery_status','desc')
                // ->orderBy('deliveryform.delivery_date', 'asc')
                ->get();


                // check for already time in with the date
                $exist = DB::table('deliverytracking')
                ->where('created_by', '=', $me->Id)
                ->where('deliveryform_Id', '=', $Id)
                // ->where('created_at', '=', $input["Date"])
                ->where('created_at','<>','')
                ->where('type', '=', 'Incomplete Delivery')
                ->first();

                $id=0;

                if(! $exist)
                {
                        // $did=4;
                        foreach ($mydelivery as $delivery)
                        {
                                $id=DB::table('deliverystatuses')
                                ->insertGetId([
                                        'deliveryform_Id' => $delivery->DeliveryId,
                                        'user_Id' => $me->Id,
                                        'delivery_status' => 'Incomplete',
                                        'delivery_status_details' => 'Task Completed by Driver',
                                        'created_at' => DB::raw('now()'),
                                        'remarks' => $input["Remarks"],
                                ]);

                                if($delivery->salesorderid != 0)
                                {
                                    DB::table('salesorderid')
                                    ->where('Id','=',$delivery->salesorderid)
                                    ->update([
                                        'do' => 0
                                    ]);
                                }

                                $insertid=DB::table('deliverytracking')
                                ->insertGetId([
                                        'deliveryform_id'  => $delivery->DeliveryId,
                                        'deliverystatus_id' => $id,
                                        // 'latitude1' => $input["Latitude_In"],
                                        // 'Longitude1' => $input["Longitude_In"],
                                        'created_at' => DB::raw('now()'),
                                        'created_by' => $me->Id,
                                        'type' => 'Incomplete Delivery',
                                        // 'Scope' => $input["Scope"],
                                        // 'Project_Code' => $input["Project_Code"],
                                        // 'Deduction' => $deduction
                                ]);

                                $did=$delivery->DeliveryId;
                                $id = $id;
                        }

                        $pic = DB::table('deliveryreview')
                        ->insertGetId([
                                // 'pic_staff_id' => $input["Staff_id"],
                                // 'pic_name' => $input["pic_name"],
                                'created_at' => DB::raw('now()'),
                                'created_by' => $me->Id,
                                'deliveryform_Id' => $did,
                                // 'onTime' => $input["onTime"],
                        ]);

                        $filenames="";
                        $attachmentUrl = null;
                        $type="Delivery";
                        $uploadcount=count($request->file('attachment'));

                        if ($request->hasFile('attachment')) {

                            for ($i=0; $i <$uploadcount ; $i++) {
                                # code...
                                $file = $request->file('attachment')[$i];
                                $destinationPath=public_path()."/private/upload/Delivery";
                                $extension = $file->getClientOriginalExtension();
                                $originalName=$file->getClientOriginalName();
                                $fileSize=$file->getSize();
                                $fileName=time()."_".$i.".".$extension;
                                $upload_success = $file->move($destinationPath, $fileName);
                                $insert=DB::table('files')->insertGetId(
                                    ['Type' => $type,
                                     'TargetId' => $id,
                                     'UserId' => $me->Id,
                                     'File_Name' => $originalName,
                                     'File_Size' => $fileSize,
                                     'Web_Path' => '/private/upload/Delivery/'.$fileName
                                    ]
                                );
                                $attachmentUrl = url('/private/upload/Delivery/'.$fileName);
                                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
                            }

                            $filenames=substr($filenames, 0, strlen($filenames)-1);

                            //return '/private/upload/'.$fileName;
                        }
                }

                $deliverydetail = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
                ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->select('requestor.Name as requestorName','roadtax.Vehicle_No','roadtax.Lorry_Size','projects.Project_Name','options.Option','driver.Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','deliveryitem.Qty_received','options.Option','approver.Name as approverName','deliverystatuses.delivery_status','requestor.Company_Email as requestorCompanyEmail','approver.Company_Email as approverEmail','deliverystatuses.remarks','radius.Location_Name','deliverystatuses.delivery_status_details','deliverystatuses.remarks as deliveryremarks','tracker.Site_Name','deliveryform.DO_No','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date')
                ->where('deliveryform.Id', '=',$Id)
                ->first();

                $NotificationSubject="";
                $emails = array();

                // if($id>0)
                // {
                //         array_push($emails,$deliverydetail->requestorCompanyEmail);
                //         array_push($emails,$deliverydetail->approverEmail);

                //         $subscribers = DB::table('notificationtype')
                //         ->leftJoin('notificationsubscriber', 'notificationtype.Id', '=', 'notificationsubscriber.NotificationTypeId')
                //         ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                //         ->where('notificationtype.Id','=',78)
                //         ->get();

                //         $NotificationSubject == "" ? $NotificationSubject="Task Completed by Driver":$NotificationSubject;

                //         Mail::send('emails.deliveryemailapp', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                //         {
                //                 array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                //                 $emails = array_filter($emails);
                //                 $message->to($emails)->subject($NotificationSubject);
                //         });

                //         $emails=array();
                //         foreach ($subscribers as $subscriber)
                //         {
                //                 $NotificationSubject = $subscriber->Notification_Subject;

                //                 if ($subscriber->Company_Email!="")
                //                 {
                //                         array_push($emails,$subscriber->Company_Email);
                //                 }
                //                 else
                //                 {
                //                         array_push($emails,$subscriber->Personal_Email);
                //                 }
                //         }

                //         $NotificationSubject == "" ? $NotificationSubject="Task Completed by Driver":$NotificationSubject;

                //         Mail::send('emails.warehouse2', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                //         {
                //                 array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                //                 $emails = array_filter($emails);
                //                 $message->to($emails)
                //                 ->subject($NotificationSubject);
                //         });

                //         return 1;
                // }

                // return json_encode($deliverydetail);
                return 1;
        }

        public function getitem(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();

                $Id = explode(",", $input["Id"]);

                $projectid = DB::table('deliveryform')
                ->select('ProjectId')
                ->where('Id','=',$Id)
                ->first();

                $genset = DB::table('projects')
                ->select('Id','Project_Name')
                ->where('Project_Name','LIKE',"%Genset%")
                ->get();

                $gensetid = json_decode(json_encode($genset),true);
                $excludeid = [142,143,144];
                for($i=0; $i<Count($gensetid); $i++)
                {
                 array_push($excludeid,$gensetid[$i]['Id']);
                }

                // if($projectid = 142)
                // {
                //         $item = DB::table('deliveryitem')
                //         ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                //         ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                //         ->whereIn('deliveryitem.formId', $Id)
                //         // ->where('deliveryitem.available', '=', 1)
                //         ->get();
                // }
                // elseif($projectid = 143)
                // {
                //         $item = DB::table('deliveryitem')
                //         ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                //         ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                //         ->whereIn('deliveryitem.formId', $Id)
                //         // ->where('deliveryitem.available', '=', 1)
                //         ->get();
                // }
                // elseif($projectid = 144)
                // {
                //         $item = DB::table('deliveryitem')
                //         ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                //         ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                //         ->whereIn('deliveryitem.formId', $Id)
                //         // ->where('deliveryitem.available', '=', 1)
                //         ->get();
                // }
                if(in_array($projectid->ProjectId, $excludeid))
                {
                         $item = DB::table('deliveryitem')
                        ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                        ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                        ->whereIn('deliveryitem.formId', $Id)
                        // ->where('deliveryitem.available', '=', 1)
                        ->get();
                }
                else
                {
                        $item = DB::table('deliveryitem')
                        ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                        ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                        ->whereIn('deliveryitem.formId', $Id)
                        ->where('deliveryitem.available', '=', 1)
                        ->get();
                }

                return json_encode($item);
        }


        public function checkitem(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $input = $request->all();

                // dd($input);

                $ItemId = $input['ItemId'];

                $Id = $input["Id"];

                $projectid = DB::table('deliveryform')
                ->select('ProjectId')
                ->where('Id','=',$Id)
                ->first();

                $checkbox = $input["checkbox"];

                if($checkbox == 1) {

                        // if($projectid = 142)
                        // {
                        //         $items = DB::table('deliveryitem')
                        //         ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                        //         ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                        //         ->where('deliveryitem.formId', $Id)
                        //         // ->where('deliveryitem.available', '=', 1)
                        //         ->get();
                        // }
                        // else if($projectid = 143)
                        // {
                        //         $items = DB::table('deliveryitem')
                        //         ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                        //         ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                        //         ->where('deliveryitem.formId', $Id)
                        //         // ->where('deliveryitem.available', '=', 1)
                        //         ->get();
                        // }
                        // else if($projectid = 144)
                        // {
                        //         $items = DB::table('deliveryitem')
                        //         ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                        //         ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                        //         ->where('deliveryitem.formId', $Id)
                        //         // ->where('deliveryitem.available', '=', 1)
                        //         ->get();
                        // }
                        $genset = DB::table('projects')
                        ->select('Id','Project_Name')
                        ->where('Project_Name','LIKE',"%Genset%")
                        ->get();

                        $gensetid = json_decode(json_encode($genset),true);
                        $excludeid = [142,143,144];
                        for($i=0; $i<Count($gensetid); $i++)
                        {
                         array_push($excludeid,$gensetid[$i]['Id']);
                        }
                        if(in_array($projectid->ProjectId, $excludeid))
                        {
                               $items = DB::table('deliveryitem')
                                ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                                ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                                ->where('deliveryitem.formId', $Id)
                                // ->where('deliveryitem.available', '=', 1)
                                ->get();
                        }
                        else
                        {
                                $items = DB::table('deliveryitem')
                                ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                                ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                                ->where('deliveryitem.formId', $Id)
                                // ->where('deliveryitem.available', '=', 1)
                                ->get();
                        }

                        // if($projectid = 142)
                        // {
                        //         $items = DB::table('deliveryitem')
                        //         ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                        //         ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                        //         ->whereIn('deliveryitem.formId', $Id)
                        //         // ->where('deliveryitem.available', '=', 1)
                        //         ->get();
                        // }
                        // elseif($projectid = 143)
                        // {
                        //         $items = DB::table('deliveryitem')
                        //         ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                        //         ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                        //         ->whereIn('deliveryitem.formId', $Id)
                        //         // ->where('deliveryitem.available', '=', 1)
                        //         ->get();
                        // }
                        // else
                        // {
                        //         $items = DB::table('deliveryitem')
                        //         ->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitem.inventoryId')
                        //         ->select('deliveryitem.Id as deliveryitemId','inventories.Item_Code','deliveryitem.inventoryId', 'deliveryitem.Qty_request as Qty_send', 'deliveryitem.formId as deliveryformId')
                        //         ->whereIn('deliveryitem.formId', $Id)
                        //         ->where('deliveryitem.available', '=', 1)
                        //         ->get();
                        // }

                        foreach($items as $item)
                        {
                                // if($checkbox == 1)
                                // {
                                //         $delivery_item = DB::table('deliveryitem')
                                //         ->where('Id', '=', $item->deliveryitemId)
                                //         ->update(array(
                                //             'Qty_received' =>  $input["Qty_received"],
                                //             'remarks' => $input["Remarks"],
                                //         ));
                                // }
                                // else
                                // {
                                        $Qty_received = $item->Qty_send;

                                        $delivery_item = DB::table('deliveryitem')
                                        ->where('Id', '=', $item->deliveryitemId)
                                        ->update(array(
                                            'Qty_received' =>  $Qty_received,
                                            // 'remarks' => $input["Remarks"],
                                        ));
                                // }
                        }
                }
                else
                {

                        foreach($ItemId as $index => $id)
                        {
                                DB::table('deliveryitem')
                                ->where('Id', '=', $id)
                                ->update(array(
                                    'Qty_received' =>  $input["Qty_received"][$index],
                                    'remarks' => $input["Remarks"][$index],
                                ));
                        }
                }

                return 1;
        }

        function sendOpenTrip($playerids)
        {

                $me = JWTAuth::parseToken()->authenticate();

                $content = array(
                    "en" => 'Open Trip'
                );

                $fields = array(
                    'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
                    // 'included_segments' => array("All"),
                    'include_player_ids' =>$playerids,
                    'data' => array("type" => "OpenTrip"),
                    'contents' => $content,
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

        function getVehicle(Request $request){
                $me = JWTAuth::parseToken()->authenticate();
                return json_encode(
                        DB::Table('roadtax')
                        ->where('Type',strtoupper($request->type))
                        ->where('Lorry_Size',$request->size)
                        ->get()
                );
        }

        function getLorrySize(){
                return
                        DB::Table('roadtax')
                        ->select('Lorry_Size')
                        ->where('Type','CV')
                        ->where('Lorry_Size','<>','')
                        ->orderBy('Lorry_Size')
                        ->groupBy('Lorry_Size')
                        ->get();
        }

        public function getSite(Request $request)
        {
                $me=JWTAuth::parseToken()->authenticate();
                $input = $request->all();
                $site = DB::table('radius')
                ->select('Id','Location_Name')
                ->where('ProjectId','=',$input['Id'])
                ->get();

                $allclient = DB::table('companies')
                ->select('companies.Id','companies.Company_Name','companies.Company_Code')
                ->where('companies.Client','=','Yes')
                ->groupBy('companies.Company_Name')
                ->get();
                return response()->json(['site' => $site,'allclient'=>$allclient]);

        }
        function getProjects(){
                return
                        DB::table('projects')
                        ->select('Id','Project_Name')
                        ->whereIn('Id',array(31,51,131,142,143,144,146))
                        ->get();
        }
        //Kye Peng 14 Feb 2020 Midascom Logistics 2
        public function getdriverhistory(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $driverhistory = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->select('deliveryform.Id','deliveryform.delivery_date','deliveryform.DO_No',DB::raw('SUM(CASE WHEN deliveryform.incentive != NULL THEN deliveryform.incentive ELSE 0 END + deliveryform.basicincentive + deliveryform.ontime) as incentive'))
                ->whereRaw('deliverystatuses.delivery_status = "Completed" AND deliveryform.DriverId = "'.$me->Id.'" ')
                ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y") BETWEEN str_to_date("'.$request->Start_Date.'","%d-%M-%Y") AND str_to_date("'.$request->End_Date.'","%d-%M-%Y") ')
                ->groupby('deliveryform.Id')
                ->get();

                return json_encode($driverhistory);
        }

        public function getincentiverecord(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();

                $incentive = DB::Table('deliveryform')
                ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                ->select(DB::raw(' (CASE WHEN deliveryform.basicincentive > 0 THEN deliveryform.basicincentive ELSE 0 END) AS basic'),'deliveryform.ontime as ontime', DB::raw(' (CASE WHEN deliveryform.incentive > 0 THEN deliveryform.incentive ELSE 0 END) AS extra'))
                ->where('deliveryform.Id',$request->Id)
                ->first();

                return json_encode($incentive);
        }

        function getOptions(){
                return json_encode(
                        DB::table('options')
                        ->select('options.Id',"options.Option",'options.Field')
                        ->whereIn('options.Field',['Purpose','Delivery Note','Delivery Condition'])
                        ->orderBy('options.Option','asc')
                        ->get());
        }
        function getPIC(){
                return json_encode(
                        DB::table('companies')
                        ->select('Person_In_Charge','Contact_No','Id')
                        ->groupBy('Person_In_Charge')
                        ->get());
        }
        function getSalesOrder(){
                return
                        DB::Table('salesorder')
                        ->select('Id','SO_Number')
                        ->where('SO_Number','<>','')
                        // ->where('do','=',0) ravi requested on 15/4/2020
                        ->get();
        }
        function getAllOptions(){
                return json_encode([
                        'salesOrder'=>$this->getSalesOrder(),
                        'projects'=>$this->getProjects(),
                        'lorrySize'=>$this->getLorrySize()
                ]);
        }
        function getItems(Request $request){

            // dd($request->all());
            $trackerid = DB::table('salesorder')
            ->select('trackerid')
            ->where('Id',$request->salesOrder)
            ->first();

            $checkanysoitem = DB::Table('deliveryitem')
            ->leftjoin('deliveryform','deliveryform.Id','=','deliveryitem.formId')
            ->leftJoin('salesorder','salesorder.Id','=','deliveryform.salesorderid')
            ->leftJoin('tracker','tracker.Id','=','salesorder.trackerid')
            ->where('salesorder.trackerid',$trackerid ? $trackerid->trackerid : 0 )
            ->count();

            if($request->salesOrder != 0 && $request->purpose == 1815)
            {
                $item = DB::table('deliveryitem')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,DO_No,salesorderid from deliveryform Group By salesorderid) as max'), 'max.maxid', '=', 'deliveryitem.formId')
                // ->leftJoin('deliveryform','deliveryform.Id','=','deliveryitem.formId')
                ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                ->select('inventories.Id','inventories.Item_Code','inventories.Description',DB::Raw('Concat(Item_Code," - ",Description) as item'),'inventories.dimension')
                // ->whereRaw('deliveryform.salesorderid = "'.$details->Id.'" OR deliveryform.salesorderid = "'.$details->parentId.'"')
                ->where('max.salesorderid','=',$request->salesOrder)
                ->groupby('inventories.Id')
                ->get();
            }
            else if($request->salesOrder != 0 && $request->purpose == 1814 && $checkanysoitem)
            {
                $item = DB::table('inventories')
                        ->select('inventories.Id','inventories.Item_Code','inventories.Description',DB::Raw('Concat(Item_Code," - ",Description) as item'),'inventories.dimension')
                        ->whereRaw('inventories.Item_Code LIKE "SC%" OR inventories.Item_Code LIKE "%Oil%" OR inventories.Categories LIKE "%Tank%"')
                        ->get();
            }
            else
            {
                $item = DB::table('inventories')
                ->select('Id','Item_Code','Description',DB::Raw('Concat(Item_Code," - ",Description) as item'),'inventories.dimension')
                ->get();
            }

                return json_encode($item);
        }
        function getMR(Request $request){
                $site=trim($request->site,'()');
                $arr=collect(json_decode($request->mrItem));
                if(!$arr->isEmpty())
                        $arr=" AND materialrequest.Id not in (".$arr->implode("Id",',').")";
                else $arr="";
                return json_encode(
                DB::select('SELECT (Count(CASE WHEN materialrequest.DeliveryId = 0 OR deliverystatuses.delivery_status = "Cancelled" OR
                        deliverystatuses.delivery_status= "Incomplete" OR deliveryform.Id is null or deliveryitem.Id is null  THEN 1 ELSE 0 END)) as total,material.Id,material.MR_No,inventories.Type,projects.Project_Name,materialstatus.Status
                from
                        materialrequest
                left join
                        material
                        on material.Id = materialrequest.MaterialId
                left join
                        inventories
                        on inventories.Id = materialrequest.InventoryId
                left join
                        projects
                        on projects.Id = material.ProjectId
                left join
                        (SELECT Max(Id) as maxid,MaterialId from materialstatus group by MaterialId) as status
                        on status.MaterialId = materialrequest.MaterialId
                left join
                        tracker
                        on tracker.Id = material.TrackerId
                left join
                        materialstatus
                        on materialstatus.Id = status.maxid
                left join
                        deliveryform
                        on deliveryform.Id = materialrequest.DeliveryId
                left join
                        (Select Max(Id) as maxid,deliveryform_Id from deliverystatuses group by deliveryform_Id) as max1
                        on max1.deliveryform_Id = deliveryform.Id
                left join
                        deliverystatuses
                        on deliverystatuses.Id = max1.maxid
                left join
                        deliveryitem
                        on deliveryitem.Id = materialrequest.DeliveryItemId
                where
                        inventories.Type = "MPSB" '.$arr.'  AND materialstatus.Status <> "Recalled" AND tracker.Project_Code = "'.$site.'" AND
                        (deliveryform.Id IS NULL OR deliveryitem.Id is null OR  materialrequest.DeliveryId = 0 OR deliverystatuses.delivery_status = "Cancelled" OR deliverystatuses.delivery_status= "Incomplete")
                        '.$arr.'
                GROUP BY
                        materialrequest.MaterialId'
                )
        );
        }

        function getMrItems(Request $request){
                $arr=collect(json_decode($request->mrItem));
                if(!$arr->isEmpty())
                        $arr=" AND materialrequest.Id not in (".$arr->implode("Id",',').")";
                else $arr="";
                return json_encode(
                DB::select('SELECT materialrequest.InventoryId, inventories.Item_Code,materialrequest.Id,inventories.Description,materialrequest.Qty,
                inventories.Unit,"false" as isChecked,"1" as MR,"" as addDesc, inventories.dimension
                from
                materialrequest
                left join
                inventories
                        on inventories.Id = materialrequest.InventoryId
                left join
                deliveryform
                        on deliveryform.Id = materialrequest.DeliveryId
                left join
                (Select Max(Id) as maxid,deliveryform_Id from deliverystatuses group by deliveryform_Id) as max
                        on max.deliveryform_Id = deliveryform.Id
                left join
                deliverystatuses
                        on deliverystatuses.Id = max.maxid
                left join
                deliveryitem
                        on deliveryitem.Id = materialrequest.DeliveryItemId
                where
                inventories.Type = "MPSB" AND materialrequest.MaterialId='.$request->materialId.'  AND
                (deliveryform.Id IS NULL OR deliveryitem.Id is null OR  materialrequest.DeliveryId = 0 OR deliverystatuses.delivery_status = "Cancelled" OR deliverystatuses.delivery_status= "Incomplete") '.$arr));
        }

        function submitDelivery(Request $request){
                $me=JWTAuth::parseToken()->authenticate();
                $input = $request->all();
                $formbytype = collect(json_decode($input['dataArray']))->groupby('purpose');
                foreach(json_decode($formbytype) as $k => $v)
                {
                        $detail = json_decode($input['delivery']);
                        $so = isset($detail->so->Id) ? $detail->so->Id : 0;

                        foreach($v as $key => $value){
                                $items = collect($value->itemarray)->groupby('MR');
                                $generateDO = $this->generateDONumber($value->detail->company,$so,$k);
                                // If import & normal add item return error
                                if( count($items) > 1)
                                {
                                        return json_encode(-1);
                                }

                                if($k == 1814)
                                {
                                        $dotype = "Delivery Order";
                                }
                                else
                                {
                                        $dotype = "Return Note";
                                }

                                $projtype = DB::table('companies')
                                ->where('Id',$value->detail->company)
                                ->select('type')
                                ->first();

                                $id = DB::table('deliveryform')
                                ->insertGetId([
                                        'roadtaxId' =>  $detail->roadTaxId,
                                        'created_at' => Carbon::now(),
                                        'delivery_date' => date('d-M-Y',strtotime($detail->deliveryDate)),
                                        'delivery_time' => $detail->deliveryTime,
                                        'pick_up_time' => $detail->pickupTime,
                                        'pickup_date' => date('d-M-Y',strtotime($detail->pickUpDate)),
                                        'Location' => $value->detail->site->Id,
                                        'ProjectId' => $value->project,
                                        'Purpose' => $k,
                                        'RequestorId' => $me->Id,
                                        'company_id' => $value->detail->company,
                                        'client' => $value->detail->client,
                                        'PIC_Name' => isset($value->detail->picinput) ? $value->detail->picinput:"",
                                        'PIC_Contact' => isset($value->detail->contact) ? $value->detail->contact:"",
                                        'Remarks' => isset($value->detail->remarks) ? $value->detail->remarks : "",
                                        'po' => isset($value->detail->po) ? $value->detail->po : "",
                                        'term' => isset($value->detail->term) ? $value->detail->term : "",
                                        'DO_NO' => $generateDO,
                                        'salesorderid' => $so,
                                        'project_type'=>$projtype->type,
                                        'offhire_date' => isset($value->detail->offhire) ? date('d-M-Y', strtotime($value->detail->offhire)) : ""
                                ]);

                                DB::table('salesorder')
                                ->where('Id',$so)
                                ->update([
                                    'do' => 1
                                ]);

                                if(isset($value->detail->note))
                                {
                                        foreach($value->detail->note as $ki => $val)
                                        {
                                                DB::table('deliverynote')
                                                ->insert([
                                                        'deliveryform_Id' => $id,
                                                        'options_Id' => $val->Id
                                                ]);
                                        }
                                }

                                if(isset($value->detail->condition))
                                {
                                        foreach($value->detail->condition as $ki => $val)
                                        {
                                                DB::table('deliverycondition')
                                                ->insert([
                                                        'deliveryform_Id' => $id,
                                                        'options_Id' => $val->Id
                                                ]);
                                        }
                                }

                                foreach($items as $itemkey => $itemvalue)
                                {
                                    foreach ($itemvalue as $ki => $va) {

                                        DB::table('deliveryitem')
                                        ->insert([
                                                'formId' => $id,
                                                'inventoryId' => $va->InventoryId,
                                                'Qty_request' => $va->Qty,
                                                'add_desc' => isset($va->addDesc) ? $va->addDesc:""
                                        ]);
                                    }

                                    if($itemkey == 1)
                                    {
                                            DB::table('deliveryform')
                                            ->where('Id',$id)
                                            ->update([
                                                    'import' => "MR"
                                                    ]);
                                    }

                                }

                                if($detail->lorrytype == "truck")
                                {
                                        DB::table('deliverystatuses')
                                        ->insert([
                                                'deliveryform_Id' => $id,
                                                'user_Id' => $me->Id,
                                                'delivery_status' => "Completed",
                                                'delivery_status_details' => "Special Delivery",
                                                'remarks' => "Logistics 2",
                                                'created_at' => Carbon::now()
                                        ]);
                                }
                                else
                                {
                                        DB::table('deliverystatuses')
                                        ->insert([
                                                'deliveryform_Id' => $id,
                                                'user_Id' => $me->Id,
                                                'delivery_status' => "Pending",
                                                'delivery_status_details' => "New ".$dotype." Application",
                                                'remarks' => "Logistics 2",
                                                'created_at' => Carbon::now()
                                        ]);
                                        DB::table('deliverystatuses')
                                        ->insert([
                                                'deliveryform_Id' => $id,
                                                'user_Id' => $me->Id,
                                                'delivery_status' => "Processing",
                                                'delivery_status_details' => "Accepted By Admin",
                                                'remarks' => "Logistics 2",
                                                'created_at' => Carbon::now()
                                        ]);
                                }

                                //insert image if offhiredate
                                if( isset($value->detail->offhire) )
                                {
                                    $filenames="";
                                    $attachmentUrl = null;
                                    $type="Delivery";
                                    $uploadcount=count($request->file('attachment'));

                                    if ($request->hasFile('attachment')) {

                                        for ($i=0; $i <$uploadcount ; $i++) {
                                            # code...
                                            $file = $request->file('attachment')[$i];
                                            $destinationPath=public_path()."/private/upload/Delivery";
                                            $extension = $file->getClientOriginalExtension();
                                            $originalName=$file->getClientOriginalName();
                                            $fileSize=$file->getSize();
                                            $fileName=time()."_".$i.".".$extension;
                                            $upload_success = $file->move($destinationPath, $fileName);
                                            $insert=DB::table('files')->insertGetId(
                                                ['Type' => $type,
                                                 'TargetId' => $id,
                                                 'UserId' => $me->Id,
                                                 'File_Name' => $originalName,
                                                 'File_Size' => $fileSize,
                                                 'Web_Path' => '/private/upload/Delivery/'.$fileName
                                                ]
                                            );
                                            $attachmentUrl = url('/private/upload/Delivery/'.$fileName);
                                            $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
                                        }

                                        $filenames=substr($filenames, 0, strlen($filenames)-1);
                                    }

                                    // terminate SO
                                    $trackerid = DB::table('salesorder')
                                    ->select('Id','trackerid','rental_start','rental_end')
                                    ->where('Id','=',$so)
                                    ->first();

                                    $project = DB::table('tracker')
                                    ->leftJoin('projects','projects.Id','=','tracker.ProjectID')
                                    ->select('tracker.Project_Code','projects.Project_Name','tracker.sales_order')
                                    ->where('tracker.Id','=',$trackerid->trackerid)
                                    ->first();

                                     $item = DB::table('salesorderitem')
                                      ->select('salesorderitem.*')
                                      ->where('salesorderitem.salesorderId','=',$so)
                                      ->get();

                                    $days = date("t",strtotime($trackerid->rental_start));
                                    $diff = strtotime($value->detail->offhire) - strtotime($trackerid->rental_start);
                                    $diff = ($diff / (60*60*24) ) +1;
                                    if($project->sales_order > 0)
                                    {
                                        foreach($item as $i => $val)
                                        {
                                        // $charges = round(($val->price/$days)*$diff);
                                            $charges = round(($val->price/$days)*$diff,2);
                                            DB::table('salesorderitem')
                                            ->where('Id','=',$val->Id)
                                            ->update([
                                                'price'=> $charges
                                            ]);
                                        }

                                            $itemtotal = DB::table('salesorderitem')
                                            ->select(DB::raw('SUM(qty * price) as total'))
                                            ->where('salesorderId','=',$so)
                                            ->groupby('salesorderid')
                                            ->first();

                                            DB::table('salesorder')
                                            ->where('Id','=',$so)
                                            ->update([
                                                'total_amount' => $itemtotal->total
                                            ]);

                                             DB::table('salesorder')
                                            ->where('Id',$so)
                                            ->update(['rental_end' => date('d-M-Y',strtotime($value->detail->offhire)) ]);

                                            DB::table('tracker')
                                            ->where('Id',$trackerid->trackerid)
                                            ->update([
                                                'recurring'=> 0
                                            ]);

                                            DB::table('salesorderdetails')
                                            ->insert([
                                                'salesorderId' => $so,
                                                'details' => "Terminate Auto Generating Sales Order",
                                                'userId' => $me->Id,
                                                'created_at' => Carbon::now()
                                            ]);
                                    }
                                }

                                $deliverydetail = DB::table('deliveryform')
                                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                                ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                                ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                                ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
                                ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                                ->select('requestor.Name as requestorName','roadtax.Vehicle_No','roadtax.Lorry_Size','projects.Project_Name','options.Option','driver.Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','deliveryitem.Qty_received','options.Option','approver.Name as approverName','deliverystatuses.delivery_status','requestor.Company_Email as requestorCompanyEmail','approver.Company_Email as approverEmail','deliverystatuses.remarks','radius.Location_Name','deliverystatuses.delivery_status_details','deliverystatuses.remarks as deliveryremarks','tracker.Site_Name','deliveryform.DO_No','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pick_up_time','deliveryform.pickup_date')
                                ->where('deliveryform.Id', $id)
                                ->first();
                                $deliveryitemlist = DB::table('deliveryitem')
                                ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                                ->select('inventories.Item_Code','inventories.Description','inventories.Unit','deliveryitem.Qty_request')
                                ->where('deliveryitem.formId','=',$id)
                                ->get();
                                $NotificationSubject="";
                                $emails = array();
                                $notifylist=array();
                                $notifyplayerid=array();

                                if($id)
                                {
                                        array_push($emails,$deliverydetail->requestorCompanyEmail);
                                        array_push($emails,$deliverydetail->approverEmail);

                                        $emails=array();
                                        $subscribers = DB::table('notificationtype')
                                        ->leftJoin('notificationsubscriber', 'notificationtype.Id', '=', 'notificationsubscriber.NotificationTypeId')
                                        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                                        ->where('notificationtype.Id','=',68)
                                        ->get();

                                        foreach ($subscribers as $subscriber)
                                        {
                                                array_push($emails, $subscriber->Company_Email);
                                                array_push($notifyplayerid, $subscriber->Player_Id);
                                                $notifyplayerid = array_filter($notifyplayerid);
                                        }

                                        if($notifyplayerid)
                                        {
                                                $this->sendpushnotifcation($notifyplayerid, 'New Delivery Request');
                                        }

                                        $NotificationSubject == "" ? $NotificationSubject="New Delivery Request":$NotificationSubject;

                                        // Mail::send('emails.deliveryapplication', ['deliverydetail' => $deliverydetail,'deliveryitemlist'=>$deliveryitemlist], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                                        // {
                                        //         array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                                        //         $emails = array_filter($emails);
                                        //         $message->to($emails)->subject($NotificationSubject);
                                        // });

                                }
                        }
                }
                //end of insert
                return json_encode(1);
        }

        private function submitimages(Reqeust $request)
        {
            dd($request->all());
        }

        private function generateDONumber($companyid, $soid, $purpose)
        {
            if($companyid == NULL && $soid == NULL)
            {
                $company=DB::Table('companies')
                ->where('Id','=',1)
                ->get();
            }
            else if($companyid == NULL)
            {
                $companyid = DB::table('salesorder')
                ->select('companyId')
                ->where('Id','=',$soid)
                ->first();

                $company=DB::Table('companies')
                ->where('Id','=',$companyid->companyId)
                ->get();
            }
            else
            {
                $company=DB::Table('companies')
                ->where('Id','=',$companyid)
                ->get();
            }

            if($purpose == 1815)
            {
                $type = "RN";
            }
            else
            {
                $type = "DO";
            }

            $checking=DB::table('deliveryform')
            ->where('DO_No','LIKE',$company[0]->Initial.$type.Carbon::now()->format('y').'%')
            ->select(DB::raw(' Max(DO_No) as DO_No'),'Id')
            ->orderBy('Id','DESC')
            ->first();

            $temp1 = $company[0]->Initial.$type.Carbon::now()->format('y');
              if($checking->DO_No != null)
                {
                   $temp1=explode("-",$checking->DO_No)[0];
                   $temp=explode("-",$checking->DO_No)[1];
                   $temp=explode("_",$temp)[0];
                 }
              else
              {
                $temp = 0;
              }
               $checking == null ? $conv=sprintf("%05s",1):$conv=sprintf("%05s",$temp+1);
               $generateDO = $temp1.'-'.$conv;

               return $generateDO;
            }

        function calculateDimension(Request $request){
                return json_encode(
                        DB::table('deliveryitem')
                        ->select(
                                DB::raw('SUM(inventories.Dimension) as usedDimension')
                        )
                        ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                        ->leftjoin('deliveryform','deliveryitem.formId','=','deliveryform.Id')
                        ->leftjoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                        ->where('roadtaxId',$request->roadTaxId)
                        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y") = '.date_format(new DateTime($request->deliveryDate),"Y-m-d") )
                        ->get()
                );

        }

        function deliveryDetails(Request $request){
                $me=JWTAuth::parseToken()->authenticate();
                $deliveryDetails=DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftjoin(DB::raw('(SELECT  DeliveryId,Count(Id) as total from materialrequest group by DeliveryId) mr'),'mr.DeliveryId','=','deliveryform.Id')
                ->select('deliveryform.Id as DeliveryId','deliverystatuses.Id as StatusId','deliverystatuses.delivery_status',
                'deliverystatuses.delivery_status_details','deliveryform.pickup_date',
                'deliveryform.DriverId','driver.Name as Driver','deliveryform.DO_No',
                'roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','radius.Location_Name as Location',
                'radius.Longitude','radius.Latitude','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','roadtax.dimension',
                'deliveryform.created_at','deliveryform.delivery_time','deliveryform.pick_up_time',DB::raw('CASE WHEN mr.total is null then false else true end as isMR'),'requestor.Name as Requestor',DB::Raw('(CASE WHEN requestor.Id = '.$me->Id.' THEN requestor.Id ELSE "" END) as requestorId' ))
                // ->where('deliveryform.RequestorId', '=', 562)
                ->where('deliverystatuses.delivery_status',$request->status)
                ->whereRaw('STR_TO_DATE(deliveryform.delivery_date,"%d-%M-%Y") between STR_TO_DATE("'.$request->start.'","%d-%M-%Y") and STR_TO_DATE("'.$request->end.'","%d-%M-%Y")')
                // ->orderBy(DB::raw('str_to_date(deliverystatuses.delivery_status,"%d-%M-%Y")'),'desc')
                ->orderBy(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),'asc')
                ->get();
                return json_encode($deliveryDetails);
        }
        function deliveryDetail(Request $request){
                $detail=DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->where('deliveryform.Id',$request->deliveryId)
                ->first();
                $condition=DB::Table('deliverycondition')
                ->join('options','options.Id','=','deliverycondition.options_Id')
                ->where('deliverycondition.deliveryform_Id',$request->deliveryId)
                ->get();
                $note=DB::Table('deliverynote')
                ->join('options','options.Id','=','deliverynote.options_Id')
                ->where('deliverynote.deliveryform_Id',$request->deliveryId)
                ->get();
                $vehicle = DB::table('roadtax')
                ->select('Id','Vehicle_No')
                ->whereRaw('Lorry_Size != "" OR Type = "TRUCK"');
                // ->get();
                $lorries = DB::table('roadtax')
                ->select(DB::raW('0 as Id'), DB::raw('"SELF COLLECT" as Vehicle_No'))
                ->union($vehicle)
                ->get();

                return json_encode(['detail'=>$detail,'condition'=>$condition,'note'=>$note,'lorries'=>$lorries]);
        }
        function getDeliveryItems(Request $request){
                $item=DB::Table('deliveryitem')
                ->select('deliveryitem.Id','inventories.Item_Code','inventories.Description','inventories.Dimension','deliveryitem.add_desc',
                'deliveryitem.Qty_Request','inventories.Id as invId')
                ->join('inventories','inventories.Id','=','deliveryitem.inventoryId')
                ->where('formId',$request->deliveryId)
                ->get();
                return json_encode($item);
        }

        function saveItems(Request $request){
                foreach(json_decode($request->itemData) as $data){
                        if(!isset($data->Id)){
                                DB::Table('deliveryitem')
                                ->insert([
                                        'inventoryId'=>$data->invId,
                                        'Qty_Request'=>$data->Qty_Request,
                                        'add_desc'=> isset($data->add_desc) ? $data->add_desc : "",
                                        'formId'=>$request->deliveryId
                                ]);
                        }else{
                                DB::Table('deliveryitem')
                                ->where('Id',$data->Id)
                                ->update([
                                        'inventoryId'=>$data->invId,
                                        'Qty_Request'=>$data->Qty_Request,
                                        'add_desc'=> isset($data->add_desc) ? $data->add_desc : "",
                                ]);
                        }
                }
                DB::Table('deliveryitem')
                ->whereIn('Id',json_decode($request->removeData))
                ->delete();
                return json_encode($request->all());
        }
        function resubmit(Request $request){
                $me=JWTAuth::parseToken()->authenticate();
                $input = $request->all();
                $formIds = $input["Id"];
                $delivery = DB::table('deliveryform')
                ->leftJoin('deliverystatuses', 'deliveryform.Id', '=', 'deliverystatuses.deliveryform_Id')
                ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
                ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->select('requestor.Name as Requestor','deliveryform.delivery_date as Delivery_Date','deliverystatuses.delivery_status as Status','roadtax.Vehicle_No as Lorry','deliveryform.roadtaxId','roadtax.Lorry_Size','driver.Name as Driver','deliveryform.Location as Site','projects.Project_Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','options.Option as Purpose','deliveryform.Purpose as deliverypurpose','deliveryform.DO_No')
                ->orderBy('deliverystatuses.Id','desc')
                ->where('deliveryform.Id', '=',$formIds)
                ->first();
                $lorrytype = DB::table('roadtax')
                ->select('Type')
                ->where('Id','=',$delivery->roadtaxId)
                ->first();
                $status = $delivery->Status;

                $check=DB::table('deliveryitem')
                ->select(
                        DB::raw('roadtax.dimension - SUM(inventories.Dimension) as usedDimension')
                )
                ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                ->leftjoin('deliveryform','deliveryitem.formId','=','deliveryform.Id')
                ->leftjoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                ->where('roadtaxId',$request->roadTaxId)
                ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y") = '.date_format(new DateTime($request->deliveryDate),"Y-m-d") )
                ->get();
                if((float) $check[0]->usedDimension <= 0 && !json_decode($request->proceed)){
                        return "-1";
                }

                // $recalltime = DB::table('deliverystatuses')
                // ->where('deliverystatuses.deliveryform_Id','=',$input["Id"])
                // ->where('deliverystatuses.delivery_status','=','Recalled')
                // ->count();

                // $explode = explode("_",$delivery->DO_No);
                // $generateDO = $explode[0]."_rev".$recalltime;

                // DB::Table('deliveryform')
                // ->where('Id','=',$input['Id'])
                // ->update([
                //         'DO_No' => $generateDO
                // ]);

                if(str_contains($status,"Recalled")!==false){
                        DB::table('deliverystatuses')->insert(
                                ['deliveryform_Id' => $input["Id"],
                                        'user_Id' => $me->Id,
                                        'delivery_status' => "Processing",
                                        'delivery_status_details' => "Resubmit by Requestor",
                                        'updated_at'=> DB::raw('now()')
                                ]
                        );
                    if($delivery->roadtaxId == 0 || $lorrytype->Type == "TRUCK")
                    {

                       DB::table('deliverystatuses')->insert(
                                        ['deliveryform_Id' => $input["Id"],
                                         'user_Id' => $me->Id,
                                         'delivery_status' => "Completed",
                                         'delivery_status_details' => "",
                                         'updated_at'=> Carbon::now()
                                        ]
                                    );
                    }
                }
                else if(str_contains($status,"Insufficient")!==false){
                    DB::table('deliverystatuses')->insert(
                                        ['deliveryform_Id' => $input["Id"],
                                         'user_Id' => $me->Id,
                                         'delivery_status' => "Processing",
                                         'delivery_status_details' => "Resubmit by Requestor",
                                         'updated_at'=> Carbon::now()
                                        ]
                                    );
                }

                    $deliverydetail = DB::table('deliveryform')
                    ->leftJoin('deliverystatuses', 'deliveryform.Id', '=', 'deliverystatuses.deliveryform_Id')
                    ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                    ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                    ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                    ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                    ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                    ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
                    ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                    ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                    ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                    ->select('requestor.Name as Requestor','deliveryform.delivery_date as Delivery_Date','deliverystatuses.delivery_status as Status','roadtax.Vehicle_No as Lorry','roadtax.Lorry_Size','driver.Name as Driver','deliveryform.Location as Site','projects.Project_Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','options.Option as Purpose')
                    ->orderBy('deliverystatuses.Id','desc')
                    ->where('deliveryform.Id', '=',$input["Id"])
                    ->first();

                    $driver = DB::table('deliveryform')
                    ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                    ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                    ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                    ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                    ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                    ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                    ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                    ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                    ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
                    ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                    ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                    ->select('requestor.Name as requestorName','deliveryform.delivery_date','roadtax.Vehicle_No','roadtax.Lorry_Size','projects.Project_Name','options.Option','driver.Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','deliveryitem.Qty_send','deliveryitem.Qty_received','options.Option','approver.Name as approverName','deliverystatuses.delivery_status','requestor.Company_Email as requestorCompanyEmail','approver.Company_Email as approverEmail','deliverystatuses.remarks','deliverystatuses.delivery_status_details','tracker.Site_Name','deliveryform.DO_No','driver.Id as driverId')
                    ->where('deliveryform.Id', '=', $input["Id"])
                    ->first();

                     $subscribers = DB::table('notificationtype')
                    ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
                    ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                    ->where('notificationtype.Id','=',73)
                    ->get();

                    $emails = array();
                    $notifylist=array();
                    $notifyplayerid = array();

                    $notify = DB::table('users')
                    ->whereIn('Id', [$me->UserId])
                    ->get();

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

                        array_push($notifyplayerid,$subscriber->Player_Id);
                    }

                    foreach ($notify as $user) {

                        if ($user->Company_Email!="") {
                            array_push($emails,$user->Company_Email);
                        } else {
                            array_push($emails,$user->Personal_Email);
                        }
                    }

                    $this->sendpushnotifcation($notifyplayerid, $NotificationSubject);

                // Mail::send('emails.deliverystatus', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$me,$NotificationSubject)
                // {
                // array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                // $emails = array_filter($emails);
                // $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
                // });

                // array_push($notifylist,$driver->driverId);

                // $notify = DB::table('users')
                // ->whereIn('Id',$notifylist)
                // ->get();

                // $notifyplayerid=array();

                // foreach ($notify as $u)
                // {
                // array_push($notifyplayerid,$u->Player_Id);
                // }

                // if($notifyplayerid)
                // {
                // $this->sendNewTrip($notifyplayerid);
                // }
                return 1;
        }

        function canceldelivery(Request $request)
        {
                $me = JWTAuth::parseToken()->authenticate();
                $deliveryform = DB::table('deliveryform')
                ->where('Id',$request->Id)
                ->select(DB::raw('concat(delivery_date,"",delivery_time) as formdate'))
                ->first();

                // calculate the time difference
                $now = DB::select("select now() as today")[0]->today;
                $start = new DateTime($deliveryform->formdate);
                $end = new DateTime($now);
                $interval = $start->diff($end);
                $day = $interval->format('%d');

                if($day >= 3)
                {
                        $deductionrate = DB::table('options')
                        ->select('Option')
                        ->where('Table','Logistics')
                        ->where('Field','Late_Cancel_Deduction')
                        ->first();

                        DB::Table('deliveryform')
                        ->where('Id',$request->Id)
                        ->update([
                                'deduction' => $deductionrate->Option
                        ]);
                }

                DB::table('deliverystatuses')
                ->insert([
                        'deliveryform_Id' => $request->Id,
                        'user_Id' => $me->Id,
                        'delivery_status' => 'Cancelled',
                        'delivery_status_details' => 'Delivery Cancel Request',
                        'remarks' => $request->remarks
                ]);

                $deliverydetail = DB::table('deliveryform')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
                ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
                ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                ->leftJoin('options','options.Id','=','deliveryform.Purpose')
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
                ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
                ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->select('requestor.Name as requestorName','roadtax.Vehicle_No','roadtax.Lorry_Size','projects.Project_Name','options.Option','driver.Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','deliveryitem.Qty_received','options.Option','approver.Name as approverName','deliverystatuses.delivery_status','requestor.Company_Email as requestorCompanyEmail','approver.Company_Email as approverEmail','deliverystatuses.remarks','radius.Location_Name','deliverystatuses.delivery_status_details','deliverystatuses.remarks as deliveryremarks','tracker.Site_Name','deliveryform.DO_No','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pick_up_time','deliveryform.pickup_date')
                ->where('deliveryform.Id', $request->Id)
                ->first();

                $NotificationSubject="";
                $emails = array();
                $notifylist=array();
                $notifyplayerid=array();

                $notifyplayerid = array();
                array_push($notifyplayerid,$deliverydetail->Player_Id);

                $subscribers = DB::table('notificationtype')
                ->leftJoin('notificationsubscriber', 'notificationtype.Id', '=', 'notificationsubscriber.NotificationTypeId')
                ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                ->where('notificationtype.Id','=',74)
                ->get();

                foreach ($subscribers as $subscriber)
                {
                        array_push($notifyplayerid, $subscriber->Player_Id);
                        $notifyplayerid = array_filter($notifyplayerid);
                }

                $this->sendpushnotifcation($notifyplayerid, $pushnotificationtitle);

                // if($request->Id)
                // {
                //         array_push($emails,$deliverydetail->requestorCompanyEmail);
                //         array_push($emails,$deliverydetail->approverEmail);

                //         $subscribers = DB::table('notificationtype')
                //         ->leftJoin('notificationsubscriber', 'notificationtype.Id', '=', 'notificationsubscriber.NotificationTypeId')
                //         ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                //         ->where('notificationtype.Id','=',74)
                //         ->get();

                //         $NotificationSubject == "" ? $NotificationSubject="Requestor Cancel Delivery Trip":$NotificationSubject;

                //         Mail::send('emails.deliverycancel', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
                //         {
                //                 array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                //                 $emails = array_filter($emails);
                //                 $message->to($emails)->subject($NotificationSubject);
                //         });

                //         $emails=array();
                //         foreach ($subscribers as $subscriber)
                //         {
                //                 array_push($notifylist, $subscriber->Player_Id);
                //                 array_push($notifyplayerid, $subscriber->Player_Id);
                //                 $notifyplayerid = array_filter($notifyplayerid);
                //         }

                //         if($notifyplayerid)
                //         {
                //                 $this->sendpushnotifcation($notifyplayerid, 'Requestor Cancel Delivery Request');
                //         }
                // }\
                return "1";
        }

        function sendpushnotifcation($playerids, $title)
        {

                // $me = JWTAuth::parseToken()->authenticate();

                $content = array(
                    "en" => $title
                );

                $fields = array(
                    'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
                    'include_player_ids' =>$playerids,
                    'data' => array("type" => $title),
                    'contents' => $content,
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
        function saveDeliveryDetails(Request $request){
                $update=DB::Table('deliveryform')
                ->where('Id',$request->deliveryId)
                ->update([
                        'delivery_date'=>date('d-M-Y',strtotime(substr($request->deliveryDate,0,10))),
                        'delivery_time'=>$request->deliveryTime,
                        'pickup_date'=>date('d-M-Y',strtotime(substr($request->pickUpDate,0,10))),
                        'pick_up_time'=>$request->pickUpTime,
                        'PIC_Name'=>$request->pic,
                        'PIC_Contact'=>$request->picContact,
                        'roadtaxId' => $request->roadtaxId
                ]);
                return $update;
        }

        function recall(Request $request){
                $me = JWTAuth::parseToken()->authenticate();
                $input = $request->all();
                $deliverydetail = DB::table('deliveryform')
                ->leftJoin('deliverystatuses', 'deliveryform.Id', '=', 'deliverystatuses.deliveryform_Id')
                ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
                ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
                ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
                ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
                ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
                ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
                ->leftJoin('radius','radius.Id','=','deliveryform.Location')
                ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
                ->select('approver.Name as Approver','requestor.Name as Requestor','deliveryform.delivery_date as Delivery_Date','deliverystatuses.delivery_status as Status','roadtax.Vehicle_No as Lorry','roadtax.Lorry_Size','driver.Name as Driver','radius.Location_Name as Site','projects.Project_Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','options.Option as Purpose')
                ->orderBy('deliverystatuses.Id','desc')
                ->where('deliveryform.Id', '=',$input["Id"])
                ->first();

                $id=DB::table('deliverystatuses')->insertGetId(
                    ['deliveryform_Id' => $input["Id"],
                     'user_Id' => $me->Id,
                     'delivery_status' => "Recalled",
                     'delivery_status_details' => "Delivery Has Been Recalled",
                     'created_at' => DB::raw('now()'),
                     'updated_at' => DB::raw('now()')
                    ]
                );
                $recalltime = DB::table('deliverystatuses')
                ->where('deliverystatuses.deliveryform_Id','=',$input["Id"])
                ->where('deliverystatuses.delivery_status','=','Recalled')
                ->select(DB::raw('count(deliverystatuses.deliveryform_Id) as recallcount'))
                ->get();

                $do = DB::table('deliveryform')
                ->select('deliveryform.DO_No')
                ->where('deliveryform.Id','=',$input['Id'])
                ->get();

                $companyinit = DB::table('deliveryform')
                ->leftJoin('companies','deliveryform.company_id','=','companies.Id')
                ->select('companies.Initial')
                ->where('deliveryform.Id','=',$input['Id'])
                ->get();

                $init = json_decode(json_encode($companyinit), True);
                $do_string = json_decode(json_encode($do), True);
                $newrecall = json_decode(json_encode($recalltime), True);

                $explode = explode("_",$do_string[0]["DO_No"]);
                if($do_string[0]["DO_No"]==NULL || $do_string[0]["DO_No"]=="")
                {
                    $do1 = "";
                }
                else
                {
                    $do1 = $explode[0]."_rev".$newrecall[0]["recallcount"];
                }

                $update = DB::table('deliveryform')
                        ->where('deliveryform.Id','=',$input['Id'])
                        ->update(['VisitStatus'=>1,'DO_No'=>$do1,'DriverId'=>0]);
                $subscribers = DB::table('notificationtype')
                ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
                ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                ->where('notificationtype.Id','=',70)
                ->get();

                if ($id>0)
                {
                    $notify = DB::table('users')
                    ->whereIn('Id', [$me->UserId, $deliverydetail->Approver])
                    ->get();
                    $emails = array();
                    $notifyplayerid = array();
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

                        array_push($notifyplayerid,$subscriber->Player_Id);

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

                    $this->sendpushnotifcation($notifyplayerid, $NotificationSubject);

                //     Mail::send('emails.deliveryrecall', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$me,$NotificationSubject)
                //     {
                //             array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                //             $emails = array_filter($emails);
                //             $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
                //     });

                    return 1;
                }
                else {
                    return 0;
                }
        }

        public function getSOdetails(Request $request)
        {
                $details = DB::table('salesorder')
                ->leftjoin('companies','companies.Id','=','salesorder.companyId')
                ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
                ->leftJoin('tracker','tracker.Id','=','salesorder.trackerId')
                ->leftJoin('radius','radius.Location_Name','LIKe',DB::raw(' concat("(",`tracker`.`Site Name`,")") '))
                ->where('salesorder.Id',$request->Id)
                ->select('companies.Id as companyId','client.Id as clientId','companies.Company_Name as company','client.Company_Name as client','radius.Id as radiusId',DB::raw('tracker.`Site Name` as SiteName'),'client.Person_In_Charge','client.Contact_No','salesorder.term','salesorder.po')
                ->first();

                return json_encode($details);
        }

        public function combinetrip(Request $request)
        {

            $date = date('d-M-Y', strtotime($request->deliveryDate));
            $getregion = DB::table('tracker')
            ->select('Region')
            ->whereRaw('Project_Code LIKE "'.$request->site.'" ')
            ->first();

            $getarea = DB::table('radius')
            ->where('Id',$request->siteId)
            ->select('Area')
            ->first();

            $getsimilarDO = DB::table('deliveryform')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
            ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin('radius','radius.Id','=','deliveryform.Location')
            ->leftJoin('tracker',function($join)
            {
                $join->on(DB::raw('CONCAT("(",tracker.Project_Code,")")'),'LIKE','radius.Location_Name');
            })
            ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
            ->where('deliveryform.delivery_date',$date)
            ->where('tracker.Region',$getregion ? $getregion->Region : "")
            // ->where('deliveryform.roadtaxId',$request->vehicle)
            ->where('deliverystatuses.delivery_status',"Processing")
            ->select('roadtax.Id','roadtax.Vehicle_No','radius.Location_Name')
            ->first();

            if($getsimilarDO)
            {
                return json_encode($getsimilarDO);
            }
            else
            {
                return -1;
            }
        }

        public function googleapi($lat,$long,$lat2,$long2)
        {
            $origin = $lat.",".$long;
            $destination = $lat2.",".$long2;
            $api = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".$origin."&destinations=".$destination."&key=AIzaSyDuDdIs9T09hGo4LV3dRSiuHVMbUnkS-JE");
            $data = json_decode($api,true);
            //Distance
        //     $distance = $data['rows'][0]['elements'][0]['distance']['value'];
        //     $km = $distance / 1000;
        //     return $km;
              $distance = isset($data['rows'][0]['elements'][0]['distance']['value']) ? $data['rows'][0]['elements'][0]['distance']['value'] : 0;
              $km = round($distance / 1000);
              return $km;
        }

        public function test($lat1,$long1,$lat2,$long2)
        {
            $distance = $this->googleapi($lat1,$long1,$lat2,$long2);
            return $distance;
        }

        public function checkdriverresponse()
        {
            $deliverydetail = DB::table('deliveryform')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
            ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
            ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
            ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
            ->leftJoin('radius','radius.Id','=','deliveryform.Location')
            ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
            ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
            ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
            ->select('deliveryform.DO_No','roadtax.Vehicle_No','radius.Location_Name as Location','projects.Project_Name','deliverystatuses.created_at','requestor.Company_Email','requestor.Player_Id')
            ->whereRaw('deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND TIMESTAMPDIFF(HOUR,deliverystatuses.created_at, NOW()) >= 1 ')
            ->get();


            foreach($deliverydetail as $key => $value)
            {
                $emails=array();
                $notifyplayerid = array();
                $subscribers = DB::table('notificationtype')
                ->leftJoin('notificationsubscriber', 'notificationtype.Id', '=', 'notificationsubscriber.NotificationTypeId')
                ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                ->where('notificationtype.Id','=',87)
                ->get();

                foreach ($subscribers as $subscriber)
                {
                        $NotificationSubject = $subscriber->Notification_Subject;

                        if ($subscriber->Company_Email!="")
                        {
                                array_push($emails,$subscriber->Company_Email);
                        }
                        else
                        {
                                array_push($emails,$subscriber->Personal_Email);
                        }
                        array_push($notifyplayerid, $subscriber->Player_Id);
                }
                array_push($notifyplayerid,$value->Player_Id);
                array_push($emails,$value->Company_Email);

                $NotificationSubject == "" ? $NotificationSubject = "Driver Failed to Accept Delivery"  : "Driver Failed to Accept Delivery";
                $this->sendpushnotifcation($notifyplayerid, $NotificationSubject);

                //Stop sending emial upon request
                // Mail::send('emails.driveracceptfail', ['deliverydetail' => $value], function($message) use ($emails,$NotificationSubject)
                // {
                //         array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                //         $emails = array_filter($emails);
                //         $message->to($emails)->subject($NotificationSubject);
                // });

            }

            return 1;
        }
}
