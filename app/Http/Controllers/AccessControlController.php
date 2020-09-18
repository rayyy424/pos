<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;

class AccessControlController extends Controller {

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

        $accounts = DB::table('users')
        ->leftJoin('accesscontroltemplates', 'users.AccessControlTemplateId', '=', 'accesscontroltemplates.Id')
        ->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
        ->select('users.Id','users.Status','users.Internship_Status','users.Internship_End_Date','users.Name','users.StaffId as Staff_ID','users.User_Type','superior.Name as Superior','users.Country_Base','users.Home_Base','accesscontroltemplates.Template_Name as Access_Control_Template','users.Active','users.Admin','users.Approved')
        ->orderBy('users.Name','asc')
        ->get();

        $users = DB::table('users')->select('users.Id','files.Web_Path','users.StaffId as Staff_ID','users.Name','users.Nick_Name','users.User_Type','users.Company_Email','users.Personal_Email','users.Contact_No_1','users.Contact_No_2','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.Nationality','users.DOB','users.NRIC','users.Passport_No','users.Gender','users.Marital_Status','superior.Name as Superior','users.Position','users.Joining_Date','users.Resignation_Date','users.Emergency_Contact_Person','users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Address')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
        ->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
        ->where('users.User_Type','<>','Contractor')
        ->orderBy('users.Id')
        ->get();

        $templates = DB::table('accesscontroltemplates')
        ->orderBy('accesscontroltemplates.Template_Name','asc')
        ->get();

        $options= DB::table('options')
        ->whereIn('Table', ["users"])
        ->orderBy('Table','asc')
        ->orderBy('Option','asc')
        ->get();

        $access = DB::table('accesscontroltemplates')
        ->select('accesscontroltemplates.Id','accesscontroltemplates.Template_Name',
        DB::raw("if(`accesscontroltemplates`.View_User_Profile, 'Yes', 'No') as View_User_Profile"),
        DB::raw("if(`accesscontroltemplates`.Edit_User, 'Yes', 'No') as Edit_User"),
        DB::raw("if(`accesscontroltemplates`.Staff_Monitoring, 'Yes', 'No') as Staff_Monitoring"),
        DB::raw("if(`accesscontroltemplates`.Edit_Time, 'Yes', 'No') as Edit_Time"),
        DB::raw("if(`accesscontroltemplates`.View_CV, 'Yes', 'No') as View_CV"),
        DB::raw("if(`accesscontroltemplates`.Export_CV, 'Yes', 'No') as Export_CV"),
        DB::raw("if(`accesscontroltemplates`.Edit_CV, 'Yes', 'No') as Edit_CV"),
        DB::raw("if(`accesscontroltemplates`.View_Contractor_Profile, 'Yes', 'No') as View_Contractor_Profile"),
        DB::raw("if(`accesscontroltemplates`.Edit_Contractor, 'Yes', 'No') as Edit_Contractor"),
        DB::raw("if(`accesscontroltemplates`.View_Org_Chart, 'Yes', 'No') as View_Org_Chart"),
        DB::raw("if(`accesscontroltemplates`.Update_Org_Chart, 'Yes', 'No') as Update_Org_Chart"),
        DB::raw("if(`accesscontroltemplates`.Approve_Leave, 'Yes', 'No') as Approve_Leave"),
        DB::raw("if(`accesscontroltemplates`.View_All_Leave, 'Yes', 'No') as View_All_Leave"),
        DB::raw("if(`accesscontroltemplates`.Delete_Leave, 'Yes', 'No') as Delete_Leave"),
        DB::raw("if(`accesscontroltemplates`.View_Leave_Summary, 'Yes', 'No') as View_Leave_Summary"),
        DB::raw("if(`accesscontroltemplates`.Show_Leave_To_Public, 'Yes', 'No') as Show_Leave_To_Public"),
        DB::raw("if(`accesscontroltemplates`.Approve_Timesheet, 'Yes', 'No') as Approve_Timesheet"),
        DB::raw("if(`accesscontroltemplates`.View_All_Timesheet, 'Yes', 'No') as View_All_Timesheet"),
        DB::raw("if(`accesscontroltemplates`.View_Timesheet_Summary, 'Yes', 'No') as View_Timesheet_Summary"),
        DB::raw("if(`accesscontroltemplates`.View_Allowance, 'Yes', 'No') as View_Allowance"),
        DB::raw("if(`accesscontroltemplates`.Edit_Allowance, 'Yes', 'No') as Edit_Allowance"),
        DB::raw("if(`accesscontroltemplates`.Timesheet_Required, 'Yes', 'No') as Timesheet_Required"),
        DB::raw("if(`accesscontroltemplates`.Approve_Claim, 'Yes', 'No') as Approve_Claim"),
        DB::raw("if(`accesscontroltemplates`.View_All_Claim, 'Yes', 'No') as View_All_Claim"),
        DB::raw("if(`accesscontroltemplates`.View_Claim_Summary, 'Yes', 'No') as View_Claim_Summary"),
        DB::raw("if(`accesscontroltemplates`.Update_Payment_Month, 'Yes', 'No') as Update_Payment_Month"),
        DB::raw("if(`accesscontroltemplates`.Access_Control, 'Yes', 'No') as Access_Control"),
        DB::raw("if(`accesscontroltemplates`.Approval_Control, 'Yes', 'No') as Approval_Control"),
        DB::raw("if(`accesscontroltemplates`.Allowance_Control, 'Yes', 'No') as Allowance_Control"),
        DB::raw("if(`accesscontroltemplates`.Asset_Tracking, 'Yes', 'No') as Asset_Tracking"),
        DB::raw("if(`accesscontroltemplates`.Option_Control, 'Yes', 'No') as Option_Control"),
        DB::raw("if(`accesscontroltemplates`.Holiday_Management, 'Yes', 'No') as Holiday_Management"),
        DB::raw("if(`accesscontroltemplates`.Notice_Board_Management, 'Yes', 'No') as Notice_Board_Management"),
        DB::raw("if(`accesscontroltemplates`.Cutoff_Management, 'Yes', 'No') as Cutoff_Management"),
        DB::raw("if(`accesscontroltemplates`.Chart_Management, 'Yes', 'No') as Chart_Management"),
        DB::raw("if(`accesscontroltemplates`.Template_Access, 'Yes', 'No') as Template_Access"),
        DB::raw("if(`accesscontroltemplates`.Notification_Maintenance, 'Yes', 'No') as Notification_Maintenance"),
        DB::raw("if(`accesscontroltemplates`.Leave_Entitlement, 'Yes', 'No') as Leave_Entitlement"),
        DB::raw("if(`accesscontroltemplates`.Schedule, 'Yes', 'No') as Schedule"),
        DB::raw("if(`accesscontroltemplates`.View_Login_Tracking, 'Yes', 'No') as View_Login_Tracking"),
        DB::raw("if(`accesscontroltemplates`.Import_Data, 'Yes', 'No') as Import_Data"),
        DB::raw("if(`accesscontroltemplates`.View_Resource_Calendar, 'Yes', 'No') as View_Resource_Calendar"),
        DB::raw("if(`accesscontroltemplates`.View_Resource_Summary, 'Yes', 'No') as View_Resource_Summary"),
        DB::raw("if(`accesscontroltemplates`.View_Report_Store, 'Yes', 'No') as View_Report_Store"),
        DB::raw("if(`accesscontroltemplates`.Resource_Allocation, 'Yes', 'No') as Resource_Allocation"),
        DB::raw("if(`accesscontroltemplates`.Staff_Skill, 'Yes', 'No') as Staff_Skill"),
        DB::raw("if(`accesscontroltemplates`.Aging_Rules_Management, 'Yes', 'No') as Aging_Rules_Management"),
        DB::raw("if(`accesscontroltemplates`.Target_Rules_Management, 'Yes', 'No') as Target_Rules_Management"),
        DB::raw("if(`accesscontroltemplates`.Dependency_Rules_Management, 'Yes', 'No') as Dependency_Rules_Management"),
        DB::raw("if(`accesscontroltemplates`.Auto_Date_Management, 'Yes', 'No') as Auto_Date_Management"),
        DB::raw("if(`accesscontroltemplates`.Site_Issue, 'Yes', 'No') as Site_Issue"),
        DB::raw("if(`accesscontroltemplates`.Task_Management, 'Yes', 'No') as Task_Management"),

        DB::raw("if(`accesscontroltemplates`.License_Checklist, 'Yes', 'No') as License_Checklist"),
        DB::raw("if(`accesscontroltemplates`.Tracker_Management, 'Yes', 'No') as Tracker_Management"),

        DB::raw("if(`accesscontroltemplates`.Add_Column, 'Yes', 'No') as Add_Column"),
    		DB::raw("if(`accesscontroltemplates`.Update_Column, 'Yes', 'No') as Update_Column"),
    		DB::raw("if(`accesscontroltemplates`.Delete_Column, 'Yes', 'No') as Delete_Column"),
    		DB::raw("if(`accesscontroltemplates`.Reorder_Column, 'Yes', 'No') as Reorder_Column"),
    		DB::raw("if(`accesscontroltemplates`.Add_Option, 'Yes', 'No') as Add_Option"),
        DB::raw("if(`accesscontroltemplates`.Delete_File, 'Yes', 'No') as Delete_File"),

        DB::raw("if(`accesscontroltemplates`.Import_Tracker, 'Yes', 'No') as Import_Tracker"),
        DB::raw("if(`accesscontroltemplates`.Create_PO, 'Yes', 'No') as Create_PO"),
        DB::raw("if(`accesscontroltemplates`.Delete_PO, 'Yes', 'No') as Delete_PO"),
        DB::raw("if(`accesscontroltemplates`.View_PO_Management, 'Yes', 'No') as View_PO_Management"),
        DB::raw("if(`accesscontroltemplates`.View_PO_Summary, 'Yes', 'No') as View_PO_Summary"),
        DB::raw("if(`accesscontroltemplates`.Create_Invoice, 'Yes', 'No') as Create_Invoice"),
        DB::raw("if(`accesscontroltemplates`.Delete_Invoice, 'Yes', 'No') as Delete_Invoice"),
        DB::raw("if(`accesscontroltemplates`.View_Invoice_Management, 'Yes', 'No') as View_Invoice_Management"),
        DB::raw("if(`accesscontroltemplates`.View_Invoice_Summary, 'Yes', 'No') as View_Invoice_Summary"),
        DB::raw("if(`accesscontroltemplates`.Credit_Note, 'Yes', 'No') as Credit_Note"),
        DB::raw("if(`accesscontroltemplates`.Credit_Note_List, 'Yes', 'No') as Credit_Note_List"),
        DB::raw("if(`accesscontroltemplates`.View_WIP, 'Yes', 'No') as View_WIP"),
        DB::raw("if(`accesscontroltemplates`.View_Forecast, 'Yes', 'No') as View_Forecast"),
        DB::raw("if(`accesscontroltemplates`.View_PNL, 'Yes', 'No') as View_PNL"),
        DB::raw("if(`accesscontroltemplates`.Staff_Loan, 'Yes', 'No') as Staff_Loan"),
        DB::raw("if(`accesscontroltemplates`.Presaving, 'Yes', 'No') as Presaving"),
        DB::raw("if(`accesscontroltemplates`.Staff_Expenses, 'Yes', 'No') as Staff_Expenses"),
        DB::raw("if(`accesscontroltemplates`.Deduction, 'Yes', 'No') as Deduction"),
        DB::raw("if(`accesscontroltemplates`.Request_Management, 'Yes', 'No') as Request_Management"),

        DB::raw("if(`accesscontroltemplates`.Payroll, 'Yes', 'No') as Payroll"),
        DB::raw("if(`accesscontroltemplates`.Motor_Vehicle, 'Yes', 'No') as Motor_Vehicle"),
        DB::raw("if(`accesscontroltemplates`.Phone_Bills, 'Yes', 'No') as Phone_Bills"),
        DB::raw("if(`accesscontroltemplates`.Insurance, 'Yes', 'No') as Insurance"),
        DB::raw("if(`accesscontroltemplates`.Shell_Cards, 'Yes', 'No') as Shell_Cards"),
        DB::raw("if(`accesscontroltemplates`.Summon, 'Yes', 'No') as Summon"),
        DB::raw("if(`accesscontroltemplates`.TouchNGo, 'Yes', 'No') as TouchNGo"),
        DB::raw("if(`accesscontroltemplates`.Credit_Card, 'Yes', 'No') as Credit_Card"),
        DB::raw("if(`accesscontroltemplates`.Utility_Bills, 'Yes', 'No') as Utility_Bills"),
        DB::raw("if(`accesscontroltemplates`.License, 'Yes', 'No') as License"),
        DB::raw("if(`accesscontroltemplates`.Agreement, 'Yes', 'No') as Agreement"),
        DB::raw("if(`accesscontroltemplates`.Property, 'Yes', 'No') as Property"),
        DB::raw("if(`accesscontroltemplates`.Filing_System, 'Yes', 'No') as Filing_System"),
        DB::raw("if(`accesscontroltemplates`.Printer, 'Yes', 'No') as Printer"),
        DB::raw("if(`accesscontroltemplates`.IT_Services, 'Yes', 'No') as IT_Services"),
        DB::raw("if(`accesscontroltemplates`.Service_Contact, 'Yes', 'No') as Service_Contact"),
        DB::raw("if(`accesscontroltemplates`.Approve_Staff_Loan, 'Yes', 'No') as Approve_Staff_Loan"),
        DB::raw("if(`accesscontroltemplates`.View_All_Staff_Loan, 'Yes', 'No') as View_All_Staff_Loan"),
        DB::raw("if(`accesscontroltemplates`.Update_Medical_Claim, 'Yes', 'No') as Update_Medical_Claim"),
        DB::raw("if(`accesscontroltemplates`.OT_Management_HR, 'Yes', 'No') as OT_Management_HR"),
        DB::raw("if(`accesscontroltemplates`.OT_Management_HOD, 'Yes', 'No') as OT_Management_HOD"),
        DB::raw("if(`accesscontroltemplates`.Payslip_Management, 'Yes', 'No') as Payslip_Management"),
        DB::raw("if(`accesscontroltemplates`.Leave_Adjustment, 'Yes', 'No') as Leave_Adjustment"),
        DB::raw("if(`accesscontroltemplates`.View_Incentive_Summary, 'Yes', 'No') as View_Incentive_Summary"),
        DB::raw("if(`accesscontroltemplates`.View_Medical_Claim_Summary, 'Yes', 'No') as View_Medical_Claim_Summary"),
        DB::raw("if(`accesscontroltemplates`.View_Staff_Deduction_Detail, 'Yes', 'No') as View_Staff_Deduction_Detail"),

        DB::raw("if(`accesscontroltemplates`.Delivery_Dashboard, 'Yes', 'No') as Delivery_Dashboard"),
        DB::raw("if(`accesscontroltemplates`.Delivery_Approval, 'Yes', 'No') as Delivery_Approval"),
        DB::raw("if(`accesscontroltemplates`.Delete_Delivery, 'Yes', 'No') as Delete_Delivery"),
        DB::raw("if(`accesscontroltemplates`.Delivery_Tracking, 'Yes', 'No') as Delivery_Tracking"),
        DB::raw("if(`accesscontroltemplates`.Site_Delivery_Summary, 'Yes', 'No') as Site_Delivery_Summary"),
        DB::raw("if(`accesscontroltemplates`.Warehouse_Checklist, 'Yes', 'No') as Warehouse_Checklist"),
        DB::raw("if(`accesscontroltemplates`.Requestor_KPI, 'Yes', 'No') as Requestor_KPI"),
        DB::raw("if(`accesscontroltemplates`.Driver_KPI, 'Yes', 'No') as Driver_KPI"),
        DB::raw("if(`accesscontroltemplates`.Warehouse_KPI, 'Yes', 'No') as Warehouse_KPI"),

        DB::raw("if(`accesscontroltemplates`.Material_Request, 'Yes', 'No') as Material_Request"),
        DB::raw("if(`accesscontroltemplates`.View_Costing_Summary, 'Yes', 'No') as View_Costing_Summary"),
        DB::raw("if(`accesscontroltemplates`.View_OSU_Costing_Summary, 'Yes', 'No') as View_OSU_Costing_Summary"),

        DB::raw("if(`accesscontroltemplates`.Material_Approval, 'Yes', 'No') as Material_Approval"),
        DB::raw("if(`accesscontroltemplates`.Driver_Incentive_Summary, 'Yes', 'No') as Driver_Incentive_Summary"),
        DB::raw("if(`accesscontroltemplates`.Transport_Charges, 'Yes', 'No') as Transport_Charges"),
        DB::raw("if(`accesscontroltemplates`.View_Material_Request, 'Yes', 'No') as View_Material_Request"),
        DB::raw("if(`accesscontroltemplates`.View_PO_Listing, 'Yes', 'No') as View_PO_Listing"),
        DB::raw("if(`accesscontroltemplates`.Generate_PO, 'Yes', 'No') as Generate_PO"),
        DB::raw("if(`accesscontroltemplates`.Quotation_Approval, 'Yes', 'No') as Quotation_Approval"),
        DB::raw("if(`accesscontroltemplates`.Upload_Quotation, 'Yes', 'No') as Upload_Quotation"),
        DB::raw("if(`accesscontroltemplates`.Recall_MR, 'Yes', 'No') as Recall_MR"),
        DB::raw("if(`accesscontroltemplates`.Speedfreak, 'Yes', 'No') as Speedfreak"),
        DB::raw("if(`accesscontroltemplates`.Speedfreak_Summary, 'Yes', 'No') as Speedfreak_Summary"),
        DB::raw("if(`accesscontroltemplates`.Delete_RQO, 'Yes', 'No') as Delete_RQO"),
        DB::raw("if(`accesscontroltemplates`.Update_Inventory, 'Yes', 'No') as Update_Inventory"),
        DB::raw("if(`accesscontroltemplates`.Sales_Order, 'Yes', 'No') as Sales_Order"),
        DB::raw("if(`accesscontroltemplates`.Sales_Summary, 'Yes', 'No') as Sales_Summary"),
        DB::raw("if(`accesscontroltemplates`.Delete_SO, 'Yes', 'No') as Delete_SO"),
        DB::raw("if(`accesscontroltemplates`.Recur_SO, 'Yes', 'No') as Recur_SO"),
        DB::raw("if(`accesscontroltemplates`.View_Invoice, 'Yes', 'No') as View_Invoice"),
        DB::raw("if(`accesscontroltemplates`.Update_Inv_Num, 'Yes', 'No') as Update_Inv_Num"),
        DB::raw("if(`accesscontroltemplates`.Update_Inv, 'Yes', 'No') as Update_Inv"),
        DB::raw("if(`accesscontroltemplates`.Generate_Invoice, 'Yes', 'No') as Generate_Invoice"),
        DB::raw("if(`accesscontroltemplates`.invoice_listing, 'Yes', 'No') as invoice_listing"),
        DB::raw("if(`accesscontroltemplates`.Storekeeper, 'Yes', 'No') as Storekeeper"),
        DB::raw("if(`accesscontroltemplates`.dummy_do, 'Yes', 'No') as dummy_do"),
        DB::raw("if(`accesscontroltemplates`.Costing, 'Yes', 'No') as Costing"),
        DB::raw("if(`accesscontroltemplates`.autocount, 'Yes', 'No') as autocount"),
        DB::raw("if(`accesscontroltemplates`.View_MIA, 'Yes', 'No') as View_MIA"),
        DB::raw("if(`accesscontroltemplates`.ewallet, 'Yes', 'No') as ewallet"),
        DB::raw("if(`accesscontroltemplates`.Todolist_Dashboard, 'Yes', 'No') as Todolist_Dashboard"),
        DB::raw("if(`accesscontroltemplates`.View_Todolist, 'Yes', 'No') as View_Todolist"),
        DB::raw("if(`accesscontroltemplates`.Delete_Todo, 'Yes', 'No') as Delete_Todo"),
        DB::raw("if(`accesscontroltemplates`.SO_Details, 'Yes', 'No') as SO_Details"),
        DB::raw("if(`accesscontroltemplates`.Task_List, 'Yes', 'No') as Task_List"),
        DB::raw("if(`accesscontroltemplates`.CME_Dashboard, 'Yes', 'No') as CME_Dashboard"),
        DB::raw("if(`accesscontroltemplates`.View_All_Branch, 'Yes', 'No') as View_All_Branch"),
        DB::raw("if(`accesscontroltemplates`.radius, 'Yes', 'No') as radius")
        )
        ->first();

    return view('accesscontrol', ['me' => $me,'users' => $users,'access' => $access,'accounts' => $accounts,'templates' => $templates,'options' =>$options]);

    }

    public function accesscontrol($Id)
    {
        $me = (new CommonController)->get_current_user();

        $template = DB::table('accesscontroltemplates')
        ->get();

        return view('modifyaccesscontrol', ['me' => $me,'access' => $access, 'templates' => $template]);

    }

    public function update(Request $request)
    {

            $input = $request->all();

            $value=json_decode($input["value"],true);
            $id=$value["Id"];


            $result= DB::table('accesscontroltemplates')
            ->where('Id', $id)
            ->update(array(
                        'View_User_Profile' =>  $input["View_User_Profile"],
                        'Edit_User' =>  $input["Edit_User"],
                        'Staff_Monitoring' =>  $input["Staff_Monitoring"],
                        'Edit_Time' =>  $input["Edit_Time"],
                        'View_CV' =>  $input["View_CV"],
                        'Export_CV' =>  $input["Export_CV"],
                        'Edit_CV' =>  $input["Edit_CV"],
                        'View_Contractor_Profile' =>  $input["View_Contractor_Profile"],
                        'Edit_Contractor' =>  $input["Edit_Contractor"],
                        'View_Org_Chart' =>  $input["View_Org_Chart"],
                        'Update_Org_Chart' =>  $input["Update_Org_Chart"],
                        'Approve_Leave' =>  $input["Approve_Leave"],
                        'View_All_Leave' =>  $input["View_All_Leave"],
                        'Delete_Leave' =>  $input["Delete_Leave"],
                        'View_Leave_Summary' =>  $input["View_Leave_Summary"],
                        'Show_Leave_To_Public' =>  $input["Show_Leave_To_Public"],
                        'Approve_Timesheet' =>  $input["Approve_Timesheet"],
                        'View_All_Timesheet' =>  $input["View_All_Timesheet"],
                        'View_Timesheet_Summary' =>  $input["View_Timesheet_Summary"],
                        'Timesheet_Required' =>  $input["Timesheet_Required"],
                        'View_Allowance' =>  $input["View_Allowance"],
                        'Edit_Allowance' =>  $input["Edit_Allowance"],
                        'Approve_Claim' =>  $input["Approve_Claim"],
                        'View_All_Claim' =>  $input["View_All_Claim"],
                        'View_Claim_Summary' =>  $input["View_Claim_Summary"],
                        'Update_Payment_Month' =>  $input["Update_Payment_Month"],
                        'Access_Control' =>  $input["Access_Control"],
                        'Approval_Control' =>  $input["Approval_Control"],
                        'Allowance_Control' =>  $input["Allowance_Control"],
                        'Asset_Tracking' =>  $input["Asset_Tracking"],
                        'Option_Control' =>  $input["Option_Control"],
                        'Holiday_Management' =>  $input["Holiday_Management"],
                        'Notice_Board_Management' =>  $input["Notice_Board_Management"],
                        'Cutoff_Management' =>  $input["Cutoff_Management"],
                        'Chart_Management' =>  $input["Chart_Management"],
                        'Template_Access' =>  $input["Template_Access"],
                        'Notification_Maintenance' =>  $input["Notification_Maintenance"],
                        'Leave_Entitlement' =>  $input["Leave_Entitlement"],
                        'Schedule' =>  $input["Schedule"],
                        'View_Login_Tracking' =>  $input["View_Login_Tracking"],
                        'Import_Data' =>  $input["Import_Data"],
                        'View_Resource_Calendar' =>  $input["View_Resource_Calendar"],
                        'View_Resource_Summary' =>  $input["View_Resource_Summary"],
                        'View_Report_Store' =>  $input["View_Report_Store"],
                        'Resource_Allocation' =>  $input["Resource_Allocation"],
                        'Staff_Skill' =>  $input["Staff_Skill"],
                        'Aging_Rules_Management' =>  $input["Aging_Rules_Management"],
                        'Target_Rules_Management' =>  $input["Target_Rules_Management"],
                        'Dependency_Rules_Management' =>  $input["Dependency_Rules_Management"],
                        'Task_Management' =>  $input["Task_Management"],
                        'Site_Issue' =>  $input["Site_Issue"],
                        'Auto_Date_Management' =>  $input["Auto_Date_Management"],

                        'License_Checklist' =>  $input["License_Checklist"],
                        'Tracker_Management' =>  $input["Tracker_Management"],

                        'Add_Column' =>  $input["Add_Column"],
            						'Delete_Column' =>  $input["Delete_Column"],
            						'Update_Column' =>  $input["Update_Column"],
            						'Reorder_Column' =>  $input["Reorder_Column"],
            						'Add_Option' =>  $input["Add_Option"],
                        'Delete_File' =>  $input["Delete_File"],

                        'Import_Tracker' =>  $input["Import_Tracker"],
                        'Create_PO' =>  $input["Create_PO"],
                        'Delete_PO' =>  $input["Delete_PO"],
                        'View_PO_Management' =>  $input["View_PO_Management"],
                        'View_PO_Summary' =>  $input["View_PO_Summary"],
                        'Create_Invoice' =>  $input["Create_Invoice"],
                        'Delete_Invoice' =>  $input["Delete_Invoice"],
                        'View_Invoice_Management' =>  $input["View_Invoice_Management"],
                        'View_Invoice_Summary' =>  $input["View_Invoice_Summary"],
                        'Credit_Note' => $input['Credit_Note'],
                        'Credit_Note_List' => $input['Credit_Note_List'],
                        'View_WIP' =>  $input["View_WIP"],
                        'View_Forecast' =>  $input["View_Forecast"],
                        'View_PNL' =>  $input["View_PNL"],
                        'Staff_Loan' =>  $input["Staff_Loan"],
                        'Presaving' =>  $input["Presaving"],
                        'Staff_Expenses' =>  $input["Staff_Expenses"],
                        'Deduction' =>  $input["Deduction"],
                        'Request_Management' =>  $input["Request_Management"],

                        'Payroll' =>  $input["Payroll"],
                        'Motor_Vehicle' =>  $input["Motor_Vehicle"],
                        'Phone_Bills' =>  $input["Phone_Bills"],
                        'Insurance' =>  $input["Insurance"],
                        'Shell_Cards' =>  $input["Shell_Cards"],
                        'Summon' =>  $input["Summon"],
                        'TouchNGo' =>  $input["TouchNGo"],
                        'Credit_Card' =>  $input["Credit_Card"],
                        'Utility_Bills' =>  $input["Utility_Bills"],
                        'License' =>  $input["License"],
                        'Agreement' =>  $input["Agreement"],
                        'Property' =>  $input["Property"],
                        'Filing_System' =>  $input["Filing_System"],
                        'Printer' =>  $input["Printer"],
                        'IT_Services' =>  $input["IT_Services"],
                        'Service_Contact' =>  $input["Service_Contact"],
                        'Approve_Staff_Loan' =>  $input["Approve_Staff_Loan"],
                        'View_All_Staff_Loan' =>  $input["View_All_Staff_Loan"],
                        'Update_Medical_Claim' =>  $input["Update_Medical_Claim"],
                        'OT_Management_HR' =>  $input["OT_Management_HR"],
                        'OT_Management_HOD' =>  $input["OT_Management_HOD"],
                        'Payslip_Management' =>  $input["Payslip_Management"],
                        'Leave_Adjustment' =>  $input["Leave_Adjustment"],
                        'View_Incentive_Summary' =>  $input["View_Incentive_Summary"],
                        'View_Medical_Claim_Summary' =>  $input["View_Medical_Claim_Summary"],
                        'View_Staff_Deduction_Detail' =>  $input["View_Staff_Deduction_Detail"],

                        'Delivery_Dashboard'=>$input['Delivery_Dashboard'],
                        'Delivery_Approval'=>$input['Delivery_Approval'],
                        'Delete_Delivery'=>$input['Delete_Delivery'],
                        'Delivery_Tracking'=>$input['Delivery_Tracking'],
                        'Site_Delivery_Summary'=>$input['Site_Delivery_Summary'],
                        'Warehouse_Checklist'=>$input['Warehouse_Checklist'],
                        'Requestor_KPI'=>$input['Requestor_KPI'],
                        'Driver_KPI'=>$input['Driver_KPI'],
                        'Warehouse_KPI'=>$input['Warehouse_KPI'],

                        'Material_Request'=>$input['Material_Request'],
                        'View_Costing_Summary'=>$input['View_Costing_Summary'],
                        'View_OSU_Costing_Summary'=>$input['View_OSU_Costing_Summary'],
                        'Material_Approval'=>$input['Material_Approval'],
                        'Driver_Incentive_Summary'=>$input['Driver_Incentive_Summary'],
                        'Transport_Charges'=>$input['Transport_Charges'],
                        'View_Material_Request'=>$input['View_Material_Request'],
                        'View_PO_Listing'=>$input['View_PO_Listing'],
                        'Generate_PO'=>$input['Generate_PO'],
                        'Quotation_Approval'=>$input['Quotation_Approval'],
                        'Upload_Quotation'=>$input['Upload_Quotation'],
                        'Recall_MR'=>$input['Recall_MR'],
                        'Speedfreak'=>$input['Speedfreak'],
                        'Speedfreak_Summary' => $input['Speedfreak_Summary'],
                        'Delete_RQO'=>$input['Delete_RQO'],
                        'Update_Inventory'=>$input['Update_Inventory'],
                        'Sales_Order'=>$input['Sales_Order'],
                        'Sales_Summary'=>$input['Sales_Summary'],
                        'Delete_SO'=>$input['Delete_SO'],
                        'Recur_SO'=>$input['Recur_SO'],
                        'Storekeeper'=>$input['Storekeeper'],
                        'View_Invoice'=>$input['View_Invoice'],
                        'Update_Inv_Num'=>$input['Update_Inv_Num'],
                        'Update_Inv'=>$input['Update_Inv'],
                        'Generate_Invoice'=>$input['Generate_Invoice'],
                        'invoice_listing'=>$input['invoice_listing'],
                        'dummy_do'=>$input['dummy_do'],
                        'Costing'=>$input['Costing'],
                        'autocount'=>$input['autocount'],
                        'View_MIA'=>$input['View_MIA'],
                        'ewallet'=>$input['ewallet'],
                        'Todolist_Dashboard'=>$input['Todolist_Dashboard'],
                        'View_Todolist'=>$input['View_Todolist'],
                        'Delete_Todo'=>$input['Delete_Todo'],
                        'SO_Details'=>$input['SO_Details'],
                        'Task_List'=>$input['Task_List'],
                        'CME_Dashboard'=>$input['CME_Dashboard'],
                        'View_All_Branch'=>$input['View_All_Branch'],
                        'radius'=>$input['radius']
                    ));

            if ($result==1)
            {
                return json_encode (DB::table('accesscontroltemplates')
                            ->where('Id', '=', $id)
                            ->first());
            }
            else {
                return 0;
            }


    }

    public function savetemplate(Request $request)
    {

            $input = $request->all();

            $id = DB::table('accesscontroltemplates')->insertGetId(array(
                        'Created_By' =>  $input["Created_By"],
                        'Template_Name' =>  $input["Template_Name"],
                        'View_User_Profile' =>  $input["View_User_Profile"],
                        'Edit_User' =>  $input["Edit_User"],
                        'Staff_Monitoring' =>  $input["Staff_Monitoring"],
                        'Edit_Time' =>  $input["Edit_Time"],
                        'View_CV' =>  $input["View_CV"],
                        'Export_CV' =>  $input["Export_CV"],
                        'Edit_CV' =>  $input["Edit_CV"],
                        'View_Contractor_Profile' =>  $input["View_Contractor_Profile"],
                        'Edit_Contractor' =>  $input["Edit_Contractor"],
                        'View_Org_Chart' =>  $input["View_Org_Chart"],
                        'Update_Org_Chart' =>  $input["Update_Org_Chart"],
                        'Approve_Leave' =>  $input["Approve_Leave"],
                        'View_All_Leave' =>  $input["View_All_Leave"],
                        'Delete_Leave' =>  $input["Delete_Leave"],
                        'View_Leave_Summary' =>  $input["View_Leave_Summary"],
                        'Show_Leave_To_Public' =>  $input["Show_Leave_To_Public"],
                        'Approve_Timesheet' =>  $input["Approve_Timesheet"],
                        'View_All_Timesheet' =>  $input["View_All_Timesheet"],
                        'View_Timesheet_Summary' =>  $input["View_Timesheet_Summary"],
                        'Timesheet_Required' =>  $input["Timesheet_Required"],
                        'View_Allowance' =>  $input["View_Allowance"],
                        'Edit_Allowance' =>  $input["Edit_Allowance"],
                        'Approve_Claim' =>  $input["Approve_Claim"],
                        'View_All_Claim' =>  $input["View_All_Claim"],
                        'View_Claim_Summary' =>  $input["View_Claim_Summary"],
                        'Update_Payment_Month' =>  $input["Update_Payment_Month"],
                        'Access_Control' =>  $input["Access_Control"],
                        'Approval_Control' =>  $input["Approval_Control"],
                        'Allowance_Control' =>  $input["Allowance_Control"],
                        'Asset_Tracking' =>  $input["Asset_Tracking"],
                        'Option_Control' =>  $input["Option_Control"],
                        'Holiday_Management' =>  $input["Holiday_Management"],
                        'Notice_Board_Management' =>  $input["Notice_Board_Management"],
                        'Cutoff_Management' =>  $input["Cutoff_Management"],
                        'Chart_Management' =>  $input["Chart_Management"],
                        'Template_Access' =>  $input["Template_Access"],
                        'Notification_Maintenance' =>  $input["Notification_Maintenance"],
                        'Leave_Entitlement' =>  $input["Leave_Entitlement"],
                        'Schedule' =>  $input["Schedule"],
                        'View_Login_Tracking' =>  $input["View_Login_Tracking"],
                        'Import_Data' =>  $input["Import_Data"],
                        'View_Resource_Calendar' =>  $input["View_Resource_Calendar"],
                        'View_Resource_Summary' =>  $input["View_Resource_Summary"],
                        'View_Report_Store' =>  $input["View_Report_Store"],
                        'Resource_Allocation' =>  $input["Resource_Allocation"],
                        'Staff_Skill' =>  $input["Staff_Skill"],
                        'Aging_Rules_Management' =>  $input["Aging_Rules_Management"],
                        'Target_Rules_Management' =>  $input["Target_Rules_Management"],
                        'Dependency_Rules_Management' =>  $input["Dependency_Rules_Management"],
                        'Auto_Date_Management' =>  $input["Auto_Date_Management"],
                        'Site_Issue' =>  $input["Site_Issue"],
                        'Task_Management' =>  $input["Task_Management"],

                        'License_Checklist' =>  $input["License_Checklist"],
                        'Tracker_Management' =>  $input["Tracker_Management"],

                        'Add_Column' =>  $input["Add_Column"],
                        'Delete_Column' =>  $input["Delete_Column"],
                        'Update_Column' =>  $input["Update_Column"],
                        'Reorder_Column' =>  $input["Reorder_Column"],
                        'Add_Option' =>  $input["Add_Option"],
                        'Delete_File' =>  $input["Delete_File"],

                        'Import_Tracker' =>  $input["Import_Tracker"],
                        'Create_PO' =>  $input["Create_PO"],
                        'Delete_PO' =>  $input["Delete_PO"],
                        'View_PO_Management' =>  $input["View_PO_Management"],
                        'View_PO_Summary' =>  $input["View_PO_Summary"],
                        'Create_Invoice' =>  $input["Create_Invoice"],
                        'Delete_Invoice' =>  $input["Delete_Invoice"],
                        'View_Invoice_Management' =>  $input["View_Invoice_Management"],
                        'View_Invoice_Summary' =>  $input["View_Invoice_Summary"],
                        'Credit_Note' => $input['Credit_Note'],
                        'Credit_Note_List' => $input['Credit_Note_List'],
                        'View_WIP' =>  $input["View_WIP"],
                        'View_Forecast' =>  $input["View_Forecast"],
                        'View_PNL' =>  $input["View_PNL"],
                        'Staff_Loan' =>  $input["Staff_Loan"],
                        'Presaving' =>  $input["Presaving"],
                        'Staff_Expenses' =>  $input["Staff_Expenses"],
                        'Deduction' =>  $input["Deduction"],
                        'Request_Management' =>  $input["Request_Management"],

                        'Payroll' =>  $input["Payroll"],
                        'Motor_Vehicle' =>  $input["Motor_Vehicle"],
                        'Phone_Bills' =>  $input["Phone_Bills"],
                        'Insurance' =>  $input["Insurance"],
                        'Shell_Cards' =>  $input["Shell_Cards"],
                        'Summon' =>  $input["Summon"],
                        'TouchNGo' =>  $input["TouchNGo"],
                        'Credit_Card' =>  $input["Credit_Card"],
                        'Utility_Bills' =>  $input["Utility_Bills"],
                        'License' =>  $input["License"],
                        'Agreement' =>  $input["Agreement"],
                        'Property' =>  $input["Property"],
                        'Filing_System' =>  $input["Filing_System"],
                        'Printer' =>  $input["Printer"],
                        'IT_Services' =>  $input["IT_Services"],
                        'Service_Contact' =>  $input["Service_Contact"],
                        'Approve_Staff_Loan' =>  $input["Approve_Staff_Loan"],
                        'View_All_Staff_Loan' =>  $input["View_All_Staff_Loan"],
                        'Update_Medical_Claim' =>  $input["Update_Medical_Claim"],
                        'OT_Management_HR' =>  $input["OT_Management_HR"],
                        'OT_Management_HOD' =>  $input["OT_Management_HOD"],
                        'Payslip_Management' =>  $input["Payslip_Management"],
                        'Leave_Adjustment' =>  $input["Leave_Adjustment"],
                        'View_Incentive_Summary' =>  $input["View_Incentive_Summary"],
                        'View_Medical_Claim_Summary' =>  $input["View_Medical_Claim_Summary"],

                        'View_Staff_Deduction_Detail' =>  $input["View_Staff_Deduction_Detail"],

                        'Delivery_Dashboard'=>$input['Delivery_Dashboard'],
                        'Delivery_Approval'=>$input['Delivery_Approval'],
                        'Delete_Delivery'=>$input['Delete_Delivery'],
                        'Delivery_Tracking'=>$input['Delivery_Tracking'],
                        'Site_Delivery_Summary'=>$input['Site_Delivery_Summary'],
                        'Warehouse_Checklist'=>$input['Warehouse_Checklist'],
                        'Requestor_KPI'=>$input['Requestor_KPI'],
                        'Driver_KPI'=>$input['Driver_KPI'],
                        'Warehouse_KPI'=>$input['Warehouse_KPI'],

                        'Material_Request'=>$input['Material_Request'],
                        'View_Costing_Summary'=>$input['View_Costing_Summary'],
                        'View_OSU_Costing_Summary'=>$input['View_OSU_Costing_Summary'],
                        'Material_Approval'=>$input['Material_Approval'],
                        'Driver_Incentive_Summary'=>$input['Driver_Incentive_Summary'],
                        'Transport_Charges'=>$input['Transport_Charges'],
                        'View_Material_Request'=>$input['View_Material_Request'],
                        'View_PO_Listing'=>$input['View_PO_Listing'],
                        'Generate_PO'=>$input['Generate_PO'],
                        'Quotation_Approval'=>$input['Quotation_Approval'],
                        'Upload_Quotation'=>$input['Upload_Quotation'],
                        'Recall_MR'=>$input['Recall_MR'],
                        'Speedfreak'=>$input['Speedfreak'],
                        'Speedfreak_Summary'=> $input['Speedfreak_Summary'],
                        'Delete_RQO'=>$input['Delete_RQO'],
                        'Update_Inventory'=>$input['Update_Inventory'],
                        'Sales_Order'=>$input['Sales_Order'],
                        'Sales_Summary'=>$input['Sales_Summary'],
                        'Delete_SO'=>$input['Delete_SO'],
                        'Recur_SO'=>$input['Recur_SO'],
                        'Storekeeper'=>$input['Storekeeper'],
                        'View_Invoice'=>$input['View_Invoice'],
                        'Update_Inv_Num'=>$input['Update_Inv_Num'],
                        'Update_Inv'=>$input['Update_Inv'],
                        'Generate_Invoice'=>$input['Generate_Invoice'],
                        'invoice_listing'=>$input['invoice_listing'],
                        'dummy_do'=>$input['dummy_do'],
                        'Costing'=>$input['Costing'],
                        'autocount'=>$input['autocount'],
                        'View_MIA'=>$input['View_MIA'],
                        'ewallet'=>$input['ewallet'],
                        'Todolist_Dashboard'=>$input['Todolist_Dashboard'],
                        'View_Todolist'=>$input['View_Todolist'],
                        'Delete_Todo'=>$input['Delete_Todo'],
                        'SO_Details'=>$input['SO_Details'],
                        'Task_List'=>$input['Task_List'],
                        'CME_Dashboard'=>$input['CME_Dashboard'],
                        'View_All_Branch'=>$input['View_All_Branch'],
                        'radius'=>$input['radius']
                    ));

         return json_encode (DB::table('accesscontroltemplates')
                        ->where('Id', '=', $id)
                        ->first());


    }

    public function removetemplate(Request $request)
    {

            $input = $request->all();

            return DB::table('accesscontroltemplates')->where('Id', '=', $input["Id"])->delete();

    }

    public function documenttypeaccesscontrol($id=null,$projectid=null)
  	{
  		$me = (new CommonController)->get_current_user();

  		$templates = DB::table('accesscontroltemplates')
  		->orderBy('accesscontroltemplates.Template_Name','asc')
  		->get();

  		if($id==null)
  		{
  			$id=$templates[0]->Id;
  		}

  		$projects = DB::table('projects')
  		->where('Active','=','1')
  		->get();

  		if(!$projectid)
  		{
  			$projectid=$projects[0]->Id;
  		}

  		$documentaccess = DB::table('documenttypeaccess')
      ->distinct('Document_Type')
  		->leftJoin('options','options.Option','=','Document_Type')
  		->leftJoin('optionprojects','options.Id','=','OptionId')
  		->where('AccessControlTemplateId','=', $id)
  		->where('ProjectId','=', $projectid)
      ->groupBy('documenttypeaccess.Document_Type')
  		->orderBy('documenttypeaccess.Document_Type','asc')
  		->get();

  		$options= DB::table('options')
  		->whereIn('Table', ["users"])
  		->orderBy('Table','asc')
  		->orderBy('Option','asc')
  		->get();

  		$documenttype= DB::table('options')
  		->leftJoin('optionprojects','options.Id','=','OptionId')
  		->where('Table','=', "tracker")
  		->where('Field','=', 'Document_Type')
  		->where('ProjectId','=', $projectid)
  		->orderBy('Table','asc')
  		->orderBy('Option','asc')
  		->get();

  		foreach ($templates as $template) {
  			// code...
  			foreach ($documenttype as $type) {
  				// code...

  				$exist= DB::table('documenttypeaccess')
  				->where('AccessControlTemplateId','=', $template->Id)
  				->where('Document_Type','=', $type->Option)
  				->get();

  				if(!$exist)
  				{
  					DB::table('documenttypeaccess')
  								->insertGetId(array(
  								'AccessControlTemplateId' => $template->Id,
  								'Document_Type' => $type->Option,
  								'Read' => '1',
  								'Write' => '1'
  							));
  				}
  			}
  		}

      return view('documenttypeaccesscontrol', ['me' => $me,'id'=>$id,'templates' => $templates,'options' =>$options,'documenttype'=>$documenttype,'documentaccess'=>$documentaccess,'projects'=>$projects,'projectid'=>$projectid]);

  	}

    public function updatedocumenttypeaccess(Request $request)
  	{

  		$input = $request->all();

  		$write="";

  		foreach ($input as $key => $value) {
  			// code...
  			if($key=="TemplateId")
  			{
  				$templateid=$value;

  			}
  			else if(str_contains($key,"Read"))
  			{

  				$type=explode("zzz",$key)[1];
  				$type=str_replace('_',' ',$type);

  				$result= DB::table('documenttypeaccess')
  	            ->where('AccessControlTemplateId', $templateid)
  							->where('Document_Type', $type)
  	            ->update(array(
  							'Read' =>  $value

  						));

  			}
  			else if(str_contains($key,"Write"))
  			{
  				$result= DB::table('documenttypeaccess')
  	            ->where('AccessControlTemplateId', $templateid)
  							->where('Document_Type', $type)
  	            ->update(array(
  							'Write' =>  $value

  						));

  			}
  		}

  		return 1;


  	}

}
