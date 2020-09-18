<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Input;
use DateTime;

class LeaveController extends Controller {

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
            $request->merge(['Leave_Type' => 'Unpaid Leave']);
            // apply as unpaid leave for no balance
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

    /**
     * Get leave balance
     */
    public function getMyLeaveBalance()
    {
        $me = JWTAuth::parseToken()->authenticate();
        $balance = $this->checkLeaveBalance($me->Id);

        $arrMyLeaveBalance = [];

        foreach($balance as $bal) {
            if ($bal->Leave_Type == 'Annual Leave') {
                $replacementleave = clone $bal;

                $bal->Total_Leave_Balance = $bal->Leave_Balance + $bal->Pending_Leave;

                if ($bal->Total_Leave_Balance < 0) {
                    $replacementleave->Total_Leave_Balance = $bal->Replacement_Balance + $bal->Replacement_Pending + $bal->Total_Leave_Balance;
                    $bal->Total_Leave_Balance = $bal->Leave_Balance + ($bal->Replacement_Balance - $replacementleave->Total_Leave_Balance) + $bal->Pending_Leave;
                } else {
                    $replacementleave->Total_Leave_Balance = $bal->Replacement_Balance + $bal->Replacement_Pending;
                    $bal->Total_Leave_Balance = $bal->Leave_Balance + $bal->Pending_Leave;
                }
                $replacementleave->Leave_Type = 'Replacement Leave';

                array_push($arrMyLeaveBalance, $replacementleave);
                array_push($arrMyLeaveBalance, $bal);

            } else {
                $bal->Total_Leave_Balance = $bal->Leave_Balance + $bal->Pending_Leave;
                array_push($arrMyLeaveBalance, $bal);
            }


            // $bal = clone $bal;
            // $bal->Total_Leave_Balance = $bal->Pending_Leave;
            // $bal->Leave_Type = 'Pending ' . $bal->Leave_Type;
            // array_push($arrMyLeaveBalance, $bal);
        }

        return response()->json($arrMyLeaveBalance);
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
                THEN 5*ROUND(leaveentitlements.Days/12*service.Months_of_Service/5 ,1)
            WHEN leaveentitlements.Leave_Type = 'Marriage Leave' THEN
                IF(service.Marital_Status LIKE '%Married%',0,leaveentitlements.Days)
            WHEN leaveentitlements.Leave_Type = 'Paternity Leave' THEN
                IF(service.Marital_Status LIKE '%Married' AND service.Gender = 'Male', leaveentitlements.Days, 0)
            WHEN leaveentitlements.Leave_Type = 'Maternity Leave' THEN
                IF(service.Marital_Status LIKE '%Married' AND service.Gender = 'Female', leaveentitlements.Days, 0)
            ELSE
                leaveentitlements.Days
        END as Current_Entitlement,
        CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave' THEN IF(replacementadjustments.Adjustment_Value,replacementadjustments.Adjustment_Value,0) ELSE 0 END as Replacement_Adjustment,
        IF(leaveadjustments.Adjustment_Value,leaveadjustments.Adjustment_Value,0) as Adjusted,
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
        CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave' THEN $replacementLeave ELSE 0 END as Replacement,

        CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave'
            THEN IF(replacementadjustments.Adjustment_Value,replacementadjustments.Adjustment_Value,0) - $replacementLeave
            ELSE 0
        END as Replacement_Balance,

        CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave'
            THEN (SELECT Current_Entitlement + Adjusted + IF(Carried_Forward,Carried_Forward,0) - Leave_Taken)
            ELSE (SELECT Current_Entitlement + Adjusted - IF(Total_Leave_Taken,Total_Leave_Taken,0))
        END as Leave_Balance,

        CASE WHEN leaveentitlements.Leave_Type = 'Annual Leave'
            THEN (SELECT Leave_Balance + Replacement_Balance)
            ELSE (SELECT Leave_Balance)
        END as Total_Leave_Balance,

        IF(pending.Replacement_Pending_Leave,pending.Replacement_Pending_Leave,0) as Replacement_Pending,
        pending.Pending_Leave

        FROM users
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
            WHERE users.Id = $userId
        ) as service ON users.Id = service.UserId
        LEFT JOIN (SELECT leaveentitlements.*,tblEnt.MaxYear FROM leaveentitlements LEFT JOIN
                        (SELECT leaveentitlements.Grade, Leave_Type, MAX(Year) as MaxYear
                            FROM leaveentitlements
                            GROUP BY Grade, Leave_Type ) as tblEnt
                        ON leaveentitlements.Grade = tblEnt.Grade and leaveentitlements.Leave_Type = tblEnt.Leave_Type) as leaveentitlements
                ON leaveentitlements.Grade = users.Grade
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
     * Get the leaves terms
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
     * Get the current holidays for territory id.
     * Fallback to holidays table if none found
     * @param  int      $holidayTerritoryId
     * @return array
     */
    protected function getCurrentDateHoliday($current, $holidayTerritoryId = null) {

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
     * Calculate Leave Day with Period
     * @param  \Illuminate\Http\Request $request
     * @param  int $workingDays
     * @return int
     */

    public function calculateLeaveDaysWithPeriod(Request $request, $workingDays = 5, $holidayTerritoryId = null)
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

            $holiday = $this->getCurrentDateHoliday($current, $holidayTerritoryId);

            // if there is holiday for $current minus 1 day from datediff
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
                        $datediff -= 1;
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

                        // elseif ($periods[$counter] == '1 Hour') {
                        //     // minus 7 hours
                        //     $datediff -= 0.875;
                        // } elseif ($periods[$counter] == '2 Hours') {
                        //     // minus 6 hours
                        //     $datediff -= 0.75;
                        // }
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
    public function saveLeaveDaysWithPeriod(Request $request, $leaveId, $me, $originalLeaveType)
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

               $holiday = $this->getCurrentDateHoliday($current, $holidayTerritoryId);

                // if there is holiday for $current minus 1 day from datediff
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
                            $period = 'Non-working Day';
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
                            //     $period = $periods[$counter];
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
        $me = JWTAuth::parseToken()->authenticate();

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
                // get the holidays dates that in ranges of start and end dates

                // $holiday = DB::table('holidays')
                //         ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //         ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                //         ->get();
                $holiday = $this->getCurrentDateHoliday($current, $holidayTerritoryId);

                // if there is holiday for $current minus 1 day from datediff
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
                                // for 6 wd
                                if ($me->Working_Days >= 6) {
                                    // push an array to $leaveList with the details of what day is it, -1 for Workday on weekends
                                    array_push($leaveList, ['Date' => $current, 'Day_Type_Description' => 'Workday', 'Period' => null, 'Day_Type' => 1]);
                                }
                                // for 5.5 wd
                                else {
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
     * To check if dates overlapped
     * @param  Request  $request
     * @return boolean
     */
    public function isLeaveDatesOverlapped(Request $request, $me, $leaveType = null)
    {
        $start_date = $request->Start_Date;
        $end_date = $request->End_Date;
        $userId = $me->Id;

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
            -- INNER JOIN (select Max(Id) as maxid,LeaveId from leavestatuses WHERE Leave_Status LIKE '%Final Approved%' Group By LeaveId) as max ON  max.LeaveId=leaves.Id
            LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
            LEFT JOIN leavestatuses ON leavestatuses.Id = max.maxid
            WHERE leaves.UserId = $userId AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=$year AND leaves.Leave_Type = '$leaveType' AND (Leave_Status LIKE '%Approved%' OR Leave_Status LIKE '%Pending%')
            $beforeMonthFilter
            GROUP BY leaves.Leave_Type
        ");

        return $leaveTaken ? $leaveTaken[0]->Total_Leave_Taken : 0;
    }

    // Apply leave with period
    public function newleaveWithPeriod(Request $request)
    {
        $me = JWTAuth::parseToken()->authenticate();

        $input = $request->all();


        if ($input["Leave_Type"]=="Compassionate Leave" ||
            $input["Leave_Type"]=="Medical Leave" ||
            $input["Leave_Type"]=="Marriage Leave" ||
            $input["Leave_Type"]=="Hospitalization Leave") {


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
        // ->where('leaves.UserId','=',$me->Id)
        // ->whereRaw('leavestatuses.Leave_Status!="Cancelled" AND leavestatuses.Leave_Status not like "%Rejected%"')
        // ->whereRaw('((str_to_date("'.$input["Start_Date"].'","%d-%M-%Y") between str_to_date(leaves.Start_Date,"%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")) or (str_to_date("'.$input["End_Date"].'","%d-%M-%Y") between str_to_date(leaves.Start_Date,"%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")) or (str_to_date(leaves.Start_Date,"%d-%M-%Y") between str_to_date("'.$input["Start_Date"].'","%d-%M-%Y") AND str_to_date("'.$input["End_Date"].'","%d-%M-%Y")) or (str_to_date(leaves.End_Date,"%d-%M-%Y") between str_to_date("'.$input["Start_Date"].'","%d-%M-%Y") AND str_to_date("'.$input["End_Date"].'","%d-%M-%Y")))')
        // ->get();


        // if(!$leaves)
        // {
            // $days=$this->calculateleavedays($input);

            $No_of_Days=$this->calculateLeaveDaysWithPeriod($request, $me->Working_Days, $me->HolidayTerritoryId);

            if ($No_of_Days <= 0) {
                return json_encode([
                    'Invalid Days' => [
                        'The number of days is 0. Please check or select the leave term before submitting.'
                    ]
                ]);
            }

            // if ($this->isLeaveOutOfBalance($me->Id,$request->Leave_Type, $No_of_Days)) {
            //     return json_encode([
            //         'Out of balance' => [
            //             'Not enough balance. You have used all your leave balance for this type of leave'
            //         ]
            //     ]);
            // }
            if ($request->Leave_Type == '1 Hour Time Off' || $request->Leave_Type == '2 Hours Time Off') {
                $start    = strtotime($request->Start_Date);
                $firstMonthStart = strtotime("+20 day", strtotime("first day of last month", $start));
                $firstMonthEnd = strtotime("+19 day", strtotime("first day of this month", $start));
                $secondMonthStart = strtotime("+20 day", strtotime("first day of this month", $start));
                $secondMonthEnd = strtotime("+19 day", strtotime("first day of next month", $start));

                if ($start < $secondMonthStart) {
                    $timeoff = $this->getTotalTimeoff($me->Id,date("d-M-Y",$firstMonthStart),date("d-M-Y",$firstMonthEnd));
                } else {
                    $timeoff = $this->getTotalTimeoff($me->Id,date("d-M-Y",$secondMonthStart),date("d-M-Y",$secondMonthEnd));
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
            if ($this->isLeaveOutOfBalance($me->Id,$request->Leave_Type, $No_of_Days)) {

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

                $bal = $this->checkLeaveBalance($me->Id, $request->Leave_Type);

                if (! empty($bal)) {
                    $balance = $bal[0]->Total_Leave_Balance;
                } else {
                    // leave out of balance
                    $balance = 0;
                }

                // if balance is not enough
                if ($balance < $No_of_Days && $balance > 0) {
                    // convert if AL got balance
                    return $this->convertUnpaidLeaveToAnnualLeave($request, $balance, $No_of_Days, $me->Id, $me);
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

                // return $this->insertLeaveAndSendEmail($request, $me, $No_of_Days);
                return $this->splitLeaveApplicationByCutOffDate($request, $me, $No_of_Days, $originalLeaveType);
            }

        // }
        // else {
        //     return -1;
        // }
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
    protected function splitLeaveApplication($request, $me, $startYear, $endYear, $originalLeaveType)
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

        // $this->insertLeaveAndSendEmail($request, $me, $No_of_Days);
        // $this->insertLeaveAndSendEmail($requestNextYear, $me, $No_of_Days_Next_Year);
        $this->splitLeaveApplicationByCutOffDate($request, $me, $No_of_Days, $originalLeaveType);
        $this->splitLeaveApplicationByCutOffDate($requestNextYear, $me, $No_of_Days_Next_Year, $originalLeaveType);

        return 1;
    }

    /**
     * Change Unverified Medical Leave to UL
     */
    public function lastweekmedicalleavecheck()
    {
        $lastweek = date('d-M-Y',strtotime('-7 days'));

        $medicalleaves = DB::table('leaves')
        ->select('leaves.Id')
        // ->leftJoin(DB::raw('(SELECT max(id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max'),'max.LeaveId','=','leaves.Id')
        // ->leftJoin('leavestatuses','leavestatuses.Id','=','max.maxid')
        ->where('Leave_Type','Medical Leave')
        ->whereRaw('(Verified_By_HR IS NULL OR Verified_By_HR = 0)')
        ->where('Start_Date',$lastweek)
        ->lists('leaves.Id');

        DB::table('leaves')->whereIn('Id', $medicalleaves)->update([
           'Leave_Type' => 'Unpaid Leave'
        ]);

        return 1;
    }


    public function calculateprocessdays($start_date)
    {

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
                 if ($day_type=="0" || $day_type=="6")
                 {
                     $datediff-=1;
                 }
             }

             $start=strtotime("+1 day", $start);

    }

        return $datediff;

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
    protected function insertLeaveAndSendEmail(Request $request, $me, $No_of_Days, $originalLeaveType)
    {
        $input = $request->all();
        $days  = $this->calculateprocessdays($input["Start_Date"]);

        if ($input["Leave_Type"] != "Compassionate Leave" && $days < 3) {

            $id = DB::table('leaves')->insertGetId([
                'UserId' => $me->Id,
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
                'UserId' => $me->Id,
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
        ->whereIn('Id', [$me->Id, $input["Approver"]])
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
        $notifyplayerid=array();
        foreach ($notify as $user) {

            if ($user->Company_Email!="") {
                array_push($emails,$user->Company_Email);
            } else {
                array_push($emails,$user->Personal_Email);
            }

            if ($me->Id != $user->Id || count($notify) == 1) {
                 DB::table('notificationstatus')->insert([
                'userid' => $user->Id,
                'type' => 'Pending Leave',
                'seen' => 0,
                'TargetId' => $id
                ]);

                 if ($user->Player_Id){
                      array_push($notifyplayerid,$user->Player_Id);

                    }
            }


        }

        if(!empty($notifyplayerid))
        {
            $this->sendSubmit($notifyplayerid);
        }

        $periods = DB::table('leave_terms')->where('leave_terms.Leave_Id', $id)->get();

        // Mail::send('emails.leaveapplicationwithperiod', ['leavedetail' => $leavedetail,'periods'=>$periods, 'attachmentUrl' => $attachmentUrl], function($message) use ($emails,$me,$NotificationSubject)
        // {
        //     array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
        //     $emails = array_filter($emails);
        //     $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
        // });

        return 1;
    }

    public function newleave(Request $request)
    {

        $me = JWTAuth::parseToken()->authenticate();

        $input = $request->all();

        //check leave Balance
        $thisyear=date('Y');
        $lastyear=$thisyear-1;
        $currentmonth=date('n');

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
        WHERE users.Id = ".$me->Id."
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
        WHERE users.Id=".$me->Id."
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
        //end of check leave balance

        $days=$this->calculateleavedays($input);

        //not enough leave balance
        // if($input["Leave_Type"]=="Annual Leave")
        // {
        //  if($days>$annualbalance)
        //  {
        //      return -999;
        //  }
        // }
        // elseif($input["Leave_Type"]=="Medical Leave")
        // {
        //  if($days>$sickbal)
        //  {
        //      return -999;
        //  }
        // }

        $id=DB::table('leaves')->insertGetId([
            'UserId' => $me->Id,
            'Leave_Type' => $input["Leave_Type"],
            'Leave_Term' => $input["Leave_Term"],
            'Start_Date' => $input["Start_Date"],
            'End_Date' => $input["End_Date"],
            'Reason' => $input["Reason"],
            'Cover_By' => $input["Cover_By"],
            'ProjectId' => $input["ProjectId"]
        ]);

        DB::table('leavestatuses')->insert([
            'LeaveId' => $id,
            'UserId' => $input["Approver"],
            'Leave_Status' =>"Pending Approval"
        ]);

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

        $result= DB::table('leaves')
        ->where('Id', $id)
        ->update(array(
            'No_of_Days' =>  $days,
        ));

        $leavedetail = DB::table('leaves')
        ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','projects.Project_Name','leaves.created_at as Application_Date','approver.Name as Approver')
        ->orderBy('leavestatuses.Id','desc')
        ->where('leaves.Id', '=',$id)
        ->first();

        $notify = DB::table('users')
        ->whereIn('Id', [$me->Id, $input["Approver"]])
        ->get();

        $subscribers = DB::table('notificationsubscriber')
        ->leftJoin('notificationtype','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
        ->where('NotificationTypeId','=',25)
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

        $notifyplayerid=array();
        foreach ($notify as $user) {

            if ($user->Company_Email!="") {
                array_push($emails,$user->Company_Email);
            } else {
                array_push($emails,$user->Personal_Email);
            }

            if ($me->Id != $user->Id || count($notify) == 1) {
                 DB::table('notificationstatus')->insert([
                'userid' => $user->Id,
                'type' => 'Pending Leave',
                'seen' => 0,
                'TargetId' => $id
                ]);
            }

            if ($user->Player_Id){
                array_push($notifyplayerid,$user->Player_Id);
            }
        }

        // return $notifyplayerid;
        if(!empty($notifyplayerid))
        {
            $this->sendSubmit($notifyplayerid);
        }

        // Mail::send('emails.leaveapplication', ['leavedetail' => $leavedetail, 'attachmentUrl' => $attachmentUrl], function($message) use ($emails,$me,$NotificationSubject)
        // {
        //  $emails = array_filter($emails);
        //  array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
        //  $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
        // });

        return $id;
    }

    public function getallleaves(Request $request)
    {

        $me = JWTAuth::parseToken()->authenticate();

        $input = $request->all();

        $myleave = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))

        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))

        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->leftJoin(DB::raw('(SELECT leave_terms.Leave_Id, GROUP_CONCAT(DISTINCT CONCAT(SUBSTR(leave_terms.Leave_Date,1,6),\' \',leave_terms.Leave_Period) ORDER BY leave_terms.Id SEPARATOR \',\') as Terms FROM leave_terms Group By leave_terms.Leave_Id) as leave_terms'), 'leave_terms.Leave_Id', '=', 'leaves.Id')
        ->select('leaves.Id','leavestatuses.Id as StatusId','applicant.Name','leaves.Leave_Type',DB::raw("CONCAT(leaves.Leave_Term, leave_terms.Terms) as Leave_Term"),'leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.updated_at as Review_Date','leavestatuses.Comment','files.Web_Path','approver.Id as AppId')
        ->where('leaves.UserId', '=', $me->Id)
        // ->where('leavestatuses.Leave_Status', 'like','%Final Rejected%')
        ->orderBy(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'),'desc')
        ->get();

        return json_encode($myleave);
    }

    public function getleaves(Request $request)
    {

        $me = JWTAuth::parseToken()->authenticate();

        $input = $request->all();

        $myleave = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))

        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))

        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->leftJoin(DB::raw('(SELECT leave_terms.Leave_Id, GROUP_CONCAT(DISTINCT CONCAT(SUBSTR(leave_terms.Leave_Date,1,6),\' \',leave_terms.Leave_Period) ORDER BY leave_terms.Id SEPARATOR \',\') as Terms FROM leave_terms Group By leave_terms.Leave_Id) as leave_terms'), 'leave_terms.Leave_Id', '=', 'leaves.Id')
        ->select('leaves.Id','leavestatuses.Id as StatusId','applicant.Name','leaves.Leave_Type',DB::raw("CONCAT(leaves.Leave_Term, leave_terms.Terms) as Leave_Term"),'leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.updated_at as Review_Date','leavestatuses.Comment','files.Web_Path')
        ->where('leaves.UserId', '=', $me->Id)
        ->where('leavestatuses.Leave_Status', 'like','%Pending Approval%')
      ->orderBy(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'),'desc')
        ->get();

        dd($myleave);

        return json_encode($myleave);
    }


    public function getleavesrejected(Request $request)
    {

        $me = JWTAuth::parseToken()->authenticate();

        $input = $request->all();

        $myleave = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))

        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))

        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leaves.Id','leavestatuses.Id as StatusId','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.updated_at as Review_Date','leavestatuses.Comment','files.Web_Path')
        ->where('leaves.UserId', '=', $me->Id)
        ->where('leavestatuses.Leave_Status', 'like','%Final Rejected%')
        ->orderBy(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'),'desc')
        ->get();

        return json_encode($myleave);
    }


    public function getleavescancelled(Request $request)
    {

        $me = JWTAuth::parseToken()->authenticate();

        $input = $request->all();

        $myleave = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))

        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))

        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leaves.Id','leavestatuses.Id as StatusId','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.updated_at as Review_Date','leavestatuses.Comment','files.Web_Path')
        ->where('leaves.UserId', '=', $me->Id)
        ->where('leavestatuses.Leave_Status', 'like','%Cancelled%')
        ->orderBy(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'),'desc')
        ->get();

        return json_encode($myleave);
    }


    public function getleavesapproved(Request $request)
    {

        $me = JWTAuth::parseToken()->authenticate();

        $input = $request->all();

        $myleave = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))

        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))

        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leaves.Id','leavestatuses.Id as StatusId','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.updated_at as Review_Date','leavestatuses.Comment','files.Web_Path')
        ->where('leaves.UserId', '=', $me->Id)
        ->where('leavestatuses.Leave_Status', 'like','%Final Approved%')
        ->orderBy(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'),'desc')
        ->get();

        return json_encode($myleave);
    }


    public function adminapproval(Request $request)
    {
        $me = JWTAuth::parseToken()->authenticate();
        $input = $request->all();

        $Id = explode(",", $input["Id"]);
        $leaves = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leaves.Id as LeaveId','leaves.UserId','applicant.Name','leavestatuses.Id as StatusId','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Id as ApproverId','approver.Name as Approver')
        ->whereIn('leaves.Id', $Id)
        ->orderBy('leavestatuses.Id','desc')
        ->get();

        $mylevel = DB::table('approvalsettings')
        ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
        ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
        ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
        ->where('approvalsettings.Type', '=', 'Leave')
        ->where('approvalsettings.UserId', '=', $me->Id)

        // ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
        ->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
        ->orderBy('projects.Project_Name','asc')
        ->first();

        $status="Approved";

        if($mylevel){

            if ($mylevel->Level=="1st Approval"){
                $status="1st Approved";
            }

            if ($mylevel->Level=="2nd Approval"){
                $status="2nd Approved";
            }

            if ($mylevel->Level=="3rd Approval"){
                $status="3rd Approved";
            }

            if ($mylevel->Level=="4th Approval"){
                $status="4th Approved";
            }

            if ($mylevel->Level=="5th Approval"){
                $status="5th Approved";
            }

            if ($mylevel->Level=="Final Approval"){
                $status="Final Approved";
            }
        }


  //       $status="Rejected";

  //       if($mylevel){

        //  if ($mylevel->Level=="1st Approval"){
        //      $status="1st Rejected";
        //  }

        //  if ($mylevel->Level=="2nd Approval"){
        //      $status="2nd Rejected";
        //  }

        //  if ($mylevel->Level=="3rd Approval"){
        //      $status="3rd Rejected";
        //  }

        //  if ($mylevel->Level=="4th Approval"){
        //      $status="4th Rejected";
        //  }

        //  if ($mylevel->Level=="5th Approval"){
        //      $status="5th Rejected";
        //  }

        //  if ($mylevel->Level=="Final Approval"){
        //      $status="Final Rejected";
        //  }
        // }

        foreach ($leaves as $leave) {
        # code...

            if ($leave->ApproverId!=$me->Id)
            {

                $id=DB::table('leavestatuses')->insertGetId([
                    'LeaveId' => $leave->LeaveId,
                    'UserId' => $me->Id,
                    'Leave_Status' => $status,
                    'updated_at' => DB::raw('now()')
                ]);
            }

            else {

                $result= DB::table('leavestatuses')
                ->where('Id', '=',$leave->StatusId)
                ->update(array(
                    'Leave_Status' =>  $status,
                ));
            }
        }

        $leaves = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leavestatuses.Id','leaves.Id as LeaveId','leaves.UserId as ApplicantId','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Id as ApproverId','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date')
        ->orderBy('leaves.Id','desc')
        ->whereIn('leaves.Id', $Id)
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

        $emaillist=array();
        array_push($emaillist,$me->Id);

        foreach ($leaves as $leave) {

            # code...
            $emaillist=array();
            $notifylist=array();
            $rejectlist=array();
            $notapprovelist=array();

            $submitted=false;
            $currentstatus=$leave->Status;

            if ($leave->Status=="Final Approved")
            {
                array_push($emaillist,$leave->ApplicantId);
                array_push($emaillist,$leave->ApproverId);
                array_push($notifylist,$leave->ApplicantId);
            }

            if ((strpos($leave->Status, 'Rejected') === false) && (strpos($leave->Status, 'Not Approve') === false) && $leave->Status!="Final Approved"){

                foreach ($approver as $user) {

                    if (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId != $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($leave->Status, FILTER_SANITIZE_NUMBER_INT))
                    {

                        DB::table('leavestatuses')->insert([
                            'LeaveId' => $leave->LeaveId,
                            'UserId' => $user->Id,
                            'Leave_Status' => "Pending Approval"
                        ]);

                        $submitted=true;
                        array_push($emaillist,$user->Id);
                        array_push($emaillist,$leave->ApplicantId);

                        array_push($notifylist,$user->Id);
                        array_push($notifylist,$leave->ApplicantId);

                        break;
                    }

                    elseif (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId == $user->Id  && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($leave->Status, FILTER_SANITIZE_NUMBER_INT))
                    {
                        # code...
                        $submitted=true;
                        array_push($emaillist,$user->Id);
                        array_push($emaillist,$leave->ApplicantId);

                        array_push($notifylist,$user->Id);
                        array_push($notifylist,$leave->ApplicantId);
                    }

                    elseif (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId == $user->Id && $user->Level=="Final Approval")
                    {

                        DB::table('leavestatuses')->insert([
                            'LeaveId' => $leave->LeaveId,
                            'UserId' => $user->Id,
                            'Leave_Status' => "Pending Approval"
                        ]);

                        $submitted=true;
                        array_push($emaillist,$user->Id);
                        array_push($emaillist,$leave->ApplicantId);

                        array_push($notifylist,$user->Id);
                        array_push($notifylist,$leave->ApplicantId);

                    }

                    elseif (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId != $user->Id && $user->Level=="Final Approval")
                    {

                        DB::table('leavestatuses')->insert([
                            'LeaveId' => $leave->LeaveId,
                            'UserId' => $user->Id,
                            'Leave_Status' => "Pending Approval"
                        ]);

                        $submitted=true;
                        array_push($emaillist,$user->Id);
                        array_push($emaillist,$leave->ApplicantId);

                        array_push($notifylist,$user->Id);
                        array_push($notifylist,$leave->ApplicantId);

                        break;
                    }
                }
            }

            elseif ((strpos($leave->Status, 'Rejected') !== false))
            {

                array_push($emaillist,$leave->ApplicantId);

                array_push($rejectlist,$leave->ApplicantId);
            }

            elseif ((strpos($leave->Status, 'Not Approve') !== false))
            {
                // dd($leave);
                array_push($emaillist,$leave->ApplicantId);

                array_push($notapprovelist,$leave->ApplicantId);
            }

            elseif ($leave->Status=="Final Approved" || $leave->Leave_Status=="Final Rejected")
            {
                $final=true;
                array_push($emaillist,$leave->ApplicantId);

                array_push($notifylist,$leave->ApplicantId);

                // if ($leave->Status=="Final Approved") {
                //     $this->adjustReplacementLeave($leave, $me->Id);
                // }

            }

            //notification
            if (count($emaillist)>0)
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

                $notify = DB::table('users')
                ->whereIn('Id',$notifylist)
                ->get();

                $notifyplayerid=array();

                foreach ($notify as $u) {
                    # code...
                    if ($me->Id != $u->Id || count($notify) == 1) {
                        DB::table('notificationstatus')->insert([
                            'userid' => $u->Id,
                            'type' => 'Leave Approved',
                            'seen' => 0,
                            'TargetId' => $leave->LeaveId
                        ]);
                    }
                    array_push($notifyplayerid,$u->Player_Id);
                }

                // dd($notifyplayerid);
                if($notifyplayerid)
                {
                    $this->sendLeaveApproved($notifyplayerid);
                }

                $rejectnotify = DB::table('users')
                ->whereIn('Id',$rejectlist)
                ->get();

                $rejectplayerid=array();

                foreach ($rejectnotify as $u) {
                    # code...
                    array_push($rejectplayerid,$u->Player_Id);
                }

                // dd($notifyplayerid);
                if($rejectplayerid)
                {
                    $this->sendReject($rejectplayerid);
                }

                $notapprovenotify = DB::table('users')
                ->whereIn('Id',$notapprovelist)
                ->get();

                $notapproveplayerid=array();

                foreach ($notapprovenotify as $u) {
                    # code...
                    array_push($notapproveplayerid,$u->Player_Id);
                }

                // dd($notifyplayerid);
                if($notapproveplayerid)
                {
                    $this->sendNotApprove($notapproveplayerid);
                }

                // Mail::send('emails.leavestatus', ['me' => $me,'leavedetail' => $leavedetail], function($message) use ($emails,$leavedetail,$NotificationSubject)
                // {
                //  $emails = array_filter($emails);
                //  array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                //  $message->to($emails)->subject($NotificationSubject.' ['.$leavedetail[0]->Name.']');
                // });
            }

            else {
                return 0;
            }
        }
        // return 100;
        return json_encode($leaves);
    }

    public function adminrejected(Request $request)
    {
        $me = JWTAuth::parseToken()->authenticate();
        $input = $request->all();

        $Id = explode(",", $input["Id"]);
        $leaves = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leaves.Id as LeaveId','leaves.UserId','applicant.Name','leavestatuses.Id as StatusId','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Id as ApproverId','approver.Name as Approver')
        ->whereIn('leaves.Id', $Id)
        ->orderBy('leavestatuses.Id','desc')
        ->get();

        $mylevel = DB::table('approvalsettings')
        ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
        ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
        ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
        ->where('approvalsettings.Type', '=', 'Leave')
        ->where('approvalsettings.UserId', '=', $me->Id)

        // ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
        ->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
        ->orderBy('projects.Project_Name','asc')
        ->first();


        $status="Rejected";

        if($mylevel){

            if ($mylevel->Level=="1st Approval"){
                $status="1st Rejected";
            }

            if ($mylevel->Level=="2nd Approval"){
                $status="2nd Rejected";
            }

            if ($mylevel->Level=="3rd Approval"){
                $status="3rd Rejected";
            }

            if ($mylevel->Level=="4th Approval"){
                $status="4th Rejected";
            }

            if ($mylevel->Level=="5th Approval"){
                $status="5th Rejected";
            }

            if ($mylevel->Level=="Final Approval"){
                $status="Final Rejected";
            }
        }

        foreach ($leaves as $leave)
        {

            # code...
            if ($leave->ApproverId!=$me->Id)
            {

                $id=DB::table('leavestatuses')->insertGetId([
                    'LeaveId' => $leave->LeaveId,
                    'UserId' => $me->Id,
                    'Leave_Status' => $status,
                    'updated_at' => DB::raw('now()')
                ]);
            }

            else
            {

                $result= DB::table('leavestatuses')
                ->where('Id', '=',$leave->StatusId)
                ->update(array(
                    'Leave_Status' =>  $status,
                ));
            }
        }

        $leaves = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leavestatuses.Id','leaves.Id as LeaveId','leaves.UserId as ApplicantId','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Id as ApproverId','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date')
        ->orderBy('leaves.Id','desc')
        ->whereIn('leaves.Id', $Id)
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

        $emaillist=array();
        array_push($emaillist,$me->Id);

        foreach ($leaves as $leave) {

            # code...
            $emaillist=array();
            $notifylist=array();
            $rejectlist=array();
            $notapprovelist=array();

            $submitted=false;
            $currentstatus=$leave->Status;

            if ($leave->Status=="Final Approved")
            {
                array_push($emaillist,$leave->ApplicantId);
                array_push($emaillist,$leave->ApproverId);
                array_push($notifylist,$leave->ApplicantId);
            }

            if ((strpos($leave->Status, 'Rejected') === false) && (strpos($leave->Status, 'Not Approve') === false) && $leave->Status!="Final Approved")
            {

                foreach ($approver as $user) {

                    if (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId != $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($leave->Status, FILTER_SANITIZE_NUMBER_INT))
                    {

                        DB::table('leavestatuses')->insert([
                            'LeaveId' => $leave->LeaveId,
                            'UserId' => $user->Id,
                            'Leave_Status' => "Pending Approval"
                        ]);

                        $submitted=true;
                        array_push($emaillist,$user->Id);
                        array_push($emaillist,$leave->ApplicantId);

                        array_push($notifylist,$user->Id);
                        array_push($notifylist,$leave->ApplicantId);

                        break;
                    }

                    elseif (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId == $user->Id  && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($leave->Status, FILTER_SANITIZE_NUMBER_INT))
                    {
                        # code...
                        $submitted=true;
                        array_push($emaillist,$user->Id);
                        array_push($emaillist,$leave->ApplicantId);

                        array_push($notifylist,$user->Id);
                        array_push($notifylist,$leave->ApplicantId);
                    }

                    elseif (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId == $user->Id && $user->Level=="Final Approval")
                    {

                        DB::table('leavestatuses')->insert([
                            'LeaveId' => $leave->LeaveId,
                            'UserId' => $user->Id,
                            'Leave_Status' => "Pending Approval"
                        ]);

                        $submitted=true;
                        array_push($emaillist,$user->Id);
                        array_push($emaillist,$leave->ApplicantId);

                        array_push($notifylist,$user->Id);
                        array_push($notifylist,$leave->ApplicantId);
                    }

                    elseif (!empty($user->Id) && $user->Project_Name==$leave->Project_Name && $leave->ApproverId != $user->Id && $user->Level=="Final Approval")
                    {

                        DB::table('leavestatuses')->insert([
                            'LeaveId' => $leave->LeaveId,
                            'UserId' => $user->Id,
                            'Leave_Status' => "Pending Approval"
                        ]);

                        $submitted=true;
                        array_push($emaillist,$user->Id);
                        array_push($emaillist,$leave->ApplicantId);

                        array_push($notifylist,$user->Id);
                        array_push($notifylist,$leave->ApplicantId);

                        break;
                    }
                }
            }

            elseif ((strpos($leave->Status, 'Rejected') !== false))
            {

                array_push($emaillist,$leave->ApplicantId);

                array_push($rejectlist,$leave->ApplicantId);
            }

            elseif ((strpos($leave->Status, 'Not Approve') !== false))
            {
                // dd($leave);
                array_push($emaillist,$leave->ApplicantId);

                array_push($notapprovelist,$leave->ApplicantId);
            }

            elseif ($leave->Status=="Final Approved" || $leave->Leave_Status=="Final Rejected")
            {
                $final=true;
                array_push($emaillist,$leave->ApplicantId);

                array_push($notifylist,$leave->ApplicantId);
            }

            //notification
            if (count($emaillist)>0)
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

                $notify = DB::table('users')
                ->whereIn('Id',$notifylist)
                ->get();

                $notifyplayerid=array();

                foreach ($notify as $u) {
                    # code...
                    array_push($notifyplayerid,$u->Player_Id);
                }

                // dd($notifyplayerid);
                if($notifyplayerid)
                {
                    $this->sendApproved($notifyplayerid);
                }

                $rejectnotify = DB::table('users')
                ->whereIn('Id',$rejectlist)
                ->get();

                $rejectplayerid=array();

                foreach ($rejectnotify as $u)
                {
                    # code...
                    if ($me->Id != $u->Id || count($rejectnotify) == 1) {
                        DB::table('notificationstatus')->insert([
                            'userid' => $u->Id,
                            'type' => 'Leave Rejected',
                            'seen' => 0,
                            'TargetId' => $leave->LeaveId
                        ]);
                    }

                    array_push($rejectplayerid,$u->Player_Id);
                }

                // dd($notifyplayerid);
                if($rejectplayerid)
                {
                    $this->sendReject($rejectplayerid);
                }

                $notapprovenotify = DB::table('users')
                ->whereIn('Id',$notapprovelist)
                ->get();

                $notapproveplayerid=array();

                foreach ($notapprovenotify as $u) {
                    # code...
                    array_push($notapproveplayerid,$u->Player_Id);
                }

                // dd($notifyplayerid);
                if($notapproveplayerid)
                {
                    $this->sendNotApprove($notapproveplayerid);
                }

                // Mail::send('emails.leavestatus', ['me' => $me,'leavedetail' => $leavedetail], function($message) use ($emails,$leavedetail,$NotificationSubject)
                // {
                //      $emails = array_filter($emails);
                //      array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
                //      $message->to($emails)->subject($NotificationSubject.' ['.$leavedetail[0]->Name.']');
                // });
            }

            else {
                return 0;
            }
        }

        // return 100;
        return json_encode($leaves);
    }


    public function redirect(Request $request)
    {

        $me = JWTAuth::parseToken()->authenticate();

        $input = $request->all();

        // $result= DB::table('leavestatuses')
        //       ->where('Id', $input["Id"])
        //       ->update(array(
        //          'UserId' =>  $input["Approver"],
        //      ));

        $Ids = explode(",", $input["StatusIds"]);

        foreach ($Ids as $Id)
        {
            # code...
            $id=DB::table('leavestatuses')->insertGetId(
                ['LeaveId' => $Id,
                 'UserId' => $input["Approver"],
                 'Leave_Status' => "Pending Approval"
                ]
            );
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

            // dd($leavedetail);

            $notify = DB::table('users')
            ->whereIn('Id', [$me->Id, $input["Approver"],$leavedetail->UserId])
            ->get();

            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',25)
            ->get();

            $emails = array();
            $NotificationSubject="";

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

            $notifyplayerid=array();

            foreach ($notify as $u) {
                # code...
                array_push($notifyplayerid,$u->Player_Id);
            }

            // dd($notifyplayerid);
            if($notifyplayerid)
            {
                $this->sendRedirect($notifyplayerid);
            }

            // Mail::send('emails.leaveapplication', ['leavedetail' => $leavedetail], function($message) use ($emails,$me,$NotificationSubject)
            // {
            //      $emails = array_filter($emails);
            //      array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
            //      $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
            // });

            return 1;
        }

        else {
            return 0;
        }
    }

    public function cancelleave(Request $request)
    {

        $me = JWTAuth::parseToken()->authenticate();

        $input = $request->all();

        // $result= DB::table('leavestatuses')
        // ->where('Id', $input["Id"])
        // ->update(array(
            // 'UserId' =>  $input["Approver"],
        //  ));

        $leavedetail = DB::table('leaves')
        ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leaves.UserId','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','approver.Id as ApproverId','leavestatuses.LeaveId')
        ->orderBy('leavestatuses.Id','desc')
        ->where('leaves.Id', '=',$input["Id"])
        ->first();

        $id=DB::table('leavestatuses')->insertGetId([
            'LeaveId' => $input["Id"],
            'UserId' => $me->Id,
            'Leave_Status' => "Cancelled"
        ]);

        $subscribers = DB::table('notificationtype')
        ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
        ->where('notificationtype.Id','=',26)
        ->get();

        if ($id>0)
        {

            $notify = DB::table('users')
            ->whereIn('Id', [$me->Id, $leavedetail->ApproverId,$leavedetail->UserId])
            ->get();

            $emails = array();
            $notifylist=array();

            // array_push($emaillist,$me->UserId);

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

            foreach ($notify as $user)
            {
                if ($user->Company_Email!="")
                {
                    array_push($emails,$user->Company_Email);
                    array_push($notifylist,$leavedetail->UserId);

                }

                else
                {
                    array_push($emails,$user->Personal_Email);
                    array_push($notifylist,$leave->ApplicantId);

                }
            }

            // Mail::send('emails.leavecancel', ['leavedetail' => $leavedetail], function($message) use ($emails,$me,$NotificationSubject)
            // {
            //      $emails = array_filter($emails);
            //      array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
            //      $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
            // });

            DB::table('notificationstatus')
            ->where('TargetId',$leavedetail->LeaveId)
            ->where('Type','Pending Leave')
            ->delete();

            $notify = DB::table('users')
            ->whereIn('Id',$notifylist)
            ->get();

            $notifyplayerid=array();

            foreach ($notify as $u) {
                # code...
                if ($me->Id != $user->Id || count($notify) == 1) {
                    DB::table('notificationstatus')->insert([
                        'userid' => $user->Id,
                        'type' => 'Leave Cancelled',
                        'seen' => 0,
                        'TargetId' => $leavedetail->LeaveId
                    ]);
                }
                array_push($notifyplayerid,$u->Player_Id);
            }

            if($notifyplayerid)
            {
                $this->cancelMessage($notifyplayerid);
            }

            return 1;
        }

        else
        {
            return 0;
        }
    }

    public function myAllApprover(Request $request)
    {

        $auth = JWTAuth::parseToken()->authenticate();

        $me = (new AuthController)->get_current_user($auth->Id);

        $input = $request->all();

        $leaves = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->leftJoin(DB::raw('(SELECT leave_terms.Leave_Id, GROUP_CONCAT(DISTINCT CONCAT(SUBSTR(leave_terms.Leave_Date,1,6),\' \',leave_terms.Leave_Period) ORDER BY leave_terms.Id SEPARATOR \',\') as Terms FROM leave_terms Group By leave_terms.Leave_Id) as leave_terms'), 'leave_terms.Leave_Id', '=', 'leaves.Id')
        ->select('leavestatuses.Id','leaves.Id as LeaveId','leavestatuses.Leave_Status as Status','applicant.StaffId as Staff_ID','applicant.Name','leaves.Leave_Type',DB::raw("CONCAT(leaves.Leave_Term, leave_terms.Terms) as Leave_Term"),'leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Comment','leavestatuses.updated_at as Review_Date','files.Web_Path')
        ->orderBy('leaves.Id','desc')
        ->where('leavestatuses.UserId', '=',$auth->Id)
        ->where('leavestatuses.Leave_Status', '<>','Cancelled')
        ->get();

        return json_encode($leaves);
    }

    public function myApprover(Request $request)
    {

        $auth = JWTAuth::parseToken()->authenticate();

        $me = (new AuthController)->get_current_user($auth->Id);

        $input = $request->all();

        $leaves = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leavestatuses.Id','leaves.Id as LeaveId','leavestatuses.Leave_Status as Status','applicant.StaffId as Staff_ID','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Comment','leavestatuses.updated_at as Review_Date','files.Web_Path')
        ->orderBy('leaves.Id','desc')
        ->where('leavestatuses.UserId', '=',$auth->Id)
        ->where('leavestatuses.Leave_Status', '=','Pending Approval')
        ->get();

        return json_encode($leaves);
    }

    public function redirect2(Request $request)
    {

        $me = JWTAuth::parseToken()->authenticate();

        $arrLeaveId = array();

        $input = $request->all();

        $Id = explode(",", $input["Id"]);

        // $leavedetail = DB::table('leaves')
        // ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
        // ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        // ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        // ->select('leaves.Id','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver')
        // ->orderBy('leavestatuses.Id','desc')
        // ->whereIn('leaves.Id', $Ids)
        // ->get();

        $leavedetail = DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
        ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
        ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
        ->select('leaves.Id','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status')
        ->orderBy('leavestatuses.Id','desc')
        ->whereIn('leaves.Id', $Id)
        ->get();

        $id=0;

        // dd($leavedetail);
        foreach ($leavedetail as $item)
        {

            # code...
            if(str_contains($item->Leave_Status,"Final Approved")==false)
            {
                $id=DB::table('leavestatuses')->insertGetId([
                    'LeaveId' => $item->Id,
                    'UserId' => $input["Approver"],
                    'Leave_Status' => "Pending Approval"
                ]);

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
            ->whereIn('leaves.Id', $Id)
            ->first();

            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',28)
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

            $notify = DB::table('users')
            ->whereIn('Id', [$me->UserId, $input["Approver"]])
            ->get();

            foreach ($notify as $user)
            {
                if ($user->Company_Email!="")
                {
                    array_push($emails,$user->Company_Email);
                }

                else
                {
                    array_push($emails,$user->Personal_Email);
                }
            }

            $notifyplayerid=array();
            foreach ($notify as $user) {

                if ($user->Company_Email!="") {
                    array_push($emails,$user->Company_Email);
                } else {
                    array_push($emails,$user->Personal_Email);
                }

                if ($me->Id != $user->Id || count($notify) == 1) {

                    DB::table('notificationstatus')
                    ->where('TargetId','=',$input["Id"])
                    ->where('UserId','=',$input["AppId"])
                    ->where('Type','=','Pending Leave')
                    ->where('Seen','=',0)
                    ->update(array(
                        'Seen' =>1 ,
                    ));


                     DB::table('notificationstatus')->insert([
                    'userid' => $user->Id,
                    'type' => 'Pending Leave',
                    'seen' => 0,
                    'TargetId' => $input["Id"]
                    ]);

                    if ($user->Player_Id){
                      array_push($notifyplayerid,$user->Player_Id);
                    }
                }
            }

            // dd($notifyplayerid);
            if($notifyplayerid)
            {
                $this->sendRedirect($notifyplayerid);
            }

            //array_push($emails,"latifah@pronetwork.com.my");
            // Mail::send('emails.leaveapprovalrequest', ['leavedetail' => $leavedetail,'from'=>$me->Name], function($message) use ($emails,$leavedetail,$NotificationSubject)
            // {
            //  $emails = array_filter($emails);
            //  array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
            //  $message->to($emails)->subject($NotificationSubject.' ['.$leavedetail->Name.']');
            // });

            return 1;
        }

        else
        {
            return 0;
        }
    }


    public function calculateleavedays(array $input)
    {

        $me = JWTAuth::parseToken()->authenticate();

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

            else
            {

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


    public function getapprover()
    {

        $me = JWTAuth::parseToken()->authenticate();

        $approver = DB::table('approvalsettings')
        ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
        ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
        ->select(db::raw('DISTINCT users.Id'),'users.Name','projects.Project_Name')
        ->where('approvalsettings.Type', '=', 'Leave')
        ->where('projects.Project_Name', '=', $me->Department )
        // ->where('approvalsettings.Level', '=', '1st Approval')
        // ->orWhere('approvalsettings.Level', '=', 'Final Approval')
        ->orderBy('users.Name','asc')
        ->get();

        return json_encode($approver);
    }


    public function getoptions()
    {

        $me = JWTAuth::parseToken()->authenticate();

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


        return json_encode($options);
    }


    function sendApproved($playerids)
    {

        $me = JWTAuth::parseToken()->authenticate();
        //
        // $playerId = DB::table('users')
        //  ->select('users.Player_Id')
        //  ->get();

        $content = array
        (
            "en" => 'Approved new leave'
        );

        $fields = array
        (
            'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            // 'included_segments' => array("All"),
            'include_player_ids' => $playerids,
            'data' => array("type" => "Leave"),
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


    function sendLeaveApproved($playerids)
    {

        $me = JWTAuth::parseToken()->authenticate();
        //
        // $playerId = DB::table('users')
        //  ->select('users.Player_Id')
        //  ->get();


        $content = array(
            "en" => 'Leave Approved'
        );

        $fields = array(
            'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            // 'included_segments' => array("All"),
            'include_player_ids' => $playerids,
            'data' => array("type" => "Leave"),
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


    function sendReject($playerids)
    {

        $me = JWTAuth::parseToken()->authenticate();
        //
        // $playerId = DB::table('users')
        //  ->select('users.Player_Id')
        //  ->get();

        $content = array(
            "en" => 'Leave Rejected'
        );

        $fields = array(
            'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            // 'included_segments' => array("All"),
            'include_player_ids' => $playerids,
            'data' => array("foo" => "bar"),
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

    function cancelMessage($playerids){

        $me = JWTAuth::parseToken()->authenticate();

        $content = array(
            "en" => 'cancel leave'
        );

        $fields = array(
            'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            // 'included_segments' => array("All"),
            'include_player_ids' =>$playerids,
            'data' => array("foo" => "bar"),
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

    function sendRedirect($playerids){

        $me = JWTAuth::parseToken()->authenticate();
        //
        // $playerId = DB::table('users')
        //  ->select('users.Player_Id')
        //  ->get();

        $content = array
        (
            "en" => 'Redirect Leave'
        );

        $fields = array
        (
            'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            // 'included_segments' => array("All"),
            'include_player_ids' => $playerids,
            'data' => array("foo" => "bar"),
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

    function sendSubmit($playerids){

        $me = JWTAuth::parseToken()->authenticate();
        //
        // $playerId = DB::table('users')
        //  ->select('users.Player_Id')
        //  ->get();


        $content = array(
            "en" => 'Leave Submitted'
        );

        $fields = array(
            'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            // 'included_segments' => array("All"),
            'include_player_ids' => $playerids,
            'data' => array("type" => "Leave"),
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

}
