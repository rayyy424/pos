<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class TestController extends Controller {
  /*
    * API Login, on success return JWT Auth token
    *
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
  */

  public function login(Request $request) {

    $this->validate($request, [
      'StaffId' => 'required',
      'Password' => 'required',
    ]);

    $credentials = array(
      'StaffId' => $request->input('StaffId'),
      'password' => $request->input('Password'),
    );

    $test = DB::table('users')
    ->where('users.StaffId', '=',$request->input('StaffId'))
    ->first();

    if ($test)
    {
      if ($test->Active==0)
      {
        return response()->json(['error' => 'These credentials deactived.'], 500);
      }
    }

    try {

      // attempt to verify the credentials and create a token for the user
      if (! $token = JWTAuth::attempt($credentials)) 
      {
        return response()->json(['error' => 'invalid_credentials']);
      }
    } 

    catch (JWTException $e) {
      // something went wrong whilst attempting to encode the token
      return response()->json(['error' => 'could_not_create_token'], 500);
    }

    // all good so return the token
    return response()->json(compact('token'));
  }


  public function getuser() 
  {

    $auth = JWTAuth::parseToken()->authenticate();

    $me = DB::table('users')
    ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
    ->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
    ->leftJoin('allowanceschemes', 'allowanceschemes.Id', '=', 'users.AllowanceSchemeId')
    ->select('users.Id as UserId','users.AccessControlTemplateId','users.StaffId as Staff_ID','users.Name','users.Nick_Name','users.Company_Email','users.Personal_Email','users.Contact_No_1','users.Contact_No_2','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.Password','users.User_Type','users.Nationality','users.DOB','users.NRIC','users.Passport_No','users.Gender','users.Marital_Status','users.SuperiorId','users.Company','users.Department','users.Position','users.Joining_Date','users.Resignation_Date','users.Emergency_Contact_Person','users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Address','users.Note','users.Active','users.Admin','users.Approved',
    'users.First_Change','files.Web_Path','allowanceschemes.Scheme_Name',
    'Template_Name',
    'View_User_Profile',
    'Edit_User',
    'Staff_Monitoring',
    'View_CV',
    'Export_CV',
    'Edit_CV',
    'View_Contractor_Profile',
    'Edit_Contractor',
    'View_Org_Chart',
    'Update_Org_Chart',
    'Approve_Leave',
    'View_All_Leave',
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
    'Resource_Allocation',
    'Staff_Skill',
    'Project_Requirement',
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
    'Detail_Approved_On','Status','Comment',DB::raw('(select GROUP_CONCAT(CONCAT(projects.Id) SEPARATOR "|") as ProjectIds from projects WHERE Id in (SELECT ProjectId FROM projectaccess WHERE UserId='.$auth->Id.') Order By Project_Name) as ProjectIds'),DB::raw('(select GROUP_CONCAT(CONCAT(projects.Project_Name) SEPARATOR "|") as ProjectNames from projects WHERE Id in (SELECT ProjectId FROM projectaccess WHERE UserId='.$auth->Id.') Order By Project_Name) as ProjectNames'))
      ->where('users.Id', '=',$auth -> Id)
      ->first();

    if ($me -> Web_Path=="")
    {
        $me -> Web_Path = URL::to('/') ."/img/default-user.png" ;
    }

    return json_encode($me);
  }


  public function playerId(Request $request)
  {

    $auth = JWTAuth::parseToken()->authenticate();
    $me = DB::table('users')
    ->select('Player_Id')
    ->first();

    return json_encode($me);
  }


  public function postplayerid(Request $request){
    $auth = JWTAuth::parseToken()->authenticate();

    $input = $request->all();

    $me = (new AuthController)->get_current_user($auth->Id);

    // clear first to avoid duplication
    DB::table('users')
          ->where('Player_Id', '=',$input['Player_Id'])
          ->update(array(
          'Player_Id' =>  ''
        ));

    // then update the player id to current user
    $result= DB::table('users')
          ->where('Id', '=',$auth ->Id)
          ->update(array(
          'Player_Id' =>  $input["Player_Id"],
        ));

    return json_encode($result);


  }


  /*
    * Log out
    * Invalidate the token, so user cannot use it anymore
    * They have to relogin to get a new token
    *
    * @param Request $request
  */

  public function logout(Request $request) {
    $this->validate($request, [
      'token' => 'required'
    ]);

    JWTAuth::invalidate($request->input('token'));
  }

  public function clearplayerid(Request $request){
    $auth = JWTAuth::parseToken()->authenticate();

    $input = $request->all();

    $me = (new AuthController)->get_current_user($auth->Id);
    $result= DB::table('users')
          ->where('Player_Id', '=',$input['Player_Id'])
          ->update(array(
          'Player_Id' =>  ''
        ));

    return json_encode($result);


  }


  public function getavailability()
  {
    $me = JWTAuth::parseToken()->authenticate();

    $users = DB::table('users')
    ->select('Available')
    ->where('Id','=',$me -> Id)
    ->get();

    return json_encode($users);
  }


  public function setavailability(Request $request)
  {
    $me = JWTAuth::parseToken()->authenticate();
    $input = $request->all();

    $id=DB::table('users')->insertGetId(
      ['Id' => $input["Id"],
      'Available' => $input["Available"]
      ]
    );

    return $id;
  }

  public function get_current_user($Id)
  {

    $me = DB::table('users')
    ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
    ->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
    ->leftJoin('allowanceschemes', 'allowanceschemes.Id', '=', 'users.AllowanceSchemeId')
    ->select('users.Id as UserId','users.AccessControlTemplateId','users.StaffId as Staff_ID','users.Name','users.Nick_Name','users.Company_Email','users.Personal_Email','users.Contact_No_1','users.Contact_No_2','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.Password','users.User_Type','users.Nationality','users.DOB','users.NRIC','users.Passport_No','users.Gender','users.Marital_Status','users.SuperiorId','users.Company','users.Department','users.Position','users.Joining_Date','users.Resignation_Date','users.Emergency_Contact_Person','users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Address','users.Note','users.Active','users.Admin','users.Approved',
    'users.First_Change','files.Web_Path','allowanceschemes.Scheme_Name',
    'Template_Name',
    'View_User_Profile',
    'Edit_User',
    'Staff_Monitoring',
    'View_CV',
    'Export_CV',
    'Edit_CV',
    'View_Contractor_Profile',
    'Edit_Contractor',
    'View_Org_Chart',
    'Update_Org_Chart',
    'Approve_Leave',
    'View_All_Leave',
    'View_Leave_Summary',
    'Show_Leave_To_Public',
    'Approve_Timesheet',
    'View_All_Timesheet',
    'View_Timesheet_Summary',
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
    'Resource_Allocation',
    'Staff_Skill',
    'Project_Requirement',
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
    'Detail_Approved_On','Status','Comment',DB::raw('(select GROUP_CONCAT(CONCAT(projects.Id) SEPARATOR "|") as ProjectIds from projects WHERE Id in (SELECT ProjectId FROM projectaccess WHERE UserId='.$Id.') Order By Project_Name) as ProjectIds'),DB::raw('(select GROUP_CONCAT(CONCAT(projects.Project_Name) SEPARATOR "|") as ProjectNames from projects WHERE Id in (SELECT ProjectId FROM projectaccess WHERE UserId='.$Id.') Order By Project_Name) as ProjectNames'))
    ->where('users.Id', '=',$Id)
    ->first();

    if ($me -> Web_Path=="")
    {
        $me -> Web_Path = URL::to('/') ."/img/default-user.png" ;
    }

    return $me;
  }
  
}
