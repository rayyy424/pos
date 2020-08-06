<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Excel;

use File;
use Input;
use App;

class POController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index($projectcode=null,$workorderid=null,$projectid=null,$type=null,$template=null)
    {
		$me = (new CommonController)->get_current_user();

		$projectids = explode("|",$me->ProjectIds);

		if($projectcode=="null")
		{
			$projectcode=null;
		}

		if($workorderid=="null")
		{
			$workorderid=null;
		}

		$projects = DB::table('projects')
		->whereIn('Id',$projectids)
		->get();

		if($projectid==null)
		{
			$projectid=0;
			// if($projects)
			// {
			// 	$projectid=$projects[0]->Id;
			// }
		}

		if($type=="No Project PO")
		{
			$projectid=0;
		}

		if($projectcode==null)
		{

			$po=DB::table('po')
			->select(
				'po.Id' ,
				'po.Huawei_ID' ,
				'projects.Project_Name' ,
				'po.Project' ,
				'po.Project_Code' ,
				'po.PO_Status' ,
				'po.Status' ,
				'po.ROR_Status' ,
				'po.ProjectCode' ,
				'po.PO_No' ,
				'po.PR_No' ,
				'po.Cut' ,
				'po.PO_Line_No' ,
				'po.Shipment_Num' ,
				'po.Item_Code' ,
				'po.Credit_Note' ,

				'po.PO_Date' ,
				'po.PO_Type' ,

				'po.PO_Description' ,
				'po.Scope_of_Work' ,
				'po.Item_Description' ,
				'po.Company' ,
				'po.Work_Order_ID' ,
				'po.Site_ID' ,
				'po.Site_Code' ,
	      'po.Site_Name' ,
				'po.Region' ,
				'po.Payment_Term' ,
				'po.Payment_Method' ,

				'po.Engineering_No' ,

				'po.Center_Area' ,
				'po.Due_Quantity' ,
				'po.Quantity_Request' ,
				'po.Unit' ,
	      'po.Unit_Price' ,
				'po.Amount' ,
				'po.Amount_With_GST' ,
	      'po.Line_Account' ,
				'po.Start_Date' ,
				'po.End_Date' ,
				'po.Acceptance_Date' ,
				'po.Vendor_Code' ,
				'po.Vendor_Name' ,
				'po.Sub_Contract_No' ,

				'po.ESAR_Document_Submitted_Date' ,
				'po.ESAR_Date' ,
	      'po.ESAR_Status' ,
				'po.PAC_Document_Submitted_Date' ,
	      'po.PAC_Date' ,
	      'po.PAC_Status' ,

				'pm.Name as PM',
				'po.PM_Accepted_At',
				'po.PM_Status',
				'po.PM_Remarks',
				'finance.Name as Finance',
				'po.Finance_Accepted_At',
				'po.Finance_Status',
				'po.Finance_Remarks',

				'po.First_Milestone_Percentage' ,
	      'po.First_Milestone_Amount' ,
				'po.First_Milestone_Amount_With_GST' ,
	      'po.First_Milestone_Completed_Date' ,
	      'po.First_Milestone_Invoice_No' ,
				'po.First_Milestone_Invoice_Upload_Date' ,
	      'po.First_Milestone_Forecast_Invoice_Date' ,

				'po.Second_Milestone_Percentage' ,
	      'po.Second_Milestone_Amount' ,
				'po.Second_Milestone_Amount_With_GST' ,
	      'po.Second_Milestone_Completed_Date' ,
	      'po.Second_Milestone_Invoice_No' ,
				'po.Second_Milestone_Invoice_Upload_Date' ,
	      'po.Second_Milestone_Forecast_Invoice_Date' ,

				'po.Third_Milestone_Percentage' ,
	      'po.Third_Milestone_Amount' ,
				'po.Third_Milestone_Amount_With_GST' ,
	      'po.Third_Milestone_Completed_Date' ,
	      'po.Third_Milestone_Invoice_No' ,
				'po.Third_Milestone_Invoice_Upload_Date' ,
	      'po.Third_Milestone_Forecast_Invoice_Date' ,

				'po.Fourth_Milestone_Percentage' ,
				'po.Fourth_Milestone_Amount' ,
				'po.Fourth_Milestone_Amount_With_GST' ,
				'po.Fourth_Milestone_Completed_Date' ,
				'po.Fourth_Milestone_Invoice_No' ,
				'po.Fourth_Milestone_Invoice_Upload_Date' ,
				'po.Fourth_Milestone_Forecast_Invoice_Date' ,

				'po.Fifth_Milestone_Percentage' ,
				'po.Fifth_Milestone_Amount' ,
				'po.Fifth_Milestone_Amount_With_GST' ,
				'po.Fifth_Milestone_Completed_Date' ,
				'po.Fifth_Milestone_Invoice_No' ,
				'po.Fifth_Milestone_Invoice_Upload_Date' ,
				'po.Fifth_Milestone_Forecast_Invoice_Date' ,

	      'users.Name',
				'po.Remarks' )
			->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
			->leftJoin('users', 'po.created_by', '=', 'users.Id')
			->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
			->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id')
			->where('po.ProjectId','=',$projectid)
			->orderBy('po.PO_No','ASC')
			->get();

		}
		else {

			$po=DB::table('po')
			->select(
				'po.Id' ,
				'po.Huawei_ID' ,
				'projects.Project_Name' ,
				'po.Project' ,
				'po.Project_Code' ,
				'po.PO_Status' ,
				'po.Status' ,
				'po.ROR_Status' ,
				'po.ProjectCode' ,
				'po.PO_No' ,
				'po.PR_No' ,
				'po.Cut' ,
				'po.PO_Line_No' ,
				'po.Shipment_Num' ,
				'po.Item_Code' ,
				'po.Credit_Note' ,

				'po.PO_Date' ,
				'po.PO_Type' ,

				'po.PO_Description' ,
				'po.Scope_of_Work' ,
				'po.Item_Description' ,
				'po.Company' ,
				'po.Work_Order_ID' ,
				'po.Site_ID' ,
				'po.Site_Code' ,
	      'po.Site_Name' ,
				'po.Region' ,
				'po.Payment_Term' ,
				'po.Payment_Method' ,

				'po.Engineering_No' ,

				'po.Center_Area' ,
				'po.Due_Quantity' ,
				'po.Quantity_Request' ,
				'po.Unit' ,
	      'po.Unit_Price' ,
				'po.Amount' ,
				'po.Amount_With_GST' ,
	      'po.Line_Account' ,
				'po.Start_Date' ,
				'po.End_Date' ,
				'po.Acceptance_Date' ,
				'po.Vendor_Code' ,
				'po.Vendor_Name' ,
				'po.Sub_Contract_No' ,

				'po.ESAR_Document_Submitted_Date' ,
				'po.ESAR_Date' ,
	      'po.ESAR_Status' ,
				'po.PAC_Document_Submitted_Date' ,
	      'po.PAC_Date' ,
	      'po.PAC_Status' ,

				'pm.Name as PM',
				'po.PM_Accepted_At',
				'po.PM_Status',
				'po.PM_Remarks',
				'finance.Name as Finance',
				'po.Finance_Accepted_At',
				'po.Finance_Status',
				'po.Finance_Remarks',

				'po.First_Milestone_Percentage' ,
	      'po.First_Milestone_Amount' ,
				'po.First_Milestone_Amount_With_GST' ,
	      'po.First_Milestone_Completed_Date' ,
	      'po.First_Milestone_Invoice_No' ,
				'po.First_Milestone_Invoice_Upload_Date' ,
	      'po.First_Milestone_Forecast_Invoice_Date' ,

				'po.Second_Milestone_Percentage' ,
	      'po.Second_Milestone_Amount' ,
				'po.Second_Milestone_Amount_With_GST' ,
	      'po.Second_Milestone_Completed_Date' ,
	      'po.Second_Milestone_Invoice_No' ,
				'po.Second_Milestone_Invoice_Upload_Date' ,
	      'po.Second_Milestone_Forecast_Invoice_Date' ,

				'po.Third_Milestone_Percentage' ,
	      'po.Third_Milestone_Amount' ,
				'po.Third_Milestone_Amount_With_GST' ,
	      'po.Third_Milestone_Completed_Date' ,
	      'po.Third_Milestone_Invoice_No' ,
				'po.Third_Milestone_Invoice_Upload_Date' ,
	      'po.Third_Milestone_Forecast_Invoice_Date' ,

				'po.Fourth_Milestone_Percentage' ,
				'po.Fourth_Milestone_Amount' ,
				'po.Fourth_Milestone_Amount_With_GST' ,
				'po.Fourth_Milestone_Completed_Date' ,
				'po.Fourth_Milestone_Invoice_No' ,
				'po.Fourth_Milestone_Invoice_Upload_Date' ,
				'po.Fourth_Milestone_Forecast_Invoice_Date' ,

				'po.Fifth_Milestone_Percentage' ,
				'po.Fifth_Milestone_Amount' ,
				'po.Fifth_Milestone_Amount_With_GST' ,
				'po.Fifth_Milestone_Completed_Date' ,
				'po.Fifth_Milestone_Invoice_No' ,
				'po.Fifth_Milestone_Invoice_Upload_Date' ,
				'po.Fifth_Milestone_Forecast_Invoice_Date' ,

	      'users.Name',
				'po.Remarks' )
			->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
			->leftJoin('users', 'po.created_by', '=', 'users.Id')
			->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
			->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id')
			->where('po.Project_Code','=',$projectcode)
			->orderBy('po.PO_No','ASC')
			->get();

		}

		$options= DB::table('options')
		->whereIn('Table', ["purchaseorders"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$invoices = DB::table('invoices')
		->select('invoices.Id','invoices.Invoice_No','projects.Project_Name','invoices.Company','invoices.Invoice_Type','invoices.Invoice_Date','invoices.Invoice_Description','invoices.Invoice_Amount','invoices.Invoice_Status')
				->leftJoin('projects', 'invoices.ProjectId', '=', 'projects.Id')
		->where('invoices.ProjectId','=',$projectid)
		->orderBy('invoices.Invoice_No','ASC')
		->get();

		$projectcodes = DB::table('projectcodes')
		->select('projectcodes.Id','projectcodes.Project_Code','projectcodes.Site_ID')
		->where('projectcodes.ProjectId','=',$projectid)
		->orderBy('projectcodes.Project_Code','ASC')
		->get();

	if($template==null)
	{
		$template="General";
	}

	if($type==null)
	{
		$type="All PO";
	}

	if($type=="No Project PO")
	{
		$projectid=0;
	}

	$dependencyrules = DB::table('dependencyrules')
	->where('dependencyrules.ProjectId','=',$projectid)
	->get();

	$poitem=array();

	if($dependencyrules)
	{

			foreach ($dependencyrules as $rules) {
				# code...
				$condition="";
				if($rules->Column1 && $rules->Column1_Status)
				{
					if($rules->Column1_Status=="[nonempty]")
					{
						$condition=$rules->Column1."<>''";
					}
					else {
						$condition=$rules->Column1."='".$rules->Column1_Status."'";

					}

				}

				if($rules->Column2 && $rules->Column2_Status)
				{
					if($rules->Column2_Status=="[nonempty]")
					{
						$condition=$condition." AND ".$rules->Column2."<>''";
					}
					else {
						# code...
						$condition=$condition." AND ".$rules->Column2."='".$rules->Column1_Status."'";
					}

				}

				$aging=DB::select("SELECT ".$rules->PO_Column." FROM tracker WHERE ".$condition);

				foreach ($aging as $line) {
					# code...
					if($line->{$rules->PO_Column})
					{
						$split=explode(" | ",$line->{$rules->PO_Column});
						$pono=$split[0];
						if(sizeof($split)>1)
						{
							$shipment=$split[1];
							$sitename=$split[2];
						}
						else {
							# code...
							$shipment="";
							$sitename="";
						}


						$triggerpo=DB::select("SELECT Id FROM po WHERE PO_No='".$pono."' AND Shipment_Num='".$shipment."' AND Site_Name='".$sitename."' AND ".$rules->Target_Column."=''");

						if($triggerpo)
						{
							if(!in_array($triggerpo[0]->Id,$poitem))
							{
								array_push($poitem,$triggerpo[0]->Id);
							}

						}

					}

				}

			}

	}

	$Ids=null;

			return view('pomanagement', ['me' => $me,'projects' =>$projects,'projectid' =>$projectid,'po' =>$po, 'projects' =>$projects,'options' =>$options,'projectids'=>$me->ProjectIds,'projectcode'=>$projectcode,
			'invoices' =>$invoices,'workorderid'=>$workorderid,'type'=>$type,'template'=>$template,'projectcodes'=>$projectcodes,'poitem'=>$poitem,'Ids'=>$Ids]);

	}

	public function index2($ids=null,$projectid=null,$type=null,$template=null)
    {
		$me = (new CommonController)->get_current_user();

		$projectids = explode("|",$me->ProjectIds);

		$Ids=explode(",",$ids);

		$projects = DB::table('projects')
		->whereIn('Id',$projectids)
		->get();

		if($projectid==null)
		{
			$projectid=0;
			// if($projects)
			// {
			// 	$projectid=$projects[0]->Id;
			// }
		}

		if($type=="No Project PO")
		{
			$projectid=0;
		}

			$po=DB::table('po')
			->select(
				'po.Id' ,
				'po.Huawei_ID' ,
				'projects.Project_Name' ,
				'po.Project' ,
				'po.Project_Code' ,
				'po.PO_Status' ,
				'po.Status' ,
				'po.ROR_Status' ,
				'po.ProjectCode' ,
				'po.PO_No' ,
				'po.PR_No' ,
				'po.Cut' ,
				'po.PO_Line_No' ,
				'po.Shipment_Num' ,
				'po.Item_Code' ,
				'po.Credit_Note' ,

				'po.PO_Date' ,
				'po.PO_Type' ,

				'po.PO_Description' ,
				'po.Scope_of_Work' ,
				'po.Item_Description' ,
				'po.Company' ,
				'po.Work_Order_ID' ,
				'po.Site_ID' ,
				'po.Site_Code' ,
	      'po.Site_Name' ,
				'po.Region' ,
				'po.Payment_Term' ,
				'po.Payment_Method' ,

				'po.Engineering_No' ,

				'po.Center_Area' ,
				'po.Due_Quantity' ,
				'po.Quantity_Request' ,
				'po.Unit' ,
	      'po.Unit_Price' ,
				'po.Amount' ,
				'po.Amount_With_GST' ,
	      'po.Line_Account' ,
				'po.Start_Date' ,
				'po.End_Date' ,
				'po.Acceptance_Date' ,
				'po.Vendor_Code' ,
				'po.Vendor_Name' ,
				'po.Sub_Contract_No' ,

				'po.ESAR_Document_Submitted_Date' ,
				'po.ESAR_Date' ,
	      'po.ESAR_Status' ,
				'po.PAC_Document_Submitted_Date' ,
	      'po.PAC_Date' ,
	      'po.PAC_Status' ,

				'pm.Name as PM',
				'po.PM_Accepted_At',
				'po.PM_Status',
				'po.PM_Remarks',
				'finance.Name as Finance',
				'po.Finance_Accepted_At',
				'po.Finance_Status',
				'po.Finance_Remarks',

				'po.First_Milestone_Percentage' ,
	      'po.First_Milestone_Amount' ,
				'po.First_Milestone_Amount_With_GST' ,
	      'po.First_Milestone_Completed_Date' ,
	      'po.First_Milestone_Invoice_No' ,
				'po.First_Milestone_Invoice_Upload_Date' ,
	      'po.First_Milestone_Forecast_Invoice_Date' ,

				'po.Second_Milestone_Percentage' ,
	      'po.Second_Milestone_Amount' ,
				'po.Second_Milestone_Amount_With_GST' ,
	      'po.Second_Milestone_Completed_Date' ,
	      'po.Second_Milestone_Invoice_No' ,
				'po.Second_Milestone_Invoice_Upload_Date' ,
	      'po.Second_Milestone_Forecast_Invoice_Date' ,

				'po.Third_Milestone_Percentage' ,
	      'po.Third_Milestone_Amount' ,
				'po.Third_Milestone_Amount_With_GST' ,
	      'po.Third_Milestone_Completed_Date' ,
	      'po.Third_Milestone_Invoice_No' ,
				'po.Third_Milestone_Invoice_Upload_Date' ,
	      'po.Third_Milestone_Forecast_Invoice_Date' ,

				'po.Fourth_Milestone_Percentage' ,
				'po.Fourth_Milestone_Amount' ,
				'po.Fourth_Milestone_Amount_With_GST' ,
				'po.Fourth_Milestone_Completed_Date' ,
				'po.Fourth_Milestone_Invoice_No' ,
				'po.Fourth_Milestone_Invoice_Upload_Date' ,
				'po.Fourth_Milestone_Forecast_Invoice_Date' ,

				'po.Fifth_Milestone_Percentage' ,
				'po.Fifth_Milestone_Amount' ,
				'po.Fifth_Milestone_Amount_With_GST' ,
				'po.Fifth_Milestone_Completed_Date' ,
				'po.Fifth_Milestone_Invoice_No' ,
				'po.Fifth_Milestone_Invoice_Upload_Date' ,
				'po.Fifth_Milestone_Forecast_Invoice_Date' ,

	      'users.Name',
				'po.Remarks' )
			->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
			->leftJoin('users', 'po.created_by', '=', 'users.Id')
			->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
			->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id')
			->whereIn('po.Id',$Ids)
			->orderBy('po.PO_No','ASC')
			->get();

		$options= DB::table('options')
		->whereIn('Table', ["purchaseorders"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$invoices = DB::table('invoices')
		->select('invoices.Id','invoices.Invoice_No','projects.Project_Name','invoices.Company','invoices.Invoice_Type','invoices.Invoice_Date','invoices.Invoice_Description','invoices.Invoice_Amount','invoices.Invoice_Status')
				->leftJoin('projects', 'invoices.ProjectId', '=', 'projects.Id')
		->where('invoices.ProjectId','=',$projectid)
		->orderBy('invoices.Invoice_No','ASC')
		->get();

		$projectcodes = DB::table('projectcodes')
		->select('projectcodes.Id','projectcodes.Project_Code','projectcodes.Site_ID')
		->where('projectcodes.ProjectId','=',$projectid)
		->orderBy('projectcodes.Project_Code','ASC')
		->get();

	if($template==null)
	{
		$template="General";
	}

	if($type==null)
	{
		$type="All PO";
	}

	if($type=="No Project PO")
	{
		$projectid=0;
	}

	$projectcode=null;
	$workorderid=null;
	$poitem=null;

			return view('pomanagement', ['me' => $me,'projects' =>$projects,'projectid' =>$projectid,'po' =>$po, 'projects' =>$projects,'options' =>$options,'projectids'=>$me->ProjectIds,'projectcode'=>$projectcode,
			'invoices' =>$invoices,'workorderid'=>$workorderid,'type'=>$type,'template'=>$template,'projectcodes'=>$projectcodes,'poitem'=>$poitem,'Ids'=>$Ids]);

	}

    public function purchaseorderitem($Id)
    {

    $me = (new CommonController)->get_current_user();

		$projectids = explode("|",$me->ProjectIds);



		$invoices = DB::table('invoices')
		->select('invoices.Id','invoices.Invoice_No','projects.Project_Name','invoices.Company','invoices.Invoice_Type','invoices.Invoice_Date','invoices.Invoice_Description','invoices.Invoice_Amount','invoices.Invoice_Status')
				->leftJoin('projects', 'invoices.ProjectId', '=', 'projects.Id')
		->whereIn('invoices.ProjectId',$projectids)
		->orderBy('invoices.Invoice_No','ASC')
		->get();

        $receipts = DB::table('files')
		->where('TargetId', '=', $Id)
		->where('Type', '=', 'Purchaseorder')
		->get();

		return view('podetails', ['me' => $me,'purchaseorder' => $purchaseorder, 'purchaseorderitem' => $purchaseorderitem, 'invoices' =>$invoices,'receipts' =>$receipts]);

	}

	public function purchaseorderitem2($PO)
	{

	$me = (new CommonController)->get_current_user();

	$projectids = explode("|",$me->ProjectIds);

	// $purchaseorder = DB::table('purchaseorders')
	// ->select('Id','PO_No','PO_Date','PO_Type','purchaseorders.Company','purchaseorders.Job_Type','purchaseorders.Cut','purchaseorders.Payment_Term','PO_Description','purchaseorders.PO_Status','purchaseorders.ProjectId','purchaseorders.First_Cut','purchaseorders.Second_Cut','purchaseorders.Third_Cut','purchaseorders.Fourth_Cut','purchaseorders.Fifth_Cut','purchaseorders.Remarks')
	// ->orderBy('purchaseorders.PO_No','desc')
	// 		->where('purchaseorders.PO_No', '=', $PO)
	// ->first();

	$PO=explode(" | ",$PO)[0];

	$po=DB::table('po')
	->select(
		'po.Id' ,
		'po.Huawei_ID' ,
		'projects.Project_Name' ,
		'po.Project' ,
		'po.Project_Code' ,
		'po.PO_Status' ,
		'po.Status' ,
		'po.ROR_Status' ,
		'po.ProjectCode' ,
		'po.PO_No' ,
		'po.PR_No' ,
		'po.Cut' ,
		'po.PO_Line_No' ,
		'po.Shipment_Num' ,
		'po.Item_Code' ,
		'po.Credit_Note' ,

		'po.PO_Date' ,
		'po.PO_Type' ,

		'po.PO_Description' ,
		'po.Scope_of_Work' ,
		'po.Item_Description' ,
		'po.Company' ,
		'po.Work_Order_ID' ,
		'po.Site_ID' ,
		'po.Site_Code' ,
		'po.Site_Name' ,
		'po.Region' ,
		'po.Payment_Term' ,
		'po.Payment_Method' ,

		'po.Engineering_No' ,

		'po.Center_Area' ,
		'po.Due_Quantity' ,
		'po.Quantity_Request' ,
		'po.Unit' ,
		'po.Unit_Price' ,
		'po.Amount' ,
		'po.Amount_With_GST' ,
		'po.Line_Account' ,
		'po.Start_Date' ,
		'po.End_Date' ,
		'po.Acceptance_Date' ,
		'po.Vendor_Code' ,
		'po.Vendor_Name' ,
		'po.Sub_Contract_No' ,

		'po.ESAR_Document_Submitted_Date' ,
		'po.ESAR_Date' ,
		'po.ESAR_Status' ,
		'po.PAC_Document_Submitted_Date' ,
		'po.PAC_Date' ,
		'po.PAC_Status' ,

		'pm.Name as PM',
		'po.PM_Accepted_At',
		'po.PM_Status',
		'po.PM_Remarks',
		'finance.Name as Finance',
		'po.Finance_Accepted_At',
		'po.Finance_Status',
		'po.Finance_Remarks',

		'po.First_Milestone_Percentage' ,
		'po.First_Milestone_Amount' ,
		'po.First_Milestone_Amount_With_GST' ,
		'po.First_Milestone_Completed_Date' ,
		'po.First_Milestone_Invoice_No' ,
		'po.First_Milestone_Invoice_Upload_Date' ,
		'po.First_Milestone_Forecast_Invoice_Date' ,

		'po.Second_Milestone_Percentage' ,
		'po.Second_Milestone_Amount' ,
		'po.Second_Milestone_Amount_With_GST' ,
		'po.Second_Milestone_Completed_Date' ,
		'po.Second_Milestone_Invoice_No' ,
		'po.Second_Milestone_Invoice_Upload_Date' ,
		'po.Second_Milestone_Forecast_Invoice_Date' ,

		'po.Third_Milestone_Percentage' ,
		'po.Third_Milestone_Amount' ,
		'po.Third_Milestone_Amount_With_GST' ,
		'po.Third_Milestone_Completed_Date' ,
		'po.Third_Milestone_Invoice_No' ,
		'po.Third_Milestone_Invoice_Upload_Date' ,
		'po.Third_Milestone_Forecast_Invoice_Date' ,

		'po.Fourth_Milestone_Percentage' ,
		'po.Fourth_Milestone_Amount' ,
		'po.Fourth_Milestone_Amount_With_GST' ,
		'po.Fourth_Milestone_Completed_Date' ,
		'po.Fourth_Milestone_Invoice_No' ,
		'po.Fourth_Milestone_Invoice_Upload_Date' ,
		'po.Fourth_Milestone_Forecast_Invoice_Date' ,

		'po.Fifth_Milestone_Percentage' ,
		'po.Fifth_Milestone_Amount' ,
		'po.Fifth_Milestone_Amount_With_GST' ,
		'po.Fifth_Milestone_Completed_Date' ,
		'po.Fifth_Milestone_Invoice_No' ,
		'po.Fifth_Milestone_Invoice_Upload_Date' ,
		'po.Fifth_Milestone_Forecast_Invoice_Date' ,

		'users.Name',
		'po.Remarks' )
	->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
	->leftJoin('users', 'po.created_by', '=', 'users.Id')
	->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
	->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id')
	->where('po.PO_No','=',$PO)
	->orderBy('po.PO_No','ASC')
	->get();

	$poid=array();

	foreach ($po as $key => $value) {
		# code...
		array_push($poid,$value->Id);
	}

		$invoices = DB::table('invoices')
		->select('invoices.Id','invoices.Invoice_No','projects.Project_Name','invoices.Company','invoices.Invoice_Type','invoices.Invoice_Date','invoices.Invoice_Description','invoices.Invoice_Amount','invoices.Invoice_Status')
				->leftJoin('projects', 'invoices.ProjectId', '=', 'projects.Id')
		->whereIn('invoices.ProjectId',$projectids)
		->orderBy('invoices.Invoice_No','ASC')
		->get();

		$receipts = DB::table('files')
		->whereIn('TargetId',$poid )
		->where('Type', '=', 'Purchaseorder')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["purchaseorders"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$projectcodes = DB::table('projectcodes')
		->select('projectcodes.Id','projectcodes.Project_Code','projectcodes.Site_ID')
		->whereIn('projectcodes.ProjectId',$projectids)
		->orderBy('projectcodes.Project_Code','ASC')
		->get();

	return view('podetails', ['me' => $me,'po' => $po,'po_no'=>$PO, 'invoices' =>$invoices,'projectcodes'=>$projectcodes, 'receipts' =>$receipts,'options'=>$options]);

}

	public function deletereceipt(Request $request)
	{
		$input = $request->all();

		return DB::table('files')
		->where('Id', '=', $input["Id"])
		->delete();

	}

	public function uploadreceipt(Request $request)
	{
		$filenames="";
		$input = $request->all();
		$insertid=$input["purchaseorderId"];
		$type="Purchaseorder";
		$uploadcount=count($request->file('receipt'));

			if ($request->hasFile('receipt')) {

				for ($i=0; $i <$uploadcount ; $i++) {
					# code...
					$file = $request->file('receipt')[$i];
					$destinationPath=public_path()."/private/upload/PO";
					$extension = $file->getClientOriginalExtension();
					$originalName=$file->getClientOriginalName();
					$fileSize=$file->getSize();
					$fileName=time()."_".$i.".".$extension;
					$upload_success = $file->move($destinationPath, $fileName);
					$insert=DB::table('files')->insertGetId(
						['Type' => $type,
						 'TargetId' => $insertid,
						 'File_Name' => $originalName,
						 'File_Size' => $fileSize,
						 'Web_Path' => '/private/upload/PO/'.$fileName
						]
					);
					$filenames.= $insert."|".url('/private/upload/PO/'.$fileName)."|" .$originalName.",";
				}

				$filenames=substr($filenames, 0, strlen($filenames)-1);

				return $filenames;

				//return '/private/upload/'.$fileName;
			}
			else {
				return 0;
			}
	}

	public function posummary($start=null,$end=null)
	{
		$me = (new CommonController)->get_current_user();

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of this month'));

		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('last day of this month'));
		}

		$summary = DB::select("
		SELECT `PO_Type`,Count(DISTINCT po.Id) AS '#_of_PO',FORMAT(SUM(po.Amount),2) as Total_Amount
		FROM po
		WHERE po.PO_Date BETWEEN str_to_date('". $start ."','%d-%M-%Y') AND str_to_date('". $end ."','%d-%M-%Y')
		GROUP BY po.`PO_Type`
	   ");

	 $summary2 = DB::select("
	 SELECT `Company`,`PO_Type`,Count(DISTINCT po.Id) AS '#_of_PO',FORMAT(SUM(po.Amount),2) as Total_Amount
	 FROM po
	 WHERE po.PO_Date BETWEEN str_to_date('". $start ."','%d-%M-%Y') AND str_to_date('". $end ."','%d-%M-%Y')
	 GROUP BY po.`Company`,`PO_Type`
		");

		$summary3 = DB::select("
		SELECT `Scope_of_Work`,Count(DISTINCT po.Id) AS '#_of_PO',FORMAT(SUM(po.Amount),2) as Total_Amount
		FROM po
		WHERE po.PO_Date BETWEEN str_to_date('". $start ."','%d-%M-%Y') AND str_to_date('". $end ."','%d-%M-%Y')
		GROUP BY po.`Scope_of_Work`
		 ");

		 $trackercolumns = DB::table('trackercolumn')
		 ->select()
		 ->where('trackercolumn.Type','=',"PO List")
		 ->get();

		 $pendingpoquery="";

		 foreach ($trackercolumns as $key => $value) {
			 $pendingpoquery.="`".$value->Column_Name."`='No PO' OR ";
		 }

		 $pendingpoquery=substr($pendingpoquery,0,strlen($pendingpoquery)-3);

		 $pendingpo="";
		 if($pendingpoquery)
		 {

			 $pendingpo = DB::select("
				 SELECT projects.Project_Name,COUNT(*) As Pending
				 FROM tracker
				 LEFT JOIN projects on tracker.ProjectId=projects.Id
				 WHERE ".$pendingpoquery."
			 	 GROUP BY projects.Project_Name");

		 }

    return view("posummary",['me'=>$me, 'summary'=>$summary, 'summary2'=>$summary2,'summary3'=>$summary3,'pendingpo'=>$pendingpo,'start'=>$start, 'end'=>$end]);
  }

	public function poagingsummary($projctid=null)
	{
		$me = (new CommonController)->get_current_user();

		$projectids = explode("|",$me->ProjectIds);

		$projects = DB::table('projects')
		->whereIn('Id',$projectids)
		->get();

		if ($projctid==null)
		{
			$projctid=0;

		}

		$summary = DB::select("SELECT
		po.Id,
		po.PO_No,
		projects.Project_Name,
		po.Site_Code,
		po.Site_Name,
		po.Item_Description,
		po.Shipment_Num,
		po.PO_Type,
		po.PO_Status,
		po.PO_Date,
		po.created_at as Import_Date,
		DATEDIFF(po.PM_Accepted_At,po.created_at) as 'Import_to_PM_Acceptance (Aging)',
		po.PM_Accepted_At,
		DATEDIFF(po.Finance_Accepted_At,po.PM_Accepted_At) as 'PM_to_Finance_Acceptance (Aging)',
		po.Finance_Accepted_At,
		DATEDIFF(str_to_date(po.ESAR_Date,'%d-%M-%Y'),po.Finance_Accepted_At) as 'Acceptance_to_ESAR (Aging)',
		po.ESAR_Date,
		DATEDIFF(str_to_date(po.PAC_Date,'%d-%M-%Y'),str_to_date(po.ESAR_Date,'%d-%M-%Y')) as 'ESAR_to_PAC (Aging)',
		po.PAC_Date
		FROM po
		LEFT JOIN projects ON projects.Id=po.ProjectId
		WHERE po.ProjectId=".$projctid);

    return view("poagingsummary",['me'=>$me, 'summary'=>$summary,'projects'=>$projects, 'projectid'=>$projctid]);
  }

	public function pr()
	{
		$me = (new CommonController)->get_current_user();
		return view("pr",['me'=>$me]);
		//
		// $html=view('pr', []);
		// (new ExportPDFController)->Export($html);

	}
	public function prpdf()
	{
		// $me = (new CommonController)->get_current_user();
		// return view("pr",['me'=>$me]);
		//
		$html=view('prpdf', []);
		(new ExportPDFController)->Export($html);

	}

	public function pac($ids)
	{

		$me = (new CommonController)->get_current_user();

		$POId = explode("|", $ids);

		$po=DB::table('po')
		->select(
			'po.Id' ,
			'po.Huawei_ID' ,
			'projects.Project_Name' ,
			'po.Project' ,
			'po.Project_Code' ,
			'po.PO_Status' ,
			'po.Status' ,
			'po.ROR_Status' ,
			'po.ProjectCode' ,
			'po.PO_No' ,
			'po.PR_No' ,
			'po.Cut' ,
			'po.PO_Line_No' ,
			'po.Shipment_Num' ,
			'po.Item_Code' ,
			'po.Credit_Note' ,

			'po.PO_Date' ,
			'po.PO_Type' ,

			'po.PO_Description' ,
			'po.Scope_of_Work' ,
			'po.Item_Description' ,
			'po.Company' ,
			'po.Work_Order_ID' ,
			'po.Site_ID' ,
			'po.Site_Code' ,
			'po.Site_Name' ,
			'po.Region' ,
			'po.Payment_Term' ,
			'po.Payment_Method' ,

			'po.Engineering_No' ,

			'po.Center_Area' ,
			'po.Due_Quantity' ,
			'po.Quantity_Request' ,
			'po.Unit' ,
			'po.Unit_Price' ,
			'po.Amount' ,
			'po.Amount_With_GST' ,
			'po.Line_Account' ,
			'po.Start_Date' ,
			'po.End_Date' ,
			'po.Acceptance_Date' ,
			'po.Vendor_Code' ,
			'po.Vendor_Name' ,
			'po.Sub_Contract_No' ,

			'po.ESAR_Document_Submitted_Date' ,
			'po.ESAR_Date' ,
			'po.ESAR_Status' ,
			'po.PAC_Document_Submitted_Date' ,
			'po.PAC_Date' ,
			'po.PAC_Status' ,

			'pm.Name as PM',
			'po.PM_Accepted_At',
			'po.PM_Status',
			'po.PM_Remarks',
			'finance.Name as Finance',
			'po.Finance_Accepted_At',
			'po.Finance_Status',
			'po.Finance_Remarks',

			'po.First_Milestone_Percentage' ,
			'po.First_Milestone_Amount' ,
			'po.First_Milestone_Amount_With_GST' ,
			'po.First_Milestone_Completed_Date' ,
			'po.First_Milestone_Invoice_No' ,
			'po.First_Milestone_Invoice_Upload_Date' ,
			'po.First_Milestone_Forecast_Invoice_Date' ,

			'po.Second_Milestone_Percentage' ,
			'po.Second_Milestone_Amount' ,
			'po.Second_Milestone_Amount_With_GST' ,
			'po.Second_Milestone_Completed_Date' ,
			'po.Second_Milestone_Invoice_No' ,
			'po.Second_Milestone_Invoice_Upload_Date' ,
			'po.Second_Milestone_Forecast_Invoice_Date' ,

			'po.Third_Milestone_Percentage' ,
			'po.Third_Milestone_Amount' ,
			'po.Third_Milestone_Amount_With_GST' ,
			'po.Third_Milestone_Completed_Date' ,
			'po.Third_Milestone_Invoice_No' ,
			'po.Third_Milestone_Invoice_Upload_Date' ,
			'po.Third_Milestone_Forecast_Invoice_Date' ,

			'po.Fourth_Milestone_Percentage' ,
			'po.Fourth_Milestone_Amount' ,
			'po.Fourth_Milestone_Amount_With_GST' ,
			'po.Fourth_Milestone_Completed_Date' ,
			'po.Fourth_Milestone_Invoice_No' ,
			'po.Fourth_Milestone_Invoice_Upload_Date' ,
			'po.Fourth_Milestone_Forecast_Invoice_Date' ,

			'po.Fifth_Milestone_Percentage' ,
			'po.Fifth_Milestone_Amount' ,
			'po.Fifth_Milestone_Amount_With_GST' ,
			'po.Fifth_Milestone_Completed_Date' ,
			'po.Fifth_Milestone_Invoice_No' ,
			'po.Fifth_Milestone_Invoice_Upload_Date' ,
			'po.Fifth_Milestone_Forecast_Invoice_Date' ,

			'users.Name',
			'po.Remarks' )
		->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
		->leftJoin('users', 'po.created_by', '=', 'users.Id')
		->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
		->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id')
		->whereIn('po.Id',$POId)
		->orderBy('po.PO_No','ASC')
		->get();

		$potable = DB::table('po')
		->select(

			'po.PO_Line_No',
			'po.Shipment_Num',
			'po.Site_Code',
			'po.Site_Name',
			'po.Item_Code',
			'po.Item_Description',
			'po.Unit',
			DB::raw("'AC2' as milestone"),
			'po.Quantity_Request',
			DB::raw("'' as current"),
			DB::raw("'' as actual"),
			DB::raw("'' as pass"),
			DB::raw("'' as remark")





      )
		->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
		->leftJoin('users', 'po.created_by', '=', 'users.Id')
		->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
		->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id')
		->whereIn('po.Id',$POId)
		->orderBy('po.PO_No','ASC')
		->get();

		return view("pac",['me'=>$me,'po'=>$po, 'potable'=>$potable]);

	}

	public function pacpdf(Request $request)
	{
		$input = $request->all();
		// dd($input);
		// return view('pacpdf', ['input'=>$input]);
		$html=view('pacpdf', ['input'=>$input]);

		(new ExportPDFController)->ExportLandscape($html);

	}


	public function esar($ids)
	{

		$me = (new CommonController)->get_current_user();

		$POId = explode("|", $ids);

		$po=DB::table('po')
		->select(
			'po.Id' ,
			'po.Huawei_ID' ,
			'projects.Project_Name' ,
			'po.Project' ,
			'po.Project_Code' ,
			'po.PO_Status' ,
			'po.Status' ,
			'po.ROR_Status' ,
			'po.ProjectCode' ,
			'po.PO_No' ,
			'po.PR_No' ,
			'po.Cut' ,
			'po.PO_Line_No' ,
			'po.Shipment_Num' ,
			'po.Item_Code' ,
			'po.Credit_Note' ,

			'po.PO_Date' ,
			'po.PO_Type' ,

			'po.PO_Description' ,
			'po.Scope_of_Work' ,
			'po.Item_Description' ,
			'po.Company' ,
			'po.Work_Order_ID' ,
			'po.Site_ID' ,
			'po.Site_Code' ,
			'po.Site_Name' ,
			'po.Region' ,
			'po.Payment_Term' ,
			'po.Payment_Method' ,

			'po.Engineering_No' ,

			'po.Center_Area' ,
			'po.Due_Quantity' ,
			'po.Quantity_Request' ,
			'po.Unit' ,
			'po.Unit_Price' ,
			'po.Amount' ,
			'po.Amount_With_GST' ,
			'po.Line_Account' ,
			'po.Start_Date' ,
			'po.End_Date' ,
			'po.Acceptance_Date' ,
			'po.Vendor_Code' ,
			'po.Vendor_Name' ,
			'po.Sub_Contract_No' ,

			'po.ESAR_Document_Submitted_Date' ,
			'po.ESAR_Date' ,
			'po.ESAR_Status' ,
			'po.PAC_Document_Submitted_Date' ,
			'po.PAC_Date' ,
			'po.PAC_Status' ,

			'pm.Name as PM',
			'po.PM_Accepted_At',
			'po.PM_Status',
			'po.PM_Remarks',
			'finance.Name as Finance',
			'po.Finance_Accepted_At',
			'po.Finance_Status',
			'po.Finance_Remarks',

			'po.First_Milestone_Percentage' ,
			'po.First_Milestone_Amount' ,
			'po.First_Milestone_Amount_With_GST' ,
			'po.First_Milestone_Completed_Date' ,
			'po.First_Milestone_Invoice_No' ,
			'po.First_Milestone_Invoice_Upload_Date' ,
			'po.First_Milestone_Forecast_Invoice_Date' ,

			'po.Second_Milestone_Percentage' ,
			'po.Second_Milestone_Amount' ,
			'po.Second_Milestone_Amount_With_GST' ,
			'po.Second_Milestone_Completed_Date' ,
			'po.Second_Milestone_Invoice_No' ,
			'po.Second_Milestone_Invoice_Upload_Date' ,
			'po.Second_Milestone_Forecast_Invoice_Date' ,

			'po.Third_Milestone_Percentage' ,
			'po.Third_Milestone_Amount' ,
			'po.Third_Milestone_Amount_With_GST' ,
			'po.Third_Milestone_Completed_Date' ,
			'po.Third_Milestone_Invoice_No' ,
			'po.Third_Milestone_Invoice_Upload_Date' ,
			'po.Third_Milestone_Forecast_Invoice_Date' ,

			'po.Fourth_Milestone_Percentage' ,
			'po.Fourth_Milestone_Amount' ,
			'po.Fourth_Milestone_Amount_With_GST' ,
			'po.Fourth_Milestone_Completed_Date' ,
			'po.Fourth_Milestone_Invoice_No' ,
			'po.Fourth_Milestone_Invoice_Upload_Date' ,
			'po.Fourth_Milestone_Forecast_Invoice_Date' ,

			'po.Fifth_Milestone_Percentage' ,
			'po.Fifth_Milestone_Amount' ,
			'po.Fifth_Milestone_Amount_With_GST' ,
			'po.Fifth_Milestone_Completed_Date' ,
			'po.Fifth_Milestone_Invoice_No' ,
			'po.Fifth_Milestone_Invoice_Upload_Date' ,
			'po.Fifth_Milestone_Forecast_Invoice_Date' ,

			'users.Name',
			'po.Remarks' )
		->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
		->leftJoin('users', 'po.created_by', '=', 'users.Id')
		->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
		->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id')
		->whereIn('po.Id',$POId)
		->orderBy('po.PO_No','ASC')
		->get();

		$potable = DB::table('po')
		->select(

			'po.Site_Name',
			'po.Site_ID',
      'po.Item_Description',
      'po.Due_Quantity',
      'po.Unit_Price')
		->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
		->leftJoin('users', 'po.created_by', '=', 'users.Id')
		->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
		->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id')
		->whereIn('po.Id',$POId)
		->orderBy('po.PO_No','ASC')
		->get();

		return view("esar",['me'=>$me,'po'=>$po, 'potable'=>$potable]);

	}
	public function esarpdf(Request $request)
	{
		$input = $request->all();

		$html=view('esarpdf', ['input'=>$input]);
		(new ExportPDFController)->Export($html);

	}

	public function esar1($ids)
	{

		$me = (new CommonController)->get_current_user();

		$POId = explode("|", $ids);

		$po=DB::table('po')
		->select(
			'po.Id' ,
			'po.Huawei_ID' ,
			'projects.Project_Name' ,
			'po.Project' ,
			'po.Project_Code' ,
			'po.PO_Status' ,
			'po.Status' ,
			'po.ROR_Status' ,
			'po.ProjectCode' ,
			'po.PO_No' ,
			'po.PR_No' ,
			'po.Cut' ,
			'po.PO_Line_No' ,
			'po.Shipment_Num' ,
			'po.Item_Code' ,
			'po.Credit_Note' ,

			'po.PO_Date' ,
			'po.PO_Type' ,

			'po.PO_Description' ,
			'po.Scope_of_Work' ,
			'po.Item_Description' ,
			'po.Company' ,
			'po.Work_Order_ID' ,
			'po.Site_ID' ,
			'po.Site_Code' ,
			'po.Site_Name' ,
			'po.Region' ,
			'po.Payment_Term' ,
			'po.Payment_Method' ,

			'po.Engineering_No' ,

			'po.Center_Area' ,
			'po.Due_Quantity' ,
			'po.Quantity_Request' ,
			'po.Unit' ,
			'po.Unit_Price' ,
			'po.Amount' ,
			'po.Amount_With_GST' ,
			'po.Line_Account' ,
			'po.Start_Date' ,
			'po.End_Date' ,
			'po.Acceptance_Date' ,
			'po.Vendor_Code' ,
			'po.Vendor_Name' ,
			'po.Sub_Contract_No' ,

			'po.ESAR_Document_Submitted_Date' ,
			'po.ESAR_Date' ,
			'po.ESAR_Status' ,
			'po.PAC_Document_Submitted_Date' ,
			'po.PAC_Date' ,
			'po.PAC_Status' ,

			'pm.Name as PM',
			'po.PM_Accepted_At',
			'po.PM_Status',
			'po.PM_Remarks',
			'finance.Name as Finance',
			'po.Finance_Accepted_At',
			'po.Finance_Status',
			'po.Finance_Remarks',

			'po.First_Milestone_Percentage' ,
			'po.First_Milestone_Amount' ,
			'po.First_Milestone_Amount_With_GST' ,
			'po.First_Milestone_Completed_Date' ,
			'po.First_Milestone_Invoice_No' ,
			'po.First_Milestone_Invoice_Upload_Date' ,
			'po.First_Milestone_Forecast_Invoice_Date' ,

			'po.Second_Milestone_Percentage' ,
			'po.Second_Milestone_Amount' ,
			'po.Second_Milestone_Amount_With_GST' ,
			'po.Second_Milestone_Completed_Date' ,
			'po.Second_Milestone_Invoice_No' ,
			'po.Second_Milestone_Invoice_Upload_Date' ,
			'po.Second_Milestone_Forecast_Invoice_Date' ,

			'po.Third_Milestone_Percentage' ,
			'po.Third_Milestone_Amount' ,
			'po.Third_Milestone_Amount_With_GST' ,
			'po.Third_Milestone_Completed_Date' ,
			'po.Third_Milestone_Invoice_No' ,
			'po.Third_Milestone_Invoice_Upload_Date' ,
			'po.Third_Milestone_Forecast_Invoice_Date' ,

			'po.Fourth_Milestone_Percentage' ,
			'po.Fourth_Milestone_Amount' ,
			'po.Fourth_Milestone_Amount_With_GST' ,
			'po.Fourth_Milestone_Completed_Date' ,
			'po.Fourth_Milestone_Invoice_No' ,
			'po.Fourth_Milestone_Invoice_Upload_Date' ,
			'po.Fourth_Milestone_Forecast_Invoice_Date' ,

			'po.Fifth_Milestone_Percentage' ,
			'po.Fifth_Milestone_Amount' ,
			'po.Fifth_Milestone_Amount_With_GST' ,
			'po.Fifth_Milestone_Completed_Date' ,
			'po.Fifth_Milestone_Invoice_No' ,
			'po.Fifth_Milestone_Invoice_Upload_Date' ,
			'po.Fifth_Milestone_Forecast_Invoice_Date' ,

			'users.Name',
			'po.Remarks' )
		->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
		->leftJoin('users', 'po.created_by', '=', 'users.Id')
		->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
		->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id')
		->whereIn('po.Id',$POId)
		->orderBy('po.PO_No','ASC')
		->get();

		$potable = DB::table('po')
		->select(
			'po.Shipment_Num',
			'po.Site_Code',
			'po.Site_Name',
			'po.Item_Code',
			'po.Item_Description',
			'po.Unit',
			DB::raw("'after receive invoice30D days to pay' as milestone"),
			'po.Quantity_Request',
			DB::raw("'' as current"),
			DB::raw("'' as actual"),
			DB::raw("'' as pass"),
			DB::raw("'' as remark")





      )
		->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
		->leftJoin('users', 'po.created_by', '=', 'users.Id')
		->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
		->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id')
		->whereIn('po.Id',$POId)
		->orderBy('po.PO_No','ASC')
		->get();

		return view("esar1",['me'=>$me,'po'=>$po, 'potable'=>$potable]);

	}
	public function esar1pdf(Request $request)
	{
		$input = $request->all();

		$html=view('esar1pdf', ['input'=>$input]);


		(new ExportPDFController)->ExportLandscape($html);

	}

	public function importpo(Request $request)
	{
		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$projectid=$input["projectid"];

		$file = Input::file('import');

		$destinationPath=public_path()."/private/upload/ImportData";
		$extension = $file->getClientOriginalExtension();
		$originalName=$file->getClientOriginalName();
		$fileSize=$file->getSize();
		$fileName=time().".".$extension;
		$upload_success = $file->move($destinationPath, $fileName);

		$excel = Excel::load($destinationPath."/".$fileName)->all()->toArray();

		$arrpoid=array();

		$arrpoitem=array();

		$polist = DB::table('po')
		->select('Id','Huawei_ID')
		->orderBy('PO_No','ASC')
		->get();

		foreach ($polist as $key => $value) {
			# code...
			array_push($arrpoid,$value->Id);

		}

		$arrinsert=array();
			# code...
			foreach ($polist as $key => $value) {
				# code...

				array_push($arrpoitem,$value->Huawei_ID);

			}

			foreach ($excel as $row) {
				# code...

					//import PO

							if(array_search($row["id"],$arrpoitem)!==false)
							{
								//already exist no need import

										DB::table('po')
												->where('Huawei_ID', '=',$row["id"])
												->update(array(
			 									 'Project' => $row["project_name"],
			 									 'ProjectCode' => $row["project_code"],
			 									 'Site_Code' => $row["site_code"],
			 									 'Site_Name' => $row["site_name"],
			 									 'Sub_Contract_No' => $row["sub_contract_no"],
			 									 'PR_No' => $row["pr_no"],
			 									 'PO_Status' => $row["po_status"],
			 									 'PO_No' => $row["po_no"],
			 									 'PO_Line_No' => $row["po_line_no"],
			 									 'Item_Code' => $row["item_code"],
			 									 'Item_Description' => $row["item_description"],
			 									 'Unit_Price' => $row["unit_price"],
												 'Amount' => $row["unit_price"],
			 									 'Quantity_Request' => $row["requested_qty"],
			 									 'Line_Account' => $row["line_account"],
			 									 'Unit' => $row["unit"],
			 									 'Tax_Rate' => $row["tax_rate"],
			 									 'Currency' => $row["currency"],
			 									 'Payment_Term' => $row["payment_terms"],
			 									 'Payment_Method' => $row["payment_method"],
			 									 'Scope_of_Work' => $row["category"],
			 									 'Center_Area' => $row["center_area"],
												 'Bidding_Area' => $row["bidding_area"],
			 									 'Start_Date' => $row["start_date"],
			  								 'End_Date' => $row["end_date"],
			 									 'Acceptance_Date' => $row["acceptance_date"],
			 									 'Due_Quantity' => $row["due_qty"],
			 									 'Shipment_Num' => $row["shipment_no"],
			 									 'PO_Date' => $row["publish_date"],
			 									 'Engineering_No' => $row["project_code"],
											));

							}
							else {

								$insert=DB::table('po')->insertGetId(
									['created_by' => $me->UserId,
									 'Huawei_ID' => $row["id"],
									 'Project' => $row["project_name"],
									 'ProjectCode' => $row["project_code"],
									 'Site_Code' => $row["site_code"],
									 'Site_Name' => $row["site_name"],
									 'Sub_Contract_No' => $row["sub_contract_no"],
									 'PR_No' => $row["pr_no"],
									 'PO_Status' => $row["po_status"],
									 'PO_No' => $row["po_no"],
									 'PO_Line_No' => $row["po_line_no"],
									 'Item_Code' => $row["item_code"],
									 'Item_Description' => $row["item_description"],
									 'Unit_Price' => $row["unit_price"],
									 'Amount' => $row["unit_price"],
									 'Quantity_Request' => $row["requested_qty"],
									 'Line_Account' => $row["line_account"],
									 'Unit' => $row["unit"],
									 'Tax_Rate' => $row["tax_rate"],
									 'Currency' => $row["currency"],
									 'Payment_Term' => $row["payment_terms"],
									 'Payment_Method' => $row["payment_method"],
									 'Scope_of_Work' => $row["category"],
									 'Center_Area' => $row["center_area"],
									 'Bidding_Area' => $row["bidding_area"],
									 'Start_Date' => $row["start_date"],
 									 'End_Date' => $row["end_date"],
									 'Acceptance_Date' => $row["acceptance_date"],
									 'Due_Quantity' => $row["due_qty"],
									 'Shipment_Num' => $row["shipment_no"],
									 'PO_Date' => $row["publish_date"],
									 'Company'=>env('APP_COMPANY'),
									 'Engineering_No' => $row["project_code"],
									 'PO_Type' => "Receive"

									]
								);

								array_push($arrpoitem,$row["id"]);

							}


					}

			//import list B, update
		// 	else if(array_key_exists('line_account', $excel[0]))
		// 	{
		// 		# code...
		// 		foreach ($polist as $key => $value) {
		// 			# code...
		// 			$combine=$value->PO_No." ".$value->Site_Code." ".$value->Site_Name;
		//
		// 			array_push($arrpoitem,$combine);
		//
		// 		}
		//
		// 		foreach ($excel as $row) {
		// 			# code...
		//
		// 				//import PO
		// 				$combine=$row["po_no"]." ".$row["site_code"]." ".$row["site_name"];
		//
		// 				if(array_search($combine,$arrpoitem)!==false)
		// 				{
		// 					$index=array_search($combine,$arrpoitem);
		// 					$poid=$arrpoid[$index];
		//
		// 					DB::table('po')
		// 							->where('Id', '=',$poid)
		// 							->update(array(
		// 							'PO_Status' => $row["po_status"]
		// 						));
		// 				}
		//
		// 		}
		//
		// }

	return 1;
	}

	public function updateaccept(Request $request)
	{

		$me = (new CommonController)->get_current_user();
		$emaillist=array();
		array_push($emaillist,$me->UserId);

		$input = $request->all();

		$poIds = explode(",", $input["POIds"]);

		$po=DB::table('po')
		->select(
			'po.Id' ,
      'po.PO_No' ,
			'po.PO_Line_No' ,
			'po.Site_Code' ,
      'po.Site_Name' ,
			'pm.Name as PM',
			'po.PM_Accepted_At',
			'po.PM_Status',
			'finance.Name as Finance',
			'po.Finance_Accepted_At',
			'po.Finance_Status')
		->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
		->leftJoin('users', 'po.created_by', '=', 'users.Id')
		->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
		->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id')
		->whereIn('po.Id', $poIds)
		->orderBy('po.PO_No','ASC')
		->get();

		if($input["Status"]=="PM Accept")
		{

			$subscribers = DB::table('notificationtype')
			->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
			->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
			->where('notificationtype.Id','=',49)
			->get();

		}
		else if($input["Status"]=="PM Reject")
		{

			$subscribers = DB::table('notificationtype')
			->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
			->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
			->where('notificationtype.Id','=',50)
			->get();

		}
		else if($input["Status"]=="Finance Accept")
		{

			$subscribers = DB::table('notificationtype')
			->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
			->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
			->where('notificationtype.Id','=',51)
			->get();

		}
		else if($input["Status"]=="Finance Reject")
		{

			$subscribers = DB::table('notificationtype')
			->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
			->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
			->where('notificationtype.Id','=',52)
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

		foreach ($po as $item) {
			# code...

			if($input["Status"]=="PM Accept")
			{

					DB::table('po')
							->where('Id', '=',$item->Id)
							->update(array(
							'PM_Accepted_By' => $me->UserId,
							'PM_Status' => 'Accepted',
							'PM_Accepted_At' =>  DB::raw('now()'),
						));

			}
			else if($input["Status"]=="PM Reject")
			{
				DB::table('po')
						->where('Id', '=',$item->Id)
						->update(array(
						'PM_Accepted_By' => $me->UserId,
						'PM_Status' => 'Rejected',
						'PM_Accepted_At' =>  DB::raw('now()'),
					));

			}
			else if($input["Status"]=="Finance Accept")
			{

				DB::table('po')
						->where('Id', '=',$item->Id)
						->update(array(
						'Finance_Accepted_By' => $me->UserId,
						'Finance_Status' => 'Accepted',
						'PO_Status' => 'Open',
						'Finance_Accepted_At' =>  DB::raw('now()'),
					));

			}
			else if($input["Status"]=="Finance Reject")
			{
				DB::table('po')
						->where('Id', '=',$item->Id)
						->update(array(
						'Finance_Accepted_By' => $me->UserId,
						'Finance_Status' => 'Rejected',
						'Finance_Accepted_At' =>  DB::raw('now()'),
					));


			}


		}

		if (count($emaillist)>0)
		{
			$notify = DB::table('users')
			->whereIn('Id', $emaillist)
			->get();

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

			$po=DB::table('po')
			->select(
				'po.Id' ,
	      'po.PO_No' ,
				'po.PO_Line_No' ,
				'po.Site_Code' ,
	      'po.Site_Name' ,
				'pm.Name as PM',
				'po.PM_Accepted_At',
				'po.PM_Status',
				'finance.Name as Finance',
				'po.Finance_Accepted_At',
				'po.Finance_Status')
			->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
			->leftJoin('users', 'po.created_by', '=', 'users.Id')
			->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
			->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id')
			->whereIn('po.Id', $poIds)
			->orderBy('po.PO_No','ASC')
			->get();

			// Mail::send('emails.timesheetapproval', ['me' => $me,'timesheets' => $timesheets], function($message) use ($emails,$me,$NotificationSubject)
			// {
			// 		$emails = array_filter($emails);
			// 		array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
			// 		$message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
			// });

			return 1;
		}
		else {
			return 0;
		}

	}



}
