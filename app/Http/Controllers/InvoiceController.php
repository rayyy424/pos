<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
    {
		$me = (new CommonController)->get_current_user();

		$projectids = explode("|",$me->ProjectIds);

		$invoices = DB::table('invoices')
		->select('invoices.Id','invoices.Invoice_No','projects.Project_Name','invoices.Company','invoices.Invoice_Type','invoices.Invoice_Date','invoices.Invoice_Description','invoices.Invoice_Amount','invoices.Invoice_Status')
        ->leftJoin('projects', 'invoices.ProjectId', '=', 'projects.Id')
		->whereIn('invoices.ProjectId',$projectids)
		->orderBy('invoices.Invoice_No','ASC')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["invoices"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$projects = DB::table('projects')
		->whereIn('Id',$projectids)
		->get();

			return view('invoicemanagement', ['me' => $me,'invoices' => $invoices, 'projects' =>$projects,'options' =>$options]);

	}

	public function invoicedetail($Id)
	{

	$me = (new CommonController)->get_current_user();

	$invoice = DB::table('invoices')
	->select('invoices.Id','invoices.Invoice_No','projects.Project_Name','invoices.Company','invoices.Invoice_Type','invoices.Invoice_Date','invoices.Invoice_Description','invoices.Invoice_Amount','invoices.Invoice_Status')
			->leftJoin('projects', 'invoices.ProjectId', '=', 'projects.Id')
	->orderBy('invoices.Invoice_No','ASC')
	->where('invoices.Id', '=', $Id)
	->first();

			$receipts = DB::table('files')
	->where('TargetId', '=', $Id)
	->where('Type', '=', 'Invoice')
	->get();

	return view('invoicedetails', ['me' => $me,'invoice' => $invoice, 'receipts' =>$receipts]);

}

public function invoicedetail2($Invoice)
{

	$me = (new CommonController)->get_current_user();

	$invoice = DB::table('invoices')
	->select('invoices.Id','invoices.Invoice_No','projects.Project_Name','invoices.Company','invoices.Invoice_Type','invoices.Invoice_Date','invoices.Invoice_Description','invoices.Invoice_Amount','invoices.Invoice_Status')
			->leftJoin('projects', 'invoices.ProjectId', '=', 'projects.Id')
			->where('invoices.Invoice_No', '=', $Invoice)
	->orderBy('invoices.Invoice_No','ASC')
	->first();

			$receipts = DB::table('files')
	->where('TargetId', '=', $invoice->Id)
	->where('Type', '=', 'Invoice')
	->get();

	return view('invoicedetails', ['me' => $me,'invoice' => $invoice, 'receipts' =>$receipts]);

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
		$insertid=$input["InvoiceId"];
		$type="Invoice";
		$uploadcount=count($request->file('receipt'));

			if ($request->hasFile('receipt')) {

				for ($i=0; $i <$uploadcount ; $i++) {
					# code...
					$file = $request->file('receipt')[$i];
					$destinationPath=public_path()."/private/upload/Invoice";
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
						 'Web_Path' => '/private/upload/Invoice/'.$fileName
						]
					);
					$filenames.= $insert."|".url('/private/upload/Invoice/'.$fileName)."|" .$originalName.",";
				}

				$filenames=substr($filenames, 0, strlen($filenames)-1);

				return $filenames;

				//return '/private/upload/'.$fileName;
			}
			else {
				return 0;
			}
	}

	public function invoicesummary($start=null,$end=null)
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

		$summary= DB::select("
			SELECT purchaseorders.PO_Type,COUNT(Distinct purchaseorders.Id) As '#_Of_PO',FORMAT(SUM(purchaseorderitems.Amount),2) as 'Total_Amount'
			FROM purchaseorders
			LEFT JOIN purchaseorderitems on purchaseorderitems.PO_Id=purchaseorders.Id
			LEFT JOIN projects on purchaseorders.projectId=projects.Id
			WHERE (First_Cut_Completed_Date<>'' AND First_Cut_Invoice_No='') OR
			(Second_Cut_Completed_Date<>'' AND Second_Cut_Invoice_No='') OR
			(Third_Cut_Completed_Date<>'' AND Third_Cut_Invoice_No='') OR
			(Fourth_Cut_Completed_Date<>'' AND Fourth_Cut_Invoice_No='') OR
			(Fifth_Cut_Completed_Date<>'' AND Fifth_Cut_Invoice_No='')
			GROUP BY purchaseorders.PO_Type
			");

		$summary2 = DB::select("
			SELECT Invoice_Type,COUNT(*) AS '#_of_Invoice',FORMAT(SUM(Invoice_Amount),2) as 'Total_Amount'
			FROM invoices WHERE Invoice_Status='Open'
			AND str_to_date(invoices.Invoice_Date,'%d-%M-%Y') BETWEEN str_to_date('". $start ."','%d-%M-%Y') AND str_to_date('". $end ."','%d-%M-%Y')
			GROUP BY Invoice_Type");

		$summary3 = DB::select("
			SELECT Company,Invoice_Type,COUNT(*) AS '#_of_Invoice',FORMAT(SUM(Invoice_Amount),2) as 'Total_Amount'
			FROM invoices WHERE Invoice_Status='Open'
			AND str_to_date(invoices.Invoice_Date,'%d-%M-%Y') BETWEEN str_to_date('". $start ."','%d-%M-%Y') AND str_to_date('". $end ."','%d-%M-%Y')
			GROUP BY Company,Invoice_Type");

    return view("invoicesummary",['me'=>$me, 'summary'=>$summary,'summary2'=>$summary2,'summary3'=>$summary3,'start'=>$start, 'end'=>$end]);
  }



}
