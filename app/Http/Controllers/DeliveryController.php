<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\CommonController;
use DateTime;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Validator;
use PDF;
use File;
// use Request;
class DeliveryController extends Controller

{

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function mydeliveryrequest($trackerid = null, $terminate=null)
	{
        $me=(new CommonController)->get_current_user();
        $timenow = date('d-M-Y');
        $soitem = null;
        $pt = null;
        $details = null;
        $lorry = DB::table('roadtax')
        ->select('roadtax.Id','roadtax.Lorry_Size','roadtax.Vehicle_No','roadtax.Lorry_Dimension','roadtax.Availability')
        ->where('roadtax.Lorry_Size','<>',"")
        ->get();

        $truck = DB::table('roadtax')
        ->select('roadtax.Id','roadtax.Lorry_Size','roadtax.Vehicle_No','roadtax.Lorry_Dimension','roadtax.Availability')
        ->where('roadtax.Type','=',"TRUCK")
        ->get();

		$deliveryitems = DB::table('inventories')
		// ->leftJoin('deliveryitem','inventories.Id','=','deliveryitem.inventoryId')
		->select('inventories.Id','inventories.Item_Code','inventories.Description','inventories.Unit')
		->get();

		$destination= DB::table('radius')
		->select('radius.Id','radius.Location_Name')
		->get();

        $client = DB::table('companies')
        ->select('companies.Id','companies.Company_Name','companies.Company_Code')
        ->where('companies.Client','=','Yes')
        ->get();

		// $mydelivery = DB::table('deliveryform')
  //       ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
  //       ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
  //       ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
  //       ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
  //       ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
  //       ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
  //       ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
  //       // ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
  //       // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
  //       ->leftJoin('options','options.Id','=','deliveryform.Purpose')
  //       // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
  //       // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
  //       ->select('deliveryform.Id','deliverystatuses.Id as StatusId','requestor.Name as Requestor','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','deliveryform.Location','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at')
  //       ->where('deliveryform.RequestorId', '=', $me->UserId)
  //       ->orderBy('deliveryform.created_at','desc')
  //       ->get();

        $processingdelivery = DB::table('deliveryform')
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
        // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
        ->select('deliveryform.Id','deliverystatuses.Id as StatusId','requestor.Name as Requestor','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','deliveryform.Location','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at')
        ->where('deliveryform.RequestorId', '=', $me->UserId)
        ->orderBy('deliveryform.DO_No','asc')
        ->get();

        $recalldelivery = DB::table('deliveryform')
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
        // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
        ->select('deliveryform.Id','deliverystatuses.Id as StatusId','requestor.Name as Requestor','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','deliveryform.Location','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliverystatuses.created_at')
        ->where('deliveryform.RequestorId', '=', $me->UserId)
        ->orderBy('deliveryform.DO_No','asc')
        ->get();

        $completedelivery = DB::table('deliveryform')
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
        ->select('deliveryform.Id','deliverystatuses.Id as StatusId','requestor.Name as Requestor','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','deliveryform.delivery_time','radius.Location_Name','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at')
        ->where('deliveryform.RequestorId', '=', $me->UserId)
        ->orderBy('deliveryform.DO_No','asc')
        ->get();
        $incompletedelivery = DB::table('deliveryform')
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
        // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
        ->select('deliveryform.Id','deliverystatuses.Id as StatusId','requestor.Name as Requestor','deliveryform.DO_No','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.Location','projects.Project_Name','options.Option','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at','deliverystatuses.remarks')
        ->where('deliveryform.RequestorId', '=', $me->UserId)
        ->orderBy('deliveryform.DO_No','asc')
        ->get();

        $files = DB::table('files')->select('Id', 'TargetId', 'Web_Path')->where('Type', 'Delivery')->get();
        $filesByGroup = collect($files)->groupBy('TargetId')->toArray();

        $rejectdelivery = DB::table('deliveryform')
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
        // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
        ->select('deliveryform.Id','deliverystatuses.Id as StatusId','requestor.Name as Requestor','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.Location','projects.Project_Name','options.Option','deliveryform.Remarks','deliverystatuses.remarks','deliveryform.created_at','deliverystatuses.updated_at')
        ->where('deliveryform.RequestorId', '=', $me->UserId)
        ->orderBy('deliveryform.Id','desc')
        ->get();

        $canceldelivery = DB::table('deliveryform')
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
        ->select('deliveryform.Id','deliverystatuses.Id as StatusId','deliveryform.DO_No','deliveryform.delivery_date','deliveryform.delivery_time','radius.Location_Name','projects.Project_Name','options.Option','deliveryform.Remarks','deliverystatuses.remarks','deliverystatuses.created_at','deliverystatuses.updated_at','deliveryform.approve')
        ->where('deliveryform.RequestorId', '=', $me->UserId)
        ->orderBy('deliveryform.Id','desc')
        ->get();

        $de = "Department";
        // $project = DB::table('projects')
        // ->select('projects.Id','Project_Name','projects.Type')
        // ->where('projects.Customer','Not LIKE','%'.$de.'%')
        // ->orWhere('projects.Project_Name', 'LIKE', '%MY_DEPARTMENT_GST%')
        // ->get();
        $project = DB::table('projects')
        ->select('Id','Project_Name')
        ->whereIn('Id',array(31,51,131,142,143,144))
        ->get();

		$itemlist = DB::table('deliveryitem')
        // ->leftJoin('options','deliveryitem.Purpose','=','options.Id')
        ->leftJoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
		->select('deliveryitem.Id','inventories.Item_Code','inventories.Description','deliveryitem.add_desc','inventories.Unit','deliveryitem.Qty_request','deliveryitem.available')
		->where('deliveryitem.formId','=','deliveryform.Id')
		->get();

        $updateitem = DB::table('deliveryitem')
        ->leftJoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
        ->select('deliveryitem.status','deliveryitem.Id','inventories.Item_Code','inventories.Description','deliveryitem.add_desc','inventories.Unit','deliveryitem.Qty_request','deliveryitem.Qty_send','deliveryitem.Qty_received','deliveryitem.remarks')
        ->where('deliveryitem.formId','=','deliveryform.Id')
        ->get();

		$purpose = DB::table('options')
		->select('options.Id',"options.Option")
		->where('options.Field','=','Purpose')
        ->orderBy('options.Option','asc')
		->get();

        $projtype= DB::table('options')
        ->select('options.Option')
        ->where('Field',['Type'])
        ->whereIn('Table',["projects"])
        ->orderBy('options.Option','asc')
        ->get();

        $holidays = DB::table('holidays')
            ->select('holidays.Id','holidays.Holiday','holidays.Start_Date','holidays.End_Date','holidays.State','holidays.Country')
            ->whereRaw('right(Start_Date,4)='.date('Y'))
            ->orderBy('holidays.Start_Date','asc')
            ->get();

             $showdelivery = DB::table('deliveryform')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
            ->leftJoin('radius','radius.Id','=','deliveryform.Location')
            ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin('users as applicant', 'deliveryform.RequestorId', '=', 'applicant.Id')
            ->leftJoin('users as driver', 'deliveryform.DriverId', '=', 'driver.Id')
            ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
            ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
            ->leftJoin('options','options.Id','=','deliveryform.Purpose')
            ->select('roadtax.Vehicle_No as Lorry','driver.Name as Driver','applicant.Name as Requestor','deliveryform.delivery_date as Delivery_Date','deliverystatuses.delivery_status as Status','projects.Project_Name','radius.Location_Name','options.Option','deliveryform.created_at','radius.Id','radius.longitude','radius.latitude')
            ->where('deliverystatuses.delivery_status', '<>','Recalled')
            // ->where('deliveryform.RequestorId', '=',$me->UserId)
            ->get();

            $vehicleevent = DB::table('vehicleevent')
            ->leftJoin('roadtax','roadtax.Id','=','vehicleevent.VehicleId')
            ->select('roadtax.Vehicle_No','vehicleevent.Start_Date','vehicleevent.End_Date','vehicleevent.Event')
            ->get();

            $showdeliveryitem = DB::table('deliveryitem')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryitem.formId')
            ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
            ->select('inventories.Item_Code','inventories.Description','inventories.Unit','deliveryitem.Qty_request')
            ->get();

            $deliverynote = DB::table('options')
            ->select('Id','Option')
            ->where('Table',['deliveryform'])
            ->where('Field',['Delivery Note'])
            ->get();

            $condition = DB::table('options')
            ->select('Id','Option')
            ->where('Table',['deliveryform'])
            ->where('Field',['Delivery Condition'])
            ->get();

            $pic = DB::table('companies')
            ->select('Person_In_Charge','Contact_No')
            ->get();

            $requestor = DB::table('users')
            ->select('Id','Name')
            ->where('Active','=',1)
            ->get();

            $stockupdate = DB::table('deliveryform')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
            ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
            ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
            ->leftJoin('radius','radius.Id','=','deliveryform.Location')
            ->leftJoin('projects','projects.Id','=','deliveryform.ProjectId')
            ->leftJoin('companies','companies.Id','=','deliveryform.client')
            ->leftJoin('options','options.Id','=','deliveryform.Purpose')
            ->select('deliveryform.Id','deliverystatuses.Id','deliveryform.DO_No','deliveryform.created_at','deliveryform.delivery_date','deliveryform.delivery_time','radius.Location_Name','projects.Project_Name','options.Option','deliverystatuses.updated_at')
            // ->where('deliveryitem.stockin','=',1)
            ->get();

            $site = array();

            if($trackerid != null)
            {
                $details = DB::table('tracker')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,trackerid from salesorder Group By trackerid) as max'), 'max.trackerid', '=', 'tracker.Id')
                ->leftJoin('salesorder', 'salesorder.Id', '=', DB::raw('max.`maxid`'))
                ->leftjoin('companies','companies.Id','=','salesorder.companyId')
                ->leftjoin('companies as client','client.Id','=','salesorder.clientId')
                ->leftjoin('projects','projects.Id','=','salesorder.projectId')
                ->select('salesorder.Id','tracker.recurring','salesorder.do','salesorder.parentId','companies.Id as company_id','companies.Company_Name','client.Company_Name as client_company','client.Id as client_id','salesorder.projectId','projects.Project_Name','salesorder.po','tracker.Project_Code','salesorder.term')
                ->where('salesorder.trackerid','=',$trackerid)
                ->first();
                // dd($details);


                if($details != null || $details != "")
                {
                $site = DB::table('radius')
                ->select('Id','Location_Name')
                ->where('radius.ProjectId','=',$details->projectId)
                ->where('radius.Location_Name','LIKE','('.$details->Project_Code.')')
                ->first();

                $pt = DB::table('companies')
                ->where('companies.Id','=',$details->client_id)
                ->select('type')
                ->first();

                    if($terminate == "exchangedelivery")
                    {
                        // $soitem = DB::table('deliveryitem')
                        // ->leftJoin('deliveryform','deliveryform.Id','=','deliveryitem.formId')
                        // ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                        // ->select('inventories.Id','inventories.Item_Code','inventories.Description','inventories.Unit')
                        // ->where('inventories.Item_Code','LIKE',"SC%")
                        // ->groupby('inventories.Id')
                        // ->get();
                        $soitem = DB::table('inventories')
                        ->select('inventories.Id','inventories.Item_Code','inventories.Description','inventories.Unit')
                        ->where('inventories.Item_Code','LIKE',"SC%")
                        ->get();
                    }
                    else
                    {
                        $soitem = DB::table('deliveryitem')
                        ->leftJoin( DB::raw('(select Max(Id) as maxid,DO_No,salesorderid from deliveryform Group By salesorderid) as max'), 'max.maxid', '=', 'deliveryitem.formId')
                        // ->leftJoin('deliveryform','deliveryform.Id','=','deliveryitem.formId')
                        ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                        ->select('inventories.Id','inventories.Item_Code','inventories.Description','inventories.Unit')
                        // ->whereRaw('deliveryform.salesorderid = "'.$details->Id.'" OR deliveryform.salesorderid = "'.$details->parentId.'"')
                        ->where('max.salesorderid','=',$details->Id)
                        ->where('inventories.Item_Code','<>',"Tray")
                        ->groupby('inventories.Id')
                        ->get();
                    }
                }
            }
            // $mr=$this->MR($me->UserId);
            $company = DB::table('companies')
            ->select("Id",'Company_Name')
            ->get();

            return view('mydeliveryrequest', ['me' => $me,'lorry'=>$lorry,'deliveryitems'=>$deliveryitems,'destination'=>$destination,'processingdelivery'=>$processingdelivery,'completedelivery'=>$completedelivery,'incompletedelivery'=>$incompletedelivery,'recalldelivery'=>$recalldelivery,'rejectdelivery'=>$rejectdelivery,'canceldelivery'=>$canceldelivery,'project'=>$project,'itemlist'=>$itemlist,'purpose'=>$purpose,'projtype'=>$projtype,'updateitem'=>$updateitem,'client'=>$client, 'filesByGroup' => $filesByGroup,'holidays'=>$holidays,'showdelivery'=>$showdelivery,'showdeliveryitem'=>$showdeliveryitem,'deliverynote'=>$deliverynote,'condition'=>$condition,'pic'=>$pic,'requestor'=>$requestor,'stockupdate'=>$stockupdate,'vehicleevent'=>$vehicleevent,'details'=>$details,'timenow'=>$timenow,'terminate'=>$terminate,'truck'=>$truck,'soitem'=>$soitem,'site'=>$site, 'trackerid'=>$trackerid,'pt'=>$pt,'company'=>$company]);
	}

    public function salesorderterminate(Request $request, $salesorderid)
    {

        $me=(new CommonController)->get_current_user();
        $input = $request->all();
        $today= date('d-M-Y', strtotime("today"));

        $formid = DB::table('deliveryform')
        ->select('Id','roadtaxId')
        ->orderBy('deliveryform.Id','DESC')
        ->where('deliveryform.salesorderid','=',$salesorderid)
        ->first();

        if($formid->roadtaxId == 0)
        {
            DB::table('deliverystatuses')
            ->insert([
                'deliveryform_Id' => $formid->Id,
                'user_Id' => $me->UserId,
                'delivery_status' => "Completed",
                'delivery_status_details' => "",
                'created_at' => Carbon::now(),
                'remarks' => "Dummy"
            ]);
        }

        $invenId = DB::table('deliveryitem')
        ->select('inventoryId','formId')
        ->where('deliveryitem.formId','=',$formid->Id)
        ->get();

        $trackerid = DB::table('salesorder')
        ->select('Id','trackerid','rental_start','rental_end')
        ->where('Id','=',$salesorderid)
        ->first();

        $project = DB::table('tracker')
        ->leftJoin('projects','projects.Id','=','tracker.ProjectID')
        ->select('tracker.Project_Code','projects.Project_Name','tracker.sales_order')
        ->where('tracker.Id','=',$trackerid->trackerid)
        ->first();

         $item = DB::table('salesorderitem')
          ->select('salesorderitem.*')
          ->where('salesorderitem.salesorderId','=',$salesorderid)
          ->get();
        $days = date("t",strtotime($trackerid->rental_start));
        $diff = strtotime($input['offhire']) - strtotime($trackerid->rental_start);
        $diff = ($diff / (60*60*24) ) +1;
        if($project->sales_order > 1)
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
            ->where('salesorderId','=',$salesorderid)
            ->groupby('salesorderid')
            ->first();

            DB::table('salesorder')
            ->where('Id','=',$salesorderid)
            ->update([
                'total_amount' => $itemtotal->total
            ]);
        }

        $filenames="";
        $attachmentUrl = null;
        $type="Off_Hired_Support_Document";
        $path = "private/upload/Site Document/".$project->Project_Name."/".$project->Project_Code."/".$type;
        $uploadcount=count($input['terminatedoc']);
        if ($input['terminatedoc'] != null || $input['terminatedoc'] != "") {
            for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['terminatedoc'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $salesorderid,
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }
        }


        $forms = json_decode(json_encode($invenId),true);
        $formcount = count($forms);
        $soids = array();

        for($i=0; $i<$formcount;$i++)
        {
        DB::table('deliveryform')
        ->where('deliveryform.Id','=',$forms[$i]['formId'])
        ->update(['offhire_date'=>$input['offhire']]);

        $soid = DB::table('deliveryform')
        ->select('salesorderid')
        ->where('deliveryform.Id','=',$forms[$i]['formId'])
        ->first();
        array_push($soids, $soid->salesorderid);
        }
        DB::table('salesorder')
        ->whereIn('Id',$soids)
        ->update(['rental_end' => $input['offhire']]);

        $trackerids = DB::table('salesorder')
        ->select('trackerid')
        ->whereIn('Id',$soids)
        ->get();

        $trackid = json_decode(json_encode($trackerids),true);
        $counttrack = count($trackid);
        for($i=0;$i<$counttrack;$i++)
        {
        DB::table('tracker')
        ->where('Id','=',$trackid[$i]['trackerid'])
        ->update(['recurring'=> 0]);
        }
        for($i=0;$i<count($soids);$i++)
        {
        DB::table('salesorderdetails')
        ->insert([
            'salesorderId' => $salesorderid,
            'details' => "Terminate Auto Generating Sales Order",
            'userId' => $me->UserId,
            'created_at' => Carbon::now()
        ]);
        }

        return 1;
    }

	public function applydelivery(Request $request)
	{

		$me=(new CommonController)->get_current_user();

	    $input = $request->all();
        // dd($input);
	    $rules = array(
	    	'Date' => 'Required',
            'time' => 'Required',
            'pickup' => 'Required',
            'pickupdate' => 'Required',
	//    	'lorry' => 'Required',
            'section' => 'required|array'
	    	// 'project'=>'Required',
	    	// 'destination'=>'Required',
            // 'item' => 'array|required',
	    	// 'company'=>'Required',
	    	// 'client'=>'Required',
	    	// 'name'=>'Required',
	    	// 'contact'=>'Required',
	    	// 'Purpose'=>'Required',
	    	// 'item.*'=>'Required',
	    	// 'Quantity'=>'Required',
	    );
        if ($request->has('section')) {

            foreach($request->get('section') as $key => $val)
            {
                $rules['section.'.$key.'.project'] = 'required';
                $rules['section.'.$key.'.destination'] = 'required';
                // $rules['section.'.$key.'.projtype'] = 'required';
                $rules['section.'.$key.'.client'] = 'required';
                $rules['section.'.$key.'.PICname'] = 'required';
                $rules['section.'.$key.'.PICcontact'] = 'required';
                $rules['section.'.$key.'.purpose'] = 'required';

                $rules['section.'.$key.'.item'] = 'required|array';

                    if (isset($val['item'])) {
                        $item = $val['item'];
                        foreach($item as $k => $v) {
                            $rules['section.'.$key.'.item.'.$k] = 'required|numeric';
                            $rules['section.'.$key.'.quantity.'.$k] = 'required|numeric';
                            // $rules['section.'.$key.'.purpose.'.$k] = 'required';

                        }

                    }

            }

        }

	    $messages = array(
	    	// 'VisitStatus.required' => 'The Visit Status field is required',
	    	'Date.required'  =>'The Date field is required',
            'time.required'  =>'The Time field is required',
            'pickupdate.required'  =>'The Pick-up Date field is required',
            'pickup.required'  =>'The Pick-up Time field is required',
	  //  	'lorry.required'       => 'The Lorry field is required',
	    	'project.required'  =>'The Project field is required',
            'destination.required'  =>'The Site field is required',
            // 'projtype.required'  =>'The Project Type field is required',
            'client.required'  =>'The Client field is required',
            'purpose.required'  =>'The Purpose field is required',
            'PICname.required'  =>'The Name is required',
            'PICcontact.required'  =>'The Contact is required',
            'purpose.required'  =>'The purpose field is required',
            'item.required'  =>'The Item field is required',
            'quantity.required'  =>'The quantity field is required',
        );

        $validator = Validator::make($input, $rules,$messages);

        if ($validator->passes()) {

            return $this->insertDeliveryRequestAndSendEmail($request, $me);
        }

        return json_encode($validator->errors()->toArray());
	}

  public function canceldelivery(Request $request)
    {

            $me=(new CommonController)->get_current_user();

            $input = $request->all();

            $deliverydetail = DB::table('deliveryform')
            ->leftJoin('deliverystatuses', 'deliveryform.Id', '=', 'deliverystatuses.deliveryform_Id')
            ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
            ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
            ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
            ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
            // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
            ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
            ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
            ->leftJoin('radius','radius.Id','=','deliveryform.Location')
            ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
            ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
            // ->leftJoin('deliveryitem','','=','deliveryform.Id')
            ->select('approver.Name as Approver_Name','approver.Id as Approver_Id','requestor.Name as Requestor','deliveryform.delivery_date as Delivery_Date','radius.Location_Name as Site','projects.Project_Name as Project','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','options.Option as Purpose','deliverystatuses.remarks as Reason','deliverystatuses.created_at as Cancel_Request_Date')
            ->orderBy('deliverystatuses.Id','desc')
            ->where('deliveryform.Id', '=',$input["Id"])
            ->first();

            $items = DB::table('deliveryitem')
                ->leftJoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
                ->leftJoin('options','deliveryitem.Purpose','=','options.Id')
                ->select('inventories.Item_Code','inventories.Description','deliveryitem.add_desc','inventories.Unit','deliveryitem.Qty_request')
                ->where('deliveryitem.formId', $input["Id"])
                ->get();

        //     $itemcount=count($request->has('item'));
        //     if ($request->has('item')) {

        //    foreach ($itemcount as $key => $value) {
        //        # code...
        //         $item = DB::table('deliveryitem')
        //         ->leftJoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
        //         ->leftJoin('options','deliveryitem.Purpose','=','options.Id')
        //         ->select('inventories.Item_Code','options.Option as Purpose','deliveryitem.Qty_request')
        //    }
        // }
            if($input['Reason'] == "" || $input['Reason'] == null)
            {

            }
            else
            {
            $id=DB::table('deliverystatuses')->insertGetId(
                ['deliveryform_Id' => $input["Id"],
                 'user_Id' => $me->UserId,
                 'delivery_status' => "Cancelled",
                 'delivery_status_details' => "Delivery Cancel Request",
                 'remarks' => $input['Reason'],
                 'created_at' => Carbon::now()
                ]
            );
            DB::table('deliveryform')
            ->where('Id','=',$input['Id'])
            ->update(['approve'=>0]);
            }

            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',83)
            ->get();



            if ($id>0)
            {

                $notify = DB::table('users')
                ->whereIn('Id', [$deliverydetail->Approver_Id])
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


                Mail::send('emails.deliverycancel', ['deliverydetail' => $deliverydetail,'items' => $items], function($message) use ($emails,$me,$NotificationSubject)
                {
                        array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        $emails = array_filter($emails);
                        $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
                });

                return 1;
            }
            else {
                return 0;
            }

    }

     public function rejectdelivery(Request $request)
    {

            $me=(new CommonController)->get_current_user();

            $input = $request->all();

            $deliverydetail = DB::table('deliveryform')
            ->leftJoin('deliverystatuses', 'deliveryform.Id', '=', 'deliverystatuses.deliveryform_Id')
            ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
            ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
            ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
            ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
            // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
            ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
            ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
            // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
            ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
            ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
            // ->leftJoin('deliveryitem','','=','deliveryform.Id')
            ->select('approver.Name as Approver','requestor.Name as Requestor','deliveryform.delivery_date as Delivery_Date','deliverystatuses.delivery_status as Status','roadtax.Vehicle_No as Lorry','roadtax.Lorry_Size','driver.Name as Driver','deliveryform.Location as Site','projects.Project_Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','options.Option as Purpose')
            ->orderBy('deliverystatuses.Id','desc')
            ->where('deliveryform.Id', '=',$input["Id"])
            ->first();


            $id=DB::table('deliverystatuses')->insertGetId(
                ['deliveryform_Id' => $input["Id"],
                 'user_Id' => 0,
                 'delivery_status' => "Rejected"
                ]
            );

            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',75)
            ->get();



            if ($id>0)
            {

                $notify = DB::table('users')
                ->whereIn('Id', [$me->UserId, $deliverydetail->Approver])
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

                Mail::send('emails.deliveryreject', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$me,$NotificationSubject)
                {
                        array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        $emails = array_filter($emails);
                        $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
                });

                return 1;
            }
            else {
                return 0;
            }

    }

    public function recalldelivery(Request $request)
    {

            $me=(new CommonController)->get_current_user();

            $input = $request->all();

            $deliverydetail = DB::table('deliveryform')
            ->leftJoin('deliverystatuses', 'deliveryform.Id', '=', 'deliverystatuses.deliveryform_Id')
            ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
            ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
            ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
            ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
            // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
            ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
            ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
            ->leftJoin('radius','radius.Id','=','deliveryform.Location')
            ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
            ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
            // ->leftJoin('deliveryitem','','=','deliveryform.Id')
            ->select('approver.Name as Approver','requestor.Name as Requestor','deliveryform.delivery_date as Delivery_Date','deliverystatuses.delivery_status as Status','roadtax.Vehicle_No as Lorry','roadtax.Lorry_Size','driver.Name as Driver','radius.Location_Name as Site','projects.Project_Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','options.Option as Purpose')
            ->orderBy('deliverystatuses.Id','desc')
            ->where('deliveryform.Id', '=',$input["Id"])
            ->first();

            $id=DB::table('deliverystatuses')->insertGetId(
                ['deliveryform_Id' => $input["Id"],
                 'user_Id' => $me->UserId,
                 'delivery_status' => "Recalled",
                 'delivery_status_details' => "Delivery Has Been Recalled",
                 'created_at' => Carbon::now(),
                 'updated_at' => Carbon::now()
                ]
            );

            // $generateDO="";
            //     $generateDO.=$company->Initial."DO".Carbon::now()->format('y')."-";
            //     $checking == null ? $conv=sprintf("%05s",1):$conv=sprintf("%05s",(int)substr($checking->DO_No,strpos($checking->DO_No,'DO'.Carbon::now()->format('y').'-') + 5) + 1)."rev".$recalltime;
            //     $generateDO.=$conv;


            //Update DO Number with rev
            // $recalltime = DB::table('deliverystatuses')
            // ->where('deliverystatuses.deliveryform_Id','=',$input['Id'])
            // ->select(DB::RAW("SUM('deliverystatuses.deliveryform_Id')"));

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
                    ->update([
                        'VisitStatus' => 1,
                        'DO_No' => $do1,
                        'DriverId' => 0                        
                    ]);

                // if(($newrecall[0]['recallcount'])>0 && ($do!=NULL || $do!=""))
                // {
                //     $do1 = $newdo."_rev".$newrecall[0]["recallcount"];
                //     $update = DB::table('deliveryform')
                //     ->where('deliveryform.Id','=',$input['Id'])
                //     ->update(['VisitStatus'=>1,'DO_No'=>$do1]);
                // }
                // else{
                //     $update = DB::table('deliveryform')
                //     ->where('deliveryform.Id','=',$input['Id'])
                //     ->update(['VisitStatus'=>1,'DO_No'=>$do]);
                // }

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

                Mail::send('emails.deliveryrecall', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$me,$NotificationSubject)
                {
                        array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        $emails = array_filter($emails);
                        $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
                });

                return 1;
            }
            else {
                return 0;
            }

    }
    public function deleteDeliveryImage(Request $request)
    {
        DB::table('files')
        ->where('Id','=',$request->id)
        ->delete();
        return 1;
    }
    public function deletedelivery(Request $request)
    {

            $me=(new CommonController)->get_current_user();

            $input = $request->all();
            $donum = DB::table('deliveryform')
            ->select('DO_No')
            ->where('Id', '=',$input["Id"])
            ->first();

            DB::table('deliveryform')->where('Id', '=',$input["Id"])->delete();

            DB::table('deliveryitem')->where('formId','=',$input["Id"])->delete();

            DB::table('deliverystatuses')->where('deliveryform_Id','=',$input["Id"])->delete();

            DB::table('actionhistory')
            ->insert([
                'Type' => "DO",
                'ActionId' => $input["Id"],
                'action' => 'Delete',
                'UserId' => $me->UserId,
                'details' => $donum->DO_No,
                'created_at' => Carbon::now()
            ]);
            return 1;

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

    public function createDummyDO(Request $request)
    {

            $me=(new CommonController)->get_current_user();

            $input = $request->all();
            if($input['representative'] != "" || $input['representative'] != null)
            {
                $requestor = $input['representative'];
                $repre = $me->UserId;
            }
            else
            {
                $requestor = $me->UserId;
                $repre = 0;
            }
            if(isset($input['section'][0]['PICname']) == false)
            {
                $input['section'][0]['PICname'] = 0;
            }
            if(isset($input['section'][0]['PICcontact']) == false)
            {
                $input['section'][0]['PICcontact'] = 0;
            }
            if(isset($input['section'][0]['item']) == false)
            {
                return 0;
            }
            if(isset($input['lorry']) == false)
            {
                if(isset($input['truck']) == false)
                {
                    return 0;
                }
                else
                {
                    $input['lorry'] = $input['truck'];
                }
            }

            $type= DB::table('companies')
            ->select('type')
            ->where('Id','=',$input['section'][0]['client'] )
            ->first();

            $company = DB::table('companies')
            ->select('Initial')
            ->where('Id','=',$input['company'])
            ->first();

            $generateDO = $this->generateDONumber($input['company'],NULL,$input['section'][0]['purpose']);

            $id= DB::table('deliveryform')->insertGetId([
                'roadtaxId' => $input['lorry'],
                'created_at' => Carbon::now(),
                'delivery_date' => $input['Date'],
                'delivery_time' => $input['time'],
                'pickup_date' => $input['pickupdate'],
                'pick_up_time' => $input['pickup'],
                'RequestorId' => $requestor,
                'representative' => $repre,
                'ProjectId' => $input['section'][0]['project'],
                'project_type' => $type->type,
                'Purpose' => $input['section'][0]['purpose'],
                'client' => $input['section'][0]['client'],
                'Remarks' => $input['section'][0]['Remarks'],
                'Location' => $input['section'][0]['destination'],
                'term' => $input['section'][0]['term'],
                'po' => $input['section'][0]['po'],
                'salesorderid' => $input['salesorderid'],
                'PIC_Name' => $input['section'][0]['PICname'],
                'PIC_Contact' => $input['section'][0]['PICcontact'],
                'company_id' => $input['company'],
                'DO_No' => $generateDO
            ]);

            DB::table('salesorder')
            ->where('Id','=',$input['salesorderid'])
            ->update([
                'do' => 1
            ]);

            foreach ($input['section'][0]['item'] as $key => $value) {
                DB::table('deliveryitem')
                ->insert([
                'formId' => $id,
                'inventoryId' => $input['section'][0]['item'][$key],
                'Qty_request' => $input['section'][0]['quantity'][$key],
                'add_desc' => $input['section'][0]['Additional_Description'][$key]
                ]);
            }

            if($input['truck'] == 0)
            {
            DB::table('deliverystatuses')
            ->insert([
                'deliveryform_Id'=> $id,
                'user_id' => $me->UserId,
                'delivery_status' => 'Completed',
                'delivery_status_details' => '-',
                'created_at' => Carbon::now(),
                'remarks' => 'Dummy'
            ]);
            }
            else
            {
            DB::table('deliverystatuses')
            ->insert([
                'deliveryform_Id'=> $id,
                'user_id' => $me->UserId,
                'delivery_status' => 'Completed',
                'delivery_status_details' => 'Final Approved by Admin',
                'created_at' => Carbon::now(),
                'remarks' => 'Dummy'
            ]);
            }

            return 1;

    }

     public function getSite(Request $request)
    {

            $me=(new CommonController)->get_current_user();

            $input = $request->all();


           // $site= DB::table('tracker')
           // ->select('Site_Name')
           // ->where('ProjectId','=',$input['Id'])
           // ->get();
            $site = DB::table('radius')
            ->select('Id','Location_Name')
            ->where('ProjectId','=',$input['Id'])
            ->get();

            $allclient = DB::table('companies')
            ->select('companies.Id','companies.Company_Name','companies.Company_Code')
            ->where('companies.Client','=','Yes')
            ->get();

            return response()->json(['site' => $site,'allclient'=>$allclient]);

    }

     public function getSite2(Request $request)
    {

            $me=(new CommonController)->get_current_user();

            $input = $request->all();



           // $site= DB::table('tracker')
           // ->select('Site_Name')
           // ->where('ProjectId','=',$input['Id'])
           // ->get();

            $client = DB::table('companies')
            ->select('Company_Code')
            ->where('Id','=',$input['clientid'])
            ->get();
            $clientlist = json_decode(json_encode($client),true);

            $site = DB::table('radius')
            ->select('Id','Location_Name')
            ->where('ProjectId','=',$input['projectid'])
            ->where('Client','=',$clientlist[0]['Company_Code'])
            ->get();

            return response()->json(['site' => $site]);

    }

         public function lorrystatus(Request $request)
    {

            $me=(new CommonController)->get_current_user();
            $input = $request->all();
            $status = DB::table('deliveryform')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
            ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
            ->select('deliverystatuses.delivery_status')
            ->where('deliveryform.delivery_date','=',$input['date'])
            ->where('deliveryform.roadtaxId','=',$input['lorry'])
            ->where('deliverystatuses.delivery_status','<>','Recalled')
            ->where('deliverystatuses.delivery_status','<>','Rejected')
            ->get();
            // dd($status);
            if($status=="" OR $status==NULL)
            {
                $lorry = "Available";
            }
            else
            {
                $lorry = "Not Available";
            }
            $date = $input['date'];
            $available = DB::table('vehicleevent')
            ->select("VehicleId",'Start_Date','End_Date','Event')
            ->whereRaw('str_to_date("'.$date.'","%d-%M-%Y") BETWEEN str_to_date(vehicleevent.Start_Date,"%d-%M-%Y") AND str_to_date(vehicleevent.End_Date,"%d-%M-%Y")')
           ->where('VehicleId','=',$input['lorry'])
           ->first();
           if($available == "" OR $available == NULL)
           {
                $event = "No Event";
           }
           else
           {
                $event = "This vehicle is not available from ".$available->Start_Date." to ".$available->End_Date." due to ".$available->Event;
           }

            return response()->json(["lorry"=>$lorry,"event"=>$event]);

    }


    /**
     * edit Item List
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     */
    public function editItemList(Request $request, $Id){

        $me=(new CommonController)->get_current_user();
        $id = $request->Id;

        $editItem = DB::table('deliveryitem')
        ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
        ->select('inventories.Item_Code','inventories.Description','inventories.Unit','deliveryitem.Qty_request')
        ->where('deliveryitem.formId','=',$Id)
        ->get();

        return response()->json(['editItem' => $editItem]);
        return 1;

    }

     /**
     * update Item List
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     */
     public function updateItemList(Request $request, $Id)
    {

       $me=(new CommonController)->get_current_user();
        $id = $request->Id;

        $editItem = DB::table('deliveryitem')
        ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
        ->select('inventories.Item_Code','inventories.Description','inventories.Unit','deliveryitem.Qty_request','deliveryitem.Qty_send','deliveryitem.Qty_received','deliveryitem.remarks')
        ->where('deliveryitem.formId','=',$Id)
        ->get();

        return response()->json(['editItem' => $editItem]);
        return 1;

    }



     /**
     * Function to be called by ajax
     * @param  int  $formId
     * @return \Illuminate\Http\Response
     */
    public function fetchItemList($formId)
    {
        $status = DB::table('deliveryform')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->select('deliverystatuses.delivery_status')
        ->where('deliveryform.Id','=',$formId)
        ->first();
        if($status->delivery_status == "Stocks Update")
        {
            $item = DB::table('deliveryitem')
                        ->join('inventories', 'deliveryitem.inventoryId', '=', 'inventories.Id')
                        ->join('options','deliveryitem.Purpose','=','options.Id')
                        ->select("inventories.Item_Code","inventories.Description","deliveryitem.add_desc","inventories.Unit",'options.Option','deliveryitem.Qty_request')
                        ->where('deliveryitem.formId', $formId)
                        ->where('deliveryitem.stockin','=',1)
                        ->get();
        }
        else
        {
            $item = DB::table('deliveryitem')
                        ->join('inventories', 'deliveryitem.inventoryId', '=', 'inventories.Id')
                        ->join('options','deliveryitem.Purpose','=','options.Id')
                        ->select("inventories.Item_Code","inventories.Description","deliveryitem.add_desc","inventories.Unit",'options.Option','deliveryitem.Qty_request')
                        ->where('deliveryitem.formId', $formId)
                        ->get();
        }
        return response()->json(['Item' => $item]);
    }

         /**
     * Function to be called by ajax
     * @param  int  $formId
     * @return \Illuminate\Http\Response
     */
    public function fetchItem($formId)
    {
        $item = DB::table('deliveryitem')
                        ->join('inventories', 'deliveryitem.inventoryId', '=', 'inventories.Id')
                        ->join('options','deliveryitem.Purpose','=','options.Id')
                        ->select("deliveryitem.formId","deliveryitem.Id","inventories.Item_Code","inventories.Description","inventories.Unit",'options.Option','deliveryitem.Qty_request','deliveryitem.stockin')
                        ->where('deliveryitem.formId', $formId)
                        ->where('deliveryitem.stockin','<>',1)
                        ->where('deliveryitem.available','<>',1)
                        ->get();
                        
                         return response()->json(['Item' => $item]);
    }

    public function updateadddesc(Request $request)
    {
        DB::table('deliveryitem')
        ->where('Id','=',$request->id)
        ->update([
            'add_desc' => $request->desc
        ]);
        
        return 1;
    }

    protected function insertDeliveryRequestAndSendEmail(Request $request, $me)
    {
        $input = $request->all();
        $lorry = "";
        if($request->lorry == null && $request->truck_check == null)
        {
            return "Please Select Lorry or Truck";
        }
        elseif ($request->truck_check && $request->truck == "")
        {
            return "Please select truck";
        }

        $generateDO="";
            if($input['salesorderid'] != "" || $input['salesorderid'] != 0)
            {
                $generateDO = $this->generateDONumber(NULL,$input['salesorderid'],$input['section'][0]['purpose']);
                DB::table('salesorder')
                ->where('salesorder.Id','=',$input['salesorderid'])
                ->update([
                    'do' => 1
                ]);
            }

            if(isset($input['truck_check']))
            {
                    $generateDO = $this->generateDONumber(NULL,NULL,$input['section'][0]['purpose']);
            }

            $idcount = ['D' => [], 'R'=>[]];

            foreach($request->get('section') as $key => $val)
            {
                $cond ="";
                $note ="";
                $isMr=false;

                //Separate by Form
                $purpos = DB::table('options')
                ->select('options.Option')
                ->where('options.Id','=',$val['purpose'])
                ->get();
                    // $projtype="";
                        if($request->get('representative') == "" || $request->get('representative') == null)
                        {
                            $originrequestor= $me->UserId;
                            $helper="";
                        }
                        else
                        {
                            $originrequestor = $request->get('representative');
                            $helper = $me->UserId;
                        }

                        $projtype = DB::table('companies')
                        ->where('Id','=',$val['client'])
                        ->select('type')
                        ->first();


                if(isset($val['mr']))
                {

                    $isMr=true;
                }
                else $isMr=false;
                $generateDO = $this->generateDONumber($val['client'],NULL,$val['purpose']);
                $purposes = json_decode(json_encode($purpos), True);
                    if($purposes[0]['Option']=="Collection" || $purposes[0]['Option']=="Exchange-out"){
                        $id = DB::table('deliveryform')->insertGetId([
                        'delivery_date'=>$request->get('Date'),
                        'delivery_time'=>$request->get('time'),
                        'pickup_date'=>$request->get('pickupdate'),
                        'pick_up_time'=>$request->get('pickup'),
                        'roadtaxId'=>$request->get('lorry')==""? $input['truck']:$input['lorry'],
                        'representative'=>$helper,
                        'ProjectId'=>$val['project'],
                        'Location'=>$val['destination'],
                        'project_type'=>$projtype->type,
                        'RequestorId'=>$originrequestor,
                        'VisitStatus'=> 0,
                        'Purpose' =>$val['purpose'],
                        'client'=>$val['client'],
                        'PIC_Name'=>$val['PICname'],
                        'PIC_Contact'=>$val['PICcontact'],
                        'term'=>$val['term'],
                        'po'=>$val['po'],
                        'DO_No'=>$generateDO,
                        'Remarks'=>$val['Remarks'],
                        'salesorderid'=>$input['salesorderid']
                    ]);
                    $formItems = $val['item'];
                    foreach($formItems as $fi => $fis) {
                        // dd($val['Additional_Description'][$fi]);
                         DB::table('deliveryitem')->insertGetId([
                            'inventoryId'=>$val['item'][$fi],
                            'Qty_request'=>$val['quantity'][$fi],
                            'add_desc'=>$val['Additional_Description'][$fi],
                            'Purpose'=>$val['purpose'],
                            'formId'=>$id
                        ]);
                    }
                    // $con = "";
                    $con = $val['condition'];
                    // dd($con);
                    foreach ($con as $cond => $condi) {
                        if(strlen($val['condition'][$cond])>4)
                        {
                            DB::table('deliverycondition')->insertGetId([
                            'deliveryform_Id'=>$id,
                            'note'=>$val['condition'][$cond]
                            ]);
                        }
                        else{
                         DB::table('deliverycondition')->insertGetId([
                            'deliveryform_Id'=>$id,
                            'options_Id'=>$val['condition'][$cond]
                         ]);
                        }
                    }
                    $note = $val['note'];
                    foreach ($note as $n => $no) {
                         if(strlen($val['note'][$n])>4)
                        {
                            DB::table('deliverynote')->insertGetId([
                            'deliveryform_Id'=>$id,
                            'note'=>$val['note'][$n]
                            ]);
                        }
                        else{
                         DB::table('deliverynote')->insertGetId([
                            'deliveryform_Id'=>$id,
                            'options_Id'=>$val['note'][$n]
                         ]);
                        }
                    }
                    array_push($idcount['R'], $id);
                    }
                    else{

                        $id2 = DB::table('deliveryform')->insertGetId([
                        'delivery_date'=>$request->get('Date'),
                        'delivery_time'=>$request->get('time'),
                        'pickup_date'=>$request->get('pickupdate'),
                        'pick_up_time'=>$request->get('pickup'),
                        'roadtaxId'=>$request->get('lorry')==""? $input['truck']:$input['lorry'],
                        'representative'=>$helper,
                        'ProjectId'=>$val['project'],
                        'Location'=>$val['destination'],
                        'project_type'=>$projtype->type,
                        'RequestorId'=>$originrequestor,
                        'VisitStatus'=> 0,
                        'Purpose' =>$val['purpose'],
                        'client'=>$val['client'],
                        'PIC_Name'=>$val['PICname'],
                        'PIC_Contact'=>$val['PICcontact'],
                        'term'=>$val['term'],
                        'po'=>$val['po'],
                        'Remarks'=>$val['Remarks'],
                        'DO_No'=>$generateDO,
                        'company_id'=>isset($company[0]->Id)? $company[0]->Id:0,
                        'salesorderid'=>$input['salesorderid']
                    ]);
                    // if(isset($val['mr'])){
                    //     foreach($val['mr'] as $mr){

                    //     }
                    // }
                    $formItems = $val['item'];
                    if(isset($val['mr'])){
                        $tempArr=array();
                        foreach($formItems as $fi => $fis) {
                            $tempid=DB::table('deliveryitem')->insertGetId([
                               'inventoryId'=>$val['item'][$fi],
                               'Qty_request'=>$val['quantity'][$fi],
                               'add_desc'=>$val['Additional_Description'][$fi],
                               'Purpose'=>$val['purpose'],
                               'formId'=>$id2
                           ]);
                           array_push($tempArr,$tempid);
                       }
                       $comb=array_combine($val['mr'],$tempArr);

                       foreach($comb as $mrId=>$item){
                            DB::table('materialrequest')
                            ->where('Id',$mrId)
                            ->update([
                                'DeliveryId'=>$id2,
                                'DeliveryitemId'=>$item
                            ]);
                       }

                    }else{
                        foreach($formItems as $fi => $fis) {
                            DB::table('deliveryitem')->insertGetId([
                                'inventoryId'=>$val['item'][$fi],
                                'Qty_request'=>$val['quantity'][$fi],
                                'add_desc'=>$val['Additional_Description'][$fi],
                                'Purpose'=>$val['purpose'],
                                'formId'=>$id2
                            ]);
                        }
                    }

                    $con = $val['condition'];
                    foreach ($con as $cond => $condi) {
                        // $length= strlen($val['condition'][$cond]);
                        // dd($length);
                        if(strlen($val['condition'][$cond])>4)
                        {
                            DB::table('deliverycondition')->insertGetId([
                            'deliveryform_Id'=>$id2,
                            'note'=>$val['condition'][$cond]
                            ]);
                        }
                        else{
                         DB::table('deliverycondition')->insertGetId([
                            'deliveryform_Id'=>$id2,
                            'options_Id'=>$val['condition'][$cond]
                         ]);
                        }
                    }
                    $note = $val['note'];
                    foreach ($note as $n => $no) {
                         if(strlen($val['note'][$n])>4)
                        {
                            DB::table('deliverynote')->insertGetId([
                            'deliveryform_Id'=>$id2,
                            'note'=>$val['note'][$n]
                            ]);
                        }
                        else{
                         DB::table('deliverynote')->insertGetId([
                            'deliveryform_Id'=>$id2,
                            'options_Id'=>$val['note'][$n]
                         ]);
                        }
                    }
                    array_push($idcount['D'], $id2);
                    }
            }

                //Separate by item
                //A = Return Note (RN)
                //B = Delivery Order (DO)
                // $purposes = $val['purpose'];
                // $items = ['A' => [], 'B' => []];
                // foreach($purposes as $k => $v) {
                //     if ($v == 1544) //value of collection in options table
                //     {
                //         $items['A'][] = [
                //             'item' => $val['item'][$k],
                //             'quantity' => $val['quantity'][$k],
                //             'purpose' => $val['purpose'][$k]
                //         ];
                //     } else {
                //         $items['B'][] = [
                //             'item' => $val['item'][$k],
                //             'quantity' => $val['quantity'][$k],
                //             'purpose' => $val['purpose'][$k]
                //         ];
                //     }
                // }

                // if (count($items['A'])) {
                //     $id = DB::table('deliveryform')->insertGetId([
                //         'delivery_date'=>$request->get('Date'),
                //         'roadtaxId'=>$request->get('lorry'),
                //         'ProjectId'=>$val['project'],
                //         'LocationId'=>$val['destination'],
                //         'departmentId'=>$val['department'],
                //         'RequestorId'=>$me->UserId,
                //         'VisitStatus'=> 0,
                //         'Purpose' =>"Return Note",
                //         // 'clientId'=>$val['client'],
                //         'PIC_Name'=>$val['PICname'],
                //         'PIC_Contact'=>$val['PICcontact'],
                //         'Remarks'=>$val['Remarks']
                //     ]);
                //     $formItems = $items['A'];
                //     foreach($formItems as $fi) {
                //          DB::table('deliveryitem')->insertGetId([
                //             'inventoryId'=>$fi['item'],
                //             'Qty_request'=>$fi['quantity'],
                //             'Purpose'=>$fi['purpose'],
                //             'formId'=>$id
                //         ]);
                //     }
                //     array_push($idcount['R'], $id);

                // }

                // if (count($items['B'])) {
                //     $id2 = DB::table('deliveryform')->insertGetId([
                //         'delivery_date'=>$request->get('Date'),
                //         'roadtaxId'=>$request->get('lorry'),
                //         'ProjectId'=>$val['project'],
                //         'LocationId'=>$val['destination'],
                //         'departmentId'=>$val['department'],
                //         'RequestorId'=>$me->UserId,
                //         'VisitStatus'=> 0,
                //         'Purpose'=>"Delivery Order",
                //         // 'clientId'=>$val['client'],
                //         'PIC_Name'=>$val['PICname'],
                //         'PIC_Contact'=>$val['PICcontact'],
                //         'Remarks'=>$val['Remarks']
                //     ]);

                //     $formItems = $items['B'];
                //     foreach($formItems as $fi) {
                //          DB::table('deliveryitem')->insertGetId([
                //             'inventoryId'=>$fi['item'],
                //             'Qty_request'=>$fi['quantity'],
                //             'Purpose'=>$fi['purpose'],
                //             'formId'=>$id2
                //         ]);
                //     }
                //     array_push($idcount['D'], $id2);

                // }
        //        if(count($items['A'])){
            if(isset($val['mr']))
            {
                $statusdetails = "-";
            }
            else
            {
                $statusdetails = "";
            }
                foreach ($idcount['R'] as $ic => $ics) {
                    # code...
                if(isset($input['truck_check'])){
                    DB::table('deliverystatuses')->insert([
                        'deliveryform_Id' => $ics,
                        'user_Id' => $me->UserId, //set admin
                        'delivery_status' =>"Completed",
                        'delivery_status_details' => $statusdetails,
                        'updated_at'=> Carbon::now()
                    ]);
                }
                else{
                DB::table('deliverystatuses')->insert([
                    'deliveryform_Id' => $ics,
                    'user_Id' => $me->UserId, //set admin
                    'delivery_status' =>"Pending",
                    'delivery_status_details' => "New Return Note Application",
                    'updated_at'=> Carbon::now()
                ]);

                //07-Aug-2020 KP
                    DB::table('deliverystatuses')->insert([
                    'deliveryform_Id' => $ics,
                    'user_Id' => $me->UserId, //set admin
                    'delivery_status' =>"Processing",
                    'delivery_status_details' => "Accepted By Admin",
                    'updated_at'=> Carbon::now()
                    ]);
                }
                // DB::table('deliverytracking')->insert([
                //     'deliverytracking.deliverystatus_id' => $ics
                // ]);
               }

        //    if(($items['B'])!= "" OR ($items['B'])!=NULL){
               foreach ($idcount['D'] as $de => $des) {
                   # code...
                    if(isset($input['truck_check'])){
                        DB::table('deliverystatuses')->insert([
                            'deliveryform_Id' => $des,
                            'user_Id' => $me->UserId,
                            'delivery_status' =>"Completed",
                            'delivery_status_details' => $statusdetails,
                            'updated_at'=> Carbon::now()
                        ]);
                    }
                    else{
                        DB::table('deliverystatuses')->insert([
                            'deliveryform_Id' => $des,
                            'user_Id' => $me->UserId,
                            'delivery_status' =>"Pending",
                            'delivery_status_details' => "New Delivery Application",
                            'updated_at'=> Carbon::now()
                        ]);

                        //07-Aug-2020 KP
                        DB::table('deliverystatuses')->insert([
                        'deliveryform_Id' => $des,
                        'user_Id' => $me->UserId, //set admin
                        'delivery_status' =>"Processing",
                        'delivery_status_details' => "Accepted By Admin",
                        'updated_at'=> Carbon::now()
                        ]);

                    }

             // DB::table('deliverytracking')->insert([
             //        'deliverytracking.deliverystatus_id' => $des
             //    ]);
           }
        // }

            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',68)
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
    // dd($idcount['D'],$idcount['R'])
        foreach ($idcount['D'] as $c => $cs) {
        $deliverydetail = DB::table('deliveryform')
        ->leftJoin('deliverystatuses', 'deliveryform.Id', '=', 'deliverystatuses.deliveryform_Id')
        ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
        ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
        ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
        // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
        // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
        ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
        ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
        ->leftJoin('radius','radius.Id','=','deliveryform.Location')
        ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
        ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
        // ->leftJoin('deliveryitem','','=','deliveryform.Id')
        ->select('requestor.Name as Requestor','deliveryform.delivery_date as Delivery_Date','roadtax.Vehicle_No as Lorry','roadtax.Lorry_Size','radius.Location_Name as Site','projects.Project_Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date')
        ->orderBy('deliverystatuses.Id','desc')
        ->where('deliveryform.Id', '=',$cs)
        ->first();

            $deliveryitemlist = DB::table('deliveryitem')
            ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
            ->select('inventories.Item_Code','inventories.Description','inventories.Unit','deliveryitem.Qty_request')
            ->where('deliveryitem.formId','=',$cs)
            ->get();

         Mail::send('emails.deliveryapplication', ['deliverydetail' => $deliverydetail, 'deliveryitemlist'=>$deliveryitemlist], function($message) use ($emails,$me,$NotificationSubject)
        {
            array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
            $emails = array_filter($emails);
            $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
        });
    }
    foreach ($idcount['R'] as $d=> $ds) {
        # code...
        $deliverydetail2 = DB::table('deliveryform')
        ->leftJoin('deliverystatuses', 'deliveryform.Id', '=', 'deliverystatuses.deliveryform_Id')
        ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
        ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
        ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
        // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
        // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
        ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
        ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
        ->leftJoin('radius','radius.Id','=','deliveryform.Location')
        ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
        ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
        // ->leftJoin('deliveryitem','','=','deliveryform.Id')
        ->select('requestor.Name as Requestor','deliveryform.delivery_date as Delivery_Date','roadtax.Vehicle_No as Lorry','roadtax.Lorry_Size','radius.Location_Name as Site','projects.Project_Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date')
        ->orderBy('deliverystatuses.Id','desc')
        ->where('deliveryform.Id', '=',$ds)
        ->first();

            $deliveryitemlist = DB::table('deliveryitem')
            ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
            ->select('inventories.Item_Code','inventories.Description','inventories.Unit','deliveryitem.Qty_request')
            ->where('deliveryitem.formId','=',$ds)
            ->get();

         Mail::send('emails.deliveryreturn', ['deliverydetail2' => $deliverydetail2, 'deliveryitemlist' => $deliveryitemlist], function($message) use ($emails,$me,$NotificationSubject)
        {
            array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
            $emails = array_filter($emails);
            $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
        });

    }

        // $notify = DB::table('users')
        // ->whereIn('Id', [$me->UserId])
        // ->get();

        return 1;
    }

    public function resubmit(Request $request)
    {
        $me = (new CommonController)->get_current_user();
        $input = $request->all();
        $formIds = $input["Id"];
        $delivery = DB::table('deliveryform')
        ->leftJoin('deliverystatuses', 'deliveryform.Id', '=', 'deliverystatuses.deliveryform_Id')
            ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
            ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
            ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
            ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
            // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
            ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
            ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
            // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
            ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
            ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
            // ->leftJoin('deliveryitem','','=','deliveryform.Id')
            ->select('requestor.Name as Requestor','deliveryform.delivery_date as Delivery_Date','deliverystatuses.delivery_status as Status','roadtax.Vehicle_No as Lorry','deliveryform.roadtaxId','roadtax.Lorry_Size','driver.Name as Driver','deliveryform.Location as Site','projects.Project_Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','options.Option as Purpose','deliveryform.Purpose as deliverypurpose')
            ->orderBy('deliverystatuses.Id','desc')
            ->where('deliveryform.Id', '=',$formIds)
            ->first();

        $lorrytype = DB::table('roadtax')
        ->select('Type')
        ->where('Id','=',$delivery->roadtaxId)
        ->first();

        $status = $delivery->Status;

        if(str_contains($status,"Recalled")!==false){
            DB::table('deliverystatuses')->insert(
                                ['deliveryform_Id' => $input["Id"],
                                 'user_Id' => $me->UserId,
                                 'delivery_status' => "Processing",
                                 'delivery_status_details' => "Resubmit by Requestor",
                                 'updated_at'=> Carbon::now()
                                ]
                            );
            if($delivery->roadtaxId == 0 || $lorrytype->Type == "TRUCK")
            {

                // $generateDO = $this->generateDONumber(NULL,NULL,$delivery->deliverypurpose);

                // DB::Table('deliveryform')
                // ->where('Id','=',$input['Id'])
                // ->update([
                //         'DO_No' => $generateDO
                // ]);

               DB::table('deliverystatuses')->insert(
                                ['deliveryform_Id' => $input["Id"],
                                 'user_Id' => $me->UserId,
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
                                 'user_Id' => $me->UserId,
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
            // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
            ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
            ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
            ->leftJoin('radius','radius.Id','=','deliveryform.Location')
            ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
            ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
            // ->leftJoin('deliveryitem','','=','deliveryform.Id')
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
            // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
            ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
            // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
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
            }

            foreach ($notify as $user) {

                if ($user->Company_Email!="") {
                    array_push($emails,$user->Company_Email);
                } else {
                    array_push($emails,$user->Personal_Email);
                }
            }

            Mail::send('emails.deliverystatus', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$me,$NotificationSubject)
            {
                array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                $emails = array_filter($emails);
                $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
            });

            array_push($notifylist,$driver->driverId);

            $notify = DB::table('users')
            ->whereIn('Id',$notifylist)
            ->get();

            $notifyplayerid=array();

            foreach ($notify as $u)
            {
                array_push($notifyplayerid,$u->Player_Id);
            }

            if($notifyplayerid)
            {
                $this->sendNewTrip($notifyplayerid);
            }
        return 1;
    }


    public function removeupload(Request $request)
    {
        $input = $request->all();
        return DB::table('files')
        ->where('Id', '=', $input["Id"])
        ->delete();

    }

     public function upload(Request $request)
    {
        $me = (new CommonController)->get_current_user();
        $input = $request->all();
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
                     'TargetId' => $input['Id'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Web_Path' => '/private/upload/Delivery/'.$fileName
                    ]
                );
                $attachmentUrl = url('/private/upload/Delivery/'.$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            $filenames=substr($filenames, 0, strlen($filenames)-1);

            return $filenames;
            //return '/private/upload/'.$fileName;
        }
        return 0;

        // return json_encode(['formId' => $input['Id']])

    }

        public function submit(Request $request)
    {

        $me = (new CommonController)->get_current_user();

        $input = $request->all();

        $deliveryIds = explode(",", $input["deliveryIds"]);

        $delivery = DB::table('deliveryform')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
        ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
        ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
        ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
        ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
        // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
        ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
        // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
        ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
        ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
        ->leftJoin('deliveryitem','','=','deliveryform.Id')
        ->select('deliveryform.Id as FormId','deliverystatuses.Id','deliveryform.RequestorId as RequestorId','requestor.Name','deliveryform.Date','roadtax.Vehicle_No','roadtax.Lorry_Size','deliveryform.Location as Site','projects.Project_Name as Project','driver.Name as Driver','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','deliveryform.updated_at as Review_Date','inventories.Item_Code','deliveryitem.Qty_request','options.Option as Purpose','deliverystatuses.delivery_status as Status','approver.Id as ApproverId','approver.Name')
        ->orderBy('deliveryform.Id','asc')
        ->whereIn('deliveryform.Id',$deliveryIds)
        ->get();

        // $approver = DB::table('approvalsettings')
        // ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
        // ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
        // ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
        // ->where('approvalsettings.Type', '=', 'Leave')
        // ->where('approvalsettings.ProjectId', '<>', '0')
        // ->orderBy('approvalsettings.Country','asc')
        // ->orderBy('projects.Project_Name','asc')
        // ->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
        // ->get();
        $final=false;

        foreach ($delivery as $deliveries) {

            $emaillist=array();
            array_push($emaillist,$me->UserId);
            # code...
            $submitted=false;
            $currentstatus=$deliveries->Status;

            if ($deliveries->Status=="Accepted")
            {
                array_push($emaillist,$deliveries->RequestorId);
                // array_push($emaillist,$leave->ApproverId);
            }

            if ((strpos($deliveries->Status, 'Rejected') === false) && $deliveries->Status!="Accepted")
            {

                foreach ($approver as $user) {

                        if (!empty($user->Id) && $user->Project_Name==$deliveries->Project_Name && $deliveries->ApproverId != $user->Id)
                        {

                            DB::table('deliverystatuses')->insert(
                                ['deliveryform_Id' => $deliveries->FormId,
                                 'user_Id' => $user->Id,
                                 'delivery_status' => "Pending"
                                ]
                            );
                            $submitted=true;
                            array_push($emaillist,$user->Id);
                            array_push($emaillist,$deliveries->RequestorId);

                            break;
                        }
                        elseif (!empty($user->Id) && $user->Project_Name==$deliveries->Project_Name && $deliveries->ApproverId == $user->Id)
                        {
                            # code...
                                $submitted=true;
                                array_push($emaillist,$user->Id);
                                array_push($emaillist,$deliveries->RequestorId);
                        }
                        elseif (!empty($user->Id) && $user->Project_Name==$deliveries->Project_Name && $deliveries->ApproverId != $user->Id && $user->Level=="Final Approval")
                        {

                            DB::table('deliverystatuses')->insert(
                                ['formId' => $delivery->FormId,
                                 'user_Id' => $user->Id,
                                 'delivery_status' => "Pending"
                                ]
                            );
                            $submitted=true;
                            array_push($emaillist,$user->Id);
                            array_push($emaillist,$deliveries->RequestorId);

                            break;
                        }
                    }

            }
            elseif ((strpos($deliveries->Status, 'Rejected') !== false))
            {

                array_push($emaillist,$deliveries->RequestorId);
            }
            elseif ($deliveries->Status=="Accepted" || $deliveries->Status=="Rejected")
            {
                $final=true;
                array_push($emaillist,$delivery->RequestorId);
            }

            //notification
            if (count($emaillist)>1)
            {

                $notify = DB::table('users')
                ->whereIn('Id', $emaillist)
                ->get();

                if($final)
                {
                    $subscribers = DB::table('notificationtype')
                    ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
                    ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                    ->where('notificationtype.Id','=',69)
                    ->get();
                }
                else {
                    $subscribers = DB::table('notificationtype')
                    ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
                    ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                    ->where('notificationtype.Id','=',68)
                    ->get();

                }

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

               $deliverydetail = DB::table('deliveryform')
            ->leftJoin('deliverystatuses', 'deliveryform.Id', '=', 'deliverystatuses.deliveryform_Id')
            ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
            ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
            ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
            // ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
            // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
            ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
            ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
            // ->leftJoin('radius','radius.Id','=','deliveryform.LocationId')
            ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
            ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
            // ->leftJoin('deliveryitem','','=','deliveryform.Id')
            ->select('deliveryform.Id as formId','requestor.Name as Requestor','deliveryform.delivery_date as Delivery_Date','deliverystatuses.delivery_status as Status','roadtax.Vehicle_No as Lorry','roadtax.Lorry_Size','deliveryform.Location as Site','projects.Project_Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','options.Option as Purpose')
            ->orderBy('deliverystatuses.Id','desc')
            ->where('deliveryform.Id', '=',$delivery->FormId)
            ->get();

                Mail::send('emails.deliverystatus', ['me' => $me,'deliverydetail' => $deliverydetail], function($message) use ($emails,$deliverydetail,$NotificationSubject)
                {
                        array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        $emails = array_filter($emails);
                        $message->to($emails)->subject($NotificationSubject.' ['.$deliverydetail[0]->Name.']');

                });

                return 1;
            }
            else {
                return 0;
            }

        }

        return 1;

    }

	public function changeDeliveryStatus(Request $request)
	{
        $me=(new CommonController)->get_current_user();

        // $custodian = DB::table('roadtax')
        // ->select('users.Name','driver.Name')
        // ->leftJoin('users','users.Id','=','roadtax.UserId')
        // ->leftJoin('users as driver','driver.Id','=','roadtax.UserId2')
        // ->where('roadtax.Id','=',$request->vehicle)
        // ->first();
        if($request->triptype != null)
        {
            DB::table('deliveryform')
            ->where('Id','=',$request->id)
            ->update([
                'trip'=> $request->triptype
            ]);
        }

		if($request->type == "admin" && $request->status == "Pending")
		{
            $company=DB::table('companies')
            ->where('companies.Id','=',$request->company)
            ->first();

            $option=DB::table('options')
            ->leftjoin('deliveryform','options.Id','=','deliveryform.Purpose')
            ->select('options.Option','deliveryform.DO_No','deliveryform.ProjectId')
            ->where('deliveryform.Id','=',$request->id)
            ->first();

            $generateDO="";

            if($option->Option == "Collection")
            {
                $checking=DB::table('deliveryform')
                ->where('DO_No','LIKE',$company->Initial.'RN'.Carbon::now()->format('y').'%')
                ->select(DB::raw("Max(DO_No) as DO_No"))
                ->orderBy('Id','DESC')
                ->first();
                $temp="";

                if($option->DO_No == "" || $option->DO_No == null)
                {
                    //$generateDO="";
                    $temp1 = $company->Initial."RN".Carbon::now()->format('y');
                    if($checking->DO_No != null)
                    {
                    $temp1=explode("-",$checking->DO_No)[0];
                    $temp=explode("-",$checking->DO_No)[1];
                    $temp=explode("_",$temp)[0];
                    }
                    $checking == null ? $conv=sprintf("%05s",1):$conv=sprintf("%05s",$temp+1);
                    // $generateDO.=$conv;
                    $generateDO = $temp1.'-'.$conv;
                    // $temp=explode("-",$checking->DO_No)[1];
                    // $temp=explode("_",$temp)[0];
                    // $checking == null ? $conv=sprintf("%05s",1):$conv=sprintf("%05s",$temp+1);
                    // $generateDO.=$conv;
                }
                DB::table('deliverystatuses')
                ->insert([
                    "deliveryform_Id"=>$request->id,
                    "delivery_status"=>"Processing",
                    "delivery_status_details"=>"Accepted by Admin",
                    "user_Id"=>$me->UserId,
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);

                if($generateDO != "")
                {
                    DB::table('deliveryform')
                    ->where('Id','=',$request->id)
                    ->update([
                        "DO_No"=>$generateDO
                    ]);
                }

                // $this->deliveryNotification($input['formid'],"status updated");

                $this->deliveryNotification($request->id,"status updated");



            }
            elseif($option->Option == "Delivery")
            {

                $checking=DB::table('deliveryform')
                ->where('DO_No','LIKE',$company->Initial.'DO'.Carbon::now()->format('y').'%')
                ->select(DB::raw('Max(DO_No) as DO_No'))
                ->orderBy('Id','DESC')
                ->first();
                $temp = "";
                if($option->DO_No == "" || $option->DO_No == null)
                {
                    //$generateDO="";
                    $temp1=$company->Initial."DO".Carbon::now()->format('y');
                    if($checking->DO_No != null)
                    {
                    $temp1=explode("-",$checking->DO_No)[0];
                    $temp=explode("-",$checking->DO_No)[1];
                    $temp=explode("_",$temp)[0];
                    }
                    $checking == null ? $conv=sprintf("%05s",1):$conv=sprintf("%05s",$temp+1);
                    // $generateDO.=$conv;
                    $generateDO = $temp1.'-'.$conv;
                    // $checking == null ? $conv=sprintf("%05s",1):$conv=sprintf("%05s",(int)substr($checking->DO_No,strpos($checking->DO_No,'DO'.Carbon::now()->format('y').'-') + 5,
                    // strlen($generateDO)-strpos($checking->DO_No,'DO'.Carbon::now()->format('y').'-')+5
                    // )+1);
                }

                DB::table('deliverystatuses')
                ->insert([
                    "deliveryform_Id"=>$request->id,
                    "delivery_status"=>"Processing",
                    "delivery_status_details"=>"Accepted by Admin",
                    "user_Id"=>$me->UserId,
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);

                if($generateDO != "")
                {
                    DB::table('deliveryform')
                    ->where('Id','=',$request->id)
                    ->update([
                        "DO_No"=>$generateDO
                    ]);
                }
                //  $mr=DB::table('materialrequest')
                // ->select('DeliveryitemId')
                // ->where('DeliveryId',$request->id)
                // ->get();

                $mr = DB::table('deliveryitem')
                ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
                ->select('deliveryitem.Id')
                ->where('inventories.Type','=','MPSB')
                ->where('formId','=',$request->id)
                ->get();
                if($mr){
                    DB::table('deliverystatuses')
                    ->insert([
                        "deliveryform_Id"=>$request->id,
                        "delivery_status"=>"Processing",
                        "delivery_status_details"=>"Accepted by Warehouse",
                        "user_Id"=>$me->UserId,
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);
                    foreach($mr as $m){
                        DB::Table('deliveryitem')
                        ->where('Id',$m->Id)
                        ->update([
                            'available'=>1
                        ]);
                    }
                    $this->deliveryNotification($request->id,"Accepted by Admin");
                }else
                {
                    if($option->ProjectId == 142)
                    {
                        $this->deliveryNotification($request->id,"status updated");
                    }
                    elseif($option->ProjectId == 143)
                    {
                        $this->deliveryNotification($request->id,"status updated");
                    }
                    elseif($option->ProjectId == 144)
                    {
                        $this->deliveryNotification($request->id,"status updated");
                    }
                    else
                    {
                        // $this->deliveryNotification($request->id,"status updated");
                        $this->deliveryNotification($request->id,"Accepted by Admin");
                    }

                }

            }

            // DB::table('deliverystatuses')
            // ->insert([
            //     "deliveryform_Id"=>$request->id,
            //     "delivery_status"=>"Processing",
            //     "delivery_status_details"=>"Accepted by Admin",
            //     "user_Id"=>$me->UserId,
            //     "created_at"=>Carbon::now(),
            //     "updated_at"=>Carbon::now()
            // ]);

            // if($generateDO != "")
            // {
            //     DB::table('deliveryform')
            //     ->where('Id','=',$request->id)
            //     ->update([
            //         "DO_No"=>$generateDO
            //     ]);
            // }

            // if($option->Option != "Collection" || $option->Option != "Exchange-in")
            // {
            //     $this->deliveryNotification($request->id,"Accepted by Admin");
            // }
            // else
            // {
            //     $this->deliveryNotification($request->id,"status updated");
            // }

			return 1;
        }
        else if($request->type == "admin" && $request->status == "Completed")
        {
            DB::table('deliverystatuses')
            ->insert([
                "deliveryform_Id"=>$request->id,
                "delivery_status"=>"Completed",
                "delivery_status_details"=>"Final Approved by Admin",
				"user_Id"=>$me->UserId,
				"created_at"=>Carbon::now(),
				"updated_at"=>Carbon::now()
            ]);
            if($request->incentive != null)
            {
                DB::table('deliveryform')
                ->where('Id','=',$request->id)
                ->update([
                    'incentive'=>$request->incentive,
                ]);
            }
            $this->deliveryNotification($request->id,"status updated completed");
        }
        else if($request->type == "proceed")
        {
            DB::table('deliverystatuses')
            ->insert([
                'delivery_status'=>"Processing",
                "deliveryform_Id"=>$request->id,
                "delivery_status_details"=>"Proceed to Delivery",
                "updated_at"=>Carbon::now(),
                "created_at"=>Carbon::now(),
                "user_Id"=>$me->UserId
            ]);
            $this->deliveryNotification($request->id,"proceed");
        }
        else if($request->type == "bounce back"){
            DB::table('deliverystatuses')
            ->insert([
                'delivery_status'=>"Insufficient Stocks",
                "deliveryform_Id"=>$request->id,
               // "delivery_status_details"=>"",
                "updated_at"=>Carbon::now(),
                "created_at"=>Carbon::now(),
                "user_Id"=>$me->UserId
            ]);
        }
        else if($request->type == "transfer")
        {
            DB::table('deliverystatuses')
            ->insert([
                'delivery_status'=>"Processing",
                "deliveryform_Id"=>$request->id,
                "DriverId"=>$request->olddriverid,
                "delivery_status_details"=>"Transfer Trip",
                "updated_at"=>Carbon::now(),
                "created_at"=>Carbon::now(),
                "user_Id"=>$me->UserId
            ]);
            DB::table('deliveryform')
            ->where('Id','=',$request->id)
            ->update([
                'DriverId'=>$request->driverid
            ]);
            $this->deliveryNotification($request->id,'status updated transfer');
        }
        else if($request->type == "rejected")
        {
            if($request->remarks != "")
            {
                DB::table('deliverystatuses')
                ->insert([
                    "deliveryform_Id"=>$request->id,
                    'delivery_status'=>"Rejected",
                    "delivery_status_details"=>"Rejected by Admin",
                    "remarks"=>$request->remarks,
                    "user_Id"=>$me->UserId,
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);
                $this->deliveryNotification($request->id,"rejected");
                return 1;
            }
            else return 0;
        }
		else
			return 0;

		return 1;
	}

    private function deliveryNotification($id,$type)
    {
        // dd($id,$type);
        $deliverydetail = DB::table('deliveryform')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
        ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
        ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
        ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
        //->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
        ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
        ->leftJoin('radius','radius.Id','=','deliveryform.Location')
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
        ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
        ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
        ->select('requestor.Name as Requestor','deliveryform.delivery_date as Delivery_Date','roadtax.Vehicle_No','roadtax.Lorry_Size','projects.Project_Name','options.Option','driver.Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.PIC_Contact','deliveryform.Remarks',
            'deliveryform.created_at as Application_Date','options.Option','approver.Name as Approver','deliverystatuses.delivery_status as Status',
            'requestor.Company_Email as Requestor_Company_Email','approver.Company_Email as Approver_Email','deliverystatuses.remarks as Remarks','deliverystatuses.delivery_status_details as Details','radius.Location_Name as Site_Name','deliveryform.DO_No','radius.Location_Name',
            'deliveryform.delivery_time as Delivery_Time','deliveryform.pick_up_time as Pickup_Time')
        ->where('deliveryform.Id', '=',$id)
        ->first();

        $driver = DB::table('deliveryform')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
        ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
        ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
        ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
        //->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
        ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
        ->leftJoin('radius','radius.Id','=','deliveryform.Location')
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
        ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
        ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
        ->select('requestor.Name as Requestor','deliveryform.delivery_date','roadtax.Vehicle_No','roadtax.Lorry_Size','projects.Project_Name','options.Option','driver.Name','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.PIC_Contact','deliveryform.Remarks','deliveryform.created_at as Application_Date','inventories.Item_Code','deliveryitem.Qty_request','deliveryitem.Qty_send','deliveryitem.Qty_received','options.Option','approver.Name as approverName','deliverystatuses.delivery_status',
            'requestor.Company_Email as requestorCompanyEmail','approver.Company_Email as approverEmail','deliverystatuses.remarks','deliverystatuses.delivery_status_details','radius.Location_Name as Site_Name','deliveryform.DO_No','driver.Id as driverId')
        ->where('deliveryform.Id', '=',$id)
        ->first();

        $driverlist = DB::table('deliveryform')
        ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
        ->select('roadtax.UserId','roadtax.UserId2','roadtax.UserId3')
        ->where('deliveryform.Id','=',$id)
        ->first();
        // $driverlist = DB::table('users')
        // ->select('Id')
        // ->where('Position','LIKE','%Lorry%')
        // ->get();
        // dd($driverlist);

        $NotificationSubject="";
        $emails = array();
        $notifylist=array();

        if($type == "Accepted by Admin")
        {

            // foreach($driverlist as $d)
            // {
            //     array_push($notifylist, $d->Id);
            // }
             array_push($notifylist, $driverlist->UserId);
             array_push($notifylist, $driverlist->UserId2);
             array_push($notifylist, $driverlist->UserId3);
             // dd($notifylist);
            $notify = DB::table('users')
            ->whereIn('Id',$notifylist)
            ->get();
            // dd($notify);
            $notifyplayerid=array();

            foreach ($notify as $u)
            {
                array_push($notifyplayerid,$u->Player_Id);
            }

            if($notifyplayerid)
            {
                $this->sendNewTrip($notifyplayerid);
            }
            array_push($emails,$deliverydetail->Requestor_Company_Email);
            array_push($emails,$deliverydetail->Approver_Email);

            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',69)
            ->get();

            Mail::send('emails.deliverystatus', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
            {
                array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                $emails = array_filter($emails);
                $message->to($emails)->subject($NotificationSubject);
            });

            $emails=array();
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
            $NotificationSubject == "" ? $NotificationSubject="New delivery order":$NotificationSubject;
            Mail::send('emails.warehouse', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject,$deliverydetail)
            {
                array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                $emails = array_filter($emails);
                $message->to($emails)->subject($NotificationSubject);
            });
            return 1;
        }
        else if($type == "status updated")
        {
            array_push($emails,$deliverydetail->Requestor_Company_Email);
            array_push($emails,$deliverydetail->Approver_Email);
            // foreach($driverlist as $d)
            // {
            //     array_push($notifylist, $d->Id);
            // }
            array_push($notifylist, $driverlist->UserId);
             array_push($notifylist, $driverlist->UserId2);
             array_push($notifylist, $driverlist->UserId3);
            $NotificationSubject == "" ?  $NotificationSubject="Delivery status updated ":$NotificationSubject;
            Mail::send('emails.deliverystatusupdate', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject)
            {
                array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                $emails = array_filter($emails);
                $message->to($emails)->subject($NotificationSubject);
            });

            $notify = DB::table('users')
            ->whereIn('Id',$notifylist)
            ->get();

            $notifyplayerid=array();

            foreach ($notify as $u)
            {
                array_push($notifyplayerid,$u->Player_Id);
            }

            if($notifyplayerid)
            {
                $this->sendNewTrip($notifyplayerid);
            }

            return 1;
        }
        else if($type == "status updated completed")
        {
            array_push($emails,$deliverydetail->Requestor_Company_Email);
            array_push($emails,$deliverydetail->Approver_Email);
            // array_push($notifylist,$driver->driverId);
            array_push($notifylist, $driverlist->UserId);
             array_push($notifylist, $driverlist->UserId2);
             array_push($notifylist, $driverlist->UserId3);

            $NotificationSubject == "" ?  $NotificationSubject="Delivery status updated ":$NotificationSubject;
            Mail::send('emails.deliverystatusupdate', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject)
            {
                array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                $emails = array_filter($emails);
                $message->to($emails)->subject($NotificationSubject);
            });

            // $notify = DB::table('users')
            // ->whereIn('Id',$notifylist)
            // ->get();

            // $notifyplayerid=array();

            // foreach ($notify as $u)
            // {
            //     array_push($notifyplayerid,$u->Player_Id);
            // }

            // if($notifyplayerid)
            // {
            //     $this->sendNewTrip($notifyplayerid);
            // }

            return 1;
        }
        else if($type == "status updated transfer")
        {
            array_push($emails,$deliverydetail->Requestor_Company_Email);
            array_push($emails,$deliverydetail->Approver_Email);
            // array_push($notifylist,$driver->driverId);
            array_push($notifylist, $driverlist->UserId);
             array_push($notifylist, $driverlist->UserId2);
             array_push($notifylist, $driverlist->UserId3);

            $NotificationSubject == "" ?  $NotificationSubject="Delivery status updated ":$NotificationSubject;
            Mail::send('emails.deliverystatusupdate', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject)
            {
                array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                $emails = array_filter($emails);
                $message->to($emails)->subject($NotificationSubject);
            });

            // $notify = DB::table('users')
            // ->whereIn('Id',$notifylist)
            // ->get();

            // $notifyplayerid=array();

            // foreach ($notify as $u)
            // {
            //     array_push($notifyplayerid,$u->Player_Id);
            // }

            // if($notifyplayerid)
            // {
            //     $this->sendNewTrip($notifyplayerid);
            // }

            return 1;
        }
        else if($type == "Accepted by Warehouse")
        {

            array_push($emails,$deliverydetail->Requestor_Company_Email);
            array_push($emails,$deliverydetail->Approver_Email);

            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',69)
            ->get();

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

            $NotificationSubject == "" ?  $NotificationSubject="Delivery status updated ":$NotificationSubject;
            Mail::send('emails.deliverystatusupdate', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$me,$NotificationSubject)
            {
                array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                $emails = array_filter($emails);
                $message->to($emails)->subject($NotificationSubject);
            });

            return 1;
        }
        else if($type == "proceed")
        {
            array_push($emails,$deliverydetail->Requestor_Company_Email);
            array_push($emails,$deliverydetail->Approver_Email);
            // array_push($notifylist,$driver->driverId);
            array_push($notifylist, $driverlist->UserId);
             array_push($notifylist, $driverlist->UserId2);
             array_push($notifylist, $driverlist->UserId3);

            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',73)
            ->get();

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

            $NotificationSubject == "" ? $NotificationSubject="Delivery status updated":$NotificationSubject;
            Mail::send('emails.deliverystatusupdate', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject)
            {
                array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                $emails = array_filter($emails);
                $message->to($emails)->subject($NotificationSubject);
            });

            $notify = DB::table('users')
            ->whereIn('Id',$notifylist)
            ->get();

            $notifyplayerid=array();

            foreach ($notify as $u)
            {
                array_push($notifyplayerid,$u->Player_Id);
            }

            if($notifyplayerid)
            {
                $this->sendNewTrip($notifyplayerid);
            }

            return 1;
        }
        else if($type == "rejected")
        {
            array_push($emails,$deliverydetail->Requestor_Company_Email);
            array_push($emails,$deliverydetail->Approver_Email);
            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',75)
            ->get();

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

            $NotificationSubject == "" ?  $NotificationSubject="Rejected Delivery ":$NotificationSubject;
            Mail::send('emails.deliveryreject', ['deliverydetail' => $deliverydetail], function($message) use ($emails,$NotificationSubject)
            {
                array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                $emails = array_filter($emails);
                $message->to($emails)->subject($NotificationSubject);
            });
            return 1;
        }
        else if($type == "insufficient")
        {
            $items=DB::table('deliveryitem')
            ->leftjoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
            ->select('inventories.Remark','inventories.Description','inventories.Categories','inventories.Item_Code','inventories.Unit','deliveryitem.Qty_request')
            ->where('deliveryitem.available','<>',1)
            ->where('deliveryitem.formId','=',$id)
            ->get();

            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',73)
            ->get();
            array_push($emails,$deliverydetail->Requestor_Company_Email);
            array_push($emails,$deliverydetail->Approver_Email);
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
            $NotificationSubject == "" ? $NotificationSubject="Insufficient Stock":$NotificationSubject=$NotificationSubject;
            Mail::send('emails.insufficient', ['deliverydetail' => $deliverydetail,'items'=>$items], function($message) use ($emails,$NotificationSubject)
            {
                array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                $emails = array_filter($emails);
                $message->to($emails)->subject($NotificationSubject);
            });
            return 1;
        }
        return 1;
    }

    public function getDeliveryItem(Request $request)
    {
        return response()->json(['item'=>DB::table('deliveryitem')
        ->leftjoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
        ->where('deliveryitem.Id','=',$request->id)->first()]);
    }

    public function uploadDeliveryImage(Request $request)
    {
        $input=$request->all();
        $formid=$input['form_id'];
        $uploadcount=1;
        if($input['status_id'] == null){
            $statusid=DB::table('deliverystatuses')
            ->insertGetId([
                'deliveryform_Id'=>$formid,
                'user_Id'=>$input['userid'],
                'delivery_status'=>"Completed",
                "delivery_status_details"=>"Updated by Admin",
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
        }
        if($request->hasFile('file')){
            foreach($request->file('file') as $file)
            {
                $name=$file->getClientOriginalName();
                $destinationPath=public_path()."/private/upload/Delivery";
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_".$uploadcount.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $uploadcount+=1;
                DB::table('files')->insert([
                    'TargetId'=>$input['status_id']==null? $statusid:$input['status_id'],
                    'File_Name' => $originalName,
                    'File_Size'=>$fileSize,
                    'Web_Path' => '/private/upload/Delivery/'.$fileName,
                    'Type'=>'Delivery Files'
                ]);
            }
            return 1;
        }
        else return 0;
    }

    public function updateDeliveryItem(Request $request)
    {
        $input=$request->all();
        if(isset($input['data'][0]['edit']))
        {
            foreach($input['data'][0]['edit'] as $edit)
            {

                DB::table('deliveryitem')
                ->where('Id','=',$edit['id'])
                ->update([
                    'inventoryId'=>$edit['inventoryId'],
                    'Qty_request'=>$edit['Qty_request'],
                    'Qty_send'=>$edit['Qty_send'],
                    'Qty_received'=>$edit['Qty_send'],
                ]);
            }
        }
        if(isset($input['data'][0]['new']))
        {
            foreach($input['data'][0]['new'] as $insert)
            {
                DB::table('deliveryitem')
                ->insert([
                    'formId'=>$insert['formId'],
                    'inventoryId'=>$insert['inventoryId'],
                    'Qty_request'=>$insert['qty_request'],
                    'Qty_send'=>$insert['qty_send'],
                    'Qty_received'=>$insert['qty_send'],
                ]);
            }
        }
        $status_id=DB::table('deliverystatuses')
        ->insertGetId([
            'delivery_status'=>'Completed',
            'deliveryform_Id'=>$input['formid'],
            'user_Id'=>$input['userid'],
            'delivery_status_details'=>"Updated by Admin",
            'updated_at'=>Carbon::now(),
            'created_at'=>Carbon::now()
        ]);

        return response()->json([
            'status_id'=>$status_id,
            'response'=>1
        ]);
    }

	public function deliveryDetails($id)
	{

        $me = (new CommonController)->get_current_user();

        $detail=DB::table("deliveryform")
        ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftjoin('users','deliveryform.DriverId','=','users.Id')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        ->leftjoin('projects','deliveryform.ProjectId','=','projects.Id')
        ->leftjoin('users as requestor','deliveryform.RequestorId','=','requestor.Id')
        ->leftjoin('companies','deliveryform.company_id','=','companies.Id')
        ->leftjoin('companies as client','deliveryform.client','=','client.Id')
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
        ->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->leftjoin('options','deliveryform.Purpose','=','options.Id')
        ->select("deliveryform.Id",'deliveryform.trip',"roadtax.Vehicle_No","deliveryform.DO_No","deliveryform.delivery_date","deliveryform.delivery_time",
            "users.Name","deliveryform.DriverId","deliveryform.PIC_Name","deliveryform.PIC_Contact","deliveryform.Remarks","requestor.Name as requestorName"
            ,"projects.Project_Name","radius.Latitude","radius.Longitude",'deliverystatuses.delivery_status','deliverystatuses.delivery_status_details',
            'options.Option','companies.Company_Name','companies.Id as companyId','companies.Company_Code','roadtax.Id as roadtaxId','radius.Location_Name','deliveryform.pick_up_time','deliveryform.pickup_date',
            'roadtax.Id as roadtax','client.Company_Name as client','deliveryform.incentive'
        )
        ->where("deliveryform.Id","=",$id)
        ->first();

        $items=DB::table('deliveryitem')
        ->leftjoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
        ->select('inventories.Description','deliveryitem.add_desc','inventories.Item_Code','inventories.Categories','inventories.Remark','inventories.Unit'
            ,'deliveryitem.Qty_request','deliveryitem.Qty_send','deliveryitem.Qty_received','deliveryitem.Purpose',
            'deliveryitem.Id','deliveryitem.available'
        )
        ->where("deliveryitem.formId",'=',$id)
        ->get();

        $project=DB::table('projects')
        ->leftjoin('deliveryform','deliveryform.ProjectId','=','projects.Id')
        ->select('projects.Id')
        ->where("deliveryform.Id",'=',$id)
        ->get();

        $log=DB::table('deliverystatuses')
        ->leftjoin('users','deliverystatuses.user_Id','=','users.Id')
        ->leftjoin('deliverytracking','deliverytracking.deliverystatus_id','=','deliverystatuses.Id')
        ->select('deliverytracking.latitude1','deliverystatuses.Id','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','users.Name','deliverystatuses.created_at')
        ->where('deliverystatuses.deliveryform_Id','=',$id)
        ->orderBy('deliverystatuses.Id')
        ->get();

        $statusid = json_decode(json_encode($log),true);
        $statusids = array();
        foreach ($statusid as $k => $v) {
            # code...
            array_push($statusids, $v['Id']);
        }

        $view=DB::table('files')
        ->select('deliverystatuses.Id')
        ->leftjoin('deliverystatuses','files.TargetId','=','deliverystatuses.Id')
        ->where('files.Type','=','Delivery Files')
        ->orWhere('files.Type','=','Delivery')
        ->whereIn('files.TargetId',$statusids)
        ->groupBy('files.TargetId')
        ->get();

        $options=DB::table('inventories')
        ->select('Id','Categories','Description','Unit','Remark','Item_Code')
        ->get();

        $company=DB::table('companies')
        ->select('companies.Id','companies.Company_Name','companies.Company_Code')
        ->get();

        $lorry= DB::table('roadtax')
        ->select('roadtax.Id','roadtax.Lorry_Size','roadtax.Vehicle_No','roadtax.UserId')
        ->where("roadtax.Type",'=','CV')
        ->get();

        $driver=DB::table('users')
        ->select('Id','Name')
        ->where('Position','=','Lorry Driver')
        ->where('Resignation_Date', '=', '')
        ->get();

        $condOption=DB::table('options')
        ->select('Id','Option')
        ->where('Table','=','deliveryform')
        ->where('Field','=','Delivery Condition')
        ->get();

        $deliverycond=DB::table('deliverycondition')
        ->select('deliverycondition.Id','options.Id as optionId','options.Option')
        ->leftjoin('options','deliverycondition.options_Id','=','options.Id')
        ->where('deliverycondition.deliveryform_Id','=',$id)
        ->get();

        $noteOption=DB::table('options')
        ->select('Id','Option')
        ->where('Table','=','deliveryform')
        ->where('Field','=','Delivery Note')
        ->get();

        $note=DB::table('deliverynote')
        ->select('deliverynote.options_Id','options.Option','deliverynote.note','deliverynote.Id')
        ->leftjoin('options','deliverynote.options_Id','=','options.Id')
        ->where('deliveryform_Id','=',$id)
        ->get();
        return view("deliverydetails",['me'=>$me,'detail'=>$detail,'items'=>$items,'log'=>$log,'view'=>$view,'options'=>$options,'lorry'=>$lorry,
                'company'=>$company,'driver'=>$driver,'condOption'=>$condOption,'deliverycond'=>$deliverycond,'noteOption'=>$noteOption,
                'note'=>$note,'project'=>$project
                ]);
    }
    public function savePendingDelivery(Request $request)
    {
        $me = (new CommonController)->get_current_user();
        $now = DB::table('deliverystatuses')
        ->select('Id','delivery_status','delivery_status_details')
        ->where('deliveryform_Id','=',$request->id)
        ->orderBy('Id','DESC')
        ->first();

        // $input = $request->all();
        // dd($input);
        if($request->incentive != null)
        {
            DB::table('deliveryform')
            ->where('Id','=',$request->id)
            ->update([
                'incentive'=>$request->incentive,
            ]);
        }
        if($request->triptype != null)
        {
            DB::table('deliveryform')
            ->where('Id','=',$request->id)
            ->update([
                'trip'=>$request->triptype,
            ]);
        }

        if($request->company != null)
        {
            DB::table('deliveryform')
            ->where('Id','=',$request->id)
            ->update([
                'company_id'=>$request->company,
            ]);
        }
        if($request->vehicle != null)
        {
            DB::table('deliveryform')
            ->where('Id','=',$request->id)
            ->update([
                'roadtaxId'=>$request->vehicle,
            ]);
        }
        if($request->driver != null)
        {
            DB::table('deliveryform')
            ->where('Id','=',$request->id)
            ->update([
                'DriverId'=>$request->driver,
            ]);
        }
        if( ($request->deliverystatus == "Pending" && $request->details == "New Delivery Application") || ($request->deliverystatus == "Pending" && $request->details == "New Return Note Application") || ($request->deliverystatus == "Pending" && $request->details == "Resubmit by Requestor") || ($request->deliverystatus == "Pending" && $request->details == "Updated by Admin") )
        {
            if($request->company != null || $request->vehicle != null)
            {
            DB::table('deliverystatuses')
                ->insert([
                    'deliveryform_Id'=>$request->id,
                    'user_Id'=>$me->UserId,
                    'delivery_status'=>$request->deliverystatus,
                    'delivery_status_details'=>"Updated by Admin",
                    'created_at'=>Carbon::now()
                ]);
            return 1;
            }
        }
        else if($request->details == "Accepted by Admin" && $request->deliverystatus== "Processing")
        {
            // if($request->company != null || $request->vehicle != null)
            // {
                DB::table('deliverystatuses')
                ->insert([
                    'deliveryform_Id'=>$request->id,
                    'user_Id'=>$me->UserId,
                    'delivery_status'=>$request->deliverystatus,
                    'delivery_status_details'=>"Updated by Admin",
                    'created_at'=>Carbon::now()
                ]);
                DB::table('deliverystatuses')
                ->insert([
                    'deliveryform_Id'=>$request->id,
                    'user_Id'=>$me->UserId,
                    'delivery_status'=>$request->deliverystatus,
                    'delivery_status_details'=>"Accepted by Admin",
                    'created_at'=>Carbon::now()
                ]);
                return 1;
            // }
        }
        elseif( ($request->details == "Task Completed by Driver" || $request->details == "Final Approved by Admin"))
        {
            DB::table('deliverystatuses')
            ->where('Id','=',$now->Id)
            ->update([
                'delivery_status_details' => "Task Completed by Driver_1"
            ]);
            DB::table('deliverystatuses')
                ->insert([
                    'deliveryform_Id'=>$request->id,
                    'user_Id'=>$me->UserId,
                    'delivery_status'=>$request->deliverystatus,
                    'delivery_status_details'=>"Updated by Admin",
                    'created_at'=>Carbon::now()
                ]);
                DB::table('deliverystatuses')
                ->insert([
                    'deliveryform_Id'=>$request->id,
                    'user_Id'=>$me->UserId,
                    'delivery_status'=>$request->deliverystatus,
                    'delivery_status_details'=>$request->details,
                    'created_at'=>Carbon::now()
                ]);
                return 1;
        }
        elseif(($request->details != "Accepted by Admin" && $request->deliverystatus!= "Processing") || ($request->deliverystatus!= "Pending"))
        {
            // if($request->company != null || $request->vehicle != null)
            // {
                DB::table('deliverystatuses')
                ->insert([
                    'deliveryform_Id'=>$request->id,
                    'user_Id'=>$me->UserId,
                    'delivery_status'=>$request->deliverystatus,
                    'delivery_status_details'=>"Updated by Admin",
                    'created_at'=>Carbon::now()
                ]);
                DB::table('deliverystatuses')
                ->insert([
                    'deliveryform_Id'=>$request->id,
                    'user_Id'=>$me->UserId,
                    'delivery_status'=>$request->deliverystatus,
                    'delivery_status_details'=>"Accepted by Admin",
                    'created_at'=>Carbon::now()
                ]);
                DB::table('deliverystatuses')
                ->insert([
                    'deliveryform_Id'=>$request->id,
                    'user_Id'=>$me->UserId,
                    'delivery_status'=>$request->deliverystatus,
                    'delivery_status_details'=>$request->details,
                    'created_at'=>Carbon::now()
                ]);
                return 1;
            // }
        }

    }
    public function trackingphoto($statusid=null)
    {
        // $input=$request->all();
        $img=DB::table('files')
        ->select('files.Web_Path','files.Id')
        ->leftjoin('deliverystatuses','files.TargetId','=','deliverystatuses.Id')
        ->where('deliverystatuses.Id','=',$statusid)
        ->where('files.Type','Like','Delivery%')
        //->whereRaw('files.Type = "Delivery Files" or files.Type = "Delivery"')
        ->get();
        return response()->json([
            'img'=>$img
        ]);
    }

    public function getDeliveryDetails(Request $request)
    {
        $input=$request->all();
        $img=DB::table('files')
        ->select('files.Web_Path','files.Id')
        ->leftjoin('deliverystatuses','files.TargetId','=','deliverystatuses.Id')
        ->where('deliverystatuses.Id','=',$input['statusid'])
        ->where('files.Type','Like','Delivery%')
        //->whereRaw('files.Type = "Delivery Files" or files.Type = "Delivery"')
        ->get();

        $details=DB::table('deliverytracking')
        ->where('deliverystatus_id','=',$input['statusid'])
        ->where('deliveryform_id','=',$input['id'])
        ->get();

        return response()->json([
            'img'=>$img,
            'details'=>$details
        ]);
    }
	public function deliveryapproval($start=null,$end=null)
	{
        $me = (new CommonController)->get_current_user();
        if ($start==null)
        {
            $start=date('d-M-Y', strtotime('first day of last month'));
            // $start=date('d-M-Y', strtotime($start,' +16 days'));
            // $start = date('d-M-Y', strtotime($start . " +20 days"));
        }
        if ($end==null)
        {
            $end=date('d-M-Y', strtotime('last day of this month'));
            // $end = date('d-M-Y', strtotime($end . " +19 days"));
            // $end = date('d-M-Y', strtotime("today"));
        }
        // dd($start,$end);
        $holidays = DB::table('holidays')
        ->select('holidays.Id','holidays.Holiday','holidays.Start_Date','holidays.End_Date','holidays.State','holidays.Country')
        ->whereRaw('right(Start_Date,4)='.date('Y'))
        ->orderBy('holidays.Start_Date','asc')
        ->get();

  //       $pending=db::table('deliveryform')
  //       ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
  //       ->leftJoin('options','options.Id','=','deliveryform.Purpose')
  //       ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
		// ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
  //       ->leftjoin("users as driver","deliveryform.DriverId","=","driver.Id")
  //       ->leftjoin("users as requestor","deliveryform.requestorId","=","requestor.Id")
  //       //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
  //       ->leftjoin('radius','deliveryform.Location','=','radius.Id')
  //       ->select('deliveryform.Id','deliveryform.DO_No','roadtax.Vehicle_No','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','radius.Location_Name','radius.Latitude','radius.Longitude','options.Option as Purpose','requestor.Name as requestorname','deliveryform.created_at')
  //       ->where("deliverystatuses.delivery_status","=","Pending")
  //       ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(deliveryform.delivery_date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
  //       ->get();

		$processing=DB::table('deliveryform')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        ->leftjoin("users as driver","deliveryform.DriverId","=","driver.Id")
        ->leftjoin("users as requestor","deliveryform.requestorId","=","requestor.Id")
            ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->leftJoin('tracker',function($join)
        {
            $join->on(DB::raw('CONCAT("(",tracker.Project_Code,")")'),'LIKE','radius.Location_Name');
        })
        ->select('deliveryform.Id','deliveryform.DO_No','roadtax.Vehicle_No','driver.Name as Name','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','radius.Location_Name','radius.Latitude','radius.Longitude','tracker.State','options.Option as Purpose','requestor.Name as requestorname','deliveryform.created_at','deliverystatuses.delivery_status_details','deliveryform.trip')
        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(deliveryform.delivery_date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
		->where("deliverystatuses.delivery_status","=","Processing")
        ->groupby('deliveryform.DO_No')
		->get();

		$accepted=DB::table('deliveryform')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        ->leftjoin("users as requestor","deliveryform.requestorId","=","requestor.Id")
            ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        ->leftjoin("users as driver","deliveryform.DriverId","=","driver.Id")
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
		->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->leftJoin('tracker',function($join)
        {
            $join->on(DB::raw('CONCAT("(",tracker.Project_Code,")")'),'LIKE','radius.Location_Name');
        })
        ->select('deliveryform.Id','deliveryform.DO_No','roadtax.Vehicle_No','driver.Name as Name','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','radius.Location_Name','radius.Latitude','radius.Longitude','tracker.State','options.Option as Purpose','requestor.Name as requestorname','deliveryform.created_at','deliverystatuses.delivery_status_details','deliveryform.trip')
        // ->select('deliveryform.Id',"deliveryform.DO_No","roadtax.Vehicle_No",'driver.Name as Name',"deliveryform.delivery_date","deliveryform.delivery_time",'deliveryform.pick_up_time','radius.Location_Name',"radius.Latitude","radius.Longitude",'requestor.Name as requestorname','deliverystatuses.delivery_status_details','options.Option as Purpose','deliverystatuses.created_at')
        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(deliveryform.delivery_date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
		->where("deliverystatuses.delivery_status","=","Accepted")
        ->groupby('deliveryform.DO_No')
		->get();


		$recalled=DB::table('deliveryform')
            ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
		// ->leftjoin("roadtax","deliveryform.DriverId","=","roadtax.UserId")
        // ->leftjoin("users","deliveryform.DriverId","=","users.Id")
        ->leftjoin("users as driver","deliveryform.requestorId","=","driver.Id")
        ->leftjoin("users as requestor","deliveryform.DriverId","=","requestor.Id")
        // ->leftjoin("users as driver","deliveryform.DriverId","=","driver.Id")
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
		->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->select('deliveryform.Id','deliveryform.DO_No','roadtax.Vehicle_No','driver.Name as Name','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','radius.Location_Name','radius.Latitude','radius.Longitude','options.Option as Purpose','requestor.Name as requestorname','deliveryform.created_at','deliverystatuses.delivery_status_details')
        // ->select('deliveryform.Id',"deliveryform.DO_No","roadtax.Vehicle_No",'driver.Name as Name',"deliveryform.delivery_date","deliveryform.delivery_time",'deliveryform.pick_up_time','radius.Location_Name',"radius.Latitude","radius.Longitude",'requestor.Name as requestorname','deliverystatuses.delivery_status_details','deliverystatuses.created_at','options.Option as Purpose
        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(deliveryform.delivery_date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
        ->where("deliverystatuses.delivery_status","=","Recalled")
		->get();

		$completed=DB::table('deliveryform')
            ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
		// ->leftjoin("roadtax","deliveryform.DriverId","=","roadtax.UserId")
        // ->leftjoin("users","deliveryform.DriverId","=","users.Id")
        ->leftjoin("users as requestor","deliveryform.requestorId","=","requestor.Id")
        ->leftjoin("users as driver","deliveryform.DriverId","=","driver.Id")
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
		->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->leftJoin('tracker',function($join)
        {
            $join->on(DB::raw('CONCAT("(",tracker.Project_Code,")")'),'LIKE','radius.Location_Name');
        })
        ->select('deliveryform.Id','deliveryform.DO_No','roadtax.Vehicle_No','driver.Name as Name','deliveryform.delivery_date',DB::raw('(SELECT `created_at` FROM deliverystatuses WHERE (deliveryform_Id = max.deliveryform_Id AND delivery_status_details = "Task Completed by Driver") ORDER BY Id DESC limit 1) as driverdate'),'deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','radius.Location_Name','radius.Latitude','radius.Longitude','tracker.State','options.Option as Purpose','requestor.Name as requestorname','deliverystatuses.created_at','deliverystatuses.delivery_status_details','deliveryform.trip')
        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(deliveryform.delivery_date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
        ->where("deliverystatuses.delivery_status","=","Completed")
        ->whereRaw('deliveryform.DO_NO NOT LIKE BINARY "%\_R%"')
        ->groupby('deliveryform.DO_No')
		->get();

        $incomplete=DB::table('deliveryform')
            ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        ->leftjoin("users as requestor","deliveryform.requestorId","=","requestor.Id")
        ->leftjoin("users as driver","deliveryform.DriverId","=","driver.Id")
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->select('deliveryform.Id','deliveryform.DO_No','roadtax.Vehicle_No','driver.Name as Name','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','radius.Location_Name','deliverystatuses.remarks','radius.Latitude','radius.Longitude','options.Option as Purpose','requestor.Name as requestorname','deliveryform.created_at','deliverystatuses.delivery_status_details')
        // ->select('deliveryform.Id',"deliveryform.DO_No","roadtax.Vehicle_No",'driver.Name as Name',"deliveryform.delivery_date","deliveryform.delivery_time",'deliveryform.pick_up_time','radius.Location_Name',"radius.Latitude","radius.Longitude",'requestor.Name as requestorname','deliverystatuses.delivery_status_details','deliverystatuses.created_at','options.Option as Purpose')
        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(deliveryform.delivery_date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
        ->where("deliverystatuses.delivery_status","=","Incomplete")
        ->get();

         $release = DB::table('deliveryform')
        ->leftJoin('deliverystatuses', 'deliverystatuses.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
        ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
        ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
        ->leftJoin('radius','radius.Id','=','deliveryform.Location')
        ->leftJoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        ->select('deliveryform.Id','requestor.Name as requestor','driver.Name as driver','deliveryform.DO_No','deliveryform.delivery_date','deliveryform.delivery_time','roadtax.Vehicle_No','radius.Location_Name','projects.Project_Name','options.Option','deliveryform.created_at','deliverystatuses.remarks',"radius.Latitude","radius.Longitude")
        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(deliveryform.delivery_date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
        ->whereRaw("deliverystatuses.delivery_status_details = 'Driver Transfer Trip' OR deliverystatuses.delivery_status_details = 'Driver Release Trip'")
        ->groupby('deliveryform.DO_No')
        ->orderBy('deliveryform.DO_No','asc')
        ->get();

        $insufficient=DB::table('deliveryform')
        ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
		// ->leftjoin("roadtax","deliveryform.DriverId","=","roadtax.UserId")
        ->leftjoin("users as requestor","deliveryform.requestorId","=","requestor.Id")
        ->leftjoin("users as driver","deliveryform.DriverId","=","driver.Id")
        // ->leftjoin("users","deliveryform.DriverId","=","users.Id")
       // ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
		->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->select('deliveryform.Id','deliveryform.DO_No','roadtax.Vehicle_No','driver.Name as Name','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','radius.Location_Name','radius.Latitude','radius.Longitude','options.Option as Purpose','requestor.Name as requestorname','deliveryform.created_at','deliverystatuses.delivery_status_details')
        // ->select('deliveryform.Id',"deliveryform.DO_No","roadtax.Vehicle_No",'driver.Name as Name',"deliveryform.delivery_date","deliveryform.delivery_time",'deliveryform.pick_up_time','radius.Location_Name',"radius.Latitude","radius.Longitude",'requestor.Name as requestorname','deliverystatuses.delivery_status_details','options.Option as Purpose')
        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(deliveryform.delivery_date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
		->where("deliverystatuses.delivery_status","=","Insufficient Stocks")
        ->get();

        $rejected=DB::table('deliveryform')
            ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        ->leftjoin("users as requestor","deliveryform.requestorId","=","requestor.Id")
        ->leftjoin("users as driver","deliveryform.DriverId","=","driver.Id")
		// ->leftjoin("roadtax","deliveryform.DriverId","=","roadtax.UserId")
        // ->leftjoin("users","deliveryform.DriverId","=","users.Id")
       // ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
       ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
       ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->select('deliveryform.Id','deliveryform.DO_No','roadtax.Vehicle_No','driver.Name as Name','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','radius.Location_Name','radius.Latitude','radius.Longitude','options.Option as Purpose','requestor.Name as requestorname','deliverystatuses.remarks','deliverystatuses.delivery_status_details')
        // ->select('deliveryform.Id',"deliveryform.DO_No","roadtax.Vehicle_No",'driver.Name as Name',"deliveryform.delivery_date","deliveryform.delivery_time",'deliveryform.pick_up_time','radius.Location_Name',"radius.Latitude","radius.Longitude",'requestor.Name as requestorname','deliverystatuses.delivery_status_details','deliverystatuses.created_at','deliverystatuses.remarks','options.Option as Purpose')
        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(deliveryform.delivery_date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
		->where("deliverystatuses.delivery_status","=","Rejected")
        ->get();

        $cancels=DB::table('deliveryform')
            ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        ->leftjoin("users as requestor","deliveryform.requestorId","=","requestor.Id")
        ->leftjoin("users as driver","deliveryform.DriverId","=","driver.Id")
        // ->leftjoin("roadtax","deliveryform.DriverId","=","roadtax.UserId")
        // ->leftjoin("users","deliveryform.DriverId","=","users.Id")
       // ->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
       ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
       ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->select('deliveryform.Id','deliveryform.DO_No','roadtax.Vehicle_No','driver.Name as Name','deliveryform.delivery_date','deliveryform.delivery_time','radius.Location_Name','options.Option as Purpose','requestor.Name as requestorname','deliverystatuses.remarks','deliverystatuses.created_at','deliveryform.approve')
        // ->select('deliveryform.Id',"deliveryform.DO_No","roadtax.Vehicle_No",'driver.Name as Name',"deliveryform.delivery_date","deliveryform.delivery_time",'deliveryform.pick_up_time','radius.Location_Name',"radius.Latitude","radius.Longitude",'requestor.Name as requestorname','deliverystatuses.delivery_status_details','deliverystatuses.created_at','deliverystatuses.remarks','options.Option as Purpose')
        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(deliveryform.delivery_date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
        ->where("deliverystatuses.delivery_status","=","Cancelled")
        ->get();

		return view('deliveryapproval',['me' => $me,'holidays'=>$holidays,'processing'=>$processing,'accepted'=>$accepted,'recalled'=>$recalled,'completed'=>$completed,'incomplete'=>$incomplete,'rejected'=>$rejected,'insufficient'=>$insufficient,'cancels'=>$cancels,'start'=>$start,'end'=>$end,'release'=>$release]);
	}

    public function approvecancel(Request $request)
    {
        $me = (new CommonController)->get_current_user();
        $input = $request->all();
        // dd($input);

        $detail = DB::table('deliverystatuses')
        ->select('created_at','remarks')
        ->where('deliverystatuses.delivery_status','=','Cancelled')
        ->where('deliveryform_Id','=',$input['Id'])
        ->first();

        DB::table('deliveryform')
        ->where('Id','=',$input['Id'])
        ->update(['approve'=>1]);

        $id = DB::table('deliverystatuses')->insertGetId([
            'deliveryform_Id'=>$input['Id'],
            'user_Id'=> $me->UserId,
            'created_at'=>$detail->created_at,
            'updated_at'=>Carbon::now(),
            'remarks'=>$detail->remarks,
            'delivery_status'=>"Cancelled",
            'delivery_status_details' => "Cancel Request Approve by Admin"
        ]);
    }

	public function sitedeliverysummary($start=null, $end=null)
	{
		$me = (new CommonController)->get_current_user();

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of last month'));
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			// $start = date('d-M-Y', strtotime($start . " +20 days"));
		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('today'));
			// $end = date('d-M-Y', strtotime($end . " +19 days"));
		}

		$summary = DB::table('deliveryform')
        //->leftJoin('tracker','tracker.Site_Name','=','deliveryform.Location')
        ->leftJoin('radius','radius.Id','=','deliveryform.Location')
        ->select('radius.Id','radius.Location_Name','radius.Code', DB::raw('COUNT(deliveryform.delivery_date) as Visits'))
        // ->leftJoin('radius','deliveryform.LocationId','=','radius.Id')
        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(deliveryform.delivery_date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
        // ->where('deliveryform.LocationId', '!=','')
        // ->where('timesheets.Time_In', '!=','')
        // ->whereRaw('timesheets.UserId IN (select Id from users where Department="'.$dept.'")')
        // ->whereRaw($condition)
        ->orderBy('deliveryform.Location','asc')
        ->groupBy('radius.Location_Name')
        ->get();

        // dd($summary);

		return view('sitedeliverysummary', ['me' => $me, 'start' => $start, 'end' => $end, 'summary' => $summary]);
    }

    public function sitedeliverydetails($start, $end, $sitename)
    {
        $me = (new CommonController)->get_current_user();

        $summary = DB::table('deliveryform')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', 'max.maxid')
        ->leftJoin('companies', 'deliveryform.company_id', '=', 'companies.Id')
        ->leftJoin('radius', 'radius.Id', '=', 'deliveryform.Location')
        ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
        ->select('deliveryform.Id','deliveryform.DO_No','projects.Project_Name','deliveryform.delivery_date','options.Option','companies.Company_Name','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details')
        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(deliveryform.delivery_date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
        ->where('radius.Location_Name', '=', $sitename)
        ->orderBy('deliveryform.delivery_date')
        ->get();

        return view('sitedeliverydetails', ['me' => $me, 'summary'=>$summary, 'start' => $start, 'end' => $end, 'sitename' => $sitename]);
    }

    public function deliveryDetails2($id)
    {

        $me = (new CommonController)->get_current_user();

        $detail = DB::table("deliveryform")
        ->leftJoin(DB::raw('(select Max(Id) as maxid, deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'),'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        // ->leftjoin('users','deliveryform.DriverId','=','users.Id')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        ->leftjoin('projects','deliveryform.ProjectId','=','projects.Id')
        ->leftjoin('users as requestor','deliveryform.RequestorId','=','requestor.Id')
        ->leftjoin('users as driver','deliveryform.DriverId','=','driver.Id')
        ->leftjoin('companies','deliveryform.company_id','=','companies.Id')
        //->leftJoin('tracker','tracker.Site_Name','=','deliveryform.Location')
        //->leftJoin('radius','radius.Location_Name','=','tracker.Site_Name')
        ->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->select('deliveryform.Id','deliveryform.DO_No','radius.Location_Name','radius.Longitude','radius.Latitude','deliveryform.delivery_date','deliveryform.delivery_time','driver.Name as Driver_Name','driver.Id as driverId','requestor.Id as RequestorId','requestor.Name as requestorName','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.Remarks','projects.Project_Name','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','roadtax.Vehicle_No','roadtax.Lorry_Size')
        ->where("deliveryform.Id","=",$id)
        ->first();

        $items=DB::table('deliveryitem')
        //->leftjoin('options','deliveryitem.Purpose','=','options.Id')
        ->leftjoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
        ->select('inventories.Description','inventories.Item_Code','inventories.Categories','deliveryitem.remarks','inventories.Unit','deliveryitem.Qty_request','deliveryitem.Qty_received','deliveryitem.Purpose','deliveryitem.Id','deliveryitem.available')
        ->where("deliveryitem.formId",'=',$id)
        ->get();

        $log=DB::table('deliverystatuses')
        ->leftJoin('deliverytracking', 'deliverytracking.deliverystatus_id', '=', 'deliverystatuses.Id')
        ->leftjoin('users','deliverystatuses.user_Id','=','users.Id')
        ->select('deliverystatuses.Id','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','users.Name','deliverystatuses.created_at','deliverytracking.latitude1','deliverytracking.longitude1')
        ->where('deliverystatuses.deliveryform_Id','=',$id)
        ->orderBy('deliverystatuses.created_at', 'asc')
        ->get();

        $view=DB::table('files')
        ->leftJoin('deliverystatuses','deliverystatuses.deliveryform_Id','=','files.TargetId')
        ->select('deliverystatuses.delivery_status','deliverystatuses.delivery_status_details')
        ->where('files.TargetId','=',$id)
        ->get();

        $options=DB::table('inventories')
        ->get();

        return view("deliverydetails2",['me'=>$me,'detail'=>$detail,'items'=>$items,'log'=>$log,'view'=>$view,'options'=>$options]);
    }

    public function finalapprove($id)
    {
        $me = (new CommonController)->get_current_user();
                DB::table('deliverystatuses')->insert([
                "deliveryform_Id"=>$id,
                "delivery_status"=>"Completed",
                "delivery_status_details"=>"Final Approved by Admin",
                "user_Id"=>$me->UserId,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
        ]);
        $this->deliveryNotification($id,"status updated");
        return 1;
    }

    public function deliveryorder($id)
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
        return view('deliveryorder',['order'=>$order,'items'=>$items,'cond'=>$cond,'note'=>$note,'comp'=>$comp,'location1'=>$location1,'location2'=>$location2]);
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
        $link = (new ExportPDFController)->DOPdf($html,$id,$order->DO_No);

        return $link;
    }

    public function returnnote($id)
    {
        // $me = (new CommonController)->get_current_user();
        $return=DB::table('deliveryform')
        ->leftjoin('companies','deliveryform.company_id','=','companies.Id')
        ->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->leftjoin('companies as client','deliveryform.client','=','client.Id')
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Company Logo" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'companies.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid`'))
        ->select('files.Web_Path','companies.Company_Name','deliveryform.DO_No','deliveryform.delivery_date','companies.address','radius.Latitude','radius.Longitude','companies.Contact_No','companies.Fax_No','client.Contact_No as clientNum','radius.Location_Name','client.Fax_No as clientFax','client.Company_Name as clientName','client.Address as clientAddress','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.term','deliveryform.po','deliveryform.Remarks')
        ->where('deliveryform.Id','=',$id)
        ->first();

        $cond=DB::table('deliverycondition')
        ->select('options.Option')
        ->leftjoin('options','options.Id','=','deliverycondition.options_Id')
        ->where('deliveryform_Id','=',$id)
        ->get();

        $items=DB::table('deliveryitem')
        ->leftjoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
        ->where('deliveryitem.formId','=',$id)
        ->get();

        $note=DB::table('deliverynote')
        ->select('deliverynote.options_Id','options.Option','deliverynote.note')
        ->leftjoin('options','deliverynote.options_Id','=','options.Id')
        ->where('deliverynote.deliveryform_Id','=',$id)
        ->get();

        return view('returnnote',['return'=>$return,'items'=>$items,'cond'=>$cond,'note'=>$note]);
    }

	public function deliverytracking(Request $request)
	{
		$me = (new CommonController)->get_current_user();

        $do_no = $request->input('do_no');

        if($do_no != null)
        {
            $delivery = DB:: table('deliveryform')
            ->select('deliveryform.Id','deliveryform.DO_No','deliveryform.delivery_date','deliveryform.delivery_time','radius.Location_Name','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details')
            ->leftJoin('companies', 'deliveryform.company_id', '=', 'companies.Id')
            ->leftJoin('radius','deliveryform.Location','=','radius.Id')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
            ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
            ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', 'max.maxid')
            ->where('deliveryform.DO_No', 'LIKE', '%'.$do_no.'%')
            ->orderBy('deliveryform.delivery_date', 'ASC')
            ->get();
        }
        else
        {
            $delivery = [];
        }

        // dd($delivery);

		return view('deliverytracking',[ 'me' => $me, 'delivery' => $delivery, 'do_no' => $do_no ]);
	}

    public function deliverytrackingdetails($id)
    {
        $me = (new CommonController)->get_current_user();

        $deliverytrackingstatus = DB::table('deliveryform')
        ->select('deliverystatuses.delivery_status')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', 'max.maxid')
        ->where('deliveryform.Id', '=', $id)
        ->get();

        // $deliverytracking = DB::table('deliverystatuses')
        // ->leftJoin('deliverytracking', 'deliverytracking.deliverystatus_id', '=', 'deliverystatuses.Id')
        // ->leftJoin('deliveryform', 'deliverystatuses.deliveryform_Id', '=', 'deliveryform.Id')
        // ->select('deliverystatuses.Id', 'deliveryform.DO_No', DB::raw('DATE_FORMAT(deliverystatuses.created_at, "%d-%m-%Y") as delivery_date'), DB::raw('DATE_FORMAT(deliverystatuses.created_at, "%T") as delivery_time'),  'deliverytracking.longitude1', 'deliverytracking.latitude1', 'deliverystatuses.delivery_status', 'deliverystatuses.delivery_status_details')
        // ->where('deliverystatuses.deliveryform_Id', '=', $id)
        // ->orderBy('deliverystatuses.created_at', 'DESC')
        // ->get();

        $count = DB::table('deliverystatuses')
        ->where('deliverystatuses.deliveryform_Id','=',$id)
        ->select(DB::raw("count('deliverystatuses.deliveryform_Id') as measure"))
        ->get();

        $times = json_decode(json_encode($count), True);
        $history = $times[0]['measure'];

        $deliverytracking = DB::table('deliverystatuses')
        ->leftJoin('deliveryform', 'deliveryform.Id', '=', 'deliverystatuses.deliveryform_Id')
        ->leftJoin('deliverytracking', 'deliverytracking.deliverystatus_id', '=', 'deliverystatuses.Id')
        ->select('deliverystatuses.Id','deliveryform.DO_No','deliveryform.delivery_date','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliverystatuses.created_at','deliverytracking.longitude1','deliverytracking.latitude1')
        ->where('deliverystatuses.deliveryform_Id', '=', $id)
        ->orderBy('deliverystatuses.Id', 'ASC')
        ->get();
        // $deliverytracking = DB::table('deliveryform')
        // ->select('deliveryform.Id', 'deliveryform.DO_No', DB::raw('DATE_FORMAT(deliverytracking.created_at, "%d-%m-%Y") as delivery_date'), DB::raw('DATE_FORMAT(deliverytracking.created_at, "%T") as delivery_time'), 'deliverytracking.longitude1', 'deliverytracking.latitude1', 'deliverystatuses.delivery_status', 'deliverystatuses.delivery_status_details')
        // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        // ->leftJoin('deliverystatuses', 'deliverystatuses.deliveryform_Id', '=', 'deliveryform.Id')
        // ->leftjoin('deliverytracking', 'deliverytracking.deliverystatus_id', '=', 'deliverystatuses.Id')
        // ->where('deliveryform.Id', '=', $id)
        // ->where('deliverytracking.deliverystatus_id', '=', 'deliverystatuses.Id')
        // ->get();

        // dd($deliverytracking);

        // $deliverytracking = DB::table('deliverytracking')
        // ->select('deliverytracking.Id','deliverytracking.Date','deliverytracking.Time','deliverytracking.longitude','deliverytracking.latitude','deliverytracking.Activity')
        // ->orderBy('deliverytracking.Date', 'desc')
        // ->where('deliverytracking.Id', '=', $id)
        // ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="DeliveryTracking" Group By TargetId) as max'), 'max.TargetId', '=', 'deliverytracking.Id')
        // ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="DeliveryTracking"'))
        // ->get();

        // $deliverytrackingstatus = DB::table('deliverytracking')
        // ->select('deliverytracking.Id','deliverytracking.Date','deliverytracking.Time','deliverytracking.longitude','deliverytracking.latitude','deliverytracking.Activity', 'deliverystatuses.delivery_status_details')
        // ->leftjoin('deliverystatuses', 'delivery')
        // ->join(DB::raw("(SELECT MAX(Id) as maxid From deliverytracking) as max"),'max.maxid','=', 'deliverytracking.Id')
        // ->first();

        // $deliverytracking = [];
        // $category=DB::table('options')
        // ->distinct('options.Option')
        // ->select('options.Option')
        // ->where('options.Table', '=','assets')
        // ->where('options.Field', '=','DeliveryTracking')
        // ->orderBy('options.Option')
        // ->get();

        return view('deliverytrackingdetails', [ 'me' => $me, 'deliverytrackingstatus' => $deliverytrackingstatus, 'deliverytracking' => $deliverytracking]);
    }

	public function warehousechecklist($start = null, $end = null)
	{
		$me = (new CommonController)->get_current_user();

		$Date=date("d-M-Y", strtotime('+2 weeks'));
		$Today=date("d-M-Y");

		if ($start==null)
		{
			// $start=date('d-M-Y', strtotime('first day of last month'));
            // $start = date('d-M-Y', strtotime($start . " +20 days"));
            $start = date('d-M-Y', strtotime($Today."-7 days"));
        }
        else
        {
            $start=date('d-M-Y',strtotime($start));
        }

		if ($end==null)
		{
			// $end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y', strtotime($end . " +19 days"));
        }
        else
        {
            $end=date('d-M-Y',strtotime($end));
        }

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

        $data=DB::table('deliveryform')
        ->leftjoin('users as requestor','deliveryform.RequestorId','=','requestor.Id')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
		->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftjoin('users','deliveryform.DriverId','=','users.Id')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        ->leftjoin('options','deliveryform.Purpose','=','options.Id')
        ->leftJoin('radius','radius.Id','=','deliveryform.Location')
        ->select('deliveryform.Id as formid','requestor.Name as requestorName','deliveryform.Remarks','roadtax.Vehicle_No','radius.Location_Name','deliveryform.DO_No','users.Name','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','options.Option','deliveryform.ProjectId')
        // ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        // ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
        ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Admin" AND options.Option = "Delivery"))')
        ->whereNotIn('deliveryform.ProjectId',$excludeid)
        // ->where('deliverystatuses.delivery_status','=','Processing')
        // ->where('deliverystatuses.delivery_status_details','=','Accepted by Admin')
        // ->where('options.Option', '=', 'Delivery')
        // ->where('deliveryform.ProjectId', '!=', 142)
        // ->where('deliveryform.ProjectId', '!=', 143)
        ->orderBy('deliveryform.delivery_date')
        ->get();

        $accept=DB::table('deliveryform')
        ->leftjoin('users as requestor','deliveryform.RequestorId','=','requestor.Id')
        // ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        // ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('deliverystatuses', 'deliverystatuses.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('options', 'options.Id', '=','deliveryform.Purpose')
        ->leftjoin('users','deliveryform.DriverId','=','users.Id')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        ->leftJoin('radius','radius.Id','=','deliveryform.Location')
        ->select('deliveryform.Id as formid','requestor.Name as requestorName','deliveryform.Remarks','roadtax.Vehicle_No','radius.Location_Name','deliveryform.DO_No','users.Name','deliveryform.delivery_date','deliveryform.delivery_time','deliveryform.pickup_date','deliveryform.pick_up_time','options.Option')
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
        ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Accepted by Warehouse" AND options.Option = "Delivery"))')
        ->whereNotIn('deliveryform.ProjectId',$excludeid)
        // ->where('deliverystatuses.delivery_status_details','=','Accepted by Warehouse')
        ->groupBy('deliveryform.Id')
        ->orderBy('deliveryform.delivery_date')
        ->get();

        $stockin = DB::table('deliveryform')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
        // ->leftJoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
        ->leftJoin('options','options.Id','=','deliveryform.Purpose')
        ->leftJoin('radius','radius.Id','=','deliveryform.Location')
        ->leftJoin('projects','projects.Id','=','deliveryform.ProjectId')
        ->select('deliveryform.Id','deliveryform.DO_No','deliveryform.created_at as request','deliveryform.delivery_date','deliverystatuses.updated_at','radius.Location_Name','projects.Project_Name','options.Option')
        // ->where('deliverystatuses.delivery_status','LIKE','Insufficient stock')
        ->whereRaw('((deliverystatuses.delivery_status = "Processing" AND deliverystatuses.delivery_status_details = "Insufficient Stocks"))')
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
        // ->where(DB::raw('(SELECT COUNT(*) FROM deliveryitem WHERE stockin=0 AND available=0)'),'>',0)
        ->get();



        $mr=DB::Table('materialrequest')
        ->select('material.Id','material.MR_No','users.Name','projects.Project_Name','tracker.Project_Code',DB::raw('tracker.`Site Name` as sitename'),'materialstatus.Status'
        ,'material.created_at','material.Total','mrviewlog.created_at as viewon','view.Name as viewName')
        ->leftjoin('material','material.Id','=','materialrequest.MaterialId')
        ->leftjoin('tracker','tracker.Id','=','material.TrackerId')
        ->leftjoin('users','users.Id','=','material.UserId')
        ->leftjoin('projects','projects.Id','=','material.ProjectId')
        ->leftjoin(DB::raw('(select Max(Id) as maxid,MaterialId from materialstatus group by MaterialId) as max'),'max.MaterialId','=','material.id')
        ->leftjoin('materialstatus','materialstatus.Id','=','max.maxid')
        ->leftjoin(DB::raw('(Select Max(Id) as maxid,MaterialId from mrviewlog group by MaterialId) as max1'),'max1.MaterialId','=','material.Id')
        ->leftjoin('mrviewlog','mrviewlog.Id','=','max1.maxid')
        ->leftjoin('users as view','view.Id','=','mrviewlog.UserId')
        ->leftjoin('inventories','inventories.Id','=','materialrequest.InventoryId')
        ->groupBy('inventories.Type')
        ->groupBy('materialrequest.MaterialId')
        ->where('inventories.Type','MPSB')
        ->get();
        $special=DB::Table('materialrequest')
        ->select('material.Id','material.MR_No','users.Name','projects.Project_Name','tracker.Project_Code',DB::raw('tracker.`Site Name` as sitename'),'materialstatus.Status'
        ,'material.created_at','material.Total','mrviewlog.created_at as viewon','view.Name as viewName','materialrequest.DeliveryId','deliveryform.DO_No')
        ->leftjoin('material','material.Id','=','materialrequest.MaterialId')
        ->leftjoin('tracker','tracker.Id','=','material.TrackerId')
        ->leftjoin('users','users.Id','=','material.UserId')
        ->leftjoin('projects','projects.Id','=','material.ProjectId')
        ->leftjoin(DB::raw('(select Max(Id) as maxid,MaterialId from materialstatus group by MaterialId) as max'),'max.MaterialId','=','material.id')
        ->leftjoin('materialstatus','materialstatus.Id','=','max.maxid')
        ->leftjoin(DB::raw('(Select Max(Id) as maxid,MaterialId from mrviewlog group by MaterialId) as max1'),'max1.MaterialId','=','material.Id')
        ->leftjoin('mrviewlog','mrviewlog.Id','=','max1.maxid')
        ->leftjoin('users as view','view.Id','=','mrviewlog.UserId')
        ->leftjoin('inventories','inventories.Id','=','materialrequest.InventoryId')
        ->leftjoin('deliveryform','deliveryform.Id','=','materialrequest.DeliveryId')
        ->leftjoin(DB::raw('(Select Max(Id) as maxid,deliveryform_Id from deliverystatuses group by deliveryform_Id) as max2'),'max2.deliveryform_Id','=','deliveryform.Id')
        ->leftJoin('deliverystatuses','deliverystatuses.Id','=','max2.maxid')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        ->groupBy('inventories.Type')
        ->groupBy('materialrequest.DeliveryId')
        ->where('inventories.Type','MPSB')
        ->where('deliverystatuses.delivery_status_details','=','-')
        // ->where('roadtax.Type','=','TRUCK')
        ->get();
        return view('warehousechecklist', ['me' => $me, 'start' => $start, 'end' => $end,'data'=>$data,'accept'=>$accept,'stockin'=>$stockin,'mr'=>$mr,
        'special'=>$special]);
	}

    public function warehousestockin($itemId, $formId)
    {
        $me = (new CommonController)->get_current_user();

        $itemstatus = DB::table('deliveryitem')
        ->select('stockin')
        ->where('Id','=',$itemId)
        ->first();

        if($itemstatus->stockin == 0)
        {
        DB::table('deliveryitem')
                        ->where('Id','=',$itemId)
                        ->update([
                            'stockin' => 1
                    ]);
        }
        $allitem = DB::table('deliveryitem')
        ->select('stockin')
        ->where('formId','=',$formId)
        ->where('available','<>',1)
        ->get();
        // dd($allitem);
        $stockcheck = json_decode(json_encode($allitem),true);

        for($i=0; $i<count($stockcheck); $i++)
        {
            if($stockcheck[$i]['stockin']==1)
            {
                $check = 1;
            }
            else
            {
                $check = 0;
            }
        }

        // dd($check);

        if($check==1)
        {
        $id = DB::table('deliverystatuses')
        ->insertGetId([
            'deliveryform_Id' => $formId,
            'delivery_status' => "Stocks Update",
            'delivery_status_details' => "Stocks Update by Warehouse",
            'updated_at'=>Carbon::now(),
            'created_at'=>Carbon::now(),
            'user_Id' => $me->UserId
        ]);
        }
        else
        {
            $id=0;
        }

        $deliverydetail = DB::table('deliveryform')
            ->leftJoin('deliverystatuses', 'deliveryform.Id', '=', 'deliverystatuses.deliveryform_Id')
            ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
            ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
            ->leftJoin('users as approver', 'deliverystatuses.user_Id', '=', 'approver.Id')
            ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
            // ->leftJoin('visitstatus','visitstatus.Id','=','deliveryform.VisitStatus')
            ->leftJoin('deliveryitem','deliveryitem.formId','=','deliveryform.Id')
            ->leftJoin('options','options.Id','=','deliveryitem.Purpose')
            ->leftJoin('radius','radius.Id','=','deliveryform.Location')
            ->leftJoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
            ->leftJoin('projects', 'deliveryform.ProjectId', '=', 'projects.Id')
            // ->leftJoin('deliveryitem','','=','deliveryform.Id')
            ->select('approver.Name as Approver','requestor.Name as Requestor','deliveryform.delivery_date as Delivery_Date','deliverystatuses.delivery_status as Status','radius.Location_Name as Site','projects.Project_Name','deliveryform.Remarks','deliveryform.created_at as Application_Date','options.Option as Purpose')
            ->orderBy('deliverystatuses.Id','desc')
            ->where('deliveryform.Id', '=',$formId)
            ->first();
        $requestor = DB::table('deliveryform')
        ->select('RequestorId')
        ->where('Id','=',$formId)
        ->first();

        if ($id>0)
            {

                $notify = DB::table('users')
                ->whereIn('Id', [$me->UserId, $requestor->RequestorId])
                ->get();

                 $subscribers = DB::table('notificationtype')
                ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
                ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                ->where('notificationtype.Id','=',79)
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

                $deliveryitemlist = DB::table('deliveryitem')
                ->leftJoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
                ->select('inventories.Item_Code','inventories.Description','inventories.Unit','deliveryitem.Qty_request')
                ->where('deliveryitem.formId', $formId)
                ->get();

                Mail::send('emails.deliverystockin', ['deliverydetail' => $deliverydetail,'deliveryitemlist' =>$deliveryitemlist], function($message) use ($emails,$me,$NotificationSubject)
                {
                        array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        $emails = array_filter($emails);
                        $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
                });


                return 1;
        }

            return 0;
    }


	public function requestorkpi($start = null, $end = null, $id =null)
	{
		$me = (new CommonController)->get_current_user();
        $Date=date("d-M-Y", strtotime('+2 weeks'));
        $Today=date("d-M-Y");

        if ($start==null)
        {
            $start=date('d-M-Y', strtotime('first day of last month'));
            // $start=date('d-M-Y', strtotime($start,' +16 days'));
            $start = date('d-M-Y', strtotime($start . " +20 days"));
        }
        if ($end==null)
        {
            $end=date('d-M-Y', strtotime('first day of this month'));
            $end = date('d-M-Y', strtotime($end . " +19 days"));
        }

        $year = date('Y');

        $years= DB::select("
            SELECT Year(Now())-1 as yearname UNION ALL
            SELECT Year(Now()) UNION ALL
            SELECT Year(Now())+1
        ");

        $requestor = DB::table('deliveryform')
        ->leftJoin('users','deliveryform.RequestorId','=','users.Id')
        ->select('users.Name','users.Id')
        ->groupby('users.Id')
        ->get();

        if($id==null)
        {
            $recalltime = DB::table('deliverystatuses')
            ->leftJoin('deliveryform','deliverystatuses.deliveryform_Id','=','deliveryform.Id')
            ->leftJoin('users','users.Id','=','deliveryform.RequestorId')
            // ->where('deliveryform.RequestorId','=',$id)
            ->where('deliverystatuses.delivery_status','=','Recalled')
            ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
            ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
            ->select('deliveryform.RequestorId','users.Name',DB::raw('count(deliverystatuses.deliveryform_Id) as recallcount'))
            ->get();

            $details = DB::table('deliveryform')
            ->leftJoin('users','users.Id','=','deliveryform.RequestorId')
            ->select('deliveryform.RequestorId','users.Name',DB::raw('count(deliveryform.Id) as requestcount'))
            ->groupby('deliveryform.RequestorId')
            ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
            ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
            // ->where('deliveryform.RequestorId','=',$id)
            ->get();
        }
        else
        {
            // $requesttime = "";
            $recalltime = DB::table('deliverystatuses')
            ->leftJoin('deliveryform','deliverystatuses.deliveryform_Id','=','deliveryform.Id')
            ->leftJoin('users','users.Id','=','deliveryform.RequestorId')
            ->where('deliveryform.RequestorId','=',$id)
            ->where('deliverystatuses.delivery_status','=','Recalled')
            ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
            ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
            ->select('deliveryform.RequestorId','users.Name',DB::raw('count(deliverystatuses.deliveryform_Id) as recallcount'))
            ->get();
            $details = DB::table('deliveryform')
            ->leftJoin('users','users.Id','=','deliveryform.RequestorId')
            ->select('deliveryform.RequestorId','users.Name', DB::raw("count(deliveryform.Id) as requestcount"))
            ->where('deliveryform.RequestorId','=',$id)
            ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
            ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
            ->get();
        }
        // dd($details);
        // $titles = "";
        $totalrequest = 0;
        $totalrecall = 0;
        foreach($details as $key => $quote){
            $g[]=$quote->Name;
            $titles = implode(',', $g);

            for($i=0;$i<count($quote);$i++)
            {
             $totalrequest = $totalrequest + $quote->requestcount;
            }
            // $h[]=$quote->requestcount;
            // $amounts = implode(',', $h);
        }
        foreach ($recalltime as $key => $value) {
            # code...
            //  $a[]=$value->Name;
            // $title = implode(',', $a);
            for($i=0;$i<count($value);$i++)
            {
             $totalrecall = $totalrecall + $value->recallcount;
            }
            // $b[]=$value->recallcount;
            // $amount = implode(',', $b);
        }
		return view('requestorkpi', ['me' => $me, 'start' => $start, 'end' => $end,'requestor'=>$requestor,'recalltime'=>$recalltime,'details'=>$details,'totalrequest'=>$totalrequest,'totalrecall'=>$totalrecall]);
	}

	public function driverkpi($start = null, $end = null)
	{
		$me = (new CommonController)->get_current_user();

		// $Date=date("d-M-Y", strtotime('+2 weeks'));
		// $Today=date("d-M-Y");

		// if ($start==null)
		// {
		// 	$start=date('d-M-Y', strtotime('first day of last month'));
		// 	// $start=date('d-M-Y', strtotime($start,' +16 days'));
		// 	$start = date('d-M-Y', strtotime($start . " +20 days"));
		// }
		// if ($end==null)
		// {
		// 	$end=date('d-M-Y', strtotime('first day of this month'));
		// 	$end = date('d-M-Y', strtotime($end . " +19 days"));
		// }

		// $year = date('Y');

		// $years= DB::select("
		// 	SELECT Year(Now())-1 as yearname UNION ALL
		// 	SELECT Year(Now()) UNION ALL
		// 	SELECT Year(Now())+1
		// ");
        // $request = DB::table('deliveryitem')
        // ->select('deliveryitem.Qty_request')
        // ->get();
        // $send = DB::table('deliveryitem')
        // ->select('deliveryitem.Qty_send')
        // ->get();
        $missedItem = DB::select("SELECT `Qty_request`-`Qty_send` as missed FROM `deliveryitem`");

        $driver = DB::table('deliveryform')
        ->leftjoin('users as driver','driver.Id','=','deliveryform.DriverId')
        ->select('deliveryform.DriverId','driver.Name')
        ->get();

        $driverdetail = DB::table('deliveryform')
        ->leftjoin('users as driver','driver.Id','=','deliveryform.DriverId')
        ->leftjoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
        // ->leftjoin('radius','radius.Id','=','deliveryform.LocationId')
        ->select('deliveryform.DO_No','deliveryform.DriverId','driver.Name','deliveryform.delivery_date','roadtax.Vehicle_No','deliveryform.Location')
        ->orderBy('deliveryform.DO_No','asc')
        ->get();

		return view('driverkpi', ['me' => $me, 'missedItem'=>$missedItem, 'driverdetail'=>$driverdetail,'driver'=> $driver]);
	}

	public function warehousekpi($start = null, $end = null)
	{
		$me = (new CommonController)->get_current_user();

		$Date=date("d-M-Y", strtotime('+2 weeks'));
		$Today=date("d-M-Y");

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of last month'));
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			$start = date('d-M-Y', strtotime($start . " +20 days"));
		}
		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y', strtotime($end . " +19 days"));
		}

		$year = date('Y');

		$years= DB::select("
			SELECT Year(Now())-1 as yearname UNION ALL
			SELECT Year(Now()) UNION ALL
			SELECT Year(Now())+1
		");

        $admin = DB::table('deliverystatuses')
        ->select('deliveryform_Id','created_at')
        ->whereRaw('delivery_status = "Processing" AND delivery_status_details = "Accepted by Admin"')
        ->get();

        $warehouse = DB::table('deliverystatuses')
        ->select('deliveryform_Id','created_at')
        ->whereRaw('delivery_status = "Processing" AND delivery_status_details = "Accepted by Warehouse"')
        ->get();
        $avarage = array();
        $total = 0;
        $admintime = json_decode(json_encode($admin),true);
        $warehousetime = json_decode(json_encode($warehouse),true);
        foreach ($admintime as $key => $value) {
        foreach ($warehousetime as $k => $v) {
            if($admintime[$key]['deliveryform_Id'] == $warehousetime[$k]['deliveryform_Id'])
            {
                for($i=0;$i<Count($admintime);$i++)
                {
                    $ad = Carbon::parse($admintime[$key]['created_at']);
                    $wa = Carbon::parse($admintime[$k]['created_at']);
                    $diff = $wa->diff($ad)->format('%H:%I:%S');
                    // gmdate('H:i:s',$diff);
                    // dd($diff);
                    array_push($average,$diff);
                }
            }
         }
      }
        // dd($average);

		return view('warehousekpi', ['me' => $me, 'start' => $start, 'end' => $end]);
    }

    public function warehousedetails($id)
    {
        $me = (new CommonController)->get_current_user();

        $details=DB::table('deliveryform')
        ->leftjoin('users as driver','deliveryform.DriverId','=','driver.Id')
        ->leftjoin('users as requestor','deliveryform.RequestorId','=','requestor.Id')
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
        ->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->leftJoin(DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'),'max.deliveryform_Id','=','deliveryform.Id')
		->leftJoin('deliverystatuses','deliverystatuses.Id','=',DB::raw('max.`maxid`'))
        ->select('deliveryform.DO_No','deliveryform.Id','deliveryform.delivery_date','driver.Name as driverName','requestor.Name as requestorName','radius.Location_Name','roadtax.Vehicle_No','roadtax.Lorry_Size','radius.Latitude','radius.Longitude','deliveryform.Remarks','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details','deliveryform.pick_up_time','deliveryform.delivery_time')
        ->where('deliveryform.Id','=',$id)
        ->first();

        $items=DB::table('deliveryitem')
        ->leftjoin('inventories','deliveryitem.inventoryId','=','inventories.Id')
        ->leftjoin('deliveryform','deliveryform.Id','=','deliveryitem.formId')
        ->select('deliveryitem.Id','inventories.Categories','inventories.Item_Code','inventories.Description','deliveryitem.add_desc','inventories.Unit','deliveryitem.remarks','deliveryitem.Qty_request','deliveryitem.approve_qty','deliveryitem.available')
        ->where('deliveryitem.formId','=',$id)
        ->get();
        // dd($items);
        $available = DB::table('deliveryitem')
        ->where('deliveryitem.formId','=',$id)
        ->get();

        return view('warehousedetails',['me'=>$me,'details'=>$details,'items'=>$items, 'formId' => $id,'available'=>$available]);
    }

    public function warehouseaccept(Request $request)
    {
        $input=$request->all();

        $items=DB::table('deliveryitem')
        ->where('formId','=',$input['formid'])
        ->get();
        // dd($input);
        $count=0;
        // dd($input);
        if(isset($input['check']))
        {
            foreach($input['check'] as $c)
            {

                DB::table('deliveryitem')
                        ->where('Id','=',$c)
                        ->update([
                            'available' => 1
                        ]);


                // foreach($items as $item)
                // {
                //     if((int)$c == $item->Id)
                //     {
                //         $count+=1;
                //         DB::table('deliveryitem')
                //         ->where('Id','=',$item->Id)
                //         ->update([
                //             'available' => 1
                //         ]);
                //     }
                // }
            }
        }

        if(count($items) == count($input['check']))
        {
            DB::table('deliverystatuses')
            ->insert([
                'delivery_status'=>"Processing",
                'delivery_status_details'=>"Accepted by Warehouse",
                'updated_at'=>Carbon::now(),
                'created_at'=>Carbon::now(),
                'deliveryform_Id'=>$input['formid'],
                'user_Id'=>$input['userid']
            ]);
            $this->deliveryNotification($input['formid'],"status updated");
        }
        else
        {

            DB::table('deliverystatuses')
            ->insert([
                'delivery_status'=>"Processing",
                'delivery_status_details'=>"Insufficient stock",
                'updated_at'=>Carbon::now(),
                'created_at'=>Carbon::now(),
                'deliveryform_Id'=>$input['formid'],
                'user_Id'=>$input['userid']
            ]);
            $this->deliveryNotification($input['formid'],"insufficient");
        }
        return 1;
    }
     public function approvestock($itemid)
    {
        $requestqty = DB::table('deliveryitem')
        ->where('Id','=',$itemid)
        ->select('Qty_request')
        ->first();

        DB::table('deliveryitem')
                ->where('Id','=',$itemid)
                ->update([
                    'approve_qty'=>$requestqty->Qty_request,
                ]);

        return 1;
    }

    public function savePickUp(Request $request)
    {
        $me = (new CommonController)->get_current_user();

        $input=$request->all();

        $pick_up = $input['time'];

        $deliverytime = DB::table('deliveryform')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->select('deliveryform.Id','deliveryform.delivery_time','deliverystatuses.delivery_status','deliverystatuses.delivery_status_details')
        ->where('deliveryform.Id', $input['formid'])
        ->get();

        foreach ($deliverytime as $key )
        {
            if($key->delivery_time > $pick_up)
            {
                DB::table('deliveryform')
                ->where('Id','=',$input['formid'])
                ->update([
                    'pick_up_time'=>$pick_up,
                ]);

                DB::table('deliverystatuses')
                ->insert([
                    'deliveryform_Id'=>$key->Id,
                    'user_Id'=>$me->UserId,
                    'delivery_status'=>$key->delivery_status,
                    'delivery_status_details'=>"Updated by Warehouse",
                    'created_at'=>Carbon::now()
                ]);

                return 1;
                // return 0;
            }
            elseif($key->delivery_time = $pick_up || $key->delivery_time < $pick_up || $pick_up == '00:00')
            {
                return 0;
            }
        }
    }
    public function DeliveryDashboard()
    {
        $me = (new CommonController)->get_current_user();

        $insufficientdelivery=DB::table('deliveryform')
		->select(DB::raw('COUNT(*) as insufficient'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
		->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->where('deliverystatuses.delivery_status','=','Insufficient Stocks')
        ->orWhereraw('deliverystatuses.delivery_status = "Processing" and deliverystatuses.delivery_status_details="Insufficient stock"')
		->get();

        $pendingNum=DB::table('deliveryform')
		->select(DB::raw('COUNT(*) as pending'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
		->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('deliverystatuses.delivery_status','=','Pending')
        ->get();

        $completedNum=DB::table('deliveryform')
		->select(DB::raw('COUNT(*) as completed'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
		->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->where('deliverystatuses.delivery_status','=','Completed')
        ->where('deliverystatuses.delivery_status_details','=','Task Completed by Driver')
        ->get();


		$finaldelivery=DB::table('deliveryform')
		->select(DB::raw('COUNT(*) as finalappr'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
		->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('deliverystatuses.delivery_status','=','Completed')
        ->get();

        $rejected=DB::table('deliveryform')
		->select(DB::raw('COUNT(*) as rejected'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
		->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('deliverystatuses.delivery_status','=','Rejected')
        ->get();

        $pending=DB::table('deliveryform')
		->leftjoin("roadtax","deliveryform.roadtaxId","=","roadtax.Id")
        ->leftjoin("users","deliveryform.DriverId","=","users.Id")
        ->leftjoin('radius','deliveryform.Location','=','radius.Id')
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->select("deliveryform.DO_No","roadtax.Vehicle_No","roadtax.Lorry_Size","users.Name","deliveryform.delivery_date","deliveryform.Id","deliverystatuses.delivery_status"
            ,"radius.Latitude","radius.Longitude",'deliverystatuses.delivery_status_details'
        )
		->where("deliverystatuses.delivery_status","=","Pending")
        ->get();

        $processing=DB::table('deliveryform')
		->leftjoin("roadtax","deliveryform.roadtaxId","=","roadtax.Id")
        ->leftjoin("users","deliveryform.DriverId","=","users.Id")
        ->leftjoin('radius','deliveryform.Location','=','radius.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->select("deliveryform.DO_No","roadtax.Vehicle_No","roadtax.Lorry_Size","users.Name","deliveryform.delivery_date","deliveryform.Id","deliverystatuses.delivery_status"
            ,"radius.Latitude","radius.Longitude",'deliverystatuses.delivery_status_details'
        )
		->where("deliverystatuses.delivery_status","=","Processing")
        ->get();

        $accepted=DB::table('deliveryform')
		->leftjoin("roadtax","deliveryform.roadtaxId","=","roadtax.Id")
        ->leftjoin("users","deliveryform.DriverId","=","users.Id")
        ->leftjoin('radius','deliveryform.Location','=','radius.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->select("deliveryform.DO_No","roadtax.Vehicle_No","roadtax.Lorry_Size","users.Name","deliveryform.delivery_date","deliveryform.Id","deliverystatuses.delivery_status"
            ,"radius.Latitude","radius.Longitude",'deliverystatuses.delivery_status_details'
        )
		->where("deliverystatuses.delivery_status","=","Accepted")
        ->get();

        $end=date('d-M-Y', strtotime('today'));
        $end = date('d-M-Y', strtotime($end . " -3 days"));
        //dd($end);
        $overdue=DB::table('deliveryform')
        ->select(DB::raw('COUNT(*) as overdue'))
        ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->whereRaw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y") <= str_to_date("'.$end.'","%d-%M-%Y")')
        ->where('deliverystatuses.delivery_status','<>','Completed')
        //->where('deliverystatuses.delivery_status','<>','Rejected')
        ->get();

        return view('deliverydashboard',['me'=>$me,'rejected'=>$rejected,'insufficientdelivery'=>$insufficientdelivery,'finaldelivery'=>$finaldelivery,'pending'=>$pending,
            'processing'=>$processing,'accepted'=>$accepted,'pendingNum'=>$pendingNum,'completedNum'=>$completedNum,'overdue'=>$overdue
        ]);
    }

    function sendNewTrip($playerids)
    {

            // $me = JWTAuth::parseToken()->authenticate();
        $me = (new CommonController)->get_current_user();


            $content = array(
                "en" => 'New Trip'
            );

            $fields = array(
                'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
                // 'included_segments' => array("All"),
                'include_player_ids' =>$playerids,
                'data' => array("type" => "NewTrip"),
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

    public function saveCondition(Request $request)
    {
        if($request->condId != null )
        {
            foreach($request->condId as $cid)
            {
               $id=DB::table('deliverycondition')
                ->insertGetId([
                    'deliveryform_Id'=>$request->formid,
                    'options_Id'=>$cid['id']
                ]);
                $obj[]=[
                    'id'=>$id,
                    'option'=>$cid['option'],
                    'cid'=>$cid['id']
                ];
            }
            $val[]=[
                'type'=>'new',
                'obj'=>$obj
            ];
        }
        else{
            $val[]=[
                'type'=>'new',
                'obj'=>[]
            ];
        }
        if($request->editcond != null)
        {
            foreach($request->editcond as $edit)
            {
                DB::table('deliverycondition')
                ->where('Id','=',$edit['cid'])
                ->update([
                    'options_Id'=>$edit['id']
                ]);
                $obj1[]=[
                    'id'=>$edit['cid'],
                    'option'=>$edit['option']
                ];
            }
            $val[]=[
                'type'=>'edit',
                'obj'=>$obj1
            ];
        }else{
            $val[]=[
                'type'=>'edit',
                'obj'=>[]
            ];
        }
        if($request->remove != null)
        {
            foreach($request->remove as $remove)
            {
                DB::table('deliverycondition')
                ->where('Id','=',$remove['id'])
                ->delete();
            }
            $val[]=['type'=>'remove'];
        }
        if(count($val) != 0)
        {
            $c=DB::table('deliverycondition')
            ->select('Option')
            ->leftjoin('options','deliverycondition.options_Id','=','options.Id')
            ->where('deliverycondition.deliveryform_Id','=',$request->formid)
            ->orderBy('deliverycondition.Id','asc')
            ->get();
            $val[]=[
                'data'=>$c
            ];
            return $val;
        }
        else{return 0;}

    }
    public function insertNote(Request $request)
    {
        $store=array();
        $val=[];
        if($request->note != null)
        {
            foreach($request->note as $note)
            {
                if($note['id'] == 0)
                {
                    $id=DB::table('deliverynote')
                    ->insertGetId([
                        'note'=>$note['option'],
                        'deliveryform_Id'=>$request->formid
                    ]);
                }
                else
                {
                    $id=DB::table('deliverynote')
                    ->insertGetId([
                        'options_Id'=>$note['id'],
                        'deliveryform_Id'=>$request->formid
                    ]);
                }
                $obj[]=[
                    'id'=>$id,
                    'option'=>$note['option'],
                    'opId'=>$note['id']
                ];
            }

            array_push($val,(object)[
                'type'=>'new',
                'obj'=>$obj
            ]);
        }
        if($request->edit != null)
        {
            foreach($request->edit as $edit)
            {
                if($edit['noteid'] == 0)
                {
                    DB::table('deliverynote')
                    ->where('Id','=',$edit['id'])
                    ->update([
                        'note'=>$edit['option']
                    ]);
                }
                else
                {
                    DB::table('deliverynote')
                    ->where('Id','=',$edit['id'])
                    ->update([
                        'options_Id'=>$edit['noteid']
                    ]);
                }
            }
        }
        if($request->remove != null)
        {
            foreach($request->remove as $r)
            {
                DB::table('deliverynote')
                ->where('Id','=',$r['id'])
                ->delete();
            }
        }

        if($request->note != null || $request->remove != null || $request->edit != null)
        {
            $data=DB::table('deliverynote')
            ->select('deliverynote.Id','options.Option','deliverynote.note','deliverynote.Options_Id')
            ->leftjoin('options','deliverynote.options_Id','=','options.Id')
            ->where('deliverynote.deliveryform_Id','=',$request->formid)
            ->get();
            array_push($val,(object)[
                'type'=>'data',
                'obj'=>$data
            ]);
            return $val;
        }
        return 1;

    }
    public function saveTime(Request $request)
    {
        if($request->type == "Delivery Time")
        {
           $exist=DB::table('deliverytime')
            ->select(DB::raw('count(*) as num'))
            ->where('deliveryform_Id','=',$request->formid)
            ->where('type','=','Delivery Time')
            ->get();
            if($exist[0]->num == 0)
            {
                DB::table('deliverytime')
                ->insert([
                    'deliveryform_Id'=>$request->formid,
                    'delivery_time'=>$request->oldtime,
                    'type'=>'Delivery Time'
                ]);
            }
            DB::table('deliveryform')
            ->where('Id','=',$request->formid)
            ->update([
                'delivery_time'=>$request->newtime
            ]);
            DB::table('deliverytime')
            ->insert([
                'deliveryform_Id'=>$request->formid,
                'delivery_time'=>$request->newtime,
                'remarks'=>$request->remarks,
                'type'=>'Delivery Time'
            ]);
        }
        else if($request->type == "Pick up")
        {
            $exist=DB::table('deliverytime')
            ->select(DB::raw('count(*) as num'))
            ->where('deliveryform_Id','=',$request->formid)
            ->where('type','=','Pick up')
            ->get();
            if($exist[0]->num == 0)
            {
                DB::table('deliverytime')
                ->insert([
                    'deliveryform_Id'=>$request->formid,
                    'delivery_time'=>$request->oldtime,
                    'type'=>'Pick up'
                ]);
            }
            DB::table('deliveryform')
            ->where('Id','=',$request->formid)
            ->update([
                'pick_up_time'=>$request->newtime
            ]);
            DB::table('deliverytime')
            ->insert([
                'deliveryform_Id'=>$request->formid,
                'delivery_time'=>$request->newtime,
                'remarks'=>$request->remarks,
                'type'=>'Pick up'
            ]);
        }
        return 1;
    }
    public function timeLog(Request $request)
    {
        return DB::table('deliverytime')->where('deliveryform_Id','=',$request->formid)->where('type','=',$request->type)
        ->get();
    }
    function MR(Request $request){
        // return DB::select('SELECT  material.UserId,request.Type,material.MR_No,projects.Project_Name,material.created_at from material left join projects on projects.Id = material.ProjectId
        // left join (SELECT inventories.Type,MaterialId from materialrequest left join inventories on inventories.Id = materialrequest.InventoryId
        // where inventories.Type="MPSB"  group by materialrequest.MaterialId) as request on request.MaterialId = material.Id
        // where material.UserId = '.$id.'');
        $me=(new CommonController)->get_current_user();
        $arr="";
        if($request->arr){
            $arr="AND materialrequest.Id NOT IN ('".implode("','",$request->arr)."')";
        }
        $site = trim($request->site, '()');
        return DB::select('SELECT (Count(CASE WHEN materialrequest.DeliveryId = 0 OR deliverystatuses.delivery_status = "Cancelled" OR 
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
        GROUP BY
            materialrequest.MaterialId');

    }
    function MPSBItem(Request $request){
        $arr="";
        if($request->arr){
            $arr="AND materialrequest.Id NOT IN ('".implode("','",$request->arr)."')";
        }
        return DB::select('SELECT materialrequest.InventoryId, inventories.Item_Code,materialrequest.Id,inventories.Description,materialrequest.Qty,
            inventories.Unit
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
            inventories.Type = "MPSB" AND materialrequest.MaterialId='.$request->id.'  AND
            (deliveryform.Id IS NULL OR deliveryitem.Id is null OR  materialrequest.DeliveryId = 0 OR deliverystatuses.delivery_status = "Cancelled" OR deliverystatuses.delivery_status= "Incomplete")'.$arr);

    }
    function updateViewLog(Request $request){
        $me=(new CommonController)->get_current_user();
        $val=DB::table('mrviewlog')
        ->insert([
            'UserId'=>$me->UserId,
            'MaterialId'=>$request->id
        ]);
        return 1;
    }
    function ViewLog(Request $request){
        return DB::table('mrviewlog')
        ->leftjoin('users','users.Id','=','mrviewlog.UserId')
        ->where('mrviewlog.Id',DB::raw('(Select Max(Id) from mrviewlog groupby UserId'))
        ->groupBy('mrviewlog.UserId')
        ->get();
    }
    function materialList($id){
        $order=DB::table('deliveryform')
        ->leftjoin('radius','deliveryform.Location','=','radius.Id')
        ->leftjoin('companies','deliveryform.company_id','=','companies.Id')
        ->leftjoin('companies as client','deliveryform.client','=','client.Id')
        //->leftjoin('tracker','deliveryform.ProjectId','=','tracker.ProjectID')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Company Logo" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'companies.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid`'))
        ->leftjoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
        ->select('roadtax.Type','files.Web_Path','companies.Company_Name','deliveryform.DO_No','deliveryform.delivery_date','companies.address','companies.Contact_No as Company_No','companies.Fax_No','radius.Latitude','radius.Longitude','client.Company_Name as clientName','radius.Location_Name','client.Contact_No as clientNum','client.Fax_No as clientFax','client.Address as clientAddress','companies.Contact_No','deliveryform.PIC_Name','deliveryform.PIC_Contact','deliveryform.po','deliveryform.term','deliveryform.Remarks')
        ->where('deliveryform.Id','=',$id)
        ->first();

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
        return view('materiallist',['order'=>$order,'items'=>$items,'cond'=>$cond,'note'=>$note,'comp'=>$comp]);
    }
}
