<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\CommonController;

use DateTime;

class LeaveController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function myleave($start = null, $end = null)
    {
        $me=(new CommonController)->get_current_user();
        if ($start==null)
        {
            $start=date('d-M-Y', strtotime(date('Y-01-01')));
        }

        if ($end==null)
        {
            $end=date('d-M-Y');
        }

        $data="";
        $leavetype= DB::select("SELECT `Option` FROM  `options` WHERE  `Field` LIKE  'Leave_Type' ORDER BY FIELD(options.Option , 'Unpaid Leave', 'Annual Leave','1 Hour Time Off','2 Hours Time Off','Medical Leave','Replacement Leave','Emergency Leave','Paternity Leave','Maternity Leave', 'Marriage Leave','Compassionate Leave') ASC");

        foreach($leavetype as $key => $quote) {

            if (($quote->Option == 'Maternity Leave' && $me->Gender == 'MALE') || ($quote->Option == 'Paternity Leave' && $me->Gender == 'FEMALE')) {
                continue;
            } elseif (($quote->Option == 'Paternity Leave' || $quote->Option == 'Maternity Leave') && $me->Gender == '') {
                continue;
            } elseif ($quote->Option == 'Marriage Leave' && $me->Marital_Status == 'MARRIED') {
                continue;
            }
            $data.= $quote->Option.",";
        }

        $s = rtrim($data,",");
        $arr = explode(",", $s);

        $i = 0;
        $querytype = "";

        while ($i < count($arr)) {
             $a = $arr[$i];
             $querytype .= "SUM(case when leaves.Leave_Type = '".$a."' and (leavestatuses.Leave_Status like '%Pending%' OR leavestatuses.Leave_Status like '%Approved%') then leaves.No_of_Days else 0 end) as '".$a."'," ;
             $i++;
        }
        $querytype = substr($querytype, 0, strlen($querytype) - 1);

        $leavesummary = DB::select("
            SELECT users.Id, users.StaffId, ".$querytype."
            FROM users
            LEFT JOIN leaves ON users.Id = leaves.UserId and str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
            AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y')
            LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
            LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
            WHERE users.Id = ". $me->UserId ."
            GROUP BY users.Id
        ");


        $showleave = DB::table('leaves')
        ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        // ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->select('leaves.Id','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.updated_at as Review_Date','leavestatuses.Comment')
        // ->where('accesscontrols.Show_Leave_To_Public', '=', 1)
        ->orderBy('leaves.Id','desc')
        ->get();

        $myleave = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))

        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))

        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leaves.Id','leavestatuses.Id as StatusId','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.updated_at as Review_Date','leavestatuses.Comment','files.Web_Path')
        ->where('leaves.UserId', '=', $me->UserId)
        ->orderBy('leaves.Id','desc')
        ->get();

        // select approver other than final approver
        $approver = DB::table('approvalsettings')
        ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
        ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
        ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
        ->where('approvalsettings.Type', '=', 'Leave')
        ->where('projects.Project_Name', '=', $me->Department )
        ->where('approvalsettings.Level', 'NOT LIKE', '%Final Approval%')
        ->orderBy('approvalsettings.Country','asc')
        ->orderBy('projects.Project_Name','asc')
        ->orderBy('approvalsettings.Level','asc')
        ->groupBy('approvalsettings.Country','projects.Project_Name','users.Id')
        ->get();

        // if no approver, select final approver
        if (! $approver) {
            $approver = DB::table('approvalsettings')
            ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
            ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
            ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
            ->where('approvalsettings.Type', '=', 'Leave')
            ->where('projects.Project_Name', '=', $me->Department )
            ->orderBy('approvalsettings.Country','asc')
            ->orderBy('projects.Project_Name','asc')
            ->orderBy('approvalsettings.Level','asc')
            ->groupBy('approvalsettings.Country','projects.Project_Name','users.Id')
            ->get();
        }

        $projects = DB::table('projects')
        ->where('projects.Project_Name', '=', $me->Department )
        ->get();

        if ($me->Marital_Status == 'MARRIED') {
            if($me->Gender == 'MALE') {

                $options= DB::table('options')
                ->whereIn('Table', ["leaves"])
                ->where('Option','<>','Maternity Leave')
                ->where('Option','<>','Marriage Leave')
                // ->orderBy('Table','asc')
                // ->orderBy('Option','asc')
                ->orderByRaw("FIELD(options.Option , 'Unpaid Leave', 'Annual Leave','1 Hour Time Off','2 Hours Time Off','Medical Leave','Emergency Leave','Paternity Leave','Maternity Leave', 'Marriage Leave','Compassionate Leave') ASC")
                ->get();
            } else {
                $options= DB::table('options')
                ->whereIn('Table', ["leaves"])
                ->where('Option','<>','Paternity Leave')
                ->where('Option','<>','Marriage Leave')
                // ->orderBy('Table','asc')
                // ->orderBy('Option','asc')
                ->orderByRaw("FIELD(options.Option , 'Unpaid Leave', 'Annual Leave','1 Hour Time Off','2 Hours Time Off','Medical Leave','Emergency Leave','Paternity Leave','Maternity Leave', 'Marriage Leave','Compassionate Leave') ASC")
                ->get();
            }
        } else {
            $options= DB::table('options')
            ->whereIn('Table', ["leaves"])
            ->where('Option','<>','Paternity Leave')
            ->where('Option','<>','Maternity Leave')
            // ->orderBy('Table','asc')
            // ->orderBy('Option','asc')
            ->orderByRaw("FIELD(options.Option , 'Unpaid Leave', 'Annual Leave','1 Hour Time Off','2 Hours Time Off','Medical Leave','Emergency Leave','Paternity Leave','Maternity Leave', 'Marriage Leave','Compassionate Leave') ASC")
            ->get();
        }

        $thisyear = date('Y');
        $lastyear = $thisyear - 1;
        $currentmonth = date('n');
        $dayOfYear = date("z")+1;

        $leavebalance = $this->checkLeaveBalance($me->UserId);
        $replacement = null;
        foreach($leavebalance as $bal) {
            // replaement balance used for EL/AL
            if ($bal->Leave_Type == 'Annual Leave') {
                $replacementleave = clone $bal;

                if ($bal->Leave_Balance + $bal->Pending < 0) {
                    $replacementleave->Total_Leave_Balance = $bal->Replacement_Balance + $bal->Replacement_Pending + ($bal->Leave_Balance + $bal->Pending);
                    $bal->Leave_Balance = $bal->Leave_Balance + ($bal->Replacement_Balance - $replacementleave->Total_Leave_Balance) + $bal->Pending;
                    $bal->Replacement_Balance = $replacementleave->Total_Leave_Balance;
                }
            } elseif ($bal->Leave_Type == 'Replacement Leave') {
                $bal->Leave_Balance = $replacementleave->Total_Leave_Balance;
                $bal->Total_Leave_Balance = $replacementleave->Total_Leave_Balance;
            }
        }
        $today = new DateTime();
        // true / false
        $joiningDate = DateTime::createFromFormat('d-M-Y', $me->Joining_Date);

        // calculate the days difference between dates
        $datediff = $today->diff($joiningDate)->format("%a");

        // $hierarchy = DB::table('users')
        // ->select('L2.Id as L2Id','L2.Name as L2Name','L2.Leave_1st_Approval as L21st','L2.Leave_2nd_Approval as L22nd',
        // 'L3.Id as L3Id','L3.Name as L3Name','L3.Leave_1st_Approval as L31st','L3.Leave_2nd_Approval as L32nd')
        // // ->leftJoin(DB::raw("(select users.Id,users.Name,users.SuperiorId,accesscontrols.Leave_1st_Approval,accesscontrols.Leave_2nd_Approval,accesscontrols.Leave_Final_Approval from users left join accesscontrols on users.Id=accesscontrols.UserId) as L2"),'L2.Id','=','users.SuperiorId')
        // // ->leftJoin(DB::raw("(select users.Id,users.Name,users.SuperiorId,accesscontrols.Leave_1st_Approval,accesscontrols.Leave_2nd_Approval,accesscontrols.Leave_Final_Approval from users left join accesscontrols on users.Id=accesscontrols.UserId) as L3"),'L3.Id','=','L2.SuperiorId')
        // ->where('users.Id', '=', $me->UserId)
        // ->get();
        //
        // $final = DB::table('users')
        // ->select('users.Id','users.Name')
        // ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'users.Id')
        // ->where('Leave_Final_Approval', '=', 1)
        // ->get();

        return view('myleave', ['me' => $me,'showleave' => $showleave,'myleave' => $myleave,'approver' => $approver, 'options' =>$options, 'projects' =>$projects, 'leavebalance' => $leavebalance, 'leavesummary' => $leavesummary, 'leavetype' => $leavetype, 'start' => $start, 'end'=> $end, 'datediff' => $datediff]);

    }

    // Get AL, UL, EL, NPL calculation
    public function getLeaveCalculation($start = null, $end = null)
    {
        $me = (new CommonController)->get_current_user();

        if ($start==null) {
            $start = date('d-M-Y', strtotime(date('Y-01-01')));
        }

        if ($end==null) {
            $end =  date('t-M-Y');
        }

        $thisyear = date('Y');
        $lastyear = $thisyear - 1;
        $data="";
        $leavetype = DB::select("SELECT `Option` FROM  `options` WHERE  `Field` LIKE  'Leave_Type'");

        foreach ($leavetype as $key => $quote) {
            $data .= $quote->Option . ",";
        }

        $s = rtrim($data, ",");
        $arr = explode(",", $s);

        $i = 0;
        $querytype = "";

        while ($i < count($arr)) {
             $a = $arr[$i];
             $querytype.= "SUM(case when leaves.Leave_Type = '".$a."' and leavestatuses.Leave_Status like '%Final Approved%' then leaves.No_of_Days else 0 end) as '".$a."'," ;
             $i++;
        }

        $querytype = substr($querytype, 0, strlen($querytype) - 1);

        return DB::select("SELECT users.Id, users.StaffId,users.Name, $querytype,leaveadjustments.Adjustment_Value as Adjusted,
            CASE
                WHEN leaveentitlements.Leave_Type = 'Marriage Leave' THEN
                    IF(service.Marital_Status LIKE '%Married%',0,leaveentitlements.Days)
                WHEN leaveentitlements.Leave_Type = 'Paternity Leave' THEN
                    IF(service.Marital_Status LIKE '%Married' AND service.Gender = 'Male', leaveentitlements.Days, 0)
                WHEN leaveentitlements.Leave_Type = 'Maternity Leave' THEN
                    IF(service.Marital_Status LIKE '%Married' AND service.Gender = 'Female', leaveentitlements.Days, 0)
                ELSE leaveentitlements.Days
            END as Yearly_Entitlement,
            CASE
                WHEN leaveentitlements.Leave_Type = 'Annual Leave' AND service.confirmed
                    THEN 5*ROUND(leaveentitlements.Days/12*service.Months_of_Service/5 ,1)
                WHEN service.confirmed
                    THEN (SELECT Yearly_Entitlement)
                ELSE 0
            END as Current_Entitlement,

            Leave_Taken_In_Between_March,
            Leave_Taken_Before_April,
            Total_Leave_Taken,
            '' as Burnt,
            '' as Total_Leave_Days,
            '' as Total_Leave_Balance,
            leavecarryforwards.Days as Carried_Forward,
            '' as Non_Paid_Leave
            FROM users
            LEFT JOIN leaves ON users.Id = leaves.UserId and str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
            AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y')
            LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
            LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
            LEFT JOIN (
                SELECT leaves.UserId, SUM(No_of_Days) as Total_Leave_Taken, SUM(CASE WHEN MONTH(str_to_date(leaves.End_Date,'%d-%M-%Y')) <=3 THEN No_of_Days ELSE 0 END) as Leave_Taken_Before_April  FROM leaves
                INNER JOIN (select Max(Id) as maxid,LeaveId from leavestatuses WHERE Leave_Status LIKE '%Final Approved%' Group By LeaveId) as max ON  max.LeaveId = leaves.Id
                WHERE YEAR(str_to_date(leaves.Start_Date,'%d-%M-%Y')) = $thisyear AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y')) = $thisyear AND (leaves.Leave_Type = 'Annual Leave' OR leaves.Leave_Type = 'Emergency Leave' OR leaves.Leave_Type = 'Replacement Leave' OR leaves.Leave_Type = '1 Hour Time Off' OR leaves.Leave_Type = '2 Hours Time Off')
                GROUP BY CASE WHEN Leave_Type = '1 Hour Time Off' OR Leave_Type = '2 Hours Time Off' OR Leave_Type = 'Emergency Leave' OR Leave_Type = 'Replacement Leave' Then 'Annual Leave' ELSE Leave_Type END, UserId
            ) as leaveBeforeApril ON leaveBeforeApril.UserId = users.Id
            LEFT JOIN (
                SELECT leaves.UserId, sum(Case WHEN lt.Leave_Period = 'AM' OR lt.Leave_Period = 'PM' THEN 0.5 WHEN lt.Leave_Period = '1 Hour' THEN 0.125 WHEN lt.Leave_Period = '2 Hours' THEN 0.25 WHEN  lt.Leave_Period = 'Full' THEN 1 ELSE 0 END) Leave_Taken_In_Between_March  FROM leaves
                LEFT JOIN (SELECT leave_terms.Leave_Period, leave_terms.Leave_Id from leave_terms WHERE YEAR(Str_to_date(leave_terms.Leave_Date, '%d-%M-%Y')) = $thisyear AND MONTH(Str_to_date(leave_terms.Leave_Date, '%d-%M-%Y')) <= 3) as lt
                ON leaves.Id = lt.Leave_Id
                WHERE (leaves.Leave_Type = 'Annual Leave' OR leaves.Leave_Type = 'Emergency Leave' OR leaves.Leave_Type = 'Replacement Leave' OR leaves.Leave_Type = '1 Hour Time Off' OR leaves.Leave_Type = '2 Hours Time Off') AND YEAR(Str_to_date(leaves.Start_Date, '%d-%M-%Y')) = $thisyear AND MONTH(Str_to_date(leaves.Start_Date, '%d-%M-%Y')) <= 3 AND MONTH(Str_to_date(leaves.End_Date, '%d-%M-%Y')) > 3
                GROUP BY leaves.UserId
            ) as leaveInBetweenMarch ON leaveInBetweenMarch.UserId = users.Id
            LEFT JOIN leaveadjustments ON users.Id = leaveadjustments.UserId AND leaveadjustments.Adjustment_Leave_Type = leaves.Leave_Type AND leaveadjustments.Adjustment_Year = $thisyear
            LEFT JOIN (
                SELECT users.id AS UserId, users.Marital_Status, users.Gender,
                    CASE WHEN
                    DATEDIFF(Date_format(Now(), '%Y-%m-%d'), Str_to_date(users.confirmation_date, '%d-%M-%Y')) >= 0 THEN 1 ELSE 0 end AS confirmed,
                    DATEDIFF(Date_format(Now(), '%Y-%m-%d'),Str_to_date(users.joining_date, '%d-%M-%Y')) AS Days_of_Service,
                    Ceiling((SELECT days_of_service) / 365) AS Years_of_Service,
                    CASE WHEN YEAR(str_to_date(users.Joining_date,'%d-%M-%Y')) = $thisyear THEN 1 ELSE 0 END as Joined_This_Year,
                    Joining_date,
                    (SELECT days_of_service) / 30 AS Current_Completed_Month,
                    CASE WHEN (SELECT joined_this_year) THEN (SELECT current_completed_month) ELSE Month(Now()) end AS Months_of_Service,
                    CASE WHEN (SELECT Joined_This_Year) THEN (SELECT Days_of_Service) ELSE DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(NOW() ,'%Y-01-01')) END as Days_of_Service_Current_Year
                FROM users
            ) as service ON users.Id = service.UserId
            LEFT JOIN (SELECT leaveentitlements.*,tblEnt.MaxYear FROM leaveentitlements LEFT JOIN
                    (SELECT leaveentitlements.Grade, Leave_Type, MAX(Year) as MaxYear
                        FROM leaveentitlements
                        GROUP BY Grade, Leave_Type ) as tblEnt
                    ON leaveentitlements.Grade = tblEnt.Grade and leaveentitlements.Leave_Type = tblEnt.Leave_Type) as leaveentitlements
                ON leaveentitlements.Grade = users.Grade
                AND (leaveentitlements.Year = LEAST(leaveentitlements.MaxYear,service.Years_of_Service) OR leaveentitlements.Year = '')
                AND leaveentitlements.Leave_Type = leaves.Leave_Type
            LEFT JOIN leavecarryforwards ON leavecarryforwards.UserId=users.Id AND leavecarryforwards.Year = $lastyear
            GROUP BY users.Id
            ORDER BY users.Id DESC
        ");

    }

    /**
     * Check if leave start date is equal or greater than end date
     * @param  string  $startDate
     * @param  string  $endDate
     * @return boolean
     */
    protected function isLeaveDatesValid($startDate, $endDate) {
        $start = DateTime::createFromFormat('d-M-Y', $startDate);
        $end = DateTime::createFromFormat('d-M-Y', $endDate);

        if ($end >= $start) {
            return true;
        }

        return false;
    }

    /**
     * Check the available balance days for the user and/or leave type
     * @param  int    $userId
     * @param  string $leaveType
     * @return int
     */
    public function checkLeaveBalance($userId, $leaveType = null)
    {
        $thisyear = date('Y');
        $lastyear = $thisyear - 1;
        $currentmonth = date('n');
        $dayOfYear = date("z")+1;
        $timeOffTaken = 0;
        $timeOffTakenBeforeApril = 0;
        $sickLeaveTaken = 0;

        if ($leaveType) {
            $filterLeave = "AND leaveentitlements.Leave_Type = '$leaveType'";
        } else {
            $filterLeave = "";
        }

        // if ($leaveType == 'Annual Leave') {
            $beforeMonth = 4;
            $oneHourTimeOff = $this->getLeaveTaken($userId, '1 Hour Time Off', $thisyear);
            $twoHoursTimeOff = $this->getLeaveTaken($userId, '2 Hours Time Off', $thisyear);
            $oneHourTimeOffBeforeApril = $this->getLeaveTaken($userId, '1 Hour Time Off', $thisyear, $beforeMonth);
            $twoHoursTimeOffBeforeApril = $this->getLeaveTaken($userId, '2 Hours Time Off', $thisyear, $beforeMonth);

            $timeOffTaken = $oneHourTimeOff + $twoHoursTimeOff;
            $timeOffTakenBeforeApril = $oneHourTimeOffBeforeApril + $twoHoursTimeOffBeforeApril;

            $emergencyLeave = $this->getLeaveTaken($userId, 'Emergency Leave', $thisyear);
            $emergencyLeaveBeforeApril = $this->getLeaveTaken($userId, 'Emergency Leave', $thisyear, $beforeMonth);

            $replacementLeave = $this->getLeaveTaken($userId, 'Replacement Leave', $thisyear);
        // }
        $sickLeaveType = 'Medical Leave';
        if ($leaveType == "" || $leaveType == 'Medical Leave') {
            $sickLeave = DB::select("SELECT SUM(No_of_Days) as Sick_Leave_Taken  FROM leaves
                INNER JOIN (select Max(Id) as maxid,LeaveId from leavestatuses WHERE Leave_Status LIKE '%Final Approved%' Group By LeaveId) as max ON  max.LeaveId=leaves.Id
                WHERE leaves.UserId = $userId AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=$thisyear AND leaves.Leave_Type = '$sickLeaveType'
            ");
            $sickLeaveTaken = $sickLeave[0]->Sick_Leave_Taken ? $sickLeave[0]->Sick_Leave_Taken : 0;
        }

        // Get the leave start March or before and end after March, calculate the no of days taken  31 March and before
        $leaveInBetweenMarch = DB::select("
            SELECT leaves.Id as LeaveId, leaves.UserId, SUM(CASE WHEN lt.Leave_Period = 'AM' OR lt.Leave_Period = 'PM' THEN 0.5 WHEN lt.Leave_Period = '1 Hour' THEN 0.125 WHEN lt.Leave_Period = '2 Hours' THEN 0.25 WHEN lt.Leave_Period = 'Full' THEN 1 ELSE 0 END) Leave_Taken  FROM leaves
            LEFT JOIN (SELECT leave_terms.Leave_Period, leave_terms.Leave_Id from leave_terms WHERE MONTH(Str_to_date(leave_terms.Leave_Date, '%d-%M-%Y')) <= 3 AND YEAR(Str_to_date(leave_terms.Leave_Date, '%d-%M-%Y')) = $thisyear) as lt
            ON leaves.Id = lt.Leave_Id
            WHERE leaves.UserId = $userId AND (leaves.Leave_Type = 'Annual Leave' OR leaves.Leave_Type = '1 Hour Time Off' OR leaves.Leave_Type = '2 Hours Time Off' OR leaves.Leave_Type = 'Emergency Leave') AND YEAR(Str_to_date(leaves.Start_Date, '%d-%M-%Y')) = $thisyear AND (MONTH(Str_to_date(leaves.Start_Date, '%d-%M-%Y')) <= 3 AND MONTH(Str_to_date(leaves.End_Date, '%d-%M-%Y')) > 3)
            GROUP BY leaves.Id
        ");

        if(! empty($leaveInBetweenMarch)) {
            $leaveTakenBeforeApril = $leaveInBetweenMarch[0]->Leave_Taken ? $leaveInBetweenMarch[0]->Leave_Taken : 0;
        } else {
            $leaveTakenBeforeApril = 0;
        }


        return $leavebalance = DB::select("
            SELECT
            leaveentitlements.Leave_Type,
            CASE
                WHEN leaveentitlements.Leave_Type = 'Marriage Leave' THEN
                    IF(service.Marital_Status LIKE '%Married%',0,leaveentitlements.Days)
                WHEN leaveentitlements.Leave_Type = 'Paternity Leave' THEN
                    IF(service.Marital_Status LIKE '%Married' AND service.Gender = 'Male', leaveentitlements.Days, 0)
                WHEN leaveentitlements.Leave_Type = 'Maternity Leave' THEN
                    IF(service.Marital_Status LIKE '%Married' AND service.Gender = 'Female', leaveentitlements.Days, 0)
                ELSE leaveentitlements.Days
            END as Yearly_Entitlement,
            CASE WHEN leaveentitlements.Leave_Type = 'Medical Leave'
                THEN leaveentitlements.Days
            WHEN service.Days_of_Service <= 90
                THEN 0
            -- WHEN leaveentitlements.Leave_Type = 'Annual Leave' AND service.confirmed AND service.Days_of_Service > 365
            --     THEN leaveentitlements.Days
            WHEN leaveentitlements.Leave_Type = 'Annual Leave'
                -- THEN 5*ROUND(leaveentitlements.Days/365*service.Days_of_Service_Current_Year/5 ,1)
                THEN 5*ROUND(leaveentitlements.Days / 12 * service.Months_of_Service/5,1)
            WHEN leaveentitlements.Leave_Type = 'Marriage Leave' THEN
                    IF(service.Marital_Status LIKE '%Married%',0,leaveentitlements.Days)
            WHEN leaveentitlements.Leave_Type = 'Paternity Leave' THEN
                IF(service.Marital_Status LIKE '%Married' AND service.Gender = 'Male', leaveentitlements.Days, 0)
            WHEN leaveentitlements.Leave_Type = 'Maternity Leave' THEN
                IF(service.Marital_Status LIKE '%Married' AND service.Gender = 'Female', leaveentitlements.Days, 0)
            ELSE
                leaveentitlements.Days
            END as Current_Entitlement,
            IF(leaveadjustments.Adjustment_Value,leaveadjustments.Adjustment_Value,0) as Adjusted,
            CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave' THEN IF(replacementadjustments.Adjustment_Value,replacementadjustments.Adjustment_Value,0) ELSE 0 END as Replacement_Adjusted,
            IF(leaveentitlements.Leave_Type = 'Annual Leave',$leaveTakenBeforeApril + $emergencyLeaveBeforeApril +$timeOffTakenBeforeApril + LeaveTakenBeforeApril,0) as Leave_Taken_Before_April,

            CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave' THEN $emergencyLeave + $timeOffTaken + IF(Total_Leave_Taken,Total_Leave_Taken,0) ELSE IF(Total_Leave_Taken,Total_Leave_Taken,0) END as Leave_Taken,
            CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave' THEN IF(Total_Leave_Taken,Total_Leave_Taken,0) ELSE IF(Total_Leave_Taken,Total_Leave_Taken,0) END as Leave_Taken_No_Timeoff,
            CASE
                WHEN leaveentitlements.Leave_Type = 'Annual Leave' AND leavecarryforwards.Days > (SELECT Leave_Taken_Before_April) AND MONTH(NOW()) > 3
                THEN
                    0
                ELSE
                    0
            END as Burnt,
            -- leavecarryforwards.Days as Carry_Forward_From_Last_Year
            CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave'  THEN IF(leavecarryforwards.Days,leavecarryforwards.Days,0) - (SELECT Burnt) ELSE 0 END As Carried_Forward,
            CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave' THEN GREATEST((SELECT Carried_Forward - Leave_Taken),0) ELSE 0 END as Carried_Forward_Balance,


            CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave' THEN $timeOffTaken ELSE 0 END as Time_Off_Taken,
            CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave' THEN $emergencyLeave ELSE 0 END as EL,
            IF(pending.Pending_Leave,pending.Pending_Leave,0) as Pending,
            CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave'
                THEN (SELECT Current_Entitlement + Adjusted + IF(Carried_Forward,Carried_Forward,0) - Leave_Taken)
                ELSE (SELECT Current_Entitlement + Adjusted - IF(Total_Leave_Taken,Total_Leave_Taken,0))
            END as Leave_Balance,
            -- IF(pending.Pending_Leave,pending.Pending_Leave,0) + IF(pending.Replacement_Pending_Leave,pending.Replacement_Pending_Leave,0) as Total_Pending_Leave,

            CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave' THEN $replacementLeave ELSE 0 END as Replacement,
            IF(pending.Replacement_Pending_Leave,pending.Replacement_Pending_Leave,0) as Replacement_Pending,

            CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave'
                THEN IF(replacementadjustments.Adjustment_Value,replacementadjustments.Adjustment_Value,0) - $replacementLeave
                ELSE 0
            END as Replacement_Balance,


            CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave'
                THEN (SELECT Leave_Balance + Replacement_Balance)
                ELSE (SELECT Leave_Balance)
            END as Total_Leave_Balance

            FROM users
            LEFT JOIN (
                SELECT users.id AS UserId, users.Marital_Status, users.Gender,
                    CASE WHEN
                    DATEDIFF(Date_format(Now(), '%Y-%m-%d'), Str_to_date(users.confirmation_date, '%d-%M-%Y')) >= 0 THEN 1 ELSE 0 end AS confirmed,
                    DATEDIFF(Date_format(Now(), '%Y-%m-%d'),Str_to_date(users.joining_date, '%d-%M-%Y')) AS Days_of_Service,
                    Ceiling((SELECT days_of_service) / 365) AS Years_of_Service,
                    CASE WHEN YEAR(str_to_date(users.Joining_date,'%d-%M-%Y')) = $thisyear THEN 1 ELSE 0 END as Joined_This_Year,
                    Joining_date,
                    CASE WHEN (SELECT Joined_This_Year) THEN (SELECT Days_of_Service) ELSE DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(NOW() ,'%Y-01-01')) END as Days_of_Service_Current_Year,
                    (SELECT days_of_service) / 30 AS Current_Completed_Month,
                    CASE WHEN (SELECT joined_this_year) THEN (SELECT current_completed_month) ELSE Month(Now()) end AS Months_of_Service
                FROM users
                WHERE users.Id = $userId
            ) as service ON users.Id = service.UserId
            LEFT JOIN (SELECT leaveentitlements.*,tblEnt.MaxYear FROM leaveentitlements LEFT JOIN
                            (SELECT leaveentitlements.Grade, Leave_Type, MAX(Year) as MaxYear
                                FROM leaveentitlements
                                GROUP BY Grade, Leave_Type ) as tblEnt
                            ON leaveentitlements.Grade = tblEnt.Grade and leaveentitlements.Leave_Type = tblEnt.Leave_Type
                            UNION SELECT 0 as Id, 'All' as Grade, 1 as Year, 0 as Days, 'Replacement Leave' as Leave_Type, 0 as created_at, 1 as MaxYear
                        ) as leaveentitlements
                    ON (leaveentitlements.Grade = users.Grade OR leaveentitlements.Grade = 'ALL')
                    AND (leaveentitlements.Year = LEAST(leaveentitlements.MaxYear,service.Years_of_Service) OR leaveentitlements.Year = '')

            LEFT JOIN (
                SELECT leaves.Leave_Type,
                    SUM(CASE WHEN leaves.Leave_Type <> 'Marriage Leave' THEN IF(YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=$thisyear,No_of_Days,0) ELSE No_of_Days END) as Total_Leave_Taken,
                    SUM(CASE WHEN MONTH(str_to_date(leaves.End_Date,'%d-%M-%Y'))<=3 AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=$thisyear THEN No_of_Days ELSE 0 END) as LeaveTakenBeforeApril  FROM leaves
                    -- INNER JOIN (SELECT leavestatuses.Id,leavestatuses.LeaveId,leavestatuses.Leave_Status FROM leavestatuses JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON max.maxid = leavestatuses.Id AND (Leave_Status LIKE '%Approved%' OR Leave_Status LIKE '%Pending%')) as ls ON ls.LeaveId = leaves.Id
                    JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
                    JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
                WHERE leaves.UserId = $userId AND (Leave_Status LIKE '%Approved%' OR Leave_Status LIKE '%Pending%')
                GROUP BY Leave_Type
            ) as app ON app.Leave_Type = leaveentitlements.Leave_Type
            LEFT JOIN (
                SELECT SUM(CASE WHEN YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=$thisyear AND leaves.Leave_Type <> 'Replacement Leave' THEN leaves.No_of_Days ELSE 0 END) as Pending_Leave,
                SUM(CASE WHEN YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=$thisyear AND leaves.Leave_Type = 'Replacement Leave' THEN leaves.No_of_Days ELSE 0 END) as Replacement_Pending_Leave,
                CASE WHEN Leave_Type  = '2 Hours Time Off' OR Leave_Type  = '1 Hour Time Off' OR Leave_Type  = 'Emergency Leave' OR Leave_Type  = 'Replacement Leave' THEN 'Annual Leave' ELSE Leave_Type END as Type
                FROM leaves
                JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
                JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
                WHERE leaves.UserId = $userId AND (Leave_Status LIKE '%Approved%' OR Leave_Status LIKE '%Pending%') AND Leave_Status NOT LIKE '%Final Approved%'
                GROUP BY Type
            ) as pending ON pending.Type = leaveentitlements.Leave_Type
            LEFT JOIN leavecarryforwards ON leavecarryforwards.UserId=$userId AND leavecarryforwards.Year=$lastyear
            LEFT JOIN leaveadjustments ON users.Id = leaveadjustments.UserId AND leaveadjustments.Adjustment_Leave_Type = leaveentitlements.Leave_Type AND leaveadjustments.Adjustment_Year = $thisyear
            LEFT JOIN leaveadjustments as replacementadjustments ON users.Id = replacementadjustments.UserId AND replacementadjustments.Adjustment_Leave_Type = 'Replacement Leave' AND replacementadjustments.Adjustment_Year = $thisyear
            WHERE users.Id = $userId $filterLeave
            AND leaveentitlements.Leave_Type <> ''
            GROUP BY leaveentitlements.Leave_Type
        ");
    }

    /**
     * Get the number of days of leave taken
     * @param  int  $userId
     * @param  string  $leaveType
     * @param  int  $year
     * @return int
     */
    public function getLeaveTaken($userId, $leaveType, $year, $beforeMonth = null)
    {
        $beforeMonthFilter = null;
        if ($beforeMonth) {
            $beforeMonthFilter = "AND MONTH(str_to_date(leaves.End_Date,'%d-%M-%Y'))< $beforeMonth";
        }

        $leaveTaken = DB::select("
            SELECT SUM(No_of_Days) as Total_Leave_Taken FROM leaves
            LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
            LEFT JOIN leavestatuses ON leavestatuses.Id = max.maxid
            -- LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses WHERE Leave_Status LIKE '%Final Approved%' Group By LeaveId) as max ON  max.LeaveId=leaves.Id
            WHERE leaves.UserId = $userId AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=$year AND leaves.Leave_Type = '$leaveType' AND (Leave_Status LIKE '%Approved%' OR Leave_Status LIKE '%Pending%')
            $beforeMonthFilter
            GROUP BY leaves.Leave_Type
        ");

        return $leaveTaken ? $leaveTaken[0]->Total_Leave_Taken : 0;
    }

    /**
     * Get the current holidays for territory id.
     * Fallback to holidays table if none found
     * @param  int      $holidayTerritoryId
     * @return array
     */
    public function getCurrentDateHoliday($current, $holidayTerritoryId = null) {

        if ($holidayTerritoryId == null) {
            $holidayTerritory = DB::table('holidayterritories')->select('Id')->first();
            if ($holidayTerritory) {
                $holidayTerritoryId = $holidayTerritory->Id;
                $holiday = DB::table('holidayterritorydays')
                    ->where(DB::raw('str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                    ->where(DB::raw('str_to_date(holidayterritorydays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                    ->where('HolidayTerritoryId', $holidayTerritoryId)
                    ->get();
            } else {
                // $holiday = [];
                // fallback to original holidays table
                $holiday = DB::table('holidays')
                        ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                        ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                        ->get();
            }

        } else {
            $holiday = DB::table('holidayterritorydays')
                    ->where(DB::raw('str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                    ->where(DB::raw('str_to_date(holidayterritorydays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                    ->where('HolidayTerritoryId', $holidayTerritoryId)
                    ->get();
        }

        return $holiday;
    }

    /**
     * Function to be called by ajax
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function fetchCalculatedLeaveDays(Request $request)
    {
        if ($request->has('Leave_Id')) {
            $leaveDetail = DB::table('leaves')->select('Leave_Type','Start_Date','End_Date')->where("Id", $request->Leave_Id)->first();
            $request->merge(['Start_Date' => $leaveDetail->Start_Date, 'End_Date' => $leaveDetail->End_Date, 'Leave_Type' => $leaveDetail->Leave_Type]);
        }
        // return $request->all();
        $me=(new CommonController)->get_current_user();
        $holidayTerritoryId = $me->HolidayTerritoryId;
        // the selected start date
        $date1 = new DateTime($request->Start_Date);
        // the selected end date
        $date2 = new DateTime($request->End_Date);

        // the selected start date
        $startdate = $request->Start_Date;

        // calculate the days difference between dates and add one day
        $datediff = $date2->diff($date1)->format("%a") + 1;

        // start date
        $start = strtotime($request->Start_Date);
        // end date
        $end = strtotime($request->End_Date);

        // init array for the leave dates
        $leaveList = array();

        // loop until the end date
        while ($start <= $end) {
            // get start date
            $current = date("d-M-Y", $start);
            if($request->Leave_Type=="Maternity Leave" || $request->Leave_Type=="Hospitalization Leave")
            {
                array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => '','Period' => 'Full', 'Day_Type' => 1]);

            } else {

                // $holiday = DB::table('holidays')
                //     ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //     ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //     ->get();

                $holiday = $this->getCurrentDateHoliday($current, $holidayTerritoryId);

                // if there is holiday for $current minus 1 day from datediff
                // if (count($holiday) > 0 && $request->Leave_Type!="Medical Leave") {
                if (count($holiday) > 0) {
                    // push an array to $leaveList with the details of what day is it, 3 for Holiday
                    array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Holiday: ' . $holiday[0]->Holiday, 'Period' => 'Non-working Day', 'Day_Type' => 2]);
                    $datediff -= 1;
                } else {
                    // get Numeric representation of the day of
                    // the week,  0 (for Sunday), 6(for Saturday)
                    $day_type = date("w", $start);

                    // minus 1 day if its Sunday or Saturday
                    if ($day_type == 0 || $day_type == 6) {
                        if ($day_type == 6 && $me->Working_Days > 5) {
                            if ($request->Leave_Type == '1 Hour Time Off') {
                                // push an array to $leaveList with the details of what day is it, -1 for Workday on weekends
                                array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => '1 Hour', 'Day_Type' => -1]);
                                $datediff -= 0.875;

                            } elseif ($request->Leave_Type == '2 Hours Time Off') {
                                // push an array to $leaveList with the details of what day is it, -1 for Workday on weekends
                                array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => '2 Hours', 'Day_Type' => -1]);
                                $datediff -= 0.75;

                            } else {

                                if ($me->Working_Days >= 6) {
                                    // push an array to $leaveList with the details of what day is it, 1 for Workday on weekends for 6 wd
                                    array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => null, 'Day_Type' => 1]);
                                }

                                else {
                                    // push an array to $leaveList with the details of what day is it, -1 for Workday on weekends for 5.5 wd
                                    array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => 'AM', 'Day_Type' => -1]);
                                    $datediff -= 0.5;

                                }

                            }
                        } else {

                            // if ($request->Leave_Type!="Medical Leave") {
                                // push an array to $leaveList with the details of what day is it, 0 for Weekends
                                array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Weekend','Period' => 'Non-working Day', 'Day_Type' => 0]);
                                $datediff -= 1;
                            // } else {
                            //     // push an array to $leaveList with the details of what day is it, 1 for Workday on weekends for 6 wd
                            //     array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Weekend', 'Period' => null, 'Day_Type' => 1]);
                            // }

                        }
                    } else {

                        if ($request->Leave_Type == '1 Hour Time Off') {
                            // push an array to $leaveList with the details of what day is it, -1 for Workday on weekends
                            array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => '1 Hour', 'Day_Type' => -1]);
                            $datediff -= 0.875;

                        } elseif ($request->Leave_Type == '2 Hours Time Off') {
                            // push an array to $leaveList with the details of what day is it, -1 for Workday on weekends
                            array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => '2 Hours', 'Day_Type' => -1]);
                            $datediff -= 0.75;

                        } else {
                            // push an array to $leaveList with the details of what day is it, 1 for Workday
                            array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => null, 'Day_Type' => 1]);
                        }
                    }
                }
            }

            // move to the next day / +1 day
            $start = strtotime("+1 day", $start);
        }


        $leaveDetails = array(
            "list" => $leaveList,
            "calculated_days" => $datediff
        );

        // return json string
        return response()->json($leaveDetails);
    }

    /**
     * Function to be called by ajax
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function fetchCalculatedLeaveDaysForUser(Request $request)
    {
        if ($request->has('Leave_Id')) {
            $leaveDetail = DB::table('leaves')->select('Leave_Type','Start_Date','End_Date')->where("Id", $request->Leave_Id)->first();
            $request->merge(['Start_Date' => $leaveDetail->Start_Date, 'End_Date' => $leaveDetail->End_Date, 'Leave_Type' => $leaveDetail->Leave_Type]);
        }
        // return $request->all();
        // $me=(new CommonController)->get_current_user();
        $user = DB::table('users')->select('users.Id','users.HolidayTerritoryId','users.Working_Days')->where('users.Id', $request->UserId)->first();

        $holidayTerritoryId = $user->HolidayTerritoryId;
        // the selected start date
        $date1 = new DateTime($request->Start_Date);
        // the selected end date
        $date2 = new DateTime($request->End_Date);

        // the selected start date
        $startdate = $request->Start_Date;

        // calculate the days difference between dates and add one day
        $datediff = $date2->diff($date1)->format("%a") + 1;

        // start date
        $start = strtotime($request->Start_Date);
        // end date
        $end = strtotime($request->End_Date);

        // init array for the leave dates
        $leaveList = array();

        // loop until the end date
        while ($start <= $end) {
            // get start date
            $current = date("d-M-Y", $start);
            if($request->Leave_Type=="Maternity Leave" || $request->Leave_Type=="Hospitalization Leave")
            {
                array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => '','Period' => 'Full', 'Day_Type' => 1]);

            } else {

                // $holiday = DB::table('holidays')
                //     ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //     ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //     ->get();

                $holiday = $this->getCurrentDateHoliday($current, $holidayTerritoryId);

                // if there is holiday for $current minus 1 day from datediff
                // if (count($holiday) > 0 && $request->Leave_Type!="Medical Leave") {
                if (count($holiday) > 0) {
                    // push an array to $leaveList with the details of what day is it, 3 for Holiday
                    array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Holiday: ' . $holiday[0]->Holiday, 'Period' => 'Non-working Day', 'Day_Type' => 2]);
                    $datediff -= 1;
                } else {
                    // get Numeric representation of the day of
                    // the week,  0 (for Sunday), 6(for Saturday)
                    $day_type = date("w", $start);

                    // minus 1 day if its Sunday or Saturday
                    if ($day_type == 0 || $day_type == 6) {
                        if ($day_type == 6 && $user->Working_Days > 5) {
                            if ($request->Leave_Type == '1 Hour Time Off') {
                                // push an array to $leaveList with the details of what day is it, -1 for Workday on weekends
                                array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => '1 Hour', 'Day_Type' => -1]);
                                $datediff -= 0.875;

                            } elseif ($request->Leave_Type == '2 Hours Time Off') {
                                // push an array to $leaveList with the details of what day is it, -1 for Workday on weekends
                                array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => '2 Hours', 'Day_Type' => -1]);
                                $datediff -= 0.75;

                            } else {

                                if ($user->Working_Days >= 6) {
                                    // push an array to $leaveList with the details of what day is it, 1 for Workday on weekends for 6 wd
                                    array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => null, 'Day_Type' => 1]);
                                }

                                else {
                                    // push an array to $leaveList with the details of what day is it, -1 for Workday on weekends for 5.5 wd
                                    array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => 'AM', 'Day_Type' => -1]);
                                    $datediff -= 0.5;

                                }

                            }
                        } else {
                            // push an array to $leaveList with the details of what day is it, 0 for Weekends
                            array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Weekend','Period' => 'Non-working Day', 'Day_Type' => 0]);
                            $datediff -= 1;

                        }
                    } else {
                        // push an array to $leaveList with the details of what day is it, 1 for Workday
                        array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => null, 'Day_Type' => 1]);
                    }
                }
            }

            // move to the next day / +1 day
            $start = strtotime("+1 day", $start);
        }


        $leaveDetails = array(
            "list" => $leaveList,
            "calculated_days" => $datediff
        );

        // return json string
        return response()->json($leaveDetails);
    }
    public function leavemanagement2($start = null, $end = null)
    {
        $me=(new CommonController)->get_current_user();

        if ($start==null)
        {

            $start=date('d-M-Y', strtotime('first day of this month'));
        }

        if ($end==null)
        {
            $end=date('d-M-Y', strtotime('last day of this year'));

        }

        if($me->View_All_Leave)
        {
            $showleave = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin(DB::raw('(SELECT leave_terms.Leave_Id, GROUP_CONCAT(DISTINCT CONCAT(\'[\',SUBSTR(leave_terms.Leave_Date,1,6),\' \',leave_terms.Leave_Period,\']\') ORDER BY leave_terms.Id SEPARATOR \',\') as Terms FROM leave_terms WHERE str_to_date(leave_terms.Leave_Date,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") Group By leave_terms.Leave_Id) as leave_terms'), 'leave_terms.Leave_Id', '=', 'leaves.Id')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
            ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('leaves.Id','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leave_terms.Terms','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date')
            ->where('leavestatuses.Leave_Status', '<>','Cancelled')
            ->whereRaw('(str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") OR str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            // ->where(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
            ->orderBy('leaves.Id','desc')
            ->get();

        }
        else {
            # code...
            $showleave = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin(DB::raw('(SELECT leave_terms.Leave_Id, GROUP_CONCAT(DISTINCT CONCAT(\'[\',SUBSTR(leave_terms.Leave_Date,1,6),\' \',leave_terms.Leave_Period,\']\') ORDER BY leave_terms.Id SEPARATOR \',\') as Terms FROM leave_terms WHERE str_to_date(leave_terms.Leave_Date,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") Group By leave_terms.Leave_Id) as leave_terms'), 'leave_terms.Leave_Id', '=', 'leaves.Id')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
            ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('leaves.Id','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leave_terms.Terms','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date')
            ->where('leavestatuses.Leave_Status', '<>','Cancelled')
            ->where('leavestatuses.UserId', '=',$me->UserId)
            ->whereRaw('(str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") OR str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            // ->where(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
            // ->orWhere(DB::raw('str_to_date(leaves.End_Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
            ->orderBy('leaves.Id','desc')
            ->get();
        }



            $leaves = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
            ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('leavestatuses.Id','leaves.Id as LeaveId','leavestatuses.Leave_Status as Status','applicant.StaffId as Staff_ID','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.Medical_Claim','leaves.Panel_Claim','leaves.Verified_By_HR','leaves.Medical_Paid_Month','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Comment','leavestatuses.updated_at as Review_Date','files.Web_Path')
            ->orderBy('leaves.Id','desc')
            ->where('leavestatuses.UserId', '=',$me->UserId)
            ->get();



            $allleaves = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
            ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('leavestatuses.Id','leaves.Id as LeaveId','leavestatuses.Leave_Status as Status','applicant.StaffId as Staff_ID','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.Medical_Claim','leaves.Panel_Claim','leaves.Verified_By_HR','leaves.Medical_Paid_Month','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Comment','leavestatuses.updated_at as Review_Date','files.Web_Path')
            ->where(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
            ->where(DB::raw('str_to_date(leaves.End_Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
            ->orderBy('leaves.Id','desc')
            ->get();

            $finalapprovedleave = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
            ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('leavestatuses.Id','leaves.Id as LeaveId','leavestatuses.Leave_Status as Status','applicant.StaffId as Staff_ID','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.Medical_Claim','leaves.Panel_Claim','leaves.Verified_By_HR','leaves.Medical_Paid_Month','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Comment','leavestatuses.updated_at as Review_Date','files.Web_Path')
            ->where('leavestatuses.Leave_Status', 'like','%Final Approved%')
            ->orderBy('leaves.Id','desc')
            ->get();

            $approver = DB::table('approvalsettings')
            ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
            ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
            ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
            ->where('approvalsettings.Type', '=', 'Leave')
            ->orderBy('approvalsettings.Country','asc')
            ->orderBy('projects.Project_Name','asc')
            ->orderBy('approvalsettings.Level','asc')
            ->get();

            $mylevel = DB::table('approvalsettings')
            ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
            ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
            ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
            ->where('approvalsettings.Type', '=', 'Leave')
            ->where('approvalsettings.UserId', '=', $me->UserId)
            ->orderBy('approvalsettings.Country','asc')
            ->orderBy('projects.Project_Name','asc')
            // ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
            ->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
            ->first();

            $holidays = DB::table('holidays')
            ->select('holidays.Id','holidays.Holiday','holidays.Start_Date','holidays.End_Date','holidays.State','holidays.Country')
            ->whereRaw('right(Start_Date,4)='.date('Y'))
            ->orderBy('holidays.Start_Date','asc')
            ->get();

            return view('leavemanagement2', ['me' => $me,'allleaves'=>$allleaves,'leaves' => $leaves,'finalapprovedleave'=>$finalapprovedleave,'showleave' => $showleave,'approver' => $approver,'mylevel' => $mylevel,'start'=>$start,'end'=>$end,'holidays'=>$holidays ]);

    }

    public function redirect(Request $request)
    {

            $me=(new CommonController)->get_current_user();

            $input = $request->all();

            // $result= DB::table('leavestatuses')
      //       ->where('Id', $input["Id"])
      //       ->update(array(
            //          'UserId' =>  $input["Approver"],
            //      ));

            $Ids = explode(",", $input["Id"]);
            if (count($Ids)) {
                DB::table('notificationstatus')->whereIn('TargetId', $Ids)->where('type', 'Pending Leave')->update(['seen' => 1]);
            }
            foreach ($Ids as $Id) {
                # code...

                $id=DB::table('leavestatuses')->insertGetId(
                    ['LeaveId' => $Id,
                     'UserId' => $input["Approver"],
                     'Leave_Status' => "Pending Approval"
                    ]
                );

                if ($me->UserId != $input['Approver']) {
                     DB::table('notificationstatus')->insert([
                    'userid' => $input["Approver"],
                    'type' => 'Pending Leave',
                    'seen' => 0,
                    'TargetId' => $item->Id
                    ]);

                }
            }

            if ($id>0)
            {

                $leavedetail = DB::table('leaves')
                ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
                ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
                ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
                ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
                ->select('applicant.Id as UserId','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver')
                ->orderBy('leavestatuses.Id','desc')
                ->where('leaves.Id', '=',$Ids)
                ->first();

                $notify = DB::table('users')
                ->whereIn('Id', [$me->UserId, $input["Approver"],$leavedetail->UserId])
                ->get();

                $subscribers = DB::table('notificationtype')
                ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
                ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                ->where('notificationtype.Id','=',25)
                ->get();

                $emails = array();
                $NotificationSubject="";

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

                Mail::send('emails.leaveapplication', ['leavedetail' => $leavedetail], function($message) use ($emails,$me,$NotificationSubject)
                {
                        $emails = array_filter($emails);
                        array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
                });

                return 1;
            }
            else {
                return 0;
            }

    }

    public function cancelleave(Request $request)
    {

            $me=(new CommonController)->get_current_user();

            $input = $request->all();

            // $result= DB::table('leavestatuses')
      //       ->where('Id', $input["Id"])
      //       ->update(array(
            //          'UserId' =>  $input["Approver"],
            //      ));

            $leavedetail = DB::table('leaves')
            ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver')
            ->orderBy('leavestatuses.Id','desc')
            ->where('leaves.Id', '=',$input["Id"])
            ->first();

        DB::table('notificationstatus')->where('TargetId', $input["Id"])->where('type', 'Pending Leave')->update(['seen' => 1]);
            $id=DB::table('leavestatuses')->insertGetId(
                ['LeaveId' => $input["Id"],
                 'UserId' => 0,
                 'Leave_Status' => "Cancelled"
                ]
            );

            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',26)
            ->get();



            if ($id>0)
            {

                $notify = DB::table('users')
                ->whereIn('Id', [$me->UserId, $leavedetail->Approver])
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

                Mail::send('emails.leavecancel', ['leavedetail' => $leavedetail], function($message) use ($emails,$me,$NotificationSubject)
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

    public function deleteleave(Request $request)
    {

            $me=(new CommonController)->get_current_user();

            $input = $request->all();

            DB::table('leaves')->where('Id', '=',$input["Id"])->delete();

            return 1;

    }

    public function submit(Request $request)
    {

        $me = (new CommonController)->get_current_user();

        $input = $request->all();

        $leaveIds = explode(",", $input["LeaveIds"]);

        $leaves = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leavestatuses.Id','leaves.Id as LeaveId','leaves.UserId as ApplicantId','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Id as ApproverId','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date')
        ->orderBy('leaves.Id','desc')
        ->whereIn('leaves.Id', $leaveIds)
        ->get();

        $approver = DB::table('approvalsettings')
        ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
        ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
        ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
        ->where('approvalsettings.Type', '=', 'Leave')
        ->where('approvalsettings.ProjectId', '<>', '0')
        ->orderBy('approvalsettings.Country','asc')
        ->orderBy('projects.Project_Name','asc')
        ->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
        ->get();

        // $countryapprover = DB::table('approvalsettings')
        // ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
        // ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
        // ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
        // ->where('approvalsettings.Type', '=', 'Leave')
        // ->where('approvalsettings.ProjectId', '=', '0')
        // ->orderBy('approvalsettings.Country','asc')
        // ->orderBy('projects.Project_Name','asc')
        // ->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
        // ->get();

        $final=false;

        foreach ($leaves as $leave) {

            $emaillist=array();
            array_push($emaillist,$me->UserId);
            # code...
            $submitted=false;
            $currentstatus=$leave->Status;

            if ($leave->Status=="Final Approved")
            {
                array_push($emaillist,$leave->ApplicantId);
                array_push($emaillist,$leave->ApproverId);
            }

            if ((strpos($leave->Status, 'Rejected') === false) && $leave->Status!="Final Approved")
            {

                foreach ($approver as $user) {

                        if (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId != $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($leave->Status, FILTER_SANITIZE_NUMBER_INT))
                        {

                            DB::table('leavestatuses')->insert(
                                ['LeaveId' => $leave->LeaveId,
                                 'UserId' => $user->Id,
                                 'Leave_Status' => "Pending Approval"
                                ]
                            );
                            $submitted=true;
                            array_push($emaillist,$user->Id);
                            array_push($emaillist,$leave->ApplicantId);

                            break;
                        }
                        elseif (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId == $user->Id  && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($leave->Status, FILTER_SANITIZE_NUMBER_INT))
                        {
                            # code...
                                $submitted=true;
                                array_push($emaillist,$user->Id);
                                array_push($emaillist,$leave->ApplicantId);
                        }
                        elseif (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId != $user->Id && $user->Level=="Final Approval")
                        {

                            DB::table('leavestatuses')->insert(
                                ['LeaveId' => $leave->LeaveId,
                                 'UserId' => $user->Id,
                                 'Leave_Status' => "Pending Approval"
                                ]
                            );
                            $submitted=true;
                            array_push($emaillist,$user->Id);
                            array_push($emaillist,$leave->ApplicantId);

                            break;
                        }
                    }

                    // if ($submitted==false)
                    // {
                    //  foreach ($countryapprover as $user) {
                    //
                    //      if (!empty($user->Id) && $leave->UserId != $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($leave->Status, FILTER_SANITIZE_NUMBER_INT))
                    //      {
                    //          DB::table('leavestatuses')->insert(
                    //              ['LeaveId' => $leave->LeaveId,
                    //               'UserId' => $user->Id,
                    //               'Status' => "Pending Approval"
                    //              ]
                    //          );
                    //          array_push($emaillist,$user->Id);
                    //          array_push($emaillist,$leave->ApplicantId);
                    //          break;
                    //      }
                    //      elseif (!empty($user->Id) && $leave->UserId == $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($leave->Status, FILTER_SANITIZE_NUMBER_INT))
                    //      {
                    //          array_push($emaillist,$user->Id);
                    //          array_push($emaillist,$leave->ApplicantId);
                    //          break;
                    //      }
                    //      elseif (!empty($user->Id) && $leave->ApproverId != $user->Id && $user->Level=="Final Approval")
                    //      {
                    //
                    //          DB::table('leavestatuses')->insert(
                    //              ['LeaveId' => $leave->LeaveId,
                    //               'UserId' => $user->Id,
                    //               'Leave_Status' => "Pending Approval"
                    //              ]
                    //          );
                    //          $submitted=true;
                    //          array_push($emaillist,$user->Id);
                    //          array_push($emaillist,$leave->ApplicantId);
                    //
                    //          break;
                    //      }
                    //
                    //  }
                    // }

            }
            elseif ((strpos($leave->Status, 'Rejected') !== false))
            {

                array_push($emaillist,$leave->ApplicantId);
            }
            elseif ($leave->Status=="Final Approved" || $leave->Leave_Status=="Final Rejected")
            {
                $final=true;
                array_push($emaillist,$leave->ApplicantId);
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
                    ->where('notificationtype.Id','=',39)
                    ->get();
                }
                else {
                    $subscribers = DB::table('notificationtype')
                    ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
                    ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                    ->where('notificationtype.Id','=',28)
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

                // array_push($emails,"latifah@pronetwork.com.my");

                $leavedetail = DB::table('leaves')
                ->leftJoin('leavestatuses', 'leavestatuses.LeaveId', '=', 'leaves.Id')
                ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
                ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
                ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
                ->select('applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date')
                ->orderBy('leavestatuses.Id','desc')
                ->where('leaves.Id', '=',$leave->LeaveId)
                ->get();

                Mail::send('emails.leavestatus', ['me' => $me,'leavedetail' => $leavedetail], function($message) use ($emails,$leavedetail,$NotificationSubject)
                {
                        array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        $emails = array_filter($emails);
                        $message->to($emails)->subject($NotificationSubject.' ['.$leavedetail[0]->Name.']');

                });

                return 1;
            }
            else {
                return 0;
            }

        }

        return 1;

    }

    public function redirect2(Request $request)
    {

            $me=(new CommonController)->get_current_user();

            $arrLeaveId = array();

            $input = $request->all();

            $Ids = explode(",", $input["Ids"]);

            $leavedetail = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
            ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('leaves.Id','leaves.UserId','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status')
            ->orderBy('leavestatuses.Id','desc')
            ->whereIn('leaves.Id', $Ids)
            ->get();

            if (count($leavedetail)) {
                DB::table('notificationstatus')->whereIn('TargetId', $Ids)->where('type', 'Pending Leave')->update(['seen' => 0]);
            }
            foreach ($leavedetail as $item) {

                # code...
                if(str_contains($item->Leave_Status,"Final Approved")==false)
                {
                    $id=DB::table('leavestatuses')->insertGetId(
                        ['LeaveId' => $item->Id,
                         'UserId' => $input["Approver"],
                         'Leave_Status' => "Pending Approval"
                        ]
                    );

                    if ($me->UserId != $input["Approver"]) {
                         DB::table('notificationstatus')->insert([
                        'userid' => $input["Approver"],
                        'type' => 'Pending Leave',
                        'seen' => 0,
                        'TargetId' => $item->Id
                        ]);

                    }

                    array_push($arrLeaveId,$item->Id);
                }
            }


            if ($id>0)
            {

                $leavedetail = DB::table('leaves')
                ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
                ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
                ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
                ->select('applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver')
                ->orderBy('leavestatuses.Id','desc')
                ->whereIn('leaves.Id', $Ids)
                ->first();

                $subscribers = DB::table('notificationtype')
                ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
                ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                ->where('notificationtype.Id','=',28)
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

                $notify = DB::table('users')
                ->whereIn('Id', [$me->UserId, $input["Approver"]])
                ->get();

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

                //array_push($emails,"latifah@pronetwork.com.my");

                Mail::send('emails.leaveapprovalrequest', ['leavedetail' => $leavedetail,'from'=>$me->Name], function($message) use ($emails,$leavedetail,$NotificationSubject)
                {
                        array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                        $emails = array_filter($emails);
                        $message->to($emails)->subject($NotificationSubject.' ['.$leavedetail->Name.']');
                });

                return 1;
            }
            else {
                return 0;
            }

    }

    public function applyleave(Request $request)
    {
            $me=(new CommonController)->get_current_user();

            $input = $request->all();


            $rules = array(
                'Leave_Type' => 'Required',
                // 'Leave_Term'     => 'Required',
                'Start_Date'       => 'Required',
                'End_Date'  =>'Required',
                'Approver'  =>'Required',
                'Project'  =>'Required',
                // 'attachment' => 'required_unless:Leave_Type,Annual Leave,1 Hour Time Off,2 Hours Time Off'
                );

                $messages = array(
                    'Leave_Type.required' => 'The Leave Type field is required',
                    // 'Leave_Term.required'     => 'The Leave Term field is required',
                    'Start_Date.required'       => 'The Start Date field is required',
                    'End_Date.required'  =>'The End Date field is required',
                    'Approver.required'  =>'The Approver field is required',
                    'Project.required'  =>'The Project Name field is required',
                    ''
                );

            $validator = Validator::make($input, $rules,$messages);

            if ($validator->passes())
            {

                if ($input["Leave_Type"]=="Compassionate Leave" ||
                    $input["Leave_Type"]=="Medical Leave" ||
                    $input["Leave_Type"]=="Marriage Leave") {


                    if (! $request->hasFile('attachment')) {
                        return json_encode([
                            'Required' => [
                                'Attachment is required for this type of leave.'
                            ]
                        ]);
                    }
                }

                if (! $this->isLeaveDatesValid($request->Start_Date, $request->End_Date)) {
                    return json_encode([
                        'Invalid' => [
                            'The leave dates from ' . $request->Start_Date .
                            ' to ' . $request->End_Date .
                            ' is not valid.'
                        ]
                    ]);
                }

                if ($this->isLeaveDatesOverlapped($request, $me)) {
                    return json_encode([
                        'Overlapped' => [
                            'The dates between ' . $request->Start_Date .
                            ' and ' . $request->End_Date .
                            ' is overlapped with your another leave application.'
                        ]
                    ]);
                }

                if (! $request->has('Leave_Period')) {
                    return json_encode([
                        'Invalid Days' => [
                            'The number of days is 0. Please check or select the leave term before submitting.'
                        ]
                    ]);
                }


                // $leaves = DB::table('leaves')
                // ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
                // ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
                // ->where('leaves.UserId','=',$me->UserId)
                // ->whereRaw('leavestatuses.Leave_Status!="Cancelled" AND leavestatuses.Leave_Status not like "%Rejected%"')
                // ->whereRaw('((str_to_date("'.$input["Start_Date"].'","%d-%M-%Y") between str_to_date(leaves.Start_Date,"%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")) or (str_to_date("'.$input["End_Date"].'","%d-%M-%Y") between str_to_date(leaves.Start_Date,"%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")) or (str_to_date(leaves.Start_Date,"%d-%M-%Y") between str_to_date("'.$input["Start_Date"].'","%d-%M-%Y") AND str_to_date("'.$input["End_Date"].'","%d-%M-%Y")) or (str_to_date(leaves.End_Date,"%d-%M-%Y") between str_to_date("'.$input["Start_Date"].'","%d-%M-%Y") AND str_to_date("'.$input["End_Date"].'","%d-%M-%Y")))')
                // ->get();


                // if(!$leaves)
                // {
                    $No_of_Days=$this->calculateLeaveDaysWithPeriod($request, $me->Working_Days, $me->HolidayTerritoryId);

                    if ($No_of_Days <= 0) {
                        return json_encode([
                            'Invalid Days' => [
                                'The number of days is 0. Please check or select the leave term before submitting.'
                            ]
                        ]);
                    }

                    if ($request->Leave_Type == '1 Hour Time Off' || $request->Leave_Type == '2 Hours Time Off') {
                        $start    = strtotime($request->Start_Date);
                        $firstMonthStart = strtotime("+20 day", strtotime("first day of last month", $start));
                        $firstMonthEnd = strtotime("+19 day", strtotime("first day of this month", $start));
                        $secondMonthStart = strtotime("+20 day", strtotime("first day of this month", $start));
                        $secondMonthEnd = strtotime("+19 day", strtotime("first day of next month", $start));

                        if ($start < $secondMonthStart) {
                            $timeoff = $this->getTotalTimeoff($me->UserId,date("d-M-Y",$firstMonthStart),date("d-M-Y",$firstMonthEnd));
                        } else {
                            $timeoff = $this->getTotalTimeoff($me->UserId,date("d-M-Y",$secondMonthStart),date("d-M-Y",$secondMonthEnd));
                        }

                        if (($timeoff + $No_of_Days) > 1) {
                            return json_encode([
                                'Timeoff limit exceed' => [
                                    'Timeoff application failed! Timeoff application exceeded the 8 hours monthly limit.'
                                ]
                            ]);
                        }

                    }



                    // to check if AL got balance
                    $originalLeaveType = $request->Leave_Type;
                    if ($originalLeaveType == 'Emergency Leave' || $originalLeaveType == '1 Hour Time Off' || $originalLeaveType == '2 Hours Time Off' || $originalLeaveType == 'Unpaid Leave') {
                        $request->merge(['Leave_Type' => 'Annual Leave']);
                    }

                    // check balance
                    if ($this->isLeaveOutOfBalance($me->UserId,$request->Leave_Type, $No_of_Days)) {

                        //convert EL to UL if no balance
                        if ($originalLeaveType == 'Emergency Leave') {
                            $request->merge(['Leave_Type' => 'Unpaid Leave']);
                            $request->merge(['Reason' => '[EL to UL] ' . $request->Reason]);
                        } elseif($originalLeaveType == '1 Hour Time Off' || $originalLeaveType == '2 Hours Time Off') {
                            $request->merge(['Leave_Type' => 'Unpaid Leave']);
                            $request->merge(['Reason' => '[Time Off to UL] ' . $request->Reason]);
                        } elseif ($originalLeaveType == 'Unpaid Leave') {
                            $request->merge(['Leave_Type' => 'Unpaid Leave']);
                        } else {
                            return json_encode([
                                'Out of balance' => [
                                    'Not enough balance.'
                                ]
                            ]);
                        }
                    }

                    if ($originalLeaveType == 'Unpaid Leave') {

                        $request->merge(['Leave_Type' => 'Annual Leave']);

                        $bal = $this->checkLeaveBalance($me->UserId, $request->Leave_Type);

                        if (! empty($bal)) {
                            $balance = $bal[0]->Total_Leave_Balance;
                        } else {
                            // leave out of balance
                            $balance = 0;
                        }

                        // if balance is not enough
                        if ($balance < $No_of_Days && $balance > 0) {
                            // convert if AL got balance
                            return $this->convertUnpaidLeaveToAnnualLeave($request, $balance, $No_of_Days, $me->UserId, $me);
                        } else {
                            $request->merge(['Leave_Type' => 'Unpaid Leave']);
                        }
                    }

                    // keep EL or Time off if AL got balance
                    if (($originalLeaveType == 'Emergency Leave' || $originalLeaveType == '1 Hour Time Off' || $originalLeaveType == '2 Hours Time Off') && $request->Leave_Type == 'Annual Leave') {
                        $request->merge(['Leave_Type' => $originalLeaveType]);
                    }



                    $startYear = DateTime::createFromFormat('d-M-Y', $input['Start_Date'])->format("Y");
                    $endYear = DateTime::createFromFormat('d-M-Y', $input['End_Date'])->format("Y");

                    if ($startYear != $endYear) {
                        return $this->splitLeaveApplication($request, $me, $startYear, $endYear, $originalLeaveType);
                    } else {

                        return $this->splitLeaveApplicationByCutOffDate($request, $me, $No_of_Days, $originalLeaveType);
                        // return $this->insertLeaveAndSendEmail($request, $me, $No_of_Days, $originalLeaveType);
                    }

                // }
                // else {
                //     return -1;
                // }
            }
            else {
                return json_encode($validator->errors()->toArray());
            }

    }

    /**
     * Get the total timeoff application for the given user
     * @param  int $userId
     * @param  string $startDate
     * @param  string $endDate
     * @param  int $year
     * @return float
     */
    public function getTotalTimeoff($userId, $startDate = null, $endDate = null)
    {

        $year = date('Y');

        if ($startDate == null && $endDate == null) {
            $startDate = '01-Jan-' . $year;
            $endDate = '31-Dec-' . $year;
        }
        else if ($startDate == null) {
            $startDate = '01-Jan-' . $year;
        }
        else if ($endDate == null) {
            $endDate = '31-Dec-' . $year;
        }

        $leaveTaken = DB::select("
            SELECT SUM(No_of_Days) as Total_Leave_Taken FROM leaves
            LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
            LEFT JOIN leavestatuses ON leavestatuses.Id = max.maxid
            WHERE leaves.UserId = $userId AND (leaves.Reason LIKE '%[Time Off to UL]%' OR leaves.Leave_Type IN ('1 Hour Time Off', '2 Hours Time Off')) AND (Leave_Status LIKE '%Approved%' OR Leave_Status LIKE '%Pending%')
            AND str_to_date(leaves.Start_Date,'%d-%M-%Y')>= str_to_date('$startDate', '%d-%M-%Y') AND MONTH(str_to_date(leaves.End_Date,'%d-%M-%Y'))<= str_to_date('$endDate', '%d-%M-%Y')
            GROUP BY leaves.UserId
        ");

        return $leaveTaken ? $leaveTaken[0]->Total_Leave_Taken : 0;
    }

    /**
     * Split the leave into two and insert it
     * @param  \Illuminate\Http\Request $request
     * @param  int $userId
     * @param  int $startYear
     * @param  int $endYear
     * @return int
     */
    protected function splitLeaveApplication($request, $me, $startYear, $endYear, $originalLeaveType = null)
    {
        $startDate = $request->Start_Date;
        $endDate   = '31-Dec-' . $startYear;

        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        $days = $start->diff($end)->format('%a') + 1;

        $startDateNextYear = '01-Jan-' . $endYear;
        $endDateNextYear   = $request->End_Date;

        $startNextYear = new DateTime($startDateNextYear);
        $endNextYear = new DateTime($endDateNextYear);

        // $daysNextYear = $startNextYear->diff($endNextYear)->format('%a') + 1;

        $allPeriods = $request->Leave_Period;
        $periods = array_slice($allPeriods, 0, $days);
        $periodsNextYear = array_slice($allPeriods, $days);

        $requestNextYear = clone $request;
        $requestNextYear->merge(['Start_Date' => $startDateNextYear]);
        $request->merge(['End_Date' => $endDate]);

        $request->merge(['Leave_Period' => $periods]);
        $requestNextYear->merge(['Leave_Period' => $periodsNextYear]);

        $No_of_Days=$this->calculateLeaveDaysWithPeriod($request, $me->Working_Days, $me->HolidayTerritoryId);
        $No_of_Days_Next_Year=$this->calculateLeaveDaysWithPeriod($requestNextYear, $me->Working_Days, $me->HolidayTerritoryId);

        $this->splitLeaveApplicationByCutOffDate($request, $me, $No_of_Days, $originalLeaveType);
        // $this->insertLeaveAndSendEmail($request, $me, $No_of_Days, $originalLeaveType);
        $this->splitLeaveApplicationByCutOffDate($requestNextYear, $me, $No_of_Days_Next_Year, $originalLeaveType);
        // $this->insertLeaveAndSendEmail($requestNextYear, $me, $No_of_Days_Next_Year, $originalLeaveType);

        return 1;
    }

    protected function splitLeaveApplicationByCutOffDate(Request $request, $me, $No_of_Days, $originalLeaveType = null)
    {
        /*
            If days > 1
                check start < 21 and end > 20
                    if true split
                    else continue
            else continue

         */

        if ($No_of_Days <= 1) {
            $this->insertLeaveAndSendEmail($request, $me, $No_of_Days, $originalLeaveType);
        } else {

            $start    = strtotime($request->Start_Date);
            $end      = strtotime($request->End_Date);

            $arrDates = [];

            while($start <= $end ) {
                $current = date("d-M-Y", $start);
                $endDate = strtotime("+19 day", strtotime("first day of this month", $start));
                $startDate = strtotime("+20 day", strtotime("first day of this month", $start));
                $endDate2 = strtotime("+19 day", strtotime("first day of next month", $start));
                $start = strtotime("+20 day", strtotime("first day of next month", $start));

                if ($start >= $end) {

                    if (strtotime($current) <= $endDate) {

                        if ($end <= $endDate2) {
                            if ($end <= $endDate) {
                                array_push($arrDates, [
                                    'Start_Date' => $current,
                                    'End_Date' => date("d-M-Y",$end)
                                ]);
                            } else {
                                array_push($arrDates, [
                                    'Start_Date' => $current,
                                    'End_Date' => date("d-M-Y",$endDate)
                                ]);
                                array_push($arrDates, [
                                    'Start_Date' => date('d-M-Y',$startDate),
                                    'End_Date' => date("d-M-Y",$end)
                                ]);
                            }

                        } else {
                            array_push($arrDates, [
                                'Start_Date' => $current,
                                'End_Date' => date("d-M-Y",$endDate)
                            ]);

                            array_push($arrDates, [
                                'Start_Date' => date('d-M-Y',$startDate),
                                'End_Date' => date("d-M-Y",$endDate2)
                            ]);
                        }

                    } else {
                        if ($end > $endDate2) {
                            array_push($arrDates, [
                                'Start_Date' => $current,
                                'End_Date' => date("d-M-Y",$endDate2)
                            ]);
                        } else {
                            array_push($arrDates, [
                                'Start_Date' => $current,
                                'End_Date' => date("d-M-Y",$end)
                            ]);
                        }

                    }

                } else {

                    if (strtotime($current) <= $endDate) {
                        array_push($arrDates, [
                            'Start_Date' => $current,
                            'End_Date' => date("d-M-Y",$endDate)
                        ]);
                    }

                    if (strtotime($current) > $startDate) {
                        array_push($arrDates, [
                                'Start_Date' => $current,
                                'End_Date' => date("d-M-Y",$endDate2)
                        ]);
                    } else {
                        array_push($arrDates, [
                                'Start_Date' => date('d-M-Y',$startDate),
                                'End_Date' => date("d-M-Y",$endDate2)
                        ]);
                    }
                }
            }

            $allPeriods = $request->Leave_Period;
            // dd($arrDates);
            foreach($arrDates as $leaveDate) {
                $start = new DateTime($leaveDate["Start_Date"]);
                $end = new DateTime($leaveDate["End_Date"]);

                $days = $start->diff($end)->format('%a') + 1;

                $periods = array_slice($allPeriods, 0, $days);
                $allPeriods = array_slice($allPeriods, $days);

                $requestCurrentMonth = clone $request;
                $requestCurrentMonth->merge(['Start_Date' => $leaveDate["Start_Date"]]);
                $requestCurrentMonth->merge(['End_Date' => $leaveDate["End_Date"]]);
                $requestCurrentMonth->merge(['Leave_Period' => $periods]);

                $No_of_Days=$this->calculateLeaveDaysWithPeriod($requestCurrentMonth, $me->Working_Days, $me->HolidayTerritoryId);
                $this->insertLeaveAndSendEmail($requestCurrentMonth, $me, $No_of_Days, $originalLeaveType);
            }
        }


        return 1;
    }

    /**
     * Insert leave into database and send email
     * @param  \Http\Illuminate\Request
     * @return int
     */
    protected function insertLeaveAndSendEmail(Request $request, $me, $No_of_Days, $originalLeaveType = null)
    {
        $input = $request->all();
        $days  = $this->calculateprocessdays($input["Start_Date"], $me);

        if ($input["Leave_Type"] != "Medical Leave" && $input["Leave_Type"] != "Compassionate Leave" && $input["Leave_Type"] != "Emergency Leave" && $input["Leave_Type"] != "Replacement Leave" && $days < 3) {

            $id = DB::table('leaves')->insertGetId([
                'UserId' => $me->UserId,
                'Leave_Type' => $input["Leave_Type"],
                'Start_Date' => $input["Start_Date"],
                'End_Date' => $input["End_Date"],
                'ProjectId' => $input["Project"],
                'No_Of_Days' => $No_of_Days,
                'Reason' => "[LATE SUBMISSION] ".$input["Reason"]
             ]);

        } else {
            // code...
            $id=DB::table('leaves')->insertGetId([
                'UserId' => $me->UserId,
                'Leave_Type' => $input["Leave_Type"],
                'Start_Date' => $input["Start_Date"],
                'End_Date' => $input["End_Date"],
                'ProjectId' => $input["Project"],
                'No_Of_Days' => $No_of_Days,
                'Reason' => $input["Reason"]
             ]);
        }

        $this->saveLeaveDaysWithPeriod($request, $id, $me, $originalLeaveType);
        // if ($input["Leave_Type"]=="Compassionate Leave" ||
        //     $input["Leave_Type"]=="Maternity Leave" ||
        //     $input["Leave_Type"]=="Marriage Leave" ||
        //     $input["Leave_Type"]=="Paternity Leave") {

        //     //no approver if no attachment
        //     // if ($request->hasFile('attachment')) {

        //         DB::table('leavestatuses')->insert([
        //             'LeaveId' => $id,
        //             'UserId' => $input["Approver"],
        //             'Leave_Status' =>"Pending Approval"
        //         ]);

        //     // }

        // } else {
            // code...
            DB::table('leavestatuses')->insert([
                'LeaveId' => $id,
                'UserId' => $input["Approver"],
                'Leave_Status' =>"Pending Approval"
            ]);
        // }


        $filenames="";
        $attachmentUrl = null;
        $type="Leave";
        $uploadcount=count($request->file('attachment'));

        if ($request->hasFile('attachment')) {

            for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $request->file('attachment')[$i];
                $destinationPath=public_path()."/private/upload/Leave";
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $id,
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Web_Path' => '/private/upload/Leave/'.$fileName
                    ]
                );
                $attachmentUrl = url('/private/upload/Leave/'.$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            $filenames=substr($filenames, 0, strlen($filenames)-1);

            //return '/private/upload/'.$fileName;
        }

        $leavedetail = DB::table('leaves')
        ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        // ->select('applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','projects.Project_Name','leaves.created_at as Application_Date','approver.Name as Approver')
        ->select('applicant.Name','leaves.Leave_Type','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','projects.Project_Name','leaves.created_at as Application_Date','approver.Name as Approver')
        ->orderBy('leavestatuses.Id','desc')
        ->where('leaves.Id', '=',$id)
        ->first();

        $notify = DB::table('users')
        ->whereIn('Id', [$me->UserId, $input["Approver"]])
        ->get();

        $subscribers = DB::table('notificationtype')
        ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
        ->where('notificationtype.Id','=',25)
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

            if ($user->Company_Email!="") {
                array_push($emails,$user->Company_Email);
            } else {
                array_push($emails,$user->Personal_Email);
            }
        }

        $periods = DB::table('leave_terms')->where('leave_terms.Leave_Id', $id)->get();

        Mail::send('emails.leaveapplicationwithperiod', ['leavedetail' => $leavedetail,'periods'=>$periods, 'attachmentUrl' => $attachmentUrl], function($message) use ($emails,$me,$NotificationSubject)
        {
            array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
            $emails = array_filter($emails);
            $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
        });

        return 1;
    }

    /**
     * Update Leave Terms
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     */
    public function updateLeaveTerms(Request $request)
    {
        $me=(new CommonController)->get_current_user();
        $periods = $request->Leave_Period;
        $No_of_Days = 0;

        DB::table('leave_terms')->where('Leave_Id', $request->Leave_Id)->delete();

       $leave = DB::table('leaves')->select('Id','leaves.Start_Date', 'leaves.End_Date','leaves.Leave_Type')->where('Id', $request->Leave_Id)->first();
       $request->merge(['Start_Date' => $leave->Start_Date, 'End_Date'=> $leave->End_Date, 'Leave_Type' => $leave->Leave_Type]);

       $No_of_Days = $this->calculateLeaveDaysWithPeriod($request, $me->Working_Days, $me->HolidayTerritoryId);
       DB::table('leaves')->where('Id', $leave->Id)->update([
            'No_of_Days' => $No_of_Days
       ]);

       $this->saveLeaveDaysWithPeriod($request, $leave->Id, $me);
       return 1;
    }

    /**
     * Get the adjusted leave number
     * @param  int    $userId
     * @param  string $leaveType
     * @param  int    $year
     * @return int
     */
    public function adjustedLeaveValue($userId, $leaveType, $year) {
        $adjusted = DB::table('leaveadjustments')
                ->select('Adjustment_Value')
                ->where('UserId', '=', $userId)
                ->where('Adjustment_Leave_Type', '=', $leaveType)
                ->where('Adjustment_Year', '=', $year)
                ->first();

        return $adjusted ? $adjusted->Adjustment_Value : 0;
    }

    /**
     * Adjust for replacement
     */
    public function adjustReplacementLeave($leave, $approverId) {
        $userId = $leave->ApplicantId;
        $year = date("Y",strtotime($leave->Start_Date));
        $days = $leave->No_of_Days;

        if ($days != 0) {
            $updated = DB::table('leaveadjustments')
                        ->where('UserId', $userId)
                        ->where('Adjustment_Year', $year)
                        ->where('Adjustment_Leave_Type', 'Annual Leave')
                        ->update(['Adjustment_Value' => DB::raw("Adjustment_Value + " . $days)]);
            if (! $updated) {
                DB::table('leaveadjustments')->insert([
                    'UserId' => $userId,
                    'Adjustment_Value' => $days,
                    'Adjustment_Leave_Type' => 'Annual Leave',
                    'Adjustment_Year' => $year
                ]);

            }
            DB::table('leaveadjustmentshistory')->insert([
                'UserId' => $userId,
                'ApproverId' => $approverId,
                'Adjustment_Value' => $days,
                'Adjustment_Leave_Type' => 'Annual Leave',
                'Adjustment_Year' => $year,
                'Remarks' => '[Replacement Leave ' . $leave->Start_Date . '-' . $leave->End_Date . '] ' . $leave->Reason,
            ]);
        }

    }

    public function convertEmergencyLeave(Request $request)
    {

        $newLeaveType = 'Annual Leave';
        $leaveId = $request->LeaveId;

        $leave = DB::table('leaves')
                ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
                ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
                ->select('leaves.Id as LeaveId','leavestatuses.Id as StatusId')
                ->where('leaves.Id', $leaveId)
                ->first();

        DB::table('leaves')
            ->where('Id','=',$leave->LeaveId)
            ->update([
                'Leave_Type' => $newLeaveType
            ]);

        DB::table('leavestatuses')
            ->where('Id', '=', $leave->StatusId)
            ->update([
                'Comment' => DB::raw("CONCAT('[EL to AL] ', Comment)")
            ]);

        return 1;
    }

    public function checkAnnualLeave($leaveId)
    {
        $leaveType = 'Annual Leave';
        $leave = DB::table('leaves')->where('Id','=',$leaveId)->first();
        $thisyear = date('Y');

        // get the first result with already summed adjusted
        $balance = $this->checkLeaveBalance($leave->UserId, $leaveType)[0]->Total_Leave_Balance;

        return $balance;
    }

    /**
     * Determine if leave still have balance
     * @param  int $userId
     * @return boolean
     */
    public function isLeaveOutOfBalance($userId, $leaveType, $No_of_Days)
    {
        if ($leaveType == 'Emergency Leave' || $leaveType == 'Unpaid Leave' || $leaveType == '1 Hour Time Off' || $leaveType == '2 Hours Time Off') {
            return false;
        }

        // any timeoff exceeding balance will be counted as unpaid
        // if ($leaveType == '1 Hour Time Off' || $leaveType == '2 Hours Time Off') {
        //     $leaveType = 'Annual Leave';
        // }
        $thisyear = date('Y');

        if ($leaveType == 'Replacement Leave') {

            $leaveType = 'Annual Leave';
            $bal = $this->checkLeaveBalance($userId, $leaveType);

            if (! empty($bal)) {
                $balance = $bal[0];

                // return true if it is 0 or less
                return ($balance->Replacement_Balance <= 0 || $balance->Replacement_Balance < $No_of_Days);
            } else {
                // leave out of balance
                return true;
            }
        }

        // $adjustedLeave = $this->adjustedLeaveValue($userId, $leaveType, $thisyear);
        $bal = $this->checkLeaveBalance($userId, $leaveType);

        if ($leaveType == 'Annual Leave') {
            // get the first result
            if (! empty($bal)) {
                $balance = $bal[0];
                // return true if it is 0 or less
                return ($balance->Total_Leave_Balance <= 0 || $balance->Total_Leave_Balance < $No_of_Days);
            } else {
                // leave out of balance
                return true;
            }
        } else {
            // get the first result
            if (! empty($bal)) {
                $balance = $bal[0];
                // return true if it is 0 or less
                return ($balance->Total_Leave_Balance <= 0 || $balance->Total_Leave_Balance < $No_of_Days);
            } else {
                // leave out of balance
                return true;
            }
        }


    }

    /**
     * To check if dates overlapped
     * @param  Request  $request
     * @return boolean
     */
    public function isLeaveDatesOverlapped(Request $request, $me, $leaveType = null)
    {
        $start_date = $request->Start_Date;
        $end_date = $request->End_Date;
        $userId = $me->UserId;

        $datesOverlapped = DB::select(
            'SELECT leaves.Id, leaves.Start_Date, leaves.End_Date, CASE WHEN leaves.Leave_Term LIKE "%Halfday%" THEN 0.5 WHEN leaves.Leave_Term = "Full Day" THEN 1 ELSE 0 END as Term_Value FROM leaves
                LEFT JOIN (
                    SELECT LeaveId, MAX(Id) as maxid FROM leavestatuses
                        GROUP BY LeaveId
                ) AS status ON status.LeaveId = leaves.Id
                LEFT JOIN leavestatuses ON status.maxid = leavestatuses.Id
                WHERE leaves.UserId = :User_Id
                AND (GREATEST(STR_TO_DATE(:Start_Date, "%d-%b-%Y"), STR_TO_DATE(leaves.Start_Date, "%d-%b-%Y")) <= LEAST(STR_TO_DATE(:End_Date, "%d-%b-%Y"), STR_TO_DATE(leaves.End_Date, "%d-%b-%Y")))
                AND leavestatuses.Leave_Status <> "Cancelled" AND leavestatuses.Leave_Status NOT LIKE "%Rejected%"
                GROUP BY leaves.Id
                -- LIMIT 1
                ',
            [
                'Start_Date' => $start_date,
                'End_Date'   => $end_date,
                'User_Id'    => $userId
            ]
        );

        $ids = collect($datesOverlapped)->pluck('Id')->all();

        $terms = DB::table('leave_terms')
        ->select('leave_terms.Leave_Date', DB::raw("
            SUM(CASE WHEN leave_terms.Leave_Period = 'AM' OR leave_terms.Leave_Period = 'PM' THEN 0.5
            WHEN leave_terms.Leave_Period = 'Full' THEN 1
            WHEN leave_terms.Leave_Period = '1 Hour' THEN 0.125
            WHEN leave_terms.Leave_Period = '2 Hours' THEN 0.25
            ELSE 0 END) as No_Of_Days")
        )
        ->whereIn('Leave_Id', $ids)
        ->groupBy('Leave_Date')
        ->get();

        $calculatedLeaves = $this->getLeaveDaysWithPeriod($request, $me);

        foreach($terms as $term) {

            $nonPeriodLeaveTermValue = collect($datesOverlapped)->filter(function ($item) use ($term) {
                if (strtotime($item->Start_Date) >= strtotime($term->Leave_Date) && strtotime($item->End_Date) <= strtotime($term->Leave_Date)) {
                    return true;
                }

                return false;
            })->sum('Term_Value');

            if (isset($calculatedLeaves[$term->Leave_Date])) {
                $total = $term->No_Of_Days + $calculatedLeaves[$term->Leave_Date]['No_Of_Days'] + $nonPeriodLeaveTermValue;
            } else {
                $total = $term->No_Of_Days + $nonPeriodLeaveTermValue;
            }

            if ($total > 1) {
                return true;
            }
        }


        return false;
        // return count($datesOverlapped) > 0;
    }

    /**
     * Function to be called by ajax
     * @param  int  $leaveId
     * @return \Illuminate\Http\Response
     */
    public function fetchLeaveTerms($leaveId)
    {
        $leave = DB::table('leaves')->select('Leave_Type')->where('Id',$leaveId)->first();
        $leaveTerms = DB::table('leave_terms')
                        ->join('leaves', 'leaves.Id', '=', 'leave_terms.Leave_Id')
                        ->select("leave_terms.*", "leaves.Leave_Type")
                        ->where('leave_terms.Leave_Id', $leaveId)
                        ->get();
        return response()->json(['Leave_Type' => $leave->Leave_Type, 'Leave_Terms' => $leaveTerms]);
    }

    /**
     * Calculate Leave Day with Period
     * @param  \Illuminate\Http\Request $request
     * @param  int $workingDays
     * @return int
     */
    public function calculateLeaveDaysWithPeriod(Request $request, $workingDays = 5, $holidayTerritoryId)
    {
        // the selected start date
        $date1 = new DateTime($request->Start_Date);
        // the selected end date
        $date2 = new DateTime($request->End_Date);
        // the selected period
        $periods = $request->Leave_Period;

        // the selected start date
        $startdate = $request->Start_Date;

        // calculate the days difference between dates and add one day
        $datediff = $date2->diff($date1)->format("%a") + 1;

        if($request->Leave_Type=="Maternity Leave" || $request->Leave_Type=="Hospitalization Leave")
        {
            return $datediff;
        }

        // start date
        $start = strtotime($request->Start_Date);
        // end date
        $end = strtotime($request->End_Date);

        $counter = 0;

        // loop until the end date
        while ($start <= $end) {

            // get start date
            $current = date("d-M-Y", $start);

            // $holiday = DB::table('holidays')
                //     ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //     ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //     ->get();

            $holiday = $this->getCurrentDateHoliday($current, $holidayTerritoryId);

            // if there is holiday for $current minus 1 day from datediff
            // if (count($holiday) > 0 && $request->Leave_Type!="Medical Leave") {
            if (count($holiday) > 0) {
                $datediff -= 1;
            } else {
                // get Numeric representation of the day of
                // the week,  0 (for Sunday), 6(for Saturday)
                $day_type = date("w", $start);

                // minus 1 day if its Sunday or Saturday
                if ($day_type == 0 || $day_type == 6) {
                    // if working days is 5.5 or 6
                    if ($day_type == 6 && $workingDays > 5) {
                        if ($request->Leave_Type == '1 Hour Time Off') {
                            // minus 7 hours
                            $datediff -= 0.875;
                        } else if ($request->Leave_Type == '2 Hours Time Off') {
                            // minus 6 hours
                            $datediff -= 0.75;
                        } else {
                            // wd 6 may apply for fullday or halfday on saturday
                            if ($workingDays >= 6) {
                                // minus halfday for halfday leave
                                if ($periods[$counter] == 'AM' || $periods[$counter] == 'PM') {
                                    $datediff -= 0.5;
                                }
                            } else {
                                $datediff -= 0.5;
                            }
                        }
                    } else {
                        // minus 1 day for holiday weekends
                        // if ($request->Leave_Type!="Medical Leave") {
                            $datediff -= 1;
                        // }
                    }
                } else {
                    if ($request->Leave_Type == '1 Hour Time Off') {
                        // minus 7 hours
                        $datediff -= 0.875;
                    } else if ($request->Leave_Type == '2 Hours Time Off') {
                        // minus 6 hours
                        $datediff -= 0.75;
                    } else {
                        // minus halfday for halfday leave
                        if ($periods[$counter] == 'AM' || $periods[$counter] == 'PM') {
                            $datediff -= 0.5;
                        }
                    }
                }
            }

            // move to the next day / +1 day
            $start = strtotime("+1 day", $start);
            $counter = $counter + 1;
        }

        // return json string
        return $datediff;
    }

    /**
     * Save leave days on leave_terms table
     * @param  \Illuminate\Http\Request $request
     */
    public function saveLeaveDaysWithPeriod(Request $request, $leaveId, $me, $originalLeaveType = null)
    {
        $workingDays = $me->Working_Days;
        $holidayTerritoryId = $me->HolidayTerritoryId;

        // the selected period
        $periods = $request->Leave_Period;

        // start date
        $start = strtotime($request->Start_Date);
        // end date
        $end = strtotime($request->End_Date);

        // init array for the leave dates
        $leaveList = array();
        $counter = 0;

        // loop until the end date
        while ($start <= $end) {
            // get start date
            $current = date("d-M-Y", $start);

            if ($request->Leave_Type == 'Maternity Leave' || $request->Leave_Type == 'Hospitalization Leave') {
                $period = 'Full';
            } else {

                // $holiday = DB::table('holidays')
                //     ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //     ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //     ->get();

                $holiday = $this->getCurrentDateHoliday($current, $holidayTerritoryId);

                // if there is holiday for $current minus 1 day from datediff
                // if (count($holiday) > 0 && $request->Leave_Type!="Medical Leave") {
                if (count($holiday) > 0) {
                    $period = 'Holiday: ' . $holiday[0]->Holiday;
                } else {
                    // get Numeric representation of the day of
                    // the week,  0 (for Sunday), 6(for Saturday)
                    $day_type = date("w", $start);

                    // minus 1 day if its Sunday or Saturday
                    if ($day_type == 0 || $day_type == 6) {
                        // if working days is 5.5 or 6
                        if ($day_type == 6 && $workingDays > 5) {
                            if ($request->Leave_Type == '1 Hour Time Off' || $originalLeaveType == '1 Hour Time Off') {
                                $period = '1 Hour';
                            } else if ($request->Leave_Type == '2 Hours Time Off' || $originalLeaveType == '2 Hours Time Off') {
                                $period = '2 Hours';
                            } else {
                                if ($workingDays >= 6) {
                                    $period = $periods[$counter];
                                } else {
                                    $period = 'AM';
                                }
                            }
                        } else {
                            // if ($request->Leave_Type!="Medical Leave") {
                                $period = 'Non-working Day';
                            // } else {
                                // $period = $periods[$counter];
                            // }
                        }
                    } else {
                        if ($request->Leave_Type == '1 Hour Time Off' || $originalLeaveType == '1 Hour Time Off') {
                            $period = '1 Hour';
                        } else if ($request->Leave_Type == '2 Hours Time Off' || $originalLeaveType == '2 Hours Time Off') {
                            $period = '2 Hours';
                        } else {
                            $period = $periods[$counter];
                        }
                    }
                }
            }

            array_push($leaveList, [
                'Leave_Date' => $current,
                'Leave_Period' => $period,
                'Leave_Id' => $leaveId
            ]);

            // move to the next day / +1 day
            $start = strtotime("+1 day", $start);
            $counter = $counter + 1;
        }

        DB::table('leave_terms')->insert($leaveList);

    }

    /**
     * Get leave no of days for each date
     * @param  \Illuminate\Http\Request $request
     */
    public function getLeaveDaysWithPeriod(Request $request, $me)
    {
        $workingDays = $me->Working_Days;
        $holidayTerritoryId = $me->HolidayTerritoryId;

        // the selected period
        $periods = $request->Leave_Period;

        // start date
        $start = strtotime($request->Start_Date);
        // end date
        $end = strtotime($request->End_Date);

        // init array for the leave dates
        $leaveList = array();
        $counter = 0;
        $No_Of_Days = 0;

        // loop until the end date
        while ($start <= $end) {
            // get start date
            $current = date("d-M-Y", $start);

            if ($request->Leave_Type == 'Maternity Leave' || $request->Leave_Type == 'Hospitalization Leave') {
                $period = 'Full';
            } else {

                // $holiday = DB::table('holidays')
                //     ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //     ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //     ->get();

                $holiday = $this->getCurrentDateHoliday($current, $holidayTerritoryId);

                // if there is holiday for $current minus 1 day from datediff
                // if (count($holiday) > 0 && $request->Leave_Type!="Medical Leave") {
                if (count($holiday) > 0) {
                    $period = 'Holiday: ' . $holiday[0]->Holiday;
                } else {
                    // get Numeric representation of the day of
                    // the week,  0 (for Sunday), 6(for Saturday)
                    $day_type = date("w", $start);

                    // minus 1 day if its Sunday or Saturday
                    if ($day_type == 0 || $day_type == 6) {
                        // if working days is 5.5 or 6
                        if ($day_type == 6 && $workingDays > 5) {
                            if ($request->Leave_Type == '1 Hour Time Off') {
                                $period = '1 Hour';
                            } else if ($request->Leave_Type == '2 Hours Time Off') {
                                $period = '2 Hours';
                            } else {
                                if ($workingDays >= 6) {
                                    $period = $periods[$counter];
                                } else {
                                    // $period = 'AM';
                                    $period = 'Full';
                                }
                            }
                        } else {
                            // if ($request->Leave_Type!="Medical Leave") {
                                $period = 'Non-working Day';
                            // } else {
                                // $period = $periods[$counter];
                            // }
                        }
                    } else {
                        if ($request->Leave_Type == '1 Hour Time Off') {
                            $period = '1 Hour';
                        } else if ($request->Leave_Type == '2 Hours Time Off') {
                            $period = '2 Hours';
                        } else {
                            $period = $periods[$counter];
                        }
                    }
                }
            }

            if ($period == '1 Hour') {
                $No_Of_Days = 0.125;
            } elseif ($period == '2 Hours') {
                $No_Of_Days = 0.25;
            } elseif ($period == 'AM' || $period == 'PM') {
                $No_Of_Days = 0.5;
            } elseif ($period == 'Full') {
                $No_Of_Days = 1;
            } else {
                $No_Of_Days = 0;
            }

            $leaveList[$current] = [
                'No_Of_Days' => $No_Of_Days
            ];

            // move to the next day / +1 day
            $start = strtotime("+1 day", $start);
            $counter = $counter + 1;
        }

        return $leaveList;

    }

    public function calculateprocessdays($start_date, $me)
    {
        $holidayTerritoryId = $me->HolidayTerritoryId;
        $date1 = new DateTime(date("d-M-Y"));
        $date2 = new DateTime($start_date);

        $startdate=$start_date;

        $datediff = $date2->diff($date1)->format("%a")+1;

        $start=strtotime(date("d-M-Y"));
        $end=strtotime($start_date);

        if($start>$end)
        {
            return -1;
        }

        while ($start<=$end)
        {
            $current=date("d-M-Y",$start);

            // $holiday = DB::table('holidays')
                //     ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //     ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //     ->get();

            $holiday = $this->getCurrentDateHoliday($current, $holidayTerritoryId);

             if (count($holiday)>0)
             {
                 $datediff-=1;
             }
             else {

                 $day_type=date( "w", $start);
                 if ($day_type=="0" || $day_type=="6")
                 {
                     $datediff-=1;
                 }
             }

             $start=strtotime("+1 day", $start);

        }

        return $datediff;

    }

    /**
     * Show Batch Leave Page
     */
    public function leavebatch()
    {
        $me = (new CommonController)->get_current_user();

        $approver = DB::table('approvalsettings')
        ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
        ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
        ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
        ->where('approvalsettings.Type', '=', 'Leave')
        ->orderBy('approvalsettings.Country','asc')
        ->orderBy('projects.Project_Name','asc')
        ->orderBy('approvalsettings.Level','asc')
        ->groupBy('approvalsettings.Country','projects.Project_Name','users.Id')
        ->get();

        $entitlementLeaveTypes = DB::table('leaveentitlements')->select('Leave_Type')->where('Leave_Type','<>','')->groupBy('Leave_Type')->lists('Leave_Type');
        array_push($entitlementLeaveTypes, 'Replacement Leave');

        $projects = DB::table('projects')
        ->get();

        $options = DB::select("SELECT `Option` FROM  `options` WHERE  `Field` LIKE  'Leave_Type'");




        $thisyear = date('Y');
        $stafflist = DB::table('users')
        ->select('users.Id','users.StaffId','users.Name','users.Grade','users.Position','users.Department')
        ->where('users.active',1)
        ->get();


        $stafflist = $this->calculateAdjustedLeave($entitlementLeaveTypes);
        return view('leavebatch', [
            'me' => $me,
            'approver' => $approver,
            'options' =>$options,
            'projects' =>$projects,
            'stafflist' => $stafflist,
            'entitlementLeaveTypes' => $entitlementLeaveTypes
        ]);

    }

    /**
     * Show Batch Leave Page
     */
    public function leaveadjustmentshistory($userId, $leaveType = null)
    {
        $me = (new CommonController)->get_current_user();

        $userDetail = DB::table('users')->select('users.StaffId','users.Name','users.Grade','users.Position','users.Department')->where('Id', $userId)->first();

        if ($leaveType) {
            $leaveadjustmentshistory = DB::table('leaveadjustmentshistory')
                                            ->select('leaveadjustmentshistory.Id', 'leaveadjustmentshistory.Adjustment_Leave_Type', 'leaveadjustmentshistory.Adjustment_Value', 'leaveadjustmentshistory.Adjustment_Year', 'leaveadjustmentshistory.Remarks', 'leaveadjustmentshistory.UserId', 'users.Name as ApproverName', 'leaveadjustmentshistory.created_at', 'leaveadjustmentshistory.updated_at')
                                            ->leftJoin('users','users.Id','=','leaveadjustmentshistory.ApproverId')
                                            ->where('UserId', $userId)
                                            ->where('Adjustment_Leave_Type', $leaveType)
                                            ->orderBy('leaveadjustmentshistory.created_at', 'desc')
                                            ->get();

        } else {
            $leaveadjustmentshistory = DB::table('leaveadjustmentshistory')
                                            ->select('leaveadjustmentshistory.Id', 'leaveadjustmentshistory.Adjustment_Leave_Type', 'leaveadjustmentshistory.Adjustment_Value', 'leaveadjustmentshistory.Adjustment_Year', 'leaveadjustmentshistory.Remarks', 'leaveadjustmentshistory.UserId', 'users.Name as ApproverName', 'leaveadjustmentshistory.created_at', 'leaveadjustmentshistory.updated_at')
                                            ->leftJoin('users','users.Id','=','leaveadjustmentshistory.ApproverId')
                                            ->where('UserId', $userId)
                                            ->orderBy('leaveadjustmentshistory.created_at','desc')
                                            ->get();
        }

        $leaveTypes = DB::table('leaveentitlements')
                                    ->select('Leave_Type')
                                    ->where('Leave_Type','<>','')
                                    ->groupBy('Leave_Type')
                                    ->lists('Leave_Type');

        array_push($leaveTypes, 'Replacement Leave');

        return view('leaveadjustmentshistory', [
            'me' => $me,
            'userDetail' => $userDetail,
            'leaveadjustmentshistory' => $leaveadjustmentshistory,
            'leaveType' => $leaveType,
            'leaveTypes' => $leaveTypes,
            'userId' => $userId
        ]);

    }

    /**
     * Get adjusted leave
     */
    public function getAdjustedLeave()
    {
        $data = $this->calculateAdjustedLeave();

        return response()->json(['adjustedleave' => $data], 200);
    }

    /**
     * Calculate the adjusted leave for leave type
     * @param  array  $leaveTypes
     * @return array
     */
    public function calculateAdjustedLeave($leaveTypes = [])
    {
        $queries = [];

        if (! $leaveTypes) {
            $leaveTypes = DB::table('leaveentitlements')->select('Leave_Type')->where('Leave_Type','<>','')->groupBy('Leave_Type')->lists('Leave_Type');
            array_push($leaveTypes, 'Replacement Leave');
        }

        foreach($leaveTypes as $leaveType) {
            if ($leaveType == 'Annual Leave') {

                //confirmation_comment
                // array_push($queries, "SUM(case when leaveentitlements.Leave_Type = '" . $leaveType . "' AND confirmed then 5 * ROUND(leaveentitlements.Days/365 * service.Days_of_Service_Current_Year / 5,1) else 0 end) as '" . str_replace(' ', '_', $leaveType) . "'");
                // array_push($queries, "SUM(case WHEN service.Days_of_Service <= 90 THEN 0 WHEN leaveentitlements.Leave_Type = '" . $leaveType . "' AND service.confirmed AND service.Days_of_Service > 365 THEN leaveentitlements.Days when leaveentitlements.Leave_Type = '" . $leaveType . "' AND service.Days_of_Service > 90 then 5 * ROUND(leaveentitlements.Days/12 * service.Months_of_Service / 5,1) else 0 end) as '" . str_replace(' ', '_', $leaveType) . "'");
                array_push($queries, "SUM(case WHEN service.Days_of_Service <= 90 THEN 0 when leaveentitlements.Leave_Type = '" . $leaveType . "' AND service.Days_of_Service > 90 then 5 * ROUND(leaveentitlements.Days/12 * service.Months_of_Service / 5,1) else 0 end) as '" . str_replace(' ', '_', $leaveType) . "'");


            }
            else  {

                //confirmation_comment
                // array_push($queries, "SUM(case when leaveentitlements.Leave_Type = '" . $leaveType . "' AND confirmed then leaveentitlements.Days else 0 end) as '" . str_replace(' ', '_', $leaveType) . "'");
                array_push($queries, "SUM(case when Days_of_Service > 90 AND leaveentitlements.Leave_Type = '" . $leaveType . "' then leaveentitlements.Days else 0 end) as '" . str_replace(' ', '_', $leaveType) . "'");
                //

            }


            array_push($queries, "SUM(case when leaveadjustments.Adjustment_Leave_Type = '" . $leaveType . "' then leaveadjustments.Adjustment_Value else 0 end) as '" . str_replace(' ', '_', $leaveType) . "_Adjusted'");
        }
        $year = date('Y');
        $query = implode(",",$queries);
        $adjustedLeave = DB::table('users')
        ->select(
            'users.Id','users.StaffId','users.Name','users.Grade','users.Position','users.Department',
            DB::raw($query)
        )
        ->leftJoin(DB::raw("(SELECT users.Id as UserId,
            CASE WHEN DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),str_to_date(users.Confirmation_Date,'%d-%M-%Y')) >= 0 THEN 1 ELSE 0 END AS confirmed,
            DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),str_to_date(users.Joining_Date,'%d-%M-%Y')) as Days_of_Service,
            CEILING((SELECT Days_of_Service) / 365 ) as Years_of_Service,
            CASE WHEN YEAR(str_to_date(users.Joining_date,'%d-%M-%Y')) = $year THEN 1 ELSE 0 END as Joined_This_Year,
            Joining_date,
            CASE WHEN (SELECT Joined_This_Year) THEN (SELECT Days_of_Service) ELSE DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(NOW() ,'%Y-01-01')) END as Days_of_Service_Current_Year,
            (SELECT days_of_service) / 30 AS Current_Completed_Month,
            CASE WHEN (SELECT joined_this_year) THEN (SELECT current_completed_month) ELSE Month(Now()) end AS Months_of_Service
            FROM users) service
        "), 'service.UserId', '=', 'users.Id')
        ->leftJoin(DB::raw("(SELECT leaveentitlements.*,tblEnt.MaxYear FROM leaveentitlements LEFT JOIN
                            (SELECT leaveentitlements.Grade, Leave_Type, MAX(Year) as MaxYear
                                FROM leaveentitlements
                                GROUP BY Grade, Leave_Type ) as tblEnt
                            ON leaveentitlements.Grade = tblEnt.Grade and leaveentitlements.Leave_Type = tblEnt.Leave_Type UNION
                            SELECT 0 as Id, 'All' as Grade, 1 as Year, 0 as Days, 'Replacement Leave' as Leave_Type, 0 as created_at, 1 as MaxYear) as leaveentitlements"
                        ), function($join) {
            $join->on(function ($j) {
                $j->on('leaveentitlements.Grade','=',DB::raw('users.Grade OR leaveentitlements.Grade = "ALL"'));
            });
            $join->on(function($j) {
                $j->on('leaveentitlements.Year','=',DB::raw('LEAST(MaxYear,service.Years_of_Service)'));
                $j->orOn('leaveentitlements.Year','=',DB::raw('""'));
            });
        })
        ->leftJoin('leaveadjustments', function($join) {
            $join->on('users.Id','=','leaveadjustments.UserId');
            $join->on('leaveentitlements.Leave_Type','=','leaveadjustments.Adjustment_Leave_Type');
        })
        ->where('users.active',1)
        ->groupBy('users.Id')
        ->get();

        return $adjustedLeave;
    }

    public function leavebatchadjustment(Request $request)
    {
        $me = (new CommonController)->get_current_user();
        $input = $request->all();

        $rules = array(
            'Leave_Type' => 'Required',
            'Adjustment_No_Of_Days' => 'Required',
            'Year' => 'Required',
            'Remarks' => 'Required',
            'Ids'  =>'Required',
        );

        $messages = array(
            'Leave_Type.required' => 'The Leave Type field is required',
            'Adjustment_No_Of_Days.required' => 'The No Of Days field is required',
            'Year.required' => 'The Year field is required',
            'Remarks.required' => 'The Remarks field is required',
            'Ids.required'  =>'Need to select some user first'
        );

        $validator = Validator::make($input, $rules,$messages);

        if ($validator->passes()) {
            $ids = $input['Ids'];
            $userIds = explode(",", $ids);

            // Loop and apply leave to each id
            foreach($userIds as $userId) {

                if ($input["Adjustment_No_Of_Days"] != 0) {
                    $updated = DB::table('leaveadjustments')
                                ->where('UserId', $userId)
                                ->where('Adjustment_Year', $input["Year"])
                                ->where('Adjustment_Leave_Type', $input["Leave_Type"])
                                ->update(['Adjustment_Value' => DB::raw("Adjustment_Value + " . $input["Adjustment_No_Of_Days"])]);
                    if (! $updated) {
                        DB::table('leaveadjustments')->insert([
                            'UserId' => $userId,
                            'Adjustment_Value' => $input["Adjustment_No_Of_Days"],
                            'Adjustment_Leave_Type' => $input["Leave_Type"],
                            'Adjustment_Year' => $input["Year"]
                        ]);

                    }
                }

                DB::table('leaveadjustmentshistory')->insert([
                    'UserId' => $userId,
                    'ApproverId' => $me->UserId,
                    'Adjustment_Value' => $input["Adjustment_No_Of_Days"],
                    'Adjustment_Leave_Type' => $input["Leave_Type"],
                    'Adjustment_Year' => $input["Year"],
                    'Remarks' => $input['Remarks'],
                ]);
            }

            return 1;
        } else {
            return json_encode($validator->errors()->toArray());
        }
    }

    public function calculateleavedays(array $input)
    {

        $me=(new CommonController)->get_current_user();

        $date1 = new DateTime($input["Start_Date"]);
    $date2 = new DateTime($input["End_Date"]);

        $startdate=$input["Start_Date"];

    $datediff = $date2->diff($date1)->format("%a")+1;

        $start=strtotime($input["Start_Date"]);
        $end=strtotime($input["End_Date"]);

    while ($start<=$end)
    {
            $current=date("d-M-Y",$start);

             $holiday=DB::table('holidays')
             ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$current.'","%d-%M-%Y")'))
             ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$current.'","%d-%M-%Y")'))
             ->get();

             if (count($holiday)>0)
             {
                 $datediff-=1;
             }
             else {

                 $day_type=date( "w", $start);
                 if ($day_type=="6")
                 {

                     if($me->Working_Days==5.0)
                     {
                         $datediff-=1;
                     }
                     elseif($me->Working_Days==5.5)
                     {
                         $datediff-=0.5;
                     }
                     elseif($me->Working_Days==6.0)
                     {

                     }

                 }
                 elseif ($day_type=="0")
                 {

                         $datediff-=1;

                 }
             }

             $start=strtotime("+1 day", $start);

    }

        if ($input["Leave_Term"]=="")
        {

        }
        elseif ($input["Leave_Term"]!="Full Day")
        {
            $datediff=$datediff*0.5;
        }

        if($input["Leave_Type"]=="1 Hour Time Off")
        {
            $datediff=0.125;
        }
        elseif($input["Leave_Type"]=="2 Hours Time Off")
        {
            $datediff=0.25;

        }

        return $datediff;

    }

    public function checkdepartmentworkingdays()
    {

        $me=(new CommonController)->get_current_user();

        // MY_Department_ACCT   5
        // MY_Department_CME    6
        // MY_Department_CMEOSU 5.5
        // MY_Department_CMEPMO 5.5
        // MY_Department_CMEPRO 5.5
        // MY_Department_CMETSS 5.5
        // MY_Department_FAB    6
        // MY_Department_GST    6
        // MY_Department_HOD    5
        // MY_Department_HRA    5
        // MY_Department_LOG    6
        // MY_Department_MDO    6
        // MY_Department_TI 6

        if($me->Department=="MY_Department_ACCT")
        {
            return 5;
        }
        elseif ($me->Department=="MY_Department_CME")
        {
            return 6;
        }
        elseif ($me->Department=="MY_Department_CMEOSU")
        {
            return 5.5;
        }
        elseif ($me->Department=="MY_Department_CMEPMO")
        {
            return 5.5;
        }
        elseif ($me->Department=="MY_Department_CMEPRO")
        {
            return 5.5;
        }
        elseif ($me->Department=="MY_Department_CMETSS")
        {
            return 5.5;
        }
        elseif ($me->Department=="MY_Department_FAB")
        {
            return 6;
        }
        elseif ($me->Department=="MY_Department_GST")
        {
            return 6;
        }
        elseif ($me->Department=="MY_Department_HOD")
        {
            return 5;
        }
        elseif ($me->Department=="MY_Department_HRA")
        {
            return 5;
        }
        elseif ($me->Department=="MY_Department_LOG")
        {
            return 6;
        }
        elseif ($me->Department=="MY_Department_MDO")
        {
            return 6;
        }
        elseif ($me->Department=="MY_Department_TI")
        {
            return 6;
        }

    }

    public function onleavetoday()
    {

        $Today=date("d-M-Y");

        $leave = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin('users', 'users.Id', '=', 'leaves.UserId')
        ->select('users.Name','leaves.Start_Date','leaves.End_Date')
        ->where(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$Today.'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(leaves.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$Today.'","%d-%M-%Y")'))
        ->where('leavestatuses.Leave_Status','like','%Approved%')
        ->get();

        return json_encode($leave);
    }

    public function approve(Request $request)
    {

        $me = (new CommonController)->get_current_user();

        $input = $request->all();

        $Ids = explode(",", $input["Ids"]);

        $leaves = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        // ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        // ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leaves.Id as LeaveId','leaves.UserId','applicant.Name','leavestatuses.Id as StatusId','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Id as ApproverId','approver.Name as Approver')
        ->whereIn('leaves.Id', $Ids)
        ->orderBy('leavestatuses.Id','desc')
        ->get();

        foreach ($leaves as $leave) {
            # code...
            if ($leave->ApproverId!=$me->UserId)
            {
                $id=DB::table('leavestatuses')->insertGetId(
                    ['LeaveId' => $leave->LeaveId,
                     'UserId' => $me->UserId,
                     'Leave_Status' => $input["Status"],
                     'updated_at' => DB::raw('now()')
                    ]
                );


            }
            else {

                $result= DB::table('leavestatuses')
                            ->where('Id', '=',$leave->StatusId)
                            ->update(array(
                            'Leave_Status' =>  $input["Status"],
                        ));

            }
        }

        $leaves = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leavestatuses.Id','leaves.Id as LeaveId','leaves.UserId as ApplicantId','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Id as ApproverId','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date','files.Id as FileId','files.Web_Path as FileUrl')
        ->orderBy('leaves.Id','desc')
        ->whereIn('leaves.Id', $Ids)
        ->get();

        $approver = DB::table('approvalsettings')
        ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
        ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
        ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
        ->where('approvalsettings.Type', '=', 'Leave')
        ->where('approvalsettings.ProjectId', '<>', '0')
        ->orderBy('approvalsettings.Country','asc')
        ->orderBy('projects.Project_Name','asc')
        ->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
        ->get();

        $final=false;

        foreach ($leaves as $leave) {

            //update notification to set as 0
            DB::table('notificationstatus')
            ->where('UserId', $me->UserId)
            ->where('TargetId', $leave->LeaveId)
            ->where(function ($q) {
                $q->where('Type', 'Pending Leave');
                $q->orWhere('Type', 'Leave Approved');
                $q->orWhere('Type', 'Leave Rejected');
                $q->orWhere('Type', 'Leave Cancelled');
            })
            ->update([
              'Seen' => 1
            ]);
            //

            $emaillist=array();
            array_push($emaillist,$me->UserId);

            # code...
            $submitted=false;
            $currentstatus=$leave->Status;

            if ($leave->Status=="Final Approved")
            {
                array_push($emaillist,$leave->ApplicantId);
                array_push($emaillist,$leave->ApproverId);
            }

            if ((strpos($leave->Status, 'Rejected') === false) && $leave->Status!="Final Approved")
            {

                foreach ($approver as $user) {

                        if (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId != $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($leave->Status, FILTER_SANITIZE_NUMBER_INT))
                        {

                            DB::table('leavestatuses')->insert(
                                ['LeaveId' => $leave->LeaveId,
                                 'UserId' => $user->Id,
                                 'Leave_Status' => "Pending Approval"
                                ]
                            );
                            $submitted=true;
                            array_push($emaillist,$user->Id);
                            array_push($emaillist,$leave->ApplicantId);

                            break;
                        }
                        elseif (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId == $user->Id  && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($leave->Status, FILTER_SANITIZE_NUMBER_INT))
                        {
                            # code...
                                $submitted=true;
                                array_push($emaillist,$user->Id);
                                array_push($emaillist,$leave->ApplicantId);
                        }
                        elseif (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId == $user->Id && $user->Level=="Final Approval")
                        {

                            DB::table('leavestatuses')->insert(
                                ['LeaveId' => $leave->LeaveId,
                                 'UserId' => $user->Id,
                                 'Leave_Status' => "Pending Approval"
                                ]
                            );
                            $submitted=true;
                            array_push($emaillist,$user->Id);
                            array_push($emaillist,$leave->ApplicantId);

                        }
                        elseif (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId != $user->Id && $user->Level=="Final Approval")
                        {

                            DB::table('leavestatuses')->insert(
                                ['LeaveId' => $leave->LeaveId,
                                 'UserId' => $user->Id,
                                 'Leave_Status' => "Pending Approval"
                                ]
                            );
                            $submitted=true;
                            array_push($emaillist,$user->Id);
                            array_push($emaillist,$leave->ApplicantId);

                            break;
                        }
                    }

            }
            elseif ((strpos($leave->Status, 'Rejected') !== false))
            {

                array_push($emaillist,$leave->ApplicantId);
            }
            elseif ($leave->Status=="Final Approved" || $leave->Leave_Status=="Final Rejected")
            {
                $final=true;
                array_push($emaillist,$leave->ApplicantId);
                // if ($leave->Leave_Type='Replacement Leave' && $leave->Status=="Final Approved") {
                //     $this->adjustReplacementLeave($leave, $me->UserId);
                // }
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
                    ->where('notificationtype.Id','=',39)
                    ->get();
                }
                else {
                    $subscribers = DB::table('notificationtype')
                    ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
                    ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                    ->where('notificationtype.Id','=',28)
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

                // array_push($emails,"latifah@pronetwork.com.my");

                $leavedetail = DB::table('leaves')
                ->leftJoin('leavestatuses', 'leavestatuses.LeaveId', '=', 'leaves.Id')
                ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
                ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
                ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
                ->select('applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date')
                ->orderBy('leavestatuses.Id','desc')
                ->where('leaves.Id', '=',$leave->LeaveId)
                ->get();

                $attachmentUrl = null;
                if ($leave->FileUrl && $leave->FileUrl != '') {
                    $attachmentUrl = url($leave->FileUrl);
                }

                $periods = DB::table('leave_terms')->where('leave_terms.Leave_Id', $leave->LeaveId)->get();

                $emails = array_filter($emails);

                Mail::send('emails.leavestatuswithperiod', ['me' => $me,'leavedetail' => $leavedetail, 'periods' => $periods, 'attachmentUrl' => $attachmentUrl], function($message) use ($emails,$leavedetail,$NotificationSubject)
                {
                    array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                    $emails = array_filter($emails);
                    $message->to($emails)->subject($NotificationSubject.' ['.$leavedetail[0]->Name.']');
                });
            }
            else {
                return 0;
            }

        }

        return 1;

    }

    public function sgsimport($month=null,$year=null,$company=null,$department=null, $includeResigned = 'true', $includeInactive = 'true'){

        $me=(new CommonController)->get_current_user();

        if ($month==null)
        {
            $month=date('F');
        }

        if ($year==null)
        {
            $year=date('Y');
        }

        $start = strtotime('01 '.$month.' '.$year);
        $start = date('d F Y', $start);
        $start = date('d F Y', strtotime('-1 month',strtotime($start)));
        $start = date('d-M-Y', strtotime($start . " +20 days"));

        $end = strtotime('01 '.$month.' '.$year);
        $end = date('d F Y', $end);
        $end = date('d-M-Y', strtotime($end . " +19 days"));

        $start2 = $start;
        $end2 = $end;

        $paidmonth=date('M Y', strtotime($month.' '.$year));

        $years= DB::select("
          SELECT Year(Now())-1 as yearname UNION ALL
          SELECT Year(Now())
          ");

        $months = array (1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
        $monthindex=array_search($month,$months);

      // DED001	PHONE
      // DED002	STAFF LOAN
      // DED003	PRE-SAVING SCHEME
      // DED004	STAFF WELFARE
      // DED005	SUMMONS
      // DED006	OTHERS - ACCIDENT
      // DED007	SHELL CARD
      // DED008	TOUCH N GO
      // DED009	SAFETY SHOE
      // DED010	PERSONAL LOAN
      // DED011	PETTY CASH CME
      // DED013	LATENESS
      // DED014	ADVANCE  (REPAYMENT)
      // DED017 CIDB
      // DED018	PETTY CASH HRA
      $cond="";

      if($company && $company!="false")
  		{
  			$cond.=" AND Company='".$company."'";
  		}

      if($department && $department!="false")
  		{
  			$cond.=" AND Department='".$department."'";
  		}

        if ($includeResigned == 'false') {

            $today = date('d-M-Y', strtotime('today'));
            $cond.=' AND (users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))';

        } else {

            $cond.=' AND (users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$start.'","%d-%M-%Y"))';
        }

        if ($includeInactive == 'false') {

            $cond.=' AND users.Active = 1';

        }

            $sgsimport = DB::select("
            SELECT

              StaffId as '1. Employee Number',
              Name as '2. Employee Name',
              (select sum(OT1) from timesheets where OT_Verified = 1 AND OT_HOD_Verified = 1 AND str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date('$start2','%d-%M-%Y')
              AND str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date('$end2','%d-%M-%Y') and timesheets.UserId=users.Id) as '3. Overtime Hours #1',
              (select sum(OT2) from timesheets where OT_Verified = 1 AND OT_HOD_Verified = 1 AND str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date('$start2','%d-%M-%Y')
              AND str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date('$end2','%d-%M-%Y') and timesheets.UserId=users.Id) as '4. Overtime Hours #2',
              (select sum(OT3) from timesheets where OT_Verified = 1 AND OT_HOD_Verified = 1 AND str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date('$start2','%d-%M-%Y')
              AND str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date('$end2','%d-%M-%Y') and timesheets.UserId=users.Id) as '5. Overtime Hours #3',
              '' as '6. Overtime Hours #4',
              '' as '7. Overtime Hours #5',
              '' as '8. Overtime Hours #6',
              '' as '9. Overtime Hours #7',
              '' as '10. Overtime Hours #8',
              '' as '11. Overtime Hours #9',
              '' as '12. Overtime Hours #10',
              '' as '13. Additional Pay Day #1',
              '' as '14. Additional Pay Day #2',
              '' as '15. Additional Pay Day #3',
              '' as '16. Additional Pay Day #4',
              '' as '17. Additional Pay Day #5',

              'STAFF LOAN' as '18. Allowance Code #1',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='STAFF LOAN') as '19. Allowance Amount #1',

              'PRE SV DED' as '20. Allowance Code #2',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='PRE-SAVING SCHEME') as '21. Allowance Amount #2',

              'SUMMONS' as '22. Allowance Code #3',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='SUMMONS') as '23. Allowance Amount #3',

              'ACCIDNT' as '24. Allowance Code #4',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='ACCIDENT') as '25. Allowance Amount #4',

              'SHELL' as '26. Allowance Code #5',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='SHELL CARD') as '27. Allowance Amount #5',

              'TNGO' as '28. Allowance Code #6',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='TOUCH N GO') as '29. Allowance Amount #6',

              'SAFETY' as '30. Allowance Code #7',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='SAFETY SHOES') as '31. Allowance Amount #7',

              'PC SABAH' as '32. Allowance Code #8',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='PETTY CASH SABAH (FKA PETTY CASH CME)') as '33. Allowance Amount #8',

              'LATE_NIR' as '34. Allowance Code #9',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and (staffdeductions.Type='Late' or staffdeductions.Type='Not In Radius')) as '35. Allowance Amount #9',

              'ADV SALRY' as '36. Allowance Code #10',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='ADVANCE SALARY DEDUCTION') as '37. Allowance Amount #10',

              'MAX CARD' as 'Allowance Code #11',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='MAX CARD') as 'Allowance Amount #11',

              'NIOSH' as 'Allowance Code #12',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='NIOSH') as 'Allowance Amount #12',

              'CIDB' as 'Allowance Code #13',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='CIDB CARD') as 'Allowance Amount #13',

              'LOSS OF EQ' as 'Allowance Code #14',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='LOSS OF EQUIPMENT') as 'Allowance Amount #14',

              'FION' as 'Allowance Code #15',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='PETTY CASH FION') as 'Allowance Amount #15',

              'ERIC' as 'Allowance Code #16',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='PAY BACK TO ERIC') as 'Allowance Amount #16',

              'LICENSE' as 'Allowance Code #17',
              -(select sum(FinalAmount) from staffdeductions where str_to_date(staffdeductions.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(staffdeductions.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') and staffdeductions.UserId=users.Id and staffdeductions.Type='DRIVING LICENSE DEDUCTION') as 'Allowance Amount #17',

              'MED CLAIM' as 'Allowance Code #18',
              (select sum(Medical_claim) from leaves where Medical_Paid_Month='$paidmonth' and leaves.UserId=users.Id and leaves.Leave_Type='Medical Leave') as 'Allowance Amount #18',

              SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND leaves.Leave_Type='Unpaid Leave' AND str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y'),No_of_Days,0)) as '38. NPL Days',

              '' as '39. NPL Hours',
              '' as '40. Work Days',
              '' as '41. Work Hours',
              SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND leaves.Leave_Type='Annual Leave' AND str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y'),No_of_Days,0)) as '42. Leave Day #1',

              SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND leaves.Leave_Type='Medical Leave' AND str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y'),No_of_Days,0)) as '43. Leave Day #2',

              SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND leaves.Leave_Type='Compassionate Leave' AND str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y'),No_of_Days,0)) as '44. Leave Day #3',

              SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND leaves.Leave_Type='Marriage Leave' AND str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y'),No_of_Days,0)) as '45. Leave Day #4',

              SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND leaves.Leave_Type='Paternity Leave' AND str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y'),No_of_Days,0)) as '46. Leave Day #5',

              SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND leaves.Leave_Type='Maternity Leave' AND str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y'),No_of_Days,0)) as '47. Leave Day #6',

              SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND leaves.Leave_Type='Replacement Leave' AND str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
              AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y'),No_of_Days,0)) as '48. Leave Day #7',

              '' as '49. Leave Day #8',
              '' as '50. Leave Day #9',
              '' as '51. Leave Day #10',
              '' as '52. Shift Hours #1',
              '' as '53. Shift Hours #2',
              '' as '54. Shift Hours #3',
              '' as '55. Shift Hours #4',
              '' as '56. Shift Hours #5',
              '' as '57. Shift Hours #6',
              '' as '58. Shift Hours #7',
              '' as '59. Shift Hours #8',
              '' as '60. Shift Hours #9',
              '' as '61. Shift Hours #10',
              '' as '62. Previous Overtime Hours #1',
              '' as '63. Previous Overtime Hours #2',
              '' as '64. Previous Overtime Hours #3',
              '' as '65. Previous Overtime Hours #4',
              '' as '66. Previous Overtime Hours #5',
              '' as '67. Previous Overtime Hours #6',
              '' as '68. Previous Overtime Hours #7',
              '' as '69. Previous Overtime Hours #8',
              '' as '70. Previous Overtime Hours #9',
              '' as '71. Previous Overtime Hours #10',
              '' as '72. Previous Additional Pay Day #1',
              '' as '73. Previous Additional Pay Day #2',
              '' as '74. Previous Additional Pay Day #3',
              '' as '75. Previous Additional Pay Day #4',
              '' as '76. Previous Additional Pay Day #5'

            from users
            LEFT JOIN leaves ON users.Id = leaves.UserId
            LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
            LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
            WHERE 1
            ".$cond."
            GROUP BY users.Id
            Order By users.StaffId
            ");

            $companies= DB::table('options')
        		->whereIn('Table', ["users"])
        		->where('Field','=','Company')
        		->orderBy('Table','asc')
        		->orderBy('Option','asc')
        		->get();

            $departments = DB::table('projects')
        		->where('projects.Project_Name','like','%department%')
        		->get();

        return view('sgsimport',['me'=>$me, 'month'=>$month, 'years'=>$years,'year'=>$year, 'sgsimport'=>$sgsimport,'companies'=>$companies,'departments'=>$departments,'company'=>$company,'department'=>$department, 'includeResigned' => $includeResigned, 'includeInactive' => $includeInactive]);

    }

    public function departmentleavesummary($month=null,$year=null,$company=null,$department=null, $includeResigned = 'true', $includeInactive = 'true'){
		$me=(new CommonController)->get_current_user();
    if ($month==null)
		{
			$month=date('F');
		}
		if ($year==null)
		{
			$year=date('Y');
		}
		$months = array (1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
		$monthindex=array_search($month,$months);
		// $start=date('d-M-Y', strtotime(date($year.'-'.$monthindex.'-01')));
		// $end=date('t-M-Y', strtotime(date($year.'-'.$monthindex.'-01')));

		if ($month==null)
		{

			$month=date('F');

		}

			$start = strtotime('01 '.$month.' '.$year);
			$start = date('d F Y', $start);
			$start = date('d F Y', strtotime('-1 month',strtotime($start)));
			$start = date('d-M-Y', strtotime($start . " +20 days"));

			$end = strtotime('01 '.$month.' '.$year);
			$end = date('d F Y', $end);
            $end = date('d-M-Y', strtotime($end . " +19 days"));

      $today = strtotime(date('d-M-Y'));

      $monthnames = array (1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');

      if($today<=strtotime($end))
      {

        $end=date('d-M-Y');
        $monthindex2 = array_search($month,$monthnames);
        array_splice($monthnames, $monthindex2);

      } else {
        if ($year == date('Y')) {

            $end2 = strtotime('01 '.date('F Y'));
            $end2 = date('d F Y', $end2);
            $end2 = date('d-M-Y', strtotime($end2 . " +19 days"));

            if ($today > strtotime($end2)) {
                $monthindex2 = array_search(date('F'),$monthnames);
                $monthnames = array_slice($monthnames, 0,$monthindex2+1, TRUE);

            } else {
                $monthindex2 = array_search(date('F'),$monthnames);
                $monthnames = array_slice($monthnames, 0,$monthindex2, TRUE);
            }
        }
      }

    $end3 = strtotime('21 December '.date('Y'));

    if ($today >= $end3) {
        $years= DB::select("
          SELECT Year(Now())-1 as yearname UNION ALL
          SELECT Year(Now()) UNION ALL
          SELECT Year(Now())+1
          ");
    } else {
        $years= DB::select("
          SELECT Year(Now())-1 as yearname UNION ALL
          SELECT Year(Now())
          ");
    }


		$months = array (1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
		$monthindex=array_search($month,$months);

    $holidays = DB::table('holidays')
		->select('holidays.Id','holidays.Holiday','holidays.Start_Date','holidays.End_Date','holidays.State','holidays.Country')
		->whereRaw('str_to_date(holidays.Start_Date,"%d-%M-%Y") >= str_to_date("'.$start.'","%d-%M-%Y")
		AND str_to_date(holidays.End_Date,"%d-%M-%Y") <= str_to_date("'.$end.'","%d-%M-%Y")')
		->orderBy('holidays.Start_Date','asc')
		->get();
		$leaves = DB::select("
		SELECT leaves.Start_Date As Start,leaves.End_Date,leaves.Leave_Type,'Leave' as Type,No_Of_Days,Leave_Term
		FROM leaves
		LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
		LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid` and leavestatuses.Leave_Status like '%Final Approved%'
		WHERE str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
		AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y')
		UNION All
		SELECT holidays.Start_Date as Start,holidays.End_Date,holidays.Holiday,'Holiday' as Type,'',''
		FROM holidays
		WHERE right(Start_Date,4)=".date('Y')."
		ORDER BY Start ASC
		");
		$arrdepartment=array();
		$hod = DB::table('users')
		->distinct('Department')
		->select('Department')
		->where('Department','!=','')
		->orderBy('Department','ASC')
		->get();
    array_push($arrdepartment,"");
		foreach ($hod as $dept) {
			# code...
			array_push($arrdepartment,$dept->Department);
		}
		$cond="1";
		if($company=='false')
		{
				$company=null;
		}
		if($department=='false')
		{
				$department=null;
		}
		if($company)
		{
				$cond.=" AND Company='".$company."'";
		}
		if($department)
		{
			$cond.=" AND Department='".$department."'";
		}

        if ($includeResigned == 'false') {

            $today2 = date('d-M-Y', strtotime('today'));
            $cond.=' AND (users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today2.'","%d-%M-%Y"))';

        } else {
            $cond.=' AND (users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$start.'","%d-%M-%Y"))';
        }

        if ($includeInactive == 'false') {

            $cond.=' AND users.Active = 1';

        }

		$timesheetdetail = DB::table('timesheets')
		->select('users.Name','users.Company','users.Department','users.Working_Days','timesheets.Date',DB::raw('DAYOFWEEK(str_to_date(timesheets.Date,"%d-%M-%Y")) as dayofweek'),'timesheets.Time_In','timesheets.Time_Out',DB::raw("GROUP_CONCAT(leaves.Leave_Term) as Leave_Term"),DB::raw("GROUP_CONCAT(leave_terms.Leave_Period) as Leave_Period"),DB::raw("GROUP_CONCAT(TRIM(leaves.Leave_Type)) as Leave_Type"),DB::raw("GROUP_CONCAT(TRIM(leaves.Reason)) as Reason"),'holidayterritorydays.Holiday')
		->leftJoin('users', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		// ->leftJoin('leaves','leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
        ->leftJoin(DB::raw("
            (SELECT leaves.Id, leaves.UserId, leaves.Leave_Type, leaves.Leave_Term, leaves.Reason,  leaves.Start_Date, leaves.End_Date, leavestatuses.Leave_Status FROM leaves
                LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave ON maxleave.LeaveId = leaves.Id
                LEFT JOIN leavestatuses ON leavestatuses.Id = maxleave.maxid
                WHERE leavestatuses.Leave_Status LIKE '%Final Approved%'
            ) as leaves"), 'leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
    ->leftJoin('leave_terms','leave_terms.Leave_Id','=',DB::raw('leaves.Id and leave_terms.Leave_Date = timesheets.Date'))
  //   ->leftJoin(DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
		// ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser'), 'maxuser.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('maxuser.`maxid` and files.`Type`="User"'))
		->leftJoin('holidayterritorydays',DB::raw('1'),'=',DB::raw('1 AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y") and str_to_date(holidayterritorydays.End_Date,"%d-%M-%Y") AND holidayterritorydays.HolidayTerritoryId = users.HolidayTerritoryId'))
		->where('users.Name','<>','')
		->whereIn('users.Department',$arrdepartment)
		->whereNotIn('users.Id',array(855, 883,902,562,1193))
		->whereRaw($cond)
        // ->where('users.Id',645)
		// ->whereIn('users.Id',array(1189,1190))
		// ->whereIn('users.Name',['ELLYAS BIN MOHD HANIFIAH','DESMOND GANI ANAK CHRISTOPHER','ELNAZIR BIN LIPAIE'])
		// ->where('users.Resignation_Date','=','')
		// ->where('users.Id','=','774')
		->groupBy('users.StaffId')
		->groupBy('timesheets.Date')
		->orderBy('users.Company','asc')
		->orderBy('users.Department','asc')
		->orderBy('users.Name','asc')
		->orderByRaw('str_to_date(timesheets.Date,"%d-%M-%Y") ASC')
		->get();

// dd($timesheetdetail);
		$arrdays = DB::table('timesheets')
		->distinct('timesheets.Date')
		->select('timesheets.Date')
		->whereRaw('str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
		->orderByRaw('str_to_date(timesheets.Date,"%d-%M-%Y") ASC')
		->get();
		$companies= DB::table('options')
		->whereIn('Table', ["users"])
		->where('Field','=','Company')
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();
		$departments = DB::table('projects')
		->where('projects.Project_Name','like','%department%')
		->get();

		$months=array("01"=>"January","02"=>"Febraury","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
		$nodays=cal_days_in_month(CAL_GREGORIAN, $monthindex, $year);
		return view('departmentleavesummary',['me'=>$me, 'month'=>$month,'years'=>$years, 'year'=>$year, 'holidays'=>$holidays,'months'=>$months,'leaves'=>$leaves,'timesheetdetail'=>$timesheetdetail,'nodays'=>$nodays,'arrdays'=>$arrdays,'companies'=>$companies,'departments'=>$departments,'company'=>$company,'department'=>$department, 'includeInactive' => $includeInactive, 'includeResigned' => $includeResigned, 'monthnames' => $monthnames]);
	}

    public function leavesummary($start=null,$end=null){

        $me=(new CommonController)->get_current_user();

        if ($start==null)
        {
            $start=date('d-M-Y', strtotime(date('Y-01-01')));
        }

        if ($end==null)
        {
            $end=date('d-M-Y', strtotime(date('Y-12-31')));
        }

        // $leavesummary = DB::table('leaves')
        // ->select('users.Name','leaves.Start_Date','leaves.End_Date')
        // ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        // ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        // ->leftJoin('users', 'users.Id', '=', 'leaves.UserId')
        // ->where(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        // ->where(DB::raw('str_to_date(leaves.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
        // ->get();


        //
        $startdate=strtotime($start);
        $enddate=strtotime($end);
        $d5=0;
        $d55=0;
        $d6=0;

    while ($startdate<=$enddate)
    {
            $current=date("d-M-Y",$startdate);

             $holiday=DB::table('holidays')
             ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$current.'","%d-%M-%Y")'))
             ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$current.'","%d-%M-%Y")'))
             ->get();

             if (count($holiday)>0)
             {
                 //holiday
             }
             else {

                 $day_type=date( "w", $startdate);
                 if ($day_type=="6")
                 {
                     $d55+=0.5;
                     $d6+=1;


                 }
                 elseif ($day_type=="0")
                 {
                     //not working


                 }else {
                    # code...
                    $d5+=1;
                    $d55+=1;
                    $d6+=1;
                 }
             }

             $startdate=strtotime("+1 day", $startdate);

    }

        //

        $data="";
        $leavetype= DB::select("SELECT `Option`
            FROM  `options`
            WHERE  `Field` LIKE  'Leave_Type'
            -- union all (select 'Replacement Leave' from `options` limit 1)
            ");

            foreach($leavetype as $key => $quote){

            $data.= $quote->Option.",";
            }
            $s=rtrim($data,",");
            $arr = explode(",", $s);

            $i=0;
            $querytype="";

            while ($i < count($arr)) {
                 $a = $arr[$i];
                 $querytype.= "SUM(case when leaves.Leave_Type = '".$a."' and leavestatuses.Leave_Status like '%Final Approved%' then leaves.No_of_Days else 0 end) as '".$a."'," ;
                 $i++;

                 if($a=="Medical Leave")
                 {
                     $querytype.= "(SELECT SUM(Amount) FROM staffexpenses WHERE Type='Medical Claim' AND staffexpenses.UserId=users.Id AND str_to_date(staffexpenses.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y') AND str_to_date(staffexpenses.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y')) as Medical_Claim," ;
                 }
            }
            $querytype=substr($querytype,0,strlen($querytype)-1);
            $thisyear=date('Y');
            $lastyear=$thisyear-1;
            $currentmonth=date('n');

            // CASE WHEN service.Days_of_Service <= 90
            //     THEN 0
            // WHEN service.confirmed AND service.Days_of_Service > 365
            //     THEN leaveentitlements.Days
            // ELSE
            //     5*ROUND(leaveentitlements.Days/12*service.Months_of_Service/5 ,1)
            // END as Current_Entitlement,

            $hod = DB::table('projects')
            ->select('Project_Name')
            ->where('projects.Project_Manager', '=', $me->UserId)
            ->get();

            $arrdepartment=array();
            $cond = "1";
            $cond2 = "1";

            if(count($hod) && !$me->Admin)
            {
                foreach ($hod as $department) {
                    # code...
                    array_push($arrdepartment,$department->Project_Name);
                }

                $cond = "1 AND users.Department IN ('" . implode("','", $arrdepartment) . "')";
                $cond2 = "1 AND applicant.Department IN ('" . implode("','", $arrdepartment) . "')";
            }

            $leavesummary = DB::select("
            SELECT users.Id, users.StaffId,users.Name,users.Company,users.Department,users.Confirmation_Date,users.Resignation_Date,
            Working_Days,
            'Total_Working_Days',
            'Staff_Working_Days',
            timesheets.Check_In_Day,
            timesheets.Non_Check_In_Day,
            timesheets.Work_On_Sunday,
            timesheets.Work_On_Public,
            timesheets.Forgot_Time_Out,
            timesheets.On_Leave_Time_In,
            -- (SELECT COUNT(distinct Date) FROM timesheets where timesheets.UserId=users.Id and str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y') and str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') AND timesheets.Time_In!='') as 'Check_In_Day',
            -- (SELECT COUNT(distinct Date) FROM timesheets where timesheets.UserId=users.Id and str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y') and str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') AND timesheets.Time_In!='') as 'Non_Check_In_Day',
            -- (SELECT COUNT(distinct Date) FROM timesheets where timesheets.UserId=users.Id and str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y') and str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') AND DAYOFWEEK(str_to_date(timesheets.Date,'%d-%M-%Y'))=1 AND timesheets.Time_In!='') as 'Work_On_Sunday',
            -- (SELECT COUNT(distinct Date) FROM timesheets
            --   inner join holidays on str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date(holidays.Start_Date,'%d-%M-%Y') and str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date(holidays.End_Date,'%d-%M-%Y')
            --   where timesheets.UserId=users.Id and str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y') and str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') AND timesheets.Time_In!='') as 'Work_On_Public',
            -- (SELECT COUNT(distinct Date) FROM timesheets where timesheets.UserId=users.Id and str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y') and str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') AND timesheets.Remarks like '%Forgot to check-out%') as 'Forgot_Time_Out',

            -- (SELECT COUNT(distinct Date) FROM timesheets inner join leaves on timesheets.UserId=leaves.UserId and str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date(leaves.Start_Date,'%d-%M-%Y') and str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date(leaves.End_Date,'%d-%M-%Y') where timesheets.UserId=users.Id AND str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y') and str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') AND timesheets.Time_In!='') as 'On_Leave_Time_In',

            SUM(case when leavestatuses.Leave_Status like '%Final Approved%' then leaves.No_of_Days else 0 end) as 'Leave_Taken',".$querytype."
            FROM users
            LEFT JOIN (
                SELECT timesheets.UserId,
                COUNT(DISTINCT ( CASE WHEN Time_In != '' THEN timesheets.Date END)) as 'Check_In_Day',
                COUNT(DISTINCT ( CASE WHEN Time_In != '' THEN timesheets.Date END)) as 'Non_Check_In_Day',
                COUNT(DISTINCT ( CASE WHEN Time_In != '' AND DAYOFWEEK(str_to_date(timesheets.Date,'%d-%M-%Y'))=1 AND timesheets.Time_In!='' THEN date END)) as 'Work_On_Sunday',

                COUNT(DISTINCT ( CASE WHEN Time_In != '' and holidays.Id THEN timesheets.Date END)) as 'Work_On_Public',
                COUNT(DISTINCT ( CASE WHEN timesheets.Remarks like '%Forgot to check-out%' THEN timesheets.Date END)) as 'Forgot_Time_Out',
                COUNT(DISTINCT ( CASE WHEN Time_In != '' and leaves.Id THEN timesheets.Date END)) as 'On_Leave_Time_In'
                FROM timesheets
                left join holidays on str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date(holidays.Start_Date,'%d-%M-%Y') and str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date(holidays.End_Date,'%d-%M-%Y')
                left join leaves on timesheets.UserId=leaves.UserId and str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date(leaves.Start_Date,'%d-%M-%Y') and str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date(leaves.End_Date,'%d-%M-%Y')
                 where
                str_to_date(timesheets.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y') and str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y')
                GROUP BY timesheets.UserId
            ) as timesheets ON timesheets.UserId = users.id
            LEFT JOIN leaves ON users.Id = leaves.UserId and str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
            AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y')
            LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
            LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
            LEFT JOIN (
                SELECT users.id AS UserId, users.Marital_Status, users.Gender,
                    CASE WHEN
                    DATEDIFF(Date_format(Now(), '%Y-%m-%d'), Str_to_date(users.confirmation_date, '%d-%M-%Y')) >= 0 THEN 1 ELSE 0 end AS confirmed,
                    DATEDIFF(Date_format(Now(), '%Y-%m-%d'),Str_to_date(users.joining_date, '%d-%M-%Y')) AS Days_of_Service,
                    Ceiling((SELECT days_of_service) / 365) AS Years_of_Service,
                    CASE WHEN YEAR(str_to_date(users.Joining_date,'%d-%M-%Y')) = $thisyear THEN 1 ELSE 0 END as Joined_This_Year,
                    Joining_date,
                    (SELECT days_of_service) / 30 AS Current_Completed_Month,
                    CASE WHEN (SELECT joined_this_year) THEN (SELECT current_completed_month) ELSE Month(Now()) end AS Months_of_Service,
                    CASE WHEN (SELECT Joined_This_Year) THEN (SELECT Days_of_Service) ELSE DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(NOW() ,'%Y-01-01')) END as Days_of_Service_Current_Year
                FROM users
            ) as service ON service.UserId = users.Id
            LEFT JOIN (SELECT leaveentitlements.*,tblEnt.MaxYear FROM leaveentitlements LEFT JOIN
                    (SELECT leaveentitlements.Grade, Leave_Type, MAX(Year) as MaxYear
                        FROM leaveentitlements
                        GROUP BY Grade, Leave_Type ) as tblEnt
                    ON leaveentitlements.Grade = tblEnt.Grade and leaveentitlements.Leave_Type = tblEnt.Leave_Type) as leaveentitlements
                ON leaveentitlements.Grade = users.Grade
                AND (leaveentitlements.Year = LEAST(leaveentitlements.MaxYear,service.Years_of_Service) OR leaveentitlements.Year = '')
                AND leaveentitlements.Leave_Type = 'Annual Leave'
            WHERE users.Active=1 AND (
                users.Resignation_Date='' OR (
                    str_to_date(users.Resignation_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y')
                    AND str_to_date(users.Resignation_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
                ) OR (
                    MONTH(str_to_date(users.Resignation_Date,'%d-%M-%Y')) >= MONTH(str_to_date('$end','%d-%M-%Y'))
                    AND YEAR(str_to_date(users.Resignation_Date,'%d-%M-%Y')) = YEAR(str_to_date('$end','%d-%M-%Y'))
                ) OR str_to_date(users.Resignation_Date,'%d-%M-%Y') >= str_to_date('$end','%d-%M-%Y')

            )
            AND $cond
            GROUP BY users.Id
            ");

            $leavetaken = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
            ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('applicant.StaffID','applicant.Name','leaves.Leave_Type','leaves.Start_Date','leaves.End_Date','leaves.No_Of_Days','leaves.Reason')
            ->orderBy('leaves.Id','desc')
            ->whereRaw($cond2)
            ->whereRaw('str_to_date(leaves.Start_Date,"%d-%M-%Y") >= str_to_date("'.$start.'","%d-%M-%Y")
            AND str_to_date(leaves.End_Date,"%d-%M-%Y") <= str_to_date("'.$end.'","%d-%M-%Y")')
            ->where('leavestatuses.Leave_Status', 'like','%Final Approved%')
            ->get();

            // $leavebalance = DB::select("
            // SELECT users.Id,
            // users.StaffId,
            // users.Name,
            // users.Grade,
         //  users.Joining_Date,
            // DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'), str_to_date(users.Joining_Date,'%d-%M-%Y')) as Days_of_Service,
            // CEILING((DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),str_to_date(users.Joining_Date,'%d-%M-%Y')) / 365 )) as Years_of_Service,
            // '' as Yearly_Entitlement,
            // '' as Current_Entitlement,
            // leavecarryforwards.Days as Carried_Forward,
            // '' as Total_Leave_Days,
         //  SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=".$thisyear." AND leaves.Leave_Type='Annual Leave',No_of_Days,0)) as Total_Leave_Taken,
            // SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND Leave_Type IN ('1 Hour Time Off','2 Hours Time Off'),No_of_Days,0)) as Time_Off,
            // '' as Total_Leave_Balance,
            // SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND Leave_Type='Emergency Leave' AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=".$thisyear.",No_of_Days,0)) as EL,
            // SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND Leave_Type='Emergency Leave' AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=".$thisyear.",No_of_Days,0)) as EL_to_AL,
            // '' as AL_to_UL
            // from users
            // LEFT JOIN leaves ON users.Id = leaves.UserId AND (Leave_Type='Annual Leave' OR Leave_Type='Emergency Leave' OR Leave_Type='Unpaid Leave' OR Leave_Type='1 Hour Time Off' OR Leave_Type='2 Hours Time Off')
            // LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
            // LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
            // LEFT JOIN leavecarryforwards ON leavecarryforwards.UserId=users.Id AND leavecarryforwards.Year=".$lastyear."
            // WHERE users.Active=1 AND users.Resignation_Date=''
            // GROUP BY users.Id
            // ");

            $leavebalance = DB::select("SELECT users.Id, users.StaffId,users.Name, users.Grade,
            users.Joining_Date,users.Confirmation_Date,service.Days_of_Service,service.Years_of_Service,
            leaveentitlements.Days as Yearly_Entitlement,
                CASE WHEN service.Days_of_Service <= 90
                    THEN 0
                -- WHEN service.confirmed AND service.Days_of_Service > 365
                --     THEN leaveentitlements.Days
                ELSE
                    5*ROUND(leaveentitlements.Days/12*service.Months_of_Service/5 ,1)
                END as Current_Entitlement,
            Leave_Taken_In_Between_March,
            SUM(IF((leavestatuses.Leave_Status like '%Pending%' OR leavestatuses.Leave_Status like '%Approved%') AND leaves.Leave_Type IN ('Annual Leave','1 Hour Time Off','2 Hours Time Off','Emergency Leave') AND MONTH(str_to_date(leaves.End_Date,'%d-%M-%Y')) <=3,No_of_Days,0)) as Leave_Taken_Before_April,
            SUM(IF((leavestatuses.Leave_Status like '%Pending%' OR leavestatuses.Leave_Status like '%Approved%') AND leaves.Leave_Type IN ('Annual Leave'),No_of_Days,0)) as Leave_Taken,
            SUM(IF((leavestatuses.Leave_Status like '%Pending%' OR leavestatuses.Leave_Status like '%Approved%') AND leaves.Leave_Type IN ('1 Hour Time Off','2 Hours Time Off'),No_of_Days,0)) as Time_Off,
            SUM(IF((leavestatuses.Leave_Status like '%Pending%' OR leavestatuses.Leave_Status like '%Approved%') AND leaves.Leave_Type='Emergency Leave',No_of_Days,0)) as Emergency_Leave,
            SUM(IF((leavestatuses.Leave_Status like '%Pending%' OR leavestatuses.Leave_Status like '%Approved%') AND leaves.Leave_Type='Replacement Leave',No_of_Days,0)) as Replacement_Leave,
            SUM(IF(leavestatuses.Leave_Status not like '%Final Approved%' AND (leavestatuses.Leave_Status like '%Pending%' OR leavestatuses.Leave_Status like '%Approved%') AND leaves.Leave_Type IN ('Annual Leave','1 Hour Time Off','2 Hours Time Off','Emergency Leave','Replacement Leave'),No_of_Days,0)) as Total_On_Hold,
            SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND leaves.Leave_Type IN ('Annual Leave','1 Hour Time Off','2 Hours Time Off','Emergency Leave', 'Replacement Leave'),No_of_Days,0)) as Total_Leave_Taken,
            SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND leaves.Leave_Type IN ('Replacement Leave'),No_of_Days,0)) as Replacement_Taken,
            IF(replacementadjustments.Adjustment_Value,replacementadjustments.Adjustment_Value,0) as Replacement_Earned,
            leaveadjustments.Adjustment_Value as Adjusted,
            leavecarryforwards.Days as Carried_Forward,
            '' as Burnt,
            '' as Total_Leave_Days,
            '' as Replacement_Balance,
            '' as AL_Balance,
            '' as Total_Leave_Balance
            FROM users
            LEFT JOIN leaves ON users.Id = leaves.UserId and YEAR(str_to_date(leaves.Start_Date,'%d-%M-%Y')) = $thisyear
            LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
            LEFT JOIN (
                SELECT leaves.UserId, sum(Case WHEN lt.Leave_Period = 'AM' OR lt.Leave_Period = 'PM' THEN 0.5 WHEN lt.Leave_Period = '1 Hour' THEN 0.125 WHEN lt.Leave_Period = '2 Hours' THEN 0.25 WHEN  lt.Leave_Period = 'Full' THEN 1 ELSE 0 END) Leave_Taken_In_Between_March  FROM leaves
                LEFT JOIN (SELECT leave_terms.Leave_Period, leave_terms.Leave_Id from leave_terms WHERE YEAR(Str_to_date(leave_terms.Leave_Date, '%d-%M-%Y')) = $thisyear AND MONTH(Str_to_date(leave_terms.Leave_Date, '%d-%M-%Y')) <= 3) as lt
                ON leaves.Id = lt.Leave_Id
                LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
                WHERE (leaves.Leave_Type = 'Annual Leave' OR leaves.Leave_Type = 'Emergency Leave' OR leaves.Leave_Type = 'Replacement Leave' OR leaves.Leave_Type = '1 Hour Time Off' OR leaves.Leave_Type = '2 Hours Time Off') AND YEAR(Str_to_date(leaves.Start_Date, '%d-%M-%Y')) = $thisyear AND MONTH(Str_to_date(leaves.Start_Date, '%d-%M-%Y')) <= 3 AND MONTH(Str_to_date(leaves.End_Date, '%d-%M-%Y')) > 3 AND leavestatuses.Leave_Status like '%Final Approved%'
                GROUP BY leaves.UserId
            ) as leaveInBetweenMarch ON leaveInBetweenMarch.UserId = users.Id
            LEFT JOIN leaveadjustments ON users.Id = leaveadjustments.UserId AND leaveadjustments.Adjustment_Leave_Type = 'Annual Leave' AND leaveadjustments.Adjustment_Year = $thisyear
            LEFT JOIN leaveadjustments as replacementadjustments ON users.Id = replacementadjustments.UserId AND replacementadjustments.Adjustment_Leave_Type = 'Replacement Leave' AND replacementadjustments.Adjustment_Year = $thisyear
            LEFT JOIN (
                SELECT users.id AS UserId, users.Marital_Status, users.Gender,
                    CASE WHEN
                    DATEDIFF(Date_format(Now(), '%Y-%m-%d'), Str_to_date(users.confirmation_date, '%d-%M-%Y')) >= 0 THEN 1 ELSE 0 end AS confirmed,
                    DATEDIFF(Date_format(Now(), '%Y-%m-%d'),Str_to_date(users.joining_date, '%d-%M-%Y')) AS Days_of_Service,
                    Ceiling((SELECT days_of_service) / 365) AS Years_of_Service,
                    CASE WHEN YEAR(str_to_date(users.Joining_date,'%d-%M-%Y')) = $thisyear THEN 1 ELSE 0 END as Joined_This_Year,
                    Joining_date,
                    (SELECT days_of_service) / 30 AS Current_Completed_Month,
                    CASE WHEN (SELECT joined_this_year) THEN (SELECT current_completed_month) ELSE Month(Now()) end AS Months_of_Service,
                    CASE WHEN (SELECT Joined_This_Year) THEN (SELECT Days_of_Service) ELSE DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(NOW() ,'%Y-01-01')) END as Days_of_Service_Current_Year
                FROM users
            ) as service ON users.Id = service.UserId
            LEFT JOIN (SELECT leaveentitlements.*,tblEnt.MaxYear FROM leaveentitlements LEFT JOIN
                    (SELECT leaveentitlements.Grade, Leave_Type, MAX(Year) as MaxYear
                        FROM leaveentitlements
                        GROUP BY Grade, Leave_Type ) as tblEnt
                    ON leaveentitlements.Grade = tblEnt.Grade and leaveentitlements.Leave_Type = tblEnt.Leave_Type) as leaveentitlements
                ON leaveentitlements.Grade = users.Grade
                AND (leaveentitlements.Year = LEAST(leaveentitlements.MaxYear,service.Years_of_Service) OR leaveentitlements.Year = '')
                AND leaveentitlements.Leave_Type = 'Annual Leave'
            LEFT JOIN (SELECT * FROM leavecarryforwards GROUP BY Year, UserId) as leavecarryforwards ON leavecarryforwards.UserId=users.Id AND leavecarryforwards.Year = $lastyear
            WHERE users.Active=1 AND (users.Resignation_Date='' OR (MONTH(str_to_date(users.Resignation_Date,'%d-%M-%Y')) >= MONTH(now()) AND YEAR(str_to_date(users.Resignation_Date,'%d-%M-%Y')) = $thisyear))
            AND $cond
            GROUP BY users.Id
            -- ORDER BY users.Id DESC
        ");
            $sickbalance = DB::select("
            SELECT users.Id, users.StaffId, users.Name, users.Grade, users.Joining_Date,
            DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'), str_to_date(users.Joining_Date,'%d-%M-%Y')) as Days_of_Service,
            CEILING((DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),str_to_date(users.Joining_Date,'%d-%M-%Y')) / 365 )) as Years_of_Service,
            '' as Yearly_Entitlement,
            leaveadjustments.Adjustment_Value as Adjusted,
            SUM(IF(leavestatuses.Leave_Status not like '%Final Approved%' AND (leavestatuses.Leave_Status like '%Pending%' OR leavestatuses.Leave_Status like '%Approved%') AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=".$thisyear.",No_of_Days,0)) as Total_On_Hold,
          SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=".$thisyear.",No_of_Days,0)) as Total_Leave_Taken,
            '' as Total_Leave_Balance
            from users
            LEFT JOIN leaves ON users.Id = leaves.UserId AND Leave_Type='Medical Leave'
            LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
            LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
            LEFT JOIN leaveadjustments ON users.Id = leaveadjustments.UserId AND leaveadjustments.Adjustment_Leave_Type = 'Medical Leave' AND leaveadjustments.Adjustment_Year = $thisyear
            LEFT JOIN leavecarryforwards ON leavecarryforwards.UserId=users.Id AND leavecarryforwards.Year=".$thisyear."
            WHERE users.Active=1 AND (users.Resignation_Date='' OR (MONTH(str_to_date(users.Resignation_Date,'%d-%M-%Y')) >= MONTH(now()) AND YEAR(str_to_date(users.Resignation_Date,'%d-%M-%Y')) = $thisyear))
            AND $cond
            GROUP BY users.Id
            ");

            $annualentitlement = DB::table('leaveentitlements')
            ->select('Grade','Year','Days')
            ->orderBy('Grade','ASC')
            ->orderBy('Year','DESC')
            ->where('Leave_Type', '=','Annual Leave')
            ->get();

            $sickentitlement = DB::table('leaveentitlements')
            ->select('Grade','Year','Days')
            ->orderBy('Grade','ASC')
            ->orderBy('Year','DESC')
            ->where('Leave_Type', '=','Medical Leave')
            ->get();

            // INNER JOIN leaveentitlements
            // ON leaveentitlements.Grade = users.Grade
            //  dd($leavebalance);

        // $leavecalculations = $this->getLeaveCalculation($start, $end);
        $leavecalculations = [];

        return view('leavesummary',['me'=>$me, 'start'=>$start, 'end'=>$end, 'leavesummary'=>$leavesummary, 'leavetype'=>$leavetype, 'leavebalance'=>$leavebalance, 'sickbalance'=>$sickbalance,'annualentitlement'=>$annualentitlement,'sickentitlement'=>$sickentitlement,'currentmonth'=>$currentmonth,'d5'=>$d5,'d55'=>$d55,'d6'=>$d6,'leavetaken'=>$leavetaken, 'leavecalculations' => $leavecalculations]);

    }

    public function staffleave($start=null,$end=null,$userid = null){

        $me=(new CommonController)->get_current_user();

        if($start == null)
        {
            $start = date('d-M-Y',strtotime('today'));

        }
        if($end == null)
        {
            $end = date('d-M-Y',strtotime('today'));
        }

        //
        $startdate=strtotime($start);
        $enddate=strtotime($end);
        $d5=0;
        $d55=0;
        $d6=0;

    while ($startdate<=$enddate)
    {
            $current=date("d-M-Y",$startdate);

             $holiday=DB::table('holidays')
             ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$current.'","%d-%M-%Y")'))
             ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$current.'","%d-%M-%Y")'))
             ->get();

             if (count($holiday)>0)
             {
                 //holiday
             }
             else {

                 $day_type=date( "w", $startdate);
                 if ($day_type=="6")
                 {
                     $d55+=0.5;
                     $d6+=1;


                 }
                 elseif ($day_type=="0")
                 {
                     //not working


                 }else {
                    # code...
                    $d5+=1;
                    $d55+=1;
                    $d6+=1;
                 }
             }

             $startdate=strtotime("+1 day", $startdate);

    }

        //

        $data="";
        $leavetype= DB::select("SELECT `Option`
            FROM  `options`
            WHERE  `Field` LIKE  'Leave_Type'
            -- union all (select 'Replacement Leave' from `options` limit 1)
            ");

            foreach($leavetype as $key => $quote){

            $data.= $quote->Option.",";
            }
            $s=rtrim($data,",");
            $arr = explode(",", $s);

            $i=0;
            $querytype="";

            while ($i < count($arr)) {
                 $a = $arr[$i];
                 $querytype.= "SUM(case when leaves.Leave_Type = '".$a."' and leavestatuses.Leave_Status like '%Final Approved%' then leaves.No_of_Days else 0 end) as '".$a."'," ;
                 $i++;

                 if($a=="Medical Leave")
                 {
                     $querytype.= "(SELECT SUM(Amount) FROM staffexpenses WHERE Type='Medical Claim' AND staffexpenses.UserId=users.Id AND str_to_date(staffexpenses.Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y') AND str_to_date(staffexpenses.Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y')) as Medical_Claim," ;
                 }
            }

            $hod = DB::table('projects')
            ->select('Project_Name')
            ->where('projects.Project_Manager', '=', $me->UserId)
            ->get();

            $arrdepartment=array();
            $cond = "1";
            $cond2 = "1";

            if(count($hod) && !$me->Admin)
            {
                foreach ($hod as $department) {
                    # code...
                    array_push($arrdepartment,$department->Project_Name);
                }

                $cond = "1 AND users.Department IN ('" . implode("','", $arrdepartment) . "')";
                $cond2 = "1 AND applicant.Department IN ('" . implode("','", $arrdepartment) . "')";
            }

        if($userid == null)
        {
            $leavetaken = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
            ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('applicant.StaffID','applicant.Name','leaves.Leave_Type','leaves.Start_Date','leaves.End_Date','leaves.No_Of_Days','leaves.Reason')
            ->orderBy('leaves.Id','desc')
            ->whereRaw($cond2)
            ->whereRaw('str_to_date(leaves.Start_Date,"%d-%M-%Y") >= str_to_date("'.$start.'","%d-%M-%Y")
            AND str_to_date(leaves.End_Date,"%d-%M-%Y") <= str_to_date("'.$end.'","%d-%M-%Y")')
            ->where('leavestatuses.Leave_Status', 'like','%Final Approved%')
            ->get();
        }
        else
        {
            $leavetaken = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
            ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('applicant.StaffID','applicant.Name','leaves.Leave_Type','leaves.Start_Date','leaves.End_Date','leaves.No_Of_Days','leaves.Reason')
            ->orderBy('leaves.Id','desc')
            ->whereRaw($cond2)
            ->whereRaw('leaves.UserId = "'.$userid.'" AND (str_to_date(leaves.Start_Date,"%d-%M-%Y") >= str_to_date("'.$start.'","%d-%M-%Y")
            AND str_to_date(leaves.End_Date,"%d-%M-%Y") <= str_to_date("'.$end.'","%d-%M-%Y"))')
            ->where('leavestatuses.Leave_Status', 'like','%Final Approved%')
            ->get();
        }

        return view('staffleave',['me'=>$me, 'start'=>$start, 'end'=>$end, 'leavetype'=>$leavetype, 'd5'=>$d5,'d55'=>$d55,'d6'=>$d6,'leavetaken'=>$leavetaken ]);

    }

    public function individualreport($userid)
    {
        $me=(new CommonController)->get_current_user();

        $user = DB::table('users')
        ->select('users.Id','StaffId','Name','Password','User_Type','Joining_Date','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Department','Position','Emergency_Contact_Person',
        'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path','Working_Days')
        // ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
        ->where('users.Id', '=', $userid)
        ->first();

        $holidays = DB::table('holidays')
        ->select('holidays.Id','holidays.Holiday','holidays.Start_Date','holidays.End_Date','holidays.State','holidays.Country')
        ->whereRaw('right(Start_Date,4)='.date('Y'))
        ->orderBy('holidays.Start_Date','asc')
        ->get();

        $start=date('d-M-Y', strtotime(date('Y-01-01')));
        $end=date('d-M-Y', strtotime(date('Y-12-31')));
        $thisyear=date('Y');
        $lastyear=$thisyear-1;
        $currentmonth=date('n');

        $leavetaken = DB::select("
        SELECT options.Option,SUM(case when leavestatuses.Leave_Status like '%Final Approved%' then leaves.No_of_Days else 0 end) As count
        FROM options
        LEFT JOIN leaves ON leaves.Leave_Type=options.Option and str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
        AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') AND leaves.UserId=".$userid."
        LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
        LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid` and leavestatuses.Leave_Status like '%Final Approved%'
        WHERE options.Field='Leave_Type'
        GROUP BY options.Option
        ORDER BY options.Option
        ");

        $leaves = DB::select("
        SELECT leaves.Start_Date As Start,leaves.End_Date,leaves.Leave_Type,'Leave' as Type,No_Of_Days,Leave_Term
        FROM leaves
        LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
        LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid` and leavestatuses.Leave_Status like '%Final Approved%'
        WHERE str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
        AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y') AND leaves.UserId=".$userid."
        UNION All
        SELECT holidays.Start_Date as Start,holidays.End_Date,holidays.Holiday,'Holiday' as Type,'',''
        FROM holidays
        WHERE right(Start_Date,4)=".date('Y')."
        ORDER BY Start ASC
        ");

        $leavebalance = DB::select("
        SELECT users.Id,
        users.StaffId,
        users.Name,
        users.Grade,
        users.Joining_Date,
        DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'), str_to_date(users.Joining_Date,'%d-%M-%Y')) as Days_of_Service,
        CEILING((DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),str_to_date(users.Joining_Date,'%d-%M-%Y')) / 365 )) as Years_of_Service,
        '' as Yearly_Entitlement,
        '' as Current_Entitlement,
        leavecarryforwards.Days as Carried_Forward,
        '' as Total_Leave_Days,
        SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=".$thisyear.",No_of_Days,0)) as Total_Leave_Taken,
        '' as Total_Leave_Balance,
        SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND Leave_Type='Emergency Leave' AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=".$thisyear.",No_of_Days,0)) as EL,
        SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND Leave_Type='Emergency Leave' AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=".$thisyear.",No_of_Days,0)) as EL_to_AL,
        '' as AL_to_UL
        from users
        LEFT JOIN leaves ON users.Id = leaves.UserId AND (Leave_Type='Annual Leave' OR Leave_Type='Emergency Leave')
        LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
        LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
        LEFT JOIN leavecarryforwards ON leavecarryforwards.UserId=users.Id AND leavecarryforwards.Year=".$lastyear."
        WHERE users.Id=".$userid."
        GROUP BY users.Id
        ");

        $sickbalance = DB::select("
        SELECT users.Id, users.StaffId, users.Name, users.Grade, users.Joining_Date,
        DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'), str_to_date(users.Joining_Date,'%d-%M-%Y')) as Days_of_Service,
        CEILING((DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),str_to_date(users.Joining_Date,'%d-%M-%Y')) / 365 )) as Years_of_Service,
        '' as Yearly_Entitlement,
        SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=".$thisyear.",No_of_Days,0)) as Total_Leave_Taken,
        '' as Total_Leave_Balance
        from users
        LEFT JOIN leaves ON users.Id = leaves.UserId AND Leave_Type='Medical Leave'
        LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
        LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
        LEFT JOIN leavecarryforwards ON leavecarryforwards.UserId=users.Id AND leavecarryforwards.Year=".$thisyear."
        WHERE users.Id=".$userid."
        GROUP BY users.Id
        ");

        $annualentitlement = DB::table('leaveentitlements')
        ->select('Grade','Year','Days')
        ->orderBy('Grade','ASC')
        ->orderBy('Year','DESC')
        ->where('Leave_Type', '=','Annual Leave')
        ->get();

        $sickentitlement = DB::table('leaveentitlements')
        ->select('Grade','Year','Days')
        ->orderBy('Grade','ASC')
        ->orderBy('Year','DESC')
        ->where('Leave_Type', '=','Medical Leave')
        ->get();

        //annual balance
        $leavearr=array();
        $leaveentitlement=0;
        $currententitlement=0;
        $totalleavedays=0;
        $annualbalance=0;
        $leavetak=$leavebalance[0]->Total_Leave_Taken;
        $carryforward=$leavebalance[0]->Carried_Forward;
        $totalbalance=0;

        foreach ($annualentitlement as $entitlement)
        {

                if($leavebalance[0]->Grade==$entitlement->Grade)
                {
                    if($leavebalance[0]->Years_of_Service>=$entitlement->Year)
                    {
                        $leaveentitlement=$entitlement->Days;
                    }
                }
        }

        $currententitlement=round($leaveentitlement/12*$currentmonth,1);
        $totalleavedays=(round($leaveentitlement/12*$currentmonth,1))+$leavebalance[0]->Carried_Forward;
        $annualbalance=floor((round($leaveentitlement/12*$currentmonth,1))+$leavebalance[0]->Carried_Forward-($leavebalance[0]->Total_Leave_Taken));
        $totalbalance=$leaveentitlement-$leavebalance[0]->Total_Leave_Taken;

        array_push($leavearr,$leaveentitlement);
        array_push($leavearr,$currententitlement);
        array_push($leavearr,$totalleavedays);
        array_push($leavearr,$annualbalance);
        array_push($leavearr,$leavetak);
        array_push($leavearr,$carryforward);
        array_push($leavearr,$totalbalance);

        //sick leave balance
        $sickarr=array();
        $leaveentitlement=0;
        $prorate=0;
        $sickbal=0;
        $sicktaken=$sickbalance[0]->Total_Leave_Taken;

        foreach ($sickentitlement as $entitlement)
        {

                if($sickbalance[0]->Grade==$entitlement->Grade)
                {
                    if($sickbalance[0]->Years_of_Service>=$entitlement->Year)
                    {
                        $leaveentitlement=$entitlement->Days;
                    }
                }
        }

        $sickbal=$leaveentitlement-$sickbalance[0]->Total_Leave_Taken;
        $prorate=(round($leaveentitlement/12*$currentmonth,1));

        array_push($sickarr,$leaveentitlement);
        array_push($sickarr,$sicktaken);
        array_push($sickarr,$sickbal);
        array_push($sickarr,$prorate);

            $start=date('01-M-Y');
            $last=date('t-M-Y');

        $months=array("01"=>"January","02"=>"Febraury","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");

        return view('individualleavesummary',['me'=>$me,'user'=>$user,'holidays'=>$holidays,'months'=>$months,'leavetaken'=>$leavetaken,'leaves'=>$leaves,'leavebalance'=>$leavearr,'sickbalance'=>$sickarr]);
    }

    public function leavecarryforward($year = null)
    {

        $me=(new CommonController)->get_current_user();

        if($year==null)
        {
            $year=date('Y');
        }

        $users = DB::select("
        SELECT users.Id FROM users WHERE users.User_Type!='Contrator' AND users.Id NOT IN (SELECT UserId FROM leavecarryforwards WHERE Year=".$year.")");

        foreach ($users as $user) {
            # code...
            DB::table('leavecarryforwards')->insertGetId(
                ['UserId' => $user->Id,
                 'Year' => $year
                ]
            );
        }

        $carryforward = DB::table('leavecarryforwards')
        ->leftJoin('users','users.Id','=','leavecarryforwards.UserId')
        ->select('leavecarryforwards.Id','users.Id As UserId','users.StaffId','users.Name','Days')
        ->where('leavecarryforwards.Year', '=',$year)
        ->where('leavecarryforwards.UserId', '<>',0)
        ->where('users.User_Type', '!=','Contractor')
        ->get();

        $years= DB::select("
          SELECT Year(Now())-1 as yearname UNION ALL
          SELECT Year(Now()) UNION ALL
          SELECT Year(Now())+1
          ");

        return view('leavecarryforward',['me'=>$me, 'years'=>$years,'year'=>$year, 'carryforward'=>$carryforward]);

    }

    public function viewdata(Request $request){

        $input = $request->all();

        $details =  DB::table('leaves')
        ->select('leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leavestatuses.Leave_Status')
        ->where('leaves.UserId','=', $input["UserId"])
        ->where(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$input["Start"].'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(leaves.End_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$input["End"].'","%d-%M-%Y")'))
        ->where('leaves.Leave_Type','=',$input["Leave_Type"])
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        ->get();

        // dd()

        return json_encode($details);
    }

    public function viewdata2(Request $request){

        $input = $request->all();

        if($input["Type"]=="Time In")
        {
          $details =  DB::table('timesheets')
          ->distinct('timesheets.Date')
          ->select('timesheets.Date',DB::raw('DAYNAME(str_to_date(timesheets.Date,"%d-%M-%Y")) as Day'),'timesheets.Check_In_Type','timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks')
          ->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$input["Start"].'","%d-%M-%Y")'))
          ->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$input["End"].'","%d-%M-%Y")'))
          ->where('timesheets.UserId','=',$input["UserId"])
          ->where('timesheets.Time_In','!=','')
          ->orderBy(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),'ASC')
          ->get();
        }
        else if($input["Type"]=="No Time In")
        {
          $details =  DB::table('timesheets')
          ->distinct('timesheets.Date')
          ->select('timesheets.Date',DB::raw('DAYNAME(str_to_date(timesheets.Date,"%d-%M-%Y")) as Day'),'timesheets.Check_In_Type','timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks')
          ->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$input["Start"].'","%d-%M-%Y")'))
          ->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$input["End"].'","%d-%M-%Y")'))
          ->where('timesheets.UserId','=',$input["UserId"])
          ->whereRaw('DAYNAME(str_to_date(timesheets.Date,"%d-%M-%Y"))!="Sunday"')
          ->where('timesheets.Time_In','=','')
          ->orderBy(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),'ASC')
          ->get();
        }
        else if($input["Type"]=="Work On Sunday")
        {
          $details =  DB::table('timesheets')
          ->distinct('timesheets.Date')
          ->select('timesheets.Date',DB::raw('DAYNAME(str_to_date(timesheets.Date,"%d-%M-%Y")) as Day'),'timesheets.Check_In_Type','timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks')
          ->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$input["Start"].'","%d-%M-%Y")'))
          ->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$input["End"].'","%d-%M-%Y")'))
          ->where('timesheets.UserId','=',$input["UserId"])
          ->whereRaw('DAYNAME(str_to_date(timesheets.Date,"%d-%M-%Y"))="Sunday"')
          ->where('timesheets.Time_In','!=','')
          ->orderBy(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),'ASC')
          ->get();
        }
        else if($input["Type"]=="Work On Public")
        {
          $details =  DB::table('timesheets')
          ->distinct('timesheets.Date')
          ->select('timesheets.Date',DB::raw('DAYNAME(str_to_date(timesheets.Date,"%d-%M-%Y")) as Day'),'timesheets.Check_In_Type','timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks')
          ->join('holidays',DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),'>=',DB::raw("str_to_date(holidays.Start_Date,'%d-%M-%Y') AND str_to_date(timesheets.Date,'%d-%M-%Y') <= str_to_date(holidays.End_Date,'%d-%M-%Y')"))
          ->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$input["Start"].'","%d-%M-%Y")'))
          ->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$input["End"].'","%d-%M-%Y")'))
          ->where('timesheets.UserId','=',$input["UserId"])
          ->where('timesheets.Time_In','!=','')
          ->orderBy(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),'ASC')
          ->get();
        }
        else if($input["Type"]=="Forgot Time Out")
        {
          $details =  DB::table('timesheets')
          ->distinct('timesheets.Date')
          ->select('timesheets.Date',DB::raw('DAYNAME(str_to_date(timesheets.Date,"%d-%M-%Y")) as Day'),'timesheets.Check_In_Type','timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks')
          ->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$input["Start"].'","%d-%M-%Y")'))
          ->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$input["End"].'","%d-%M-%Y")'))
          ->where('timesheets.UserId','=',$input["UserId"])
          ->whereRaw('timesheets.Remarks like "%Forgot to check-out%"')
          ->orderBy(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),'ASC')
          ->get();
        }
        else if($input["Type"]=="On Leave Time In")
        {
          $details =  DB::table('timesheets')
          ->distinct('timesheets.Date')
          ->select('timesheets.Date',DB::raw('DAYNAME(str_to_date(timesheets.Date,"%d-%M-%Y")) as Day'),'timesheets.Check_In_Type','leaves.Leave_Type','leaves.Start_Date','leaves.End_Date','timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks')
          ->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$input["Start"].'","%d-%M-%Y")'))
          ->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$input["End"].'","%d-%M-%Y")'))
          ->where('timesheets.UserId','=',$input["UserId"])
          ->where('timesheets.Time_In','!=','')
          ->join('leaves','leaves.UserId','=',DB::raw('timesheets.UserId and str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date(leaves.Start_date,"%d-%M-%Y") and str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date(leaves.End_Date,"%d-%M-%Y")'))
          ->orderBy(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),'ASC')
          ->get();
        }
        // dd()

        return json_encode($details);
    }

    public function viewdata3(Request $request){

        $input = $request->all();

        $details =  DB::table('leaves')
        ->select('leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leavestatuses.Leave_Status')
        ->where('leaves.UserId','=', $input["UserId"])
        ->where(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$input["Start"].'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(leaves.End_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$input["End"].'","%d-%M-%Y")'))
        ->whereIn('leaves.Leave_Type',['1 Hour Time Off','2 Hours Time Off'])
        ->where('leavestatuses.Leave_Status','=','Final Approved')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        ->get();

        return json_encode($details);
    }

    public function entitlement(){

        $me=(new CommonController)->get_current_user();

        $entitlement = DB::table('leaveentitlements')
        ->select('leaveentitlements.Id','leaveentitlements.Grade','leaveentitlements.Year','leaveentitlements.Days','leaveentitlements.Leave_Type')
        ->leftJoin('users','users.Grade','=','leaveentitlements.Grade')
        ->get();

        $grade = DB::table('options')
        ->whereIn('Table', ["users"])
        ->where('Field','Grade')
        ->orderBy('Option','asc')
        ->get();

        $options = DB::table('options')
        ->whereIn('Table', ["leaves"])
        ->orderBy('Option','asc')
        ->get();

        return view('leaveentitlement',['me'=>$me,'entitlement'=>$entitlement,'grade'=>$grade,'options'=>$options]);

    }

    public function timeoffreport($start = null, $end = null)
    {
        $me=(new CommonController)->get_current_user();

        if ($start==null)
        {

            $start=date('d-M-Y', strtotime('first day of January'));
        }

        if ($end==null)
        {
            $end=date('d-M-Y', strtotime('last day of December'));
        }

        $report = DB::select("
        SELECT users.Id, users.StaffId,users.Name,
        SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND Leave_Type IN ('1 Hour Time Off','2 Hours Time Off'),No_of_Days,0)) as Hours
        FROM users
        LEFT JOIN leaves ON users.Id = leaves.UserId and str_to_date(leaves.Start_Date,'%d-%M-%Y') >= str_to_date('$start','%d-%M-%Y')
        AND str_to_date(leaves.End_Date,'%d-%M-%Y') <= str_to_date('$end','%d-%M-%Y')
        LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
        LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
        WHERE users.Active=1 AND users.Resignation_Date=''
        GROUP BY users.Id
        ");

        return view('timeoffreport',['me'=>$me,'report'=>$report,'start'=>$start,'end'=>$end]);

    }

    public function viewmedicalclaim(Request $request)
    {

        $input = $request->all();

        $viewmedicalclaim = DB::table('users')
            ->leftJoin('leaves','users.Id', '=', 'leaves.UserId')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->select('users.Id','users.StaffId','users.Grade','users.name','leaves.Leave_Type','leaves.Start_Date','leaves.End_Date','leaves.Medical_Claim','leaves.Panel_Claim')
            ->where('leavestatuses.Leave_Status', '<>','Cancelled')
            ->where('leavestatuses.Leave_Status', 'NOT LIKE','%Rejected%')
            ->where('users.StaffId', '=',$input["StaffId"])
            ->where('leaves.Leave_Type','=','Medical Leave')
            ->whereRaw('Year(str_to_date(leaves.Start_Date,"%d-%M-%Y"))='.date("Y"))
            // ->where('leavestatuses.Leave_Status', 'like','%Final Approved%')
        // ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        // ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        // ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        // ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
        // ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        // ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        // ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        // ->select('leavestatuses.Id','leaves.Id as LeaveId','leavestatuses.Leave_Status as Status','applicant.StaffId as Staff_ID','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.Medical_Claim','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Comment','leavestatuses.updated_at as Review_Date','files.Web_Path')
        // ->orderBy('leaves.Id','desc')
        // ->where('applicant.StaffId', '=',$input["StaffId"])
        // ->where('leaves.Leave_Type', '=','Medical Leave')
        // ->where('leavestatuses.Leave_Status', '<>','Cancelled')
        ->get();

        return json_encode($viewmedicalclaim);

    }

    public function importleavecarryforward(Request $request)
    {
        // dd($request->file('csvfile'));
        $this->validate($request, [
            'importfile' => 'required|mimes:csv,txt'
        ]);

        $filename = $request->file('importfile')->getRealPath();
        $delimiter = ',';
        // dd($filename);

        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = null;
        $dataArr = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                // if (!$header)
                //     $header = $row;
                // else
                //     $customerArr[] = array_combine($header, $row);
                $dataArr[] = $row;
            }
            fclose($handle);
        }

        $users = DB::table('users')->lists('Id', 'StaffId');;


        for ($i = 0; $i < count($dataArr); $i ++)
        {
            DB::table('leavecarryforwards')->insert([
                'UserId' => $users[$dataArr[$i][0]],
                'Year' => $dataArr[$i][1],
                'Days' => $dataArr[$i][2],
            ]);
        }

        return redirect('leavecarryforward')->with('success', 'Data successfully imported!');

    }

    /**
     * Admin apply leave batch
     * @param  Request $request
     * @return mixed
     */
    public function leavebatchapply(Request $request)
    {
        $me=(new CommonController)->get_current_user();
        $input = $request->all();
        $uploadedFileLocations = [];
        $uploadedNames = [];
        $uploadedSizes = [];

        $rules = array(
            'Leave_Type' => 'Required',
            'Leave_Period' => 'Required',
            'Start_Date'       => 'Required',
            'End_Date'  =>'Required',
            // 'Approver'  =>'Required',
            'Project'  =>'Required',
            // 'attachment' => 'required_unless:Leave_Type,Annual Leave,1 Hour Time Off,2 Hours Time Off'
            );

            $messages = array(
                'Leave_Type.required' => 'The Leave Type field is required',
                'Start_Date.required'       => 'The Start Date field is required',
                'End_Date.required'  =>'The End Date field is required',
                'Approver.required'  =>'The Approver field is required',
                'Project.required'  =>'The Project Name field is required',
                'Leave_Period.required' => 'The Leave Period is required.'
            );

        $validator = Validator::make($input, $rules,$messages);

        if ($validator->passes()) {
            $ids = $input['Ids'];
            $userIds = explode(",", $ids);
            $userArray = [];
            $userLeaveDays = [];
            $overlappedUsers = [];


            $users = DB::table('users')->select('users.Id',DB::raw('users.Id as UserId'),'users.Name','users.Working_Days', 'users.StaffId', 'users.HolidayTerritoryId')->whereIn('Id',$userIds)->get();
            foreach($users as $user) {
                $userArray[$user->Id] = $user;
                $userLeaveDays[$user->Id] = $this->calculateLeaveDaysWithPeriod($request, $user->Working_Days, $user->HolidayTerritoryId);
            }

            $subscribers = DB::table('notificationtype')
                    ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
                    ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
                    ->where('notificationtype.Id','=',25)
                    ->get();
            // $days=$this->calculateprocessdays($input["Start_Date"]);
            // $No_of_Days=$this->calculateLeaveDaysWithPeriod($request);

            // Loop and apply leave to each id
            foreach($users as $user) {

                if ($this->isLeaveDatesOverlapped($request, $user)) {
                    $overlappedUsers[$user->Id] = $userArray[$user->Id];
                    continue;
                }

                // to check if AL got balance
                $originalLeaveType = $request->Leave_Type;


                if ($originalLeaveType == 'Annual Leave' || $originalLeaveType == 'Emergency Leave' || $originalLeaveType == '1 Hour Time Off' || $originalLeaveType == '2 Hours Time Off') {

                    // check if there is available balance for annual leave
                    // $balance = $this->checkLeaveBalance($user->Id, 'Annual Leave')[0]->Total_Leave_Balance;
                    //
                    if ($originalLeaveType == 'Emergency Leave' || $originalLeaveType == '1 Hour Time Off' || $originalLeaveType == '2 Hours Time Off') {
                        $request->merge(['Leave_Type' => 'Annual Leave']);
                    }

                    $bal = $this->checkLeaveBalance($user->Id, $request->Leave_Type);
                    if (! empty($bal)) {
                        $balance = $bal[0]->Total_Leave_Balance;

                    } else {
                        // leave out of balance
                        $balance = 0;
                    }

                    // if balance is not enough or none available
                    if ($balance < $userLeaveDays[$user->Id]) {

                        // if some balance is available
                        if($balance > 0) {
                            // keep EL or Time off if AL got balance
                            if (($originalLeaveType == 'Emergency Leave' || $originalLeaveType == '1 Hour Time Off' || $originalLeaveType == '2 Hours Time Off') && $request->Leave_Type == 'Annual Leave') {
                                $request->merge(['Leave_Type' => $originalLeaveType]);
                            }
                            $input = $request->all();
                            $this->applyAnnualLeaveWithBalance($request, $balance, $userLeaveDays[$user->Id], $user->Id, $userArray[$user->Id], $subscribers);
                        } else {
                            // since there is no balance apply all days as unpaid
                            if ($originalLeaveType == 'Emergency Leave') {
                                $request->merge(['Leave_Type' => 'Unpaid Leave']);
                                $request->merge(['Reason' => '[EL to UL] ' . $request->Reason]);
                            } elseif($originalLeaveType == '1 Hour Time Off' || $originalLeaveType == '2 Hours Time Off') {
                                $request->merge(['Leave_Type' => 'Unpaid Leave']);
                                $request->merge(['Reason' => '[Time Off to UL] ' . $request->Reason]);
                            }
                            $input = $request->all();
                            $this->adminApplyLeave($input, $request, 'Unpaid Leave', $user->Id, $userArray[$user->Id], $userLeaveDays[$user->Id], $subscribers);
                        }

                    } else {
                        // keep EL or Time off if AL got balance
                        if (($originalLeaveType == 'Emergency Leave' || $originalLeaveType == '1 Hour Time Off' || $originalLeaveType == '2 Hours Time Off') && $request->Leave_Type == 'Annual Leave') {
                            $request->merge(['Leave_Type' => $originalLeaveType]);
                        }
                        $input = $request->all();
                        $this->adminApplyLeave($input, $request, $input['Leave_Type'], $user->Id, $userArray[$user->Id], $userLeaveDays[$user->Id], $subscribers);
                    }

                } else {
                    $input = $request->all();
                    $this->adminApplyLeave($input, $request, $input['Leave_Type'], $user->Id, $userArray[$user->Id], $userLeaveDays[$user->Id], $subscribers);
                }

            }

            // if there is user overlapped, need to notify the admin
            if (! empty($overlappedUsers)) {
                return json_encode(['overlappedUsers' => $overlappedUsers]);
            } else {
                return 1;
            }
        } else {

            return json_encode($validator->errors()->toArray());
        }
    }

    protected function adminApplyLeave($input, $request, $leaveType, $userId, $user, $No_Of_Days, $subscribers) {
        $me=(new CommonController)->get_current_user();
        $id = DB::table('leaves')->insertGetId([
            'UserId' => $userId,
            'Leave_Type' => $leaveType,
            'Start_Date' => $input["Start_Date"],
            'End_Date' => $input["End_Date"],
            'ProjectId' => $input["Project"],
            'No_Of_Days' => $No_Of_Days,
            'Reason' => $input["Reason"]
        ]);


        $this->saveLeaveDaysWithPeriod($request, $id, $user);

        DB::table('leavestatuses')->insert([
            'LeaveId' => $id,
            'UserId' => $me->UserId,
            'Leave_Status' => 'Final Approved',
         ]);

        $filenames = "";
        $type = "Leave";
        $uploadcount = count($request->file('attachment'));
        $attachmentUrl = null;

        if ($request->hasFile('attachment')) {

            for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                if(! isset($uploadedFileLocations[$i])) {
                    $file = $request->file('attachment')[$i];
                    $destinationPath=public_path()."/private/upload/Leave";
                    $extension = $file->getClientOriginalExtension();
                    $originalName=$file->getClientOriginalName();
                    // try {

                    // } catch (\Exception $e) {
                    //  dd($uploadedFileLocations);
                    // }
                    $fileName=time()."_".$i.".".$extension;
                    $fileSize=$file->getSize();
                    $upload_success = $file->move($destinationPath, $fileName);
                    $uploadedFileLocations[$i] = $destinationPath . '/' . $fileName;
                    $uploadedSizes[$i] = $fileSize;
                    $uploadedNames[$i] = $fileName;
                    $insert=DB::table('files')->insertGetId(
                        ['Type' => $type,
                         'TargetId' => $id,
                         'File_Name' => $originalName,
                         'File_Size' => $fileSize,
                         'Web_Path' => '/private/upload/Leave/'.$fileName
                        ]
                    );
                } else {
                    $insert=DB::table('files')->insertGetId(
                        ['Type' => $type,
                         'TargetId' => $id,
                         'File_Name' => $uploadedNames[$i],
                         'File_Size' => $uploadedSizes[$i],
                         'Web_Path' => $uploadedFileLocations[$i]
                        ]
                    );
                }
                $attachmentUrl = url('/private/upload/Leave/'.$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            $filenames=substr($filenames, 0, strlen($filenames)-1);

            //return '/private/upload/'.$fileName;
        }

        $leavedetail = DB::table('leaves')
        ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','projects.Project_Name','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date')
        ->orderBy('leavestatuses.Id','desc')
        ->where('leaves.Id', '=',$id)
        ->get();

        $notify = DB::table('users')
        ->whereIn('Id', [$userId, $me->UserId])
        ->get();

        $emails = array();

        foreach ($subscribers as $subscriber) {
            $NotificationSubject=$subscriber->Notification_Subject;
            if ($subscriber->Company_Email!="") {
                array_push($emails,$subscriber->Company_Email);
            } else {
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

        $periods = DB::table('leave_terms')->where('leave_terms.Leave_Id', $id)->get();
        Mail::send('emails.leavestatuswithperiod', ['leavedetail' => $leavedetail, 'periods'=>$periods, 'attachmentUrl' => $attachmentUrl], function($message) use ($emails,$NotificationSubject, $user) {
            $emails = array_filter($emails);
            array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
            $message->to($emails)->subject($NotificationSubject.' ['.$user->Name.']');
        });
    }

    /**
     * Apply for leave with some balace
     */
    protected function applyAnnualLeaveWithBalance(Request $request, $balance, $totalDays, $userId, $user, $subscribers)
    {
        // start date
        $start = strtotime($request->Start_Date);
        // end date
        $end = strtotime($request->End_Date);

        $periods = $request->Leave_Period;

        $counter = 0;
        $No_of_Days = 0;

        // loop until the end date
        while ($start <= $end && $No_of_Days < $balance) {

            // get start date
            $current = date("d-M-Y", $start);

            // get the holidays dates that in ranges of start and end dates
            // $holiday = DB::table('holidays')
            //         ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
            //         ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
            //         ->get();
            $holiday = $this->getCurrentDateHoliday($current, $user->HolidayTerritoryId);

            if (count($holiday) > 0) {

            } else {
                // get Numeric representation of the day of
                // the week,  0 (for Sunday), 6(for Saturday)
                $day_type = date("w", $start);


                // if saturday and working days is more than 5
                if ($day_type == 6 && $user->Working_Days > 5) {
                    if ($request->Leave_Type == '1 Hour Time Off') {
                        if ($No_of_Days + 0.125 > $balance) {
                            break;
                        }
                        $No_of_Days += 0.125;
                    } else if ($request->Leave_Type == '2 Hours Time Off') {

                        if ($No_of_Days + 0.25 > $balance) {
                            break;
                        }
                        $No_of_Days += 0.25;
                    } else {

                        if ($user->Working_Days >= 6) {

                            if ($periods[$counter] == 'AM' || $periods[$counter] == 'PM') {

                                if ($No_of_Days + 0.5 > $balance) {
                                    break;
                                }
                                $No_of_Days += 0.5;
                            } else {
                                 if ($No_of_Days + 1 > $balance) {
                                    break;
                                }
                                $No_of_Days += 1;
                            }

                        } else {

                            if ($No_of_Days + 0.5 > $balance) {
                                break;
                            }
                            $No_of_Days += 0.5;
                        }
                    }
                } else {
                    if ($request->Leave_Type == '1 Hour Time Off') {
                         if ($No_of_Days + 0.125 > $balance) {
                            break;
                        }
                        $No_of_Days += 0.125;
                    } else if ($request->Leave_Type == '2 Hours Time Off') {

                         if ($No_of_Days + 0.25 > $balance) {
                            break;
                        }
                        $No_of_Days += 0.25;
                    } else {
                        if ($periods[$counter] == 'AM' || $periods[$counter] == 'PM') {

                            if ($No_of_Days + 0.5 > $balance) {
                                break;
                            }
                            $No_of_Days += 0.5;
                        } else {
                             if ($No_of_Days + 1 > $balance) {
                                break;
                            }
                            $No_of_Days += 1;
                        }
                    }
                }
            }

            // move to the next day / +1 day
            $start = strtotime("+1 day", $start);
            $counter += 1;
        }

        if (strtotime($request->Start_Date) != $start) {

            $request1 = clone $request;
            $allPeriods = $request1->Leave_Period;
            $periods = array_slice($allPeriods, 0, $counter);
            $periods2 = array_slice($allPeriods, $counter);

            $request2 = clone $request;
            $request2->merge(['Start_Date' =>  date("d-M-Y",$start)]);
            $request1->merge(['End_Date' => date("d-M-Y",strtotime("-1 day", $start))]);

            $request1->merge(['Leave_Period' => $periods]);
            $request2->merge(['Leave_Period' => $periods2]);

            $input = $request1->all();

            // apply as annual leave for the balance
            $this->adminApplyLeave($input, $request1, $request->Leave_Type, $userId, $user, $No_of_Days, $subscribers);


            if ($request2->Leave_Type == 'Emergency Leave') {
                $request2->merge(['Reason' => '[EL to UL] ' . $request2->Reason]);
            } elseif($request2->Leave_Type == '1 Hour Time Off' || $request2->Leave_Type == '2 Hours Time Off') {
                $request2->merge(['Reason' => '[Time Off to UL] ' . $request2->Reason]);
            }

            $input2 = $request2->all();


            // apply as unpaid leave for no balance
            $this->adminApplyLeave($input2, $request2, 'Unpaid Leave', $userId, $user, $totalDays - $No_of_Days, $subscribers);

        } else {
            if ($request->Leave_Type == 'Emergency Leave') {
                $request->merge(['Reason' => '[EL to UL] ' . $request->Reason]);
            } elseif($request->Leave_Type == '1 Hour Time Off' || $request->Leave_Type == '2 Hours Time Off') {
                $request->merge(['Reason' => '[Time Off to UL] ' . $request->Reason]);
            }
            $input = $request->all();
            // apply as unpaid leave for no balance
            $this->adminApplyLeave($input, $request, 'Unpaid Leave', $userId, $user, $totalDays, $subscribers);

        }


    }

    /**
     * Convert UL to AL if have some balance
     */
    protected function convertUnpaidLeaveToAnnualLeave(Request $request, $balance, $totalDays, $userId, $me)
    {
        // start date
        $start = strtotime($request->Start_Date);
        // end date
        $end = strtotime($request->End_Date);

        $periods = $request->Leave_Period;

        $counter = 0;
        $No_of_Days = 0;

        // loop until the end date
        while ($start <= $end && $No_of_Days < $balance) {

            // get start date
            $current = date("d-M-Y", $start);

            // get the holidays dates that in ranges of start and end dates
            // $holiday = DB::table('holidays')
            //         ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
            //         ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
            //         ->get();
            $holiday = $this->getCurrentDateHoliday($current, $me->HolidayTerritoryId);

            if (count($holiday) > 0) {

            } else {
                // get Numeric representation of the day of
                // the week,  0 (for Sunday), 6(for Saturday)
                $day_type = date("w", $start);


                // if saturday and working days is more than 5
                // if saturday and working days is more than 5
                if ($day_type == 6 && $me->Working_Days > 5) {

                    if ($me->Working_Days >= 6) {

                        if ($periods[$counter] == 'AM' || $periods[$counter] == 'PM') {

                            if ($No_of_Days + 0.5 > $balance) {
                                break;
                            }
                            $No_of_Days += 0.5;
                        } else {
                             if ($No_of_Days + 1 > $balance) {
                                break;
                            }
                            $No_of_Days += 1;
                        }

                    } else {

                        if ($No_of_Days + 0.5 > $balance) {
                            break;
                        }
                        $No_of_Days += 0.5;
                    }

                } else {

                    if ($periods[$counter] == 'AM' || $periods[$counter] == 'PM') {

                        if ($No_of_Days + 0.5 > $balance) {
                            break;
                        }
                        $No_of_Days += 0.5;
                    } else {
                         if ($No_of_Days + 1 > $balance) {
                            break;
                        }
                        $No_of_Days += 1;
                    }

                }
            }

            // move to the next day / +1 day
            $start = strtotime("+1 day", $start);
            $counter += 1;
        }


        if (strtotime($request->Start_Date) != $start) {

            $request1 = clone $request;
            $allPeriods = $request1->Leave_Period;
            $periods = array_slice($allPeriods, 0, $counter);
            $periods2 = array_slice($allPeriods, $counter);

            $request2 = clone $request;
            $request2->merge(['Start_Date' =>  date("d-M-Y",$start)]);
            $request1->merge(['End_Date' => date("d-M-Y",strtotime("-1 day", $start))]);

            $request1->merge(['Leave_Period' => $periods]);
            $request2->merge(['Leave_Period' => $periods2]);

            $input = $request1->all();

            $startYear = DateTime::createFromFormat('d-M-Y', $input['Start_Date'])->format("Y");
            $endYear = DateTime::createFromFormat('d-M-Y', $input['End_Date'])->format("Y");

            $request1->merge(['Reason' => '[UL to AL] ' . $request2->Reason]);
            if ($startYear != $endYear) {
                $this->splitLeaveApplication($request1, $me, $startYear, $endYear, 'Annual Leave');
            } else {
                $this->splitLeaveApplicationByCutOffDate($request1, $me, $No_of_Days, 'Annual Leave');
            }


            $input2 = $request2->all();
            $request2->merge(['Leave_Type' => 'Unpaid Leave']);
            $startYear2 = DateTime::createFromFormat('d-M-Y', $input2['Start_Date'])->format("Y");
            $endYear2 = DateTime::createFromFormat('d-M-Y', $input2['End_Date'])->format("Y");

            if ($startYear2 != $endYear2) {
                $this->splitLeaveApplication($request2, $me, $startYear2, $endYear2, 'Unpaid Leave');
            } else {
                $this->splitLeaveApplicationByCutOffDate($request2, $me, $totalDays - $No_of_Days, 'Unpaid Leave');
            }



        } else {
            $input = $request->all();
            // apply as unpaid leave for no balance
            $request->merge(['Leave_Type' => 'Unpaid Leave']);
            $startYear = DateTime::createFromFormat('d-M-Y', $input['Start_Date'])->format("Y");
            $endYear = DateTime::createFromFormat('d-M-Y', $input['End_Date'])->format("Y");

            if ($startYear != $endYear) {
                $this->splitLeaveApplication($request, $me, $startYear, $endYear, 'Unpaid Leave');
            } else {
                $this->splitLeaveApplicationByCutOffDate($request, $me, $totalDays, 'Unpaid Leave');
            }

        }


        return 1;

    }

}
