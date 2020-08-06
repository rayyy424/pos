<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CommonController extends Controller {


	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function get_current_user()
	{

    $auth = Auth::user();

    $me = DB::table('users')
    ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
    ->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
		->leftJoin('allowanceschemes', 'allowanceschemes.Id', '=', 'users.AllowanceSchemeId')
		->select('users.Id as UserId','users.AccessControlTemplateId','users.StaffId as Staff_ID','users.Name','users.Nick_Name','users.Company_Email','users.Personal_Email','users.Contact_No_1','users.Contact_No_2','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.Password','users.User_Type','users.Nationality','users.DOB','users.NRIC','users.Passport_No','users.Gender','users.Marital_Status','users.SuperiorId','users.Company','users.Department','users.Category','users.Entitled_for_OT','users.Working_Days','users.Position','users.Grade','users.Joining_Date','users.Resignation_Date','users.Emergency_Contact_Person','users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Address','users.Note','users.Active','users.Admin','users.Approved',
		'users.First_Change','files.Web_Path','allowanceschemes.Scheme_Name','users.Confirmation_Date','users.Bank_Account_No','users.Bank_Name','users.HolidayTerritoryId','users.Payslip_Password',
		'Template_Name',
		'View_User_Profile',
		'Edit_User',
		'Staff_Monitoring',
		'Edit_Time',
		'View_CV',
		'Export_CV',
		'Edit_CV',
		'View_Contractor_Profile',
		'Edit_Contractor',
		'View_Org_Chart',
		'Update_Org_Chart',
		'Approve_Leave',
		'View_All_Leave',
		'Delete_Leave',
		'View_Leave_Summary',
		'Show_Leave_To_Public',
		'Approve_Timesheet',
		'View_All_Timesheet',
		'View_Timesheet_Summary',
		'Update_Payment_Month',
		'View_Allowance',
		'Edit_Allowance',
		'Timesheet_Required',
		'Approve_Claim',
		'View_All_Claim',
		'View_Claim_Summary',
		'Access_Control',
		'Approval_Control',
		'Allowance_Control',
		'Asset_Tracking',
		'Option_Control',
		'Holiday_Management',
		'Notice_Board_Management',
		'Cutoff_Management',
		'Chart_Management',
		'Project_Access',
		'Template_Access',
		'Notification_Maintenance',
		'View_Login_Tracking',
		'Template_Access',
		'Import_Data',
		'Create_Project',
		'Create_Project_Code',
		'Project_Manager',
		'View_Project_List',
		'View_Resource_Calendar',
		'View_Resource_Summary',
		'View_Report_Store',
		'Delete_File',
		'Resource_Allocation',
		'Staff_Skill',
		'Project_Requirement',
		'Aging_Rules_Management',
		'Target_Rules_Management',
		'Dependency_Rules_Management',
		'License_Checklist',
		'Tracker_Management',
		'Import_Tracker',
		'Create_PO',
		'Delete_PO',
		'View_PO_Management',
		'View_PO_Summary',
		'Create_Invoice',
		'Delete_Invoice',
		'View_Invoice_Management',
		'View_Invoice_Summary',
		'View_WIP',
		'View_Forecast',
		'View_PNL',
		'Leave_Entitlement',
		'Schedule',
		'IT_Services',
		'Service_Contact',
		'Staff_Loan',
		'Presaving',
		'Staff_Expenses',
		'Deduction',
		'Request_Management',

		'Payroll',
		'Motor_Vehicle' ,
		'Phone_Bills' ,
		'Insurance' ,
		'Shell_Cards',
		'Summon' ,
		'TouchNGo' ,
		'Credit_Card' ,
		'Utility_Bills' ,
		'License' ,
		'Agreement' ,
		'Property' ,
		'Filing_System' ,
		'Printer',
		'View_All_Staff_Loan',
		'Approve_Staff_Loan',
		'Update_Medical_Claim',
		'OT_Management_HR',
		'OT_Management_HOD',
		'Payslip_Management',
		'Leave_Adjustment',
		'View_Department_Attendance_Summary',
		'View_Incentive_Summary',
		'View_Medical_Claim_Summary',
		'View_Staff_Deduction_Detail',

		'Add_Column',
		'Delete_Column',
		'Update_Column',
		'Reorder_Column',
		'Add_Option',
		'Edit_Project_Code',

		'View_Vendor_Management',
		'View_Inventory_Management',

		'Delivery_Dashboard',
		'Delivery_Approval',
		'Delete_Delivery',
		'Delivery_Tracking',
		'Site_Delivery_Summary',
		'Warehouse_Checklist',
		'Requestor_KPI',
		'Driver_KPI',
		'Warehouse_KPI',

		'Material_Request',
		'View_Costing_Summary',
		'View_OSU_Costing_Summary',
		'Material_Approval',
		'Driver_Incentive_Summary',
		'Transport_Charges',
		'View_Material_Request',
		'View_PO_Listing',
		'Generate_PO',
		'Quotation_Approval',
		'ewallet_update',
		'Upload_Quotation',
		'Recall_MR',
		'Genset',
		'Update_Inventory',
		'Sales_Order',
		'View_Invoice',
		'Generate_Invoice',
		'invoice_listing',
		'Delete_SO',
		'Sales_Summary',
		'ewallet',
		'dummy_do',
		'invoicepdf',
		'View_All_Branch',
		'radius',
		'Delete_Todo',
		'Delete_RQO',
		'accesscontroltemplates.Costing',
		'accesscontroltemplates.autocount',
		'accesscontroltemplates.View_MIA',
		'accesscontroltemplates.Recur_SO',
		'accesscontroltemplates.Update_Inv',
		'accesscontroltemplates.Update_Inv_Num',
		'accesscontroltemplates.Storekeeper',
		'accesscontroltemplates.View_Todolist',
		'accesscontroltemplates.Todolist_Dashboard',
		'accesscontroltemplates.SO_Details',
		'accesscontroltemplates.Task_List',
		'accesscontroltemplates.CME_Dashboard',
		'Genset_Summary',
		'accesscontroltemplates.*',
		'Detail_Approved_On','Status','Comment',DB::raw('(select GROUP_CONCAT(CONCAT(projects.Id) SEPARATOR "|") as ProjectIds from projects WHERE Id in (SELECT ProjectId FROM projectaccess WHERE UserId='.$auth->Id.') Order By Project_Name) as ProjectIds'),DB::raw('(select GROUP_CONCAT(CONCAT(projects.Project_Name) SEPARATOR "|") as ProjectNames from projects WHERE Id in (SELECT ProjectId FROM projectaccess WHERE UserId='.$auth->Id.') Order By Project_Name) as ProjectNames'),
		DB::raw('"" as NoticeId'),DB::raw('"" as NoticeTitle'))
    ->where('users.Id', '=',$auth -> Id)
    ->first();

		if ($me -> Web_Path=="")
		{
				$me -> Web_Path = URL::to('/') ."/img/default-user.png" ;
		}

		$Date=date("d-M-Y", strtotime('+2 weeks'));
		$Today=date("d-M-Y");

		$notice = DB::table('noticeboards')
    ->select('noticeboards.Id','noticeboards.Title','noticeboards.Content','noticeboards.Start_Date','noticeboards.End_Date','users.Name as Created_By','noticeboards.created_at','f.FileName','f.Attachment')
    ->where(DB::raw('str_to_date(noticeboards.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$Date.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(noticeboards.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$Today.'","%d-%M-%Y")'))
    ->leftJoin('users', 'users.Id', '=', 'noticeboards.UserId')
		->leftJoin(DB::raw('(SELECT TargetId, GROUP_CONCAT( Web_Path SEPARATOR "|") as Attachment,GROUP_CONCAT( File_Name SEPARATOR "|") as FileName FROM files WHERE Type="Notice" GROUP BY TargetId) as f'),'f.TargetId','=','noticeboards.Id')
    ->orderBy('noticeboards.Start_Date','asc')
    ->get();

		$noticeid="";
		$noticetitle="";

		foreach ($notice as $item) {
			# code...
			$noticeid=$noticeid.$item->Id."|";
			$noticetitle=$noticetitle.$item->Title."|";

		}

		$noticeid=substr($noticeid,0,strlen($noticeid)-1);
		$noticetitle=substr($noticetitle,0,strlen($noticetitle)-1);

		$me -> NoticeId = $noticeid ;
		$me -> NoticeTitle = $noticetitle ;

    return $me;

	}

}
