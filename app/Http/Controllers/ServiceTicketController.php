<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use Dompdf\Dompdf;

class ServiceTicketController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */

	public function serviceticket()
	{
		$me = (new CommonController)->get_current_user();

		$service = DB::table('serviceticket')
		->select('serviceticket.Id','serviceticket.service_id','serviceticket.service_type','serviceticket.service_summary','serviceticket.client',
		'serviceticket.branch','inventories.Item_Code','serviceticket.service_date','tracker.Team','serviceticket.status')
		->join('deliveryform','deliveryform.Id','=','serviceticket.DeliveryId')
		->join('salesorder','salesorder.Id','=','deliveryform.salesorderid')
		->join('tracker','tracker.Id','=','salesorder.trackerid')
		->join('deliveryitem','deliveryitem.Id','=','serviceticket.speedfreak_no')
		->join('inventories','inventories.Id','=','deliveryitem.inventoryId')
		->get();
		$type = DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Service Type')
		->get();
		
		return view('serviceticket',['me'=>$me,'service'=>$service,'type'=>$type]);
	}

	public function serviceticketdetails($id)
	{
		$me = (new CommonController)->get_current_user();

		$ser = DB::table('serviceticket')
		->select('serviceticket.Id','serviceticket.service_date','serviceticket.service_id','client.Company_Name','serviceticket.speedfreak_no','client.Contact_No as clientContact','client.Person_In_Charge as clientPic',
		'client.Email as clientEmail','radius.Longitude','radius.Latitude','tech.Name','tech.Contact_No_1','radius.Location_Name','serviceticket.service_summary as remarks')
		->leftjoin('companies as client','client.Id','=','serviceticket.client')
		->leftjoin('radius','radius.Id','=','serviceticket.site')
		->leftjoin('users as tech','tech.Id','=','serviceticket.technicianId')
		->where('serviceticket.Id',$id)
		->first();

		$ser_appoint=DB::table('speedfreakservice')
		->select('last_service','upcoming_service','Status')
		->where('ServiceId',$id)
		->get();

		$replacement=DB::Table('speedfreakserviceimg')
		->select('before.Web_Path as beforeImg','after.Web_Path as afterImg','speedfreakserviceimg.newQty','speedfreakserviceimg.previousQty','item.name','item.barcode','item.type','item.model','item1.name as name2','item1.barcode as barcode2','item1.type as type2','item1.model as model2','speedfreakservice.Remarks')
		->leftjoin('files as before','before.Id','=','speedfreakserviceimg.previousFile')
		->leftjoin('files as after','after.Id','=','speedfreakserviceimg.newFile')
		->leftjoin('speedfreakinventory as item','item.Id','=','speedfreakserviceimg.previousMachinery')
		->leftjoin('speedfreakinventory as item1','item1.Id','=','speedfreakserviceimg.newMachinery')
		->leftjoin('serviceticket','serviceticket.Id','=','speedfreakserviceimg.ServiceId')
		->leftjoin('speedfreakservice','speedfreakservice.ServiceId','=','speedfreakserviceimg.ServiceId')
		->where('speedfreakserviceimg.ServiceId',$id)
		->get();
		
		$att = DB::table('speedfreakservice')
		->leftjoin('serviceticket','serviceticket.Id','=','speedfreakservice.ServiceId')
		->leftjoin('timesheets','timesheets.speedfreakServiceId','=','speedfreakservice.Id')
		->leftjoin('users','users.Id','=','serviceticket.technicianId')
		->select('users.Name','timesheets.Time_In','timesheets.Time_Out','timesheets.Time_Diff')
		->where('speedfreakservice.ServiceId','=',$id)
		->get();

		$att2 = DB::table('speedfreakservice')
		->leftjoin('serviceticket','serviceticket.Id','=','speedfreakservice.ServiceId')
		->leftjoin('timesheets','timesheets.speedfreakServiceId','=','speedfreakservice.Id')
		->leftjoin('users','users.Id','=','serviceticket.technicianId')
		->select('users.Name','speedfreakservice.Status','speedfreakservice.timeIn as timeIn','timesheets.Time_In','timesheets.Time_Out','timesheets.Time_Diff','speedfreakservice.totalTime','speedfreakservice.Remarks')
		->where('speedfreakservice.ServiceId','=',$id)
		->first();

		$pending = DB::table('serviceticket')
		->leftjoin('speedfreakservice','speedfreakservice.ServiceId','=','serviceticket.Id')
		->leftjoin('requisition','requisition.speedfreakServiceId','=','speedfreakservice.Id')
		->leftjoin('requisitionhistory','requisitionhistory.requisition_Id','=','requisition.Id')
		->leftjoin('requisitionitem','requisitionitem.Id','=','requisitionhistory.reqItemId')
		->leftjoin('speedfreakinventory','speedfreakinventory.Id','=','requisitionitem.InvId')
		->select('requisition.Id','speedfreakinventory.name','speedfreakinventory.barcode','requisitionhistory.Qty','requisitionhistory.status','requisitionhistory.status_details')
		->where('requisitionhistory.status','LIKE','%Sparepart%')
		->where('serviceticket.Id',$id)
		->get();
		
		return view('serviceticketdetails', ['me'=>$me,'ser'=>$ser,'ser_appoint'=>$ser_appoint,'replacement'=>$replacement,'att'=>$att,'att2'=>$att2, 'pending'=>$pending]);
	}

	public function SVTGetSite($speedfreak){
        $check = DB::table('inventories')
        ->select('Id')
        ->where('Item_Code','=',$speedfreak)
        ->first();

        $do = DB::table('deliveryform')
        ->leftjoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
        ->leftjoin('radius','radius.Id','=','deliveryform.Location')
        ->leftjoin('companies','companies.Id','=','deliveryform.client')
        ->where('deliveryitem.inventoryId',$check->Id)
        ->select('deliveryform.Location','radius.Location_Name','deliveryform.client','companies.Company_Name')
        ->orderby('deliveryform.Id','DESC')
        ->first();

        return response()->json(['do' => $do]);
	}

	public function servicemanagement($start = null , $end = null , $status = null, $type = null, $asset = null)
	{
		$me = (new CommonController)->get_current_user();

		if($start == null)
		{
			$start = date('d-M-Y',strtotime("today - 1month"));
		}

		if($end == null)
		{
			$end = date('d-M-Y',strtotime("today"));
		}

		// $list = DB::table('serviceticket')
		// ->leftjoin('users','users.Id','=','serviceticket.technicianId')
		// ->leftjoin('companies','companies.Id','=','serviceticket.client')
        // ->leftjoin('radius','radius.Id','=','serviceticket.site')
        // ->leftjoin('speedfreakservice','speedfreakservice.ServiceId','=','serviceticket.Id')
        // ->select('serviceticket.Id','serviceticket.service_id','serviceticket.speedfreak_no','service_type','serviceticket.sequence','serviceticket.parent','speedfreakservice.Status','users.Name','companies.Company_Name','radius.Location_Name','companies.type','companies.Address')
		// ->get();
		$list=$this->getServiceTicket($start,$end);

		if($asset)
		{
			$list = $list->where('speedfreakinventory.type',$asset);
		}

		if($type && $type != "All")
		{
			$list = $list->where('service_type',$type);
		}

		if($status && $status != "All")
		{
			if($status != "Overdue")
			{
				$list = $list->where('speedfreakservice.Status',$status)->get();
			}
			else
			{
				$list = $list->whereRaw('DATE_ADD(STR_TO_DATE(serviceticket.service_date,"%d-%M-%Y"),interval 3 day) < STR_TO_DATE(NOW(),"%Y-%m-%d")')->get();
			}
		}
		else
		{
			$list = $list->get();
		}


		$sc = DB::table('speedfreakinventory')
		->select('machinery_no')
		->where('type','SPEEDFREAK')
		->get();

		$client = DB::table('companies')
		->select('Id','Company_Name')
		->where('Client','=','Yes')
		->get();

		$site = DB::table('radius')
		->select('Id','Location_Name')
		->get();

		$pic = DB::table('users')
		->select('Id','Name')
		->get();

		$type = DB::table('options')
		->select('Option')
		->where('Field','Ticket Type')
		->where('Table','SpeedFreak')
		->get();

		$allstatus = DB::table('speedfreakservice')
		->select('Status')
		->groupby('Status')
		->get();

		$tickettype = DB::table('ticket')
		->select('Id','Name')
		->get();

		$assettype = ['GENSET','ATS','TANK','VEHICLE'];

		return view('servicemanagement', ['me'=>$me,'list'=>$list,'client'=>$client,'site'=>$site,'pic'=>$pic,'sc'=>$sc,'start'=>$start,'end'=>$end,'type'=>$type,'status'=>$status,'allstatus'=>$allstatus, 'assettype'=>$assettype,'tickettype'=>$tickettype, 'asset'=>$asset]);
	}

	public function ticketassettype($type)
	{
		$item = DB::table('speedfreakinventory')
		->select('machinery_no')
		->where('type',$type)
		->get();

		return response()->json(['item'=>$item]);
	}

	public function svtreport($start = null , $end = null)
	{
		$me = (new CommonController)->get_current_user();

		if(!$start)
		{
			$start = date('d-M-Y',strtotime('first day of last month'));
		}

		if(!$end)
		{
			$end = date('d-M-Y',strtotime('last day of this month'));
		}

		$replacement=DB::Table('speedfreakserviceimg')
		->select('serviceticket.Id','serviceticket.service_id','serviceticket.service_date','serviceticket.service_type','serviceticket.speedfreak_no','companies.Company_Name','companies.type','speedfreakinventory.name','speedfreakinventory.barcode','speedfreakserviceimg.NewQty',DB::raw('IFNULL(inventorypricehistory.price,0)'),DB::raw('(speedfreakserviceimg.NewQty * IFNULL(inventorypricehistory.price,0)) as cost'),'users.Name as technician')
		->leftjoin('speedfreakinventory','speedfreakinventory.Id','=','speedfreakserviceimg.newMachinery')
		->leftjoin('serviceticket','serviceticket.Id','=','speedfreakserviceimg.ServiceId')
		// ->leftjoin('speedfreakservice','speedfreakservice.ServiceId','=','speedfreakserviceimg.ServiceId')
		->leftjoin('users','users.Id','=','serviceticket.technicianId')
		->leftjoin('companies','companies.Id','=','serviceticket.client')
		->leftjoin(DB::raw('(SELECT MAX(Id) as maxid, inventoryId from inventorypricehistory GROUP BY inventoryId) as max'),'max.inventoryId','=','speedfreakinventory.Id')
		->leftjoin('inventorypricehistory','inventorypricehistory.Id','=','max.maxid')
		->leftjoin(DB::raw('(SELECT Max(Id) as maxserviceid,ServiceId from speedfreakservice group by ServiceId) as maxservice'),'maxservice.ServiceId','=','serviceticket.Id')
        ->leftjoin('speedfreakservice','speedfreakservice.Id','=','maxservice.maxserviceid')
        ->whereRaw('speedfreakserviceimg.newMachinery != "" AND (STR_TO_DATE(serviceticket.service_date,"%d-%M-%Y") BETWEEN STR_TO_DATE("'.$start.'","%d-%M-%Y") AND STR_TO_DATE("'.$end.'","%d-%M-%Y") )')
		->get();

		return view('svtreport',['me'=>$me,'replacement'=>$replacement,'start'=>$start,'end'=>$end]);
	}

	public function replacementhistory($start = null , $end = null, $priceId = null)
	{
		$me = (new CommonController)->get_current_user();

		if(!$start || $start == "null" )
		{
			$start = date('d-M-Y',strtotime('first day of last month'));
		}

		if(!$end || $end == "null" )
		{
			$end = date('d-M-Y',strtotime('last day of this month'));
		}

		$cond = '(STR_TO_DATE(serviceticket.service_date,"%d-%M-%Y") BETWEEN STR_TO_DATE("'.$start.'","%d-%M-%Y") AND STR_TO_DATE("'.$end.'","%d-%M-%Y") )';
		if($priceId)
		{
			$cond = "inventoryreplacement.pricehistoryId = ".$priceId;
		}

		$replacement = DB::table('inventoryreplacement')
		->leftjoin('inventorypricehistory','inventorypricehistory.Id','=','inventoryreplacement.pricehistoryId')
		->leftjoin('speedfreakinventory','speedfreakinventory.Id','=','inventorypricehistory.inventoryId')
		->leftjoin('serviceticket','serviceticket.Id','=','inventoryreplacement.ServiceId')
		->leftjoin('users','users.Id','=','serviceticket.technicianId')
		->leftjoin('companies','companies.Id','=','serviceticket.client')
		->select('serviceticket.Id','serviceticket.service_id','serviceticket.service_date','serviceticket.service_type','serviceticket.speedfreak_no','companies.Company_Name','companies.type','speedfreakinventory.name','speedfreakinventory.barcode','inventoryreplacement.qty','inventorypricehistory.price',DB::raw(' FORMAT(inventorypricehistory.price * inventoryreplacement.qty,2) as cost'),'users.Name as technician')
		->get();
		

		return view('replacementhistory',['me'=>$me,'replacement'=>$replacement,'start'=>$start,'end'=>$end]);
	}

	public function getDetails($id)
	{
		$me = (new CommonController)->get_current_user();
		$details = DB::table('serviceticket')
		->leftjoin(DB::raw('(SELECT Max(Id) as maxid,ServiceId from speedfreakservice group by ServiceId) as max'),'max.ServiceId','=','serviceticket.Id')
        ->leftjoin('speedfreakservice','speedfreakservice.Id','=','max.maxid')
		// ->leftjoin('timesheets','timesheets.speedfreakServiceId','=','speedfreakservice.Id')
		// ->leftjoin('users','users.Id','=','serviceticket.technicianId')
		->leftjoin('companies','companies.Id','=','serviceticket.client')
		->leftjoin('radius','radius.Id','=','serviceticket.site')
		->select('serviceticket.Id','companies.Company_Name','radius.Location_Name','serviceticket.technicianId','serviceticket.service_type','serviceticket.service_date','serviceticket.service_summary','serviceticket.speedfreak_no','speedfreakservice.Status')
		->where('serviceticket.Id','=',$id)
		->first();

		return response()->json(['details' => $details]);
	}
	public function update(Request $request)
	{
		$me = (new CommonController)->get_current_user();
		$input = $request->all();

		DB::table('actionhistory')
		->insert([
			'Type' => "SVT",
			'action' => "Update",
			'ActionId' => $input['serviceid'],
			'UserId' => $me->UserId,
			'created_at' => Carbon::now(),
			'datajson' => json_encode($request->all())
		]);

		DB::table('serviceticket')
		->where('Id','=',$input['serviceid'])
		->update([
			'service_date' => $input['date_update'],
			'technicianId' => $input['tech_update'],
			'service_summary' => $input['remarks_update']
		]);

		DB::table('speedfreakservice')
		->where('ServiceId',$input['serviceid'])
		->update([
			'Status' => $input['status'],
			'Remarks' => "Updated by Admin [Pending to In-Progress]"
		]);

		return 1;
	}

	public function delete(Request $request)
	{
		$me = (new CommonController)->get_current_user();
		$input = $request->all();
		DB::table('serviceticket')
		->where('Id','=',$input['Id'])
		->delete();

		return 1;
	}
	public function getServiceTicket($start,$end){
		$data=DB::Table('serviceticket')
		->select('serviceticket.Id','serviceticket.service_id','serviceticket.speedfreak_no','service_type','serviceticket.service_date','serviceticket.sequence','serviceticket.parent','speedfreakservice.Status','users.Name','companies.Company_Name','radius.Location_Name','companies.type') 
		// ->leftjoin('salesorder','salesorder.Id','=','deliveryform.salesorderid')
        // ->leftjoin('tracker','salesorder.trackerid','=','tracker.Id')
        ->leftjoin(DB::raw('(SELECT Max(Id) as maxid,ServiceId from speedfreakservice group by ServiceId) as max'),'max.ServiceId','=','serviceticket.Id')
        ->leftjoin('speedfreakservice','speedfreakservice.Id','=','max.maxid')
        // ->leftjoin('requisition','requisition.speedfreakServiceId','=','speedfreakservice.Id')
        // ->leftjoin(DB::raw('(SELECT Max(Id) as maxid,requisition_Id from requisitionhistory group by requisition_Id) as max1'),'max1.requisition_Id','=','requisition.Id')
        // ->leftjoin('requisitionhistory','requisitionhistory.Id','=','max1.maxid')
		->leftjoin('speedfreakinventory','speedfreakinventory.machinery_no','=','serviceticket.speedfreak_no')
		->leftjoin('users','users.Id','=','serviceticket.technicianId')
		->leftjoin('companies','companies.Id','=','serviceticket.client')
        ->leftjoin('radius','radius.Id','=','serviceticket.site')
        ->whereRAW("STR_TO_DATE(serviceticket.service_date,'%d-%M-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%M-%Y') AND  STR_TO_DATE('".$end."','%d-%M-%Y')")
		->orderBy('Id','desc');
		return $data;
	}

	public function storekeeper($start = null ,$end = null){
		$me = (new CommonController)->get_current_user();

		if($start == null)
		{
			$start = date('d-M-Y',strtotime("today - 1month"));
		}

		if($end == null)
		{
			$end = date('d-M-Y',strtotime("today"));
		}

		$list = DB::table('requisition')
		->leftjoin(DB::raw('(SELECT MAX(Id) as maxid, requisition_Id FROM requisitionhistory GROUP BY requisition_Id) as max'),'requisition.Id','=',DB::raw('max.requisition_Id'))
		->leftjoin('requisitionhistory','requisitionhistory.Id','=',DB::raw('max.maxid'))
		->leftjoin('speedfreakservice','speedfreakservice.Id','=','speedfreakServiceId')
		->leftjoin('serviceticket','serviceticket.Id','=','speedfreakservice.ServiceId')
		->leftjoin('users','users.Id','=','requisition.created_by')
		->select('requisition.Id','requisition.Req_No','requisitionhistory.status','requisitionhistory.status_details','serviceticket.service_id','users.Name','requisition.created_at')
		->whereRaw("STR_TO_DATE(requisition.created_at,'%Y-%m-%d') BETWEEN STR_TO_DATE('".$start."','%d-%M-%Y') AND STR_TO_DATE('".$end."','%d-%M-%Y')")
		->get();

		return view('storekeeper', ['me'=>$me,'list'=>$list,'start'=>$start,'end'=>$end]);
	}

	public function storeGetItem($id)
	{
		$item = DB::table('requisitionitem')
		->leftjoin('speedfreakinventory','speedfreakinventory.Id','=','requisitionitem.InvId')
		->leftjoin('requisition','requisition.Id','=','requisitionitem.reqId')
		->select('requisition.Req_No','speedfreakinventory.machinery_no','speedfreakinventory.name','requisitionitem.Qty')
		->where('requisitionitem.reqId','=',$id)
		->get();

		return response()->json(['item'=>$item]);
	}

	public function create(Request $request)
	{
		$me = (new CommonController)->get_current_user();
		$input = $request->all();
		if(!$input['speedfreak_no'])
		{
			return "Please Select Geneset";
		}

		$svt = DB::table('serviceticket')
		->select('service_id')
		->where('service_id','LIKE','SVT-%')
		->orderby('Id','DESC')
		->first();

		if(!$svt)
		{
			$number = 0;
		}
		else
		{
			$number = substr($svt->service_id, 4,8);
		}

		$id2 = 0;
		foreach ($input['service'] as $key => $value) {
			if($svt == null)
			{
			$number = "00000";
			$child = sprintf("%05s",$number+$key+1);
			$parent = sprintf("%05s",$number+1);
			}
			else
			{
			$number = substr($svt->service_id, 4,8);
			$child = sprintf("%05s",$number+$key+1);
			$parent = sprintf("%05s",$number+1);
			}
			if($input['service'][$key] != "")
			{
				$id = DB::table('serviceticket')
				->insertGetId([
				 'service_type' => $input['service'][$key],
				 'speedfreak_no' => $input['speedfreak_no'],
				 'technicianId' => $input['pic'][$key],
				 'client' => isset($input['client']) ? $input['client']: "",
				 'site' => isset($input['site']) ? $input['site'] : "",
				 'service_summary' => $input['remarks'][$key],
				 'service_date' => $input['date'][$key],
				 'prev_serviceId' => $id2,
				 'service_id' => "SVT-".$child,
				 'parent' => "SVT-".$parent,
				 'sequence' => $key+1,
				 'UserId'=>$me->UserId
				]);

				 DB::table('speedfreakservice')
				->insertGetId([
				 'ServiceId' => $id,
				 'Status' => "In-Progress"
				]);

				DB::table('servicelog')
				->insert([
					 'serviceticket_id' => $id,
					 'leader_id' => $input['pic'][$key],
					 'created_at' => DB::raw('NOW()')
				]);

				if($key == 0)
				{

					$playerid	= DB::table('users')->select('Player_Id')->where('Id','=',$input['pic'][$key])->first();
					$playerids = array();
					array_push($playerids, $playerid->Player_Id);
					$message 	= 'New Service Ticket Assigned';
					$type 		= 'Service Ticket';
					$title  	= 'New Ticket Assigned';
					$this->sendNotification($playerids, $title, $message, $type);
				}
			}

			$id2 = $id;
		}
		return 1;
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
	        'app_id' 				=> "b22a7a60-2cfa-4641-a309-4720c564fddf",
	        'include_player_ids' 	=> $playerids,
	        'data'					=> array("type" => $type),
	        'contents' 				=> $content,
	        'headings'              => $heading
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

	function dashboard($start = null, $end = null, $status = null)
	{
		$me = (new CommonController)->get_current_user();

		if($start == null)
		{
			$start = date('d-M-Y',strtotime('first day of last month'));
		}

		if($end == null)
		{
			$end = date('d-M-Y',strtotime('last day of this month'));
		}

		if($status == null)
		{
			$status = "All";
		}

		$cond = 'STR_TO_DATE(service_date, "%d-%M-%Y") BETWEEN STR_TO_DATE("'.$start.'","%d-%M-%Y") AND STR_TO_DATE("'.$end.'","%d-%M-%Y")';

		if($status != null && $status != "All")
		{
			$cond = $cond." AND speedfreakservice.Status = '".$status."' ";
		}

		$allstatus = DB::table('speedfreakservice')
		->select('Status')
		->groupby('Status')
		->get();

		$count = DB::Table('serviceticket')
		->leftjoin(DB::raw('(SELECT Max(Id) as maxid,ServiceId from speedfreakservice group by ServiceId) as max'),'max.ServiceId','=','serviceticket.Id')
        ->leftjoin('speedfreakservice','speedfreakservice.Id','=','max.maxid')
		->select(
			'serviceticket.service_type',DB::raw(' (SELECT COUNT(serviceticket.Id)) as count')
		)
		->groupby('serviceticket.service_type')
		->whereRaw($cond)
		->get();

		return view('svtdashboard',['me'=>$me, 'start'=>$start, 'end'=>$end, 'status'=>$status, 'allstatus'=>$allstatus, 'count'=>$count]);
	}

	public function ticketflow()
	{
		$me = (new CommonController)->get_current_user();

		$list = DB::table('ticket')
		->leftjoin('users','users.Id','=','ticket.created_by')
		->select('ticket.Id','ticket.Name','users.Name as users','ticket.created_at')
		->get();

		$type = DB::table('options')
		->select('Option')
		->where('Field','Ticket Type')
		->where('Table','SpeedFreak')
		->get();

		return view('ticketflow',['me'=>$me, 'list'=>$list, 'type'=>$type]);
	}

	public function createticketflow(Request $request)
	{
		$me = (new CommonController)->get_current_user();

		if(!$request->service || !$request->type)
		{
			return 0;
		}

		$id = DB::table('ticket')
		->insertGetId([
			"Name" => $request->type,
			'created_at' => DB::raw('NOW()'),
			'created_by' => $me->UserId
		]);

		foreach($request->service as $key => $value)
		{
			DB::table('ticketflow')
			->insert([
				'ticketId' => $id,
				'service' => $value
			]);

		}

		return 1;
	}

	public function getflowdetails($id)
	{
		$item = DB::table('ticketflow')
		->select('ticketflow.Id','ticketflow.service')
		->where('ticketId',$id)
		->orderby('Id','ASC')
		->get();

		$ticket = DB::table('ticket')
		->where('Id',$id)
		->select('Name')
		->first();

		return response()->json(['item'=>$item, 'ticket'=>$ticket]);
	}

	public function deleteticketflow($id)
	{
		$me = (new CommonController)->get_current_user();

		$data = DB::table('ticketflow')
		->where('Id',$id)
		->first();

		DB::table('ticketflow')
		->where('Id',$id)
		->delete();

		DB::table('actionhistory')
		->insert([
			'Type' => "TicketFlow",
			'action' => "Delete Flow",
			'ActionId' => $data->ticketId,
			'UserId' => $me->UserId,
			'created_at' => Carbon::now(),
			'datajson' => json_encode($data)
		]);
	}

	public function deleteticket($id)
	{
		$me = (new CommonController)->get_current_user();

		$data = DB::table('ticket')
		->where('Id',$id)
		->first();

		DB::table('ticket')
		->where('Id',$id)
		->delete();

		DB::table('actionhistory')
		->insert([
			'Type' => "TicketFlow",
			'action' => "Delete",
			'ActionId' => $id,
			'UserId' => $me->UserId,
			'created_at' => Carbon::now(),
			'datajson' => json_encode($data)
		]);

		return 1;
	}

	public function updateticketflow(Request $request)
	{
		$me = (new CommonController)->get_current_user();
		
		foreach($request->flowid as $key => $value)
		{
			if($value)
			{
				DB::table('ticketflow')
				->where('Id',$request->ticketid)
				->update([
					'service' => $request->service[$key]
				]);
			}
			else
			{
				DB::table('ticketflow')
				->insert([
					'ticketId' => $request->ticketid,
					'service' => $request->service[$key]
				]);
			}
		}
		return 1;
	}
	
}