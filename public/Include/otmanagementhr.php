<?php

include( "plugin/php/DataTables.php" );

// Alias Editor classes so they are easy to use
use
  DataTables\Editor,
  DataTables\Editor\Field,
  DataTables\Editor\Format,
  DataTables\Editor\Mjoin,
  DataTables\Editor\Upload,
  DataTables\Editor\Validate;

/*
 * Example PHP implementation used for the index.html example
 */
 if (isset($_POST['Id']))
 {
   $id=$_POST['Id'];
 }
 else {
   $id=0;
 }
 date_default_timezone_set("Asia/Kuala_Lumpur");


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'timesheets')
    // ->debug(true)
  	->fields(
      Field::inst( 'timesheets.Id' ),
      Field::inst( 'timesheets.Code' ),
      Field::inst( 'timesheets.Site_Name' ),
      Field::inst( 'users.StaffId' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'users.Resignation_Date' ),
      Field::inst( 'users.Company' ),
      Field::inst( 'users.Department' ),
      Field::inst( 'users.Category' ),
      Field::inst( 'users.Position' ),
      Field::inst( 'timesheets.Latitude_In' ),
      Field::inst( 'timesheets.Latitude_Out' ),
      Field::inst( 'timesheets.Longitude_In' ),
      Field::inst( 'timesheets.Longitude_Out' ),
      Field::inst( 'timesheets.Date' ),
      Field::inst( 'users.Available' ),
      Field::inst( 'timesheets.Check_In_Type' ),
      Field::inst( 'leaves.Leave_Type' ),
      Field::inst( 'leavestatuses.Leave_Status' ),
      Field::inst( 'timesheets.Time_In' ),
      Field::inst( 'timesheets.Time_Out' ),
      Field::inst( 'timesheets.OT1' ),
      Field::inst( 'timesheets.OT2' ),
      Field::inst( 'timesheets.OT3' ),
      Field::inst( 'timesheets.OT_Verified' ),
      Field::inst( 'timesheets.OT_HOD_Verified' ),
      Field::inst( 'timesheets.Remarks' ),
      Field::inst( 'files.Web_Path' ),
      Field::inst( 'timesheets.Deduction' )
    )
    ->on('postEdit', function ($editor, $id, $values, $row) use ($db) {

        if (isset($values['timesheets']['OT1']) || isset($values['timesheets']['OT2']) || isset($values['timesheets']['OT3'])) {

            return;
        }

        $timesheet = $db->raw()
           ->bind(':Id', $id)
           ->exec("SELECT timesheets.UserId, timesheets.Date, users.Department, users.HolidayTerritoryId FROM timesheets LEFT JOIN users ON users.Id = timesheets.UserId WHERE timesheets.Id = :Id")
           ->fetch();

        $userId = $timesheet['UserId'];
        $userDepartment = $timesheet['Department'];
        $holidayTerritoryId = $timesheet['HolidayTerritoryId'];
        $date = $timesheet['Date'];

        $sameDateTimesheets = $db->raw()
           ->bind(':UserId', $userId)
           ->bind(':Date', $date)
           ->exec("
              SELECT Id, Date, UserId, Time_In, Time_Out,
              CASE WHEN Time_Out = '' OR Time_In = '' OR Time_Out IS NULL OR Time_In IS NULL
                  THEN 0
              WHEN str_to_date(timesheets.Time_Out, '%l:%i %p') < str_to_date(timesheets.Time_In, '%l:%i %p')
                  THEN TIME_TO_SEC(ADDTIME(TIMEDIFF(str_to_date(timesheets.Time_Out, '%l:%i %p'),str_to_date(timesheets.Time_In, '%l:%i %p')),'24:00:00'))
              WHEN str_to_date(timesheets.Time_Out, '%l:%i %p') >= str_to_date(timesheets.Time_In, '%l:%i %p')
                  THEN TIME_TO_SEC(TIMEDIFF(str_to_date(timesheets.Time_Out, '%l:%i %p'),str_to_date(timesheets.Time_In, '%l:%i %p')))
              END as Duration
              FROM timesheets WHERE UserId = :UserId AND Date = :Date")
           ->fetchAll();

        if (strtoupper($userDepartment) == 'MY_DEPARTMENT_FAB') {
            $earlyTimeIn = $db->raw()
               ->bind(':UserId', $userId)
               ->bind(':Date', $date)
               ->exec("
                  SELECT SUM(
                    CASE WHEN str_to_date(timesheets.Time_Out, '%l:%i %p') < str_to_date('9:00 AM', '%l:%i %p')
                      THEN TIME_TO_SEC(TIMEDIFF(str_to_date(timesheets.Time_Out, '%l:%i %p'),str_to_date(timesheets.Time_In, '%l:%i %p')))
                    WHEN str_to_date(timesheets.Time_Out, '%l:%i %p') >= str_to_date('9:00 AM', '%l:%i %p')
                      THEN TIME_TO_SEC(TIMEDIFF(str_to_date('9:00 AM', '%l:%i %p'),str_to_date(timesheets.Time_In, '%l:%i %p')))
                    ELSE
                      0
                    END
                  ) as Total_Early
                  FROM timesheets WHERE UserId = :UserId AND Date = :Date")
               ->fetch();

            if ($earlyTimeIn['Total_Early'] > 0) {
                $earlyHours       = floor($earlyTimeIn['Total_Early'] / 3600);
                $earlyMins        = floor($earlyTimeIn['Total_Early'] / 60 % 60);
                $earlySecs        = floor($earlyTimeIn['Total_Early'] % 60);
                $totalEarlyHours  = $earlyHours + (($earlyMins / 60) * 1);
            } else {
                $totalEarlyHours = 0;
            }
        } else {
            $totalEarlyHours = 0;
        }

        $timesheetId = min(array_column($sameDateTimesheets,'Id'));

        $totalDuration = 0;
        foreach ($sameDateTimesheets as $sameDateTimesheet) {
            $totalDuration = $totalDuration + $sameDateTimesheet['Duration'];
        }

        $hours = floor($totalDuration / 3600);
        $mins = floor($totalDuration / 60 % 60);
        $secs = floor($totalDuration % 60);

        $current = $date;

        $day_type = date("w", strtotime($current));

        // FAB and MDO only 6 wd
        // saturday 6, sunday 0
        if ($day_type == 6 || $day_type == 0) {

            if ($day_type == "6") {

                $standardWorkingHours = 9 + $totalEarlyHours;

                // $totalWorkingHours = $hours + (($mins / 60) * 1);
                $totalWorkingHours = $hours + (($mins / 60) * 1);
                $OT = $totalWorkingHours - $standardWorkingHours;

                if ($OT > 0) {

                  // minus 9 hours of standard work hour
                  $OT = $totalWorkingHours - $standardWorkingHours;
                  $OT = $OT >= 0 ? $OT : 0;

                } else {

                  $OT = 0;

                }

                list($otHours, $otMins) = explode('.',number_format((float)$OT, 2, '.', ''));

                $update = $db->raw()
                   ->bind(':OT1', $otHours + ((substr($otMins, 0, 2)/100)*0.6))
                   ->bind(':Id', $timesheetId)
                   ->exec("UPDATE timesheets SET OT1 = :OT1, updated_at = NOW() WHERE Id = :Id");

            } elseif ($day_type=="0") {

                // full restday
                $standardWorkingHours = 0 + $totalEarlyHours;

                // $totalWorkingHours = $hours + (($mins / 60) * 1);
                $totalWorkingHours = $hours + (($mins / 60) * 1);
                $OT = $totalWorkingHours - $standardWorkingHours;

                if ($OT > 0) {

                  // minus 9 hours of standard work hour
                  $OT = $totalWorkingHours - $standardWorkingHours;
                  $OT = $OT >= 0 ? $OT : 0;

                } else {

                  $OT = 0;

                }

                list($otHours, $otMins) = explode('.',number_format((float)$OT, 2, '.', ''));

                $update = $db->raw()
                   ->bind(':OT2', $otHours + ((substr($otMins, 0, 2)/100)*0.6))
                   ->bind(':Id', $timesheetId)
                   ->exec("UPDATE timesheets SET OT2 = :OT2, updated_at = NOW() WHERE Id = :Id");

            }

            return;
        }


        // holiday
        if ($holidayTerritoryId == null) {

            $holidayTerritory = $db->raw()
                                   ->exec("SELECT Id FROM holidayterritories LIMIT 1")
                                   ->fetchAll();

            if (count($holidayTerritory)) {
                $holidayTerritoryId = $holidayTerritory['Id'];

                $holiday = $db->raw()
                              ->bind(':Current', $current)
                              ->bind(':HolidayTerritoryId', $holidayTerritoryId)
                              ->exec('SELECT Id FROM holidayterritorydays
                                WHERE str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y") <= str_to_date(:Current,"%d-%M-%Y") AND
                                      str_to_date(holidayterritorydays.End_Date,"%d-%M-%Y") >= str_to_date(:Current,"%d-%M-%Y") AND
                                      HolidayTerritoryId = :HolidayTerritoryId
                                LIMIT 1')
                              ->fetchAll();
            } else {
                // $holiday = [];
                // fallback to original holidays table
                $holiday = $db->raw()
                              ->bind(':Current', $current)
                              ->exec('SELECT Id FROM holidays
                                WHERE str_to_date(holidays.Start_Date,"%d-%M-%Y") <= str_to_date(:Current,"%d-%M-%Y") AND
                                      str_to_date(holidays.End_Date,"%d-%M-%Y") >= str_to_date(:Current,"%d-%M-%Y")
                                LIMIT 1')
                              ->fetchAll();
            }

        } else {

            $holiday = $db->raw()
                          ->bind(':Current', $current)
                          ->bind(':HolidayTerritoryId', $holidayTerritoryId)
                          ->exec('SELECT Id FROM holidayterritorydays
                            WHERE str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y") <= str_to_date(:Current,"%d-%M-%Y") AND
                                  str_to_date(holidayterritorydays.End_Date,"%d-%M-%Y") >= str_to_date(:Current,"%d-%M-%Y") AND
                                  HolidayTerritoryId = :HolidayTerritoryId
                            LIMIT 1')
                          ->fetchAll();
        }
        // holiday

        // today is holiday, calculate OT3
        if (count($holiday) > 0) {
          $standardWorkingHours = 0 + $totalEarlyHours;
          // $totalWorkingHours = $hours + (($mins / 60) * 1);

          $totalWorkingHours = $hours + (($mins / 60) * 1);
            $OT = $totalWorkingHours - $standardWorkingHours;
            if ($OT > 0) {
              // minus 9 hours of standard work hour
              $OT = $totalWorkingHours - $standardWorkingHours;
              $OT = $OT >= 0 ? $OT : 0;
            } else {
              $OT = 0;
            }

            list($otHours, $otMins) = explode('.',number_format((float)$OT, 2, '.', ''));

            $update = $db->raw()
               ->bind(':OT3', $otHours + ((substr($otMins, 0, 2)/100)*0.6))
               ->bind(':Id', $timesheetId)
               ->exec("UPDATE timesheets SET OT3 = :OT3, updated_at = NOW() WHERE Id = :Id");

            return;
        }

        // not holiday and not restday, calculate OT1.5
        $standardWorkingHours = 9 + $totalEarlyHours;
        // $totalWorkingHours = $hours + (($mins / 60) * 1);
        $totalWorkingHours = $hours + (($mins / 60) * 1);
          $OT = $totalWorkingHours - $standardWorkingHours;
          if ($OT > 0) {
            // minus 9 hours of standard work hour
            $OT = $totalWorkingHours - $standardWorkingHours;
            $OT = $OT >= 0 ? $OT : 0;
          } else {
            $OT = 0;
          }
        list($otHours, $otMins) = explode('.',number_format((float)$OT, 2, '.', ''));

        $update = $db->raw()
           ->bind(':OT1', $otHours + ((substr($otMins, 0, 2)/100)*0.6))
           ->bind(':Id', $timesheetId)
           ->exec("UPDATE timesheets SET OT1 = :OT1, updated_at = NOW() WHERE Id = :Id");

        return;


    })
    ->leftJoin('users', 'timesheets.UserId', '=', 'users.Id and users.Entitled_for_OT="Yes"')
    ->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
    ->leftJoin('leaves','leaves.UserId','=','users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")')
    ->leftJoin('(select Max(Id) as maxid,LeaveId,Leave_Status from leavestatuses Group By LeaveId) as maxleave', 'maxleave.LeaveId', '=', 'leaves.Id')
    ->leftJoin('leavestatuses', 'leavestatuses.Id', '=','maxleave.maxid')
    ->leftJoin('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser', 'maxuser.TargetId', '=', 'users.Id')
    ->leftJoin('files', 'files.Id', '=', 'maxuser.maxid and files.Type="User"');

    // $editor->where( function ( $q ) {
    //     $q->where("(users.Department = 'MY_Department_MDO'","users.Department = 'MY_Department_FAB')",'OR', false);
    // } );

    $editor
    ->where( function ( $q ) {
      $q->where( 'timesheets.OT_HOD_Verified', '1');
    } );

    if (isset( $_GET['IncludeResigned'])) {


      // not include resigned
      if (! ($_GET['IncludeResigned'] == 'true')) {
        // dont include resigned
        $today = date('d-M-Y', strtotime('today'));
        $editor
            ->where( function ( $q ) use ($today) {
                $q->where('(str_to_date(users.Resignation_Date,"%d-%M-%Y")','str_to_date("'.$today.'","%d-%M-%Y") OR users.Resignation_Date = "")', ">=", false );
            } );
      }
    } else {
      // dont include resigned
      $today = date('d-M-Y', strtotime('today'));
      $editor
          ->where( function ( $q ) use ($today) {
              $q->where('(str_to_date(users.Resignation_Date,"%d-%M-%Y")','str_to_date("'.$today.'","%d-%M-%Y") OR users.Resignation_Date = "")', ">=", false );
          } );
    }

    if (isset( $_GET['Start'])) {
      $editor
          ->where( function ( $q ){
              $q->where( 'str_to_date(timesheets.Date,"%d-%M-%Y")', 'BETWEEN (str_to_date("'.$_GET['Start'].'","%d-%M-%Y")) AND (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
          } );
    }

      $editor
      ->where( function ( $q ) {
        $q->where( 'users.Id', '(855, 883,902)', 'NOT IN', false );
      } );

      // $editor
      // ->where('users.Entitled_for_OT','"Yes"','=' );

    $editor->process( $_POST )->json();
