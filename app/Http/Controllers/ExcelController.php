<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Input;
use App\users;
use Excel;

class ExcelController extends Controller
{	
	public function ExcelClaim($id,$userid,$filename = null,$sheetname = null)
	{
		if ($filename==null)
		{
			$filename = 'Claim';
		}

		if ($sheetname==null)
		{
			$sheetname = 'Sheet1';
		}

		$me = (new CommonController)->get_current_user();

		$user = DB::table('users')
		->select('users.Id','StaffId','Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Position','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    	->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $userid)
		->first();

		$claim = DB::table('claimsheets')
		->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
		->select('claimsheets.Id','submitter.Name','claimsheets.Claim_Sheet_Name','claimsheets.Remarks','claimsheets.Status','claimsheets.created_at as Created_Date')
		->where('claimsheets.Id', '=', $id)
		->get();

		$receipts = DB::table('files')
		->where('TargetId', '=', $id)
		->where('Type', '=', 'Claim')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users","claims"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$claimdetail = DB::table('claims')
    	->select('claims.Date','claims.Site_Name','claims.State','claims.Work_Description',
		'claims.Next_Person','claims.Car_No','claims.Mileage','claims.Expenses_Type','claims.Total_Expenses','claims.Petrol_SmartPay',DB::raw('"" as Claims_Amount_Exclude_SmartPay'),'claims.Advance','claims.Total_Amount','claims.GST_Amount','claims.Total_Without_GST','claims.Receipt_No','claims.Company_Name','claims.GST_No','claims.Remarks','approver.Name as Approver','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Updated_At')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->where('claims.ClaimSheetId', '=', $id)
		->orderBy(DB::raw('str_to_date(claims.Date,"%d-%M-%Y")'),'asc')
		->get();

		$mylevel = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country')
		->where('approvalsettings.Type', '=', 'Claim')
		->where('approvalsettings.UserId', '=', $me->UserId)
		->orderBy('approvalsettings.Country','asc')
		// ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
		->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
		->first();

		$total = DB::table('claims')
		->leftJoin('claimsheets', 'claimsheets.Id', '=','claims.ClaimsheetId')
		->select(DB::Raw('sum(claims.Total_Expenses) As TotalExpenses,sum(claims.Petrol_SmartPay) As TotalSmartpay,sum(claims.Advance) As TotalAdvance, sum(claims.GST_Amount) As TotalGSTAmount,sum(claims.Total_Without_GST) As TotalnoGST,sum(claims.Total_Amount) As TotalPayable'))
		->where('claimsheets.UserId', '=' , $userid)
		->get();

		Excel::create($filename, function($excel) use($sheetname,$user,$me,$claim,$receipts,$id,$claimdetail,$options, $mylevel,$total){

            $excel->sheet($sheetname, function($sheet) use($user,$me,$claim,$receipts,$id,$claimdetail,$options, $mylevel,$total){

                $sheet->loadView('excelclaim', array('me' => $me, 'claim' =>$claim,'receipts' =>$receipts, 'Id' =>$id, 'user' =>$user,'claimdetail' => $claimdetail, 'options' =>$options,'mylevel' => $mylevel,'total' => $total));

            });

        })->download('xls');
	}

	public function ExcelTimesheet($id,$start = null, $end = null,$type,$filename = null,$sheetname = null)
	{
		if ($filename==null)
		{
			$filename = 'Timesheet';
		}

		if ($sheetname==null)
		{
			$sheetname = 'Sheet1';
		}

		$me = (new CommonController)->get_current_user();

		$user = DB::table('users')->select('users.Id','StaffId','Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Position','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    	->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $id)
		->first();

		$options= DB::table('options')
		->whereIn('Table', ["users","timesheets"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		if ($start==null)
		{

			$start=date('d-M-Y', strtotime('first day of this month'));

		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('last day of this month'));

		}

		$timesheetdetail = DB::table('timesheets')
		->select('timesheets.Date',DB::raw('"" as Day'),'timesheets.Check_In_Type',
		'timesheets.Time_In','timesheets.Time_Out','timesheets.Allowance','timesheets.Leader_Member','timesheets.Next_Person','timesheets.Site_Name','timesheets.State','timesheets.Work_Description','timesheets.Reason','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Review_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Timesheet" Group By TargetId) as maxfile'), 'maxfile.TargetId', '=', 'timesheets.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('maxfile.`maxid` and files.`Type`="Timesheet"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		->where('timesheets.UserId', '=', $id)
		// ->where('timesheetstatuses.UserId', '=', $me->UserId)
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->orderBy(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),'asc')
		->get();

		$mylevel = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country')
		->where('approvalsettings.Type', '=', 'Timesheet')
		->where('approvalsettings.UserId', '=', $me->UserId)
		->orderBy('approvalsettings.Country','asc')
		// ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
		->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
		->first();

		$total = DB::table('timesheets')
		->select(DB::Raw('sum(timesheets.Allowance) As TotalAllowance'))
		->where('timesheets.UserId', '=' , $id)
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->get();

		Excel::create($filename, function($excel) use($sheetname, $me,$id,$user,$start,$end,$timesheetdetail,$options,$mylevel,$total){

            $excel->sheet($sheetname, function($sheet) use($me,$id,$user,$start,$end,$timesheetdetail,$options,$mylevel,$total){

                $sheet->loadView('exceltimesheet', array('me' => $me, 'UserId' =>$id, 'user' =>$user,'start'=>$start,'end'=>$end,'timesheetdetail' => $timesheetdetail, 'options' =>$options,'mylevel' => $mylevel,'total' => $total));

            });

        })->download('xls');
	}

	 public function ARInvoiceExcel()
	{

		$filename = 'ARInvoice';
		$sheetname = 'Sheet1';

		$me = (new CommonController)->get_current_user();

		$detail = DB::table('salesorder')
		->select('Id','invoice_number as Old_Invoice_Number',DB::raw('""as New_Invoice_Number'))
		->orderBy('invoice_number','ASC')
		->where('invoice','=',1)
		->get();

		Excel::create($filename, function($excel) use($sheetname,$detail){

            $excel->sheet($sheetname, function($sheet) use($detail){

                $sheet->loadView('ARInvoiceExcel', array('detail'=>$detail));
            });

        })->download('csv');
	}

	function ARInvoiceImport(Request $request)
    {
    	$input = $request->all();
        $this->validate($request, array(
            'file'      => 'required'
        ));
        if($request->hasFile('file')){
        	$file = $input['file'];
            $extension = $file->getClientOriginalExtension();
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
 
                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                })->get();
                if(!empty($data) && $data->count()){
                    foreach ($data as $key => $value) {
                    	if( ($value->old_invoice_number != $value->new_invoice_number) && ($value->new_invoice_number != "" || $value->new_invoice_number != NULL) )
                    	{
                    	
	                    	DB::Table('salesorder')
	                    	->where('Id','=',$value->id)
	                    	->update([
	                    		'invoice_number' => $value->new_invoice_number
	                    	]);
                    	}
                    }
                }
 
                return 1;
 
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return 0;
            }
        }
    }
}