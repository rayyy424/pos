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

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'leaves')
  	->fields(
      Field::inst( 'leaves.Id' ),
      Field::inst( 'leavestatuses.Id' ),
      Field::inst( 'applicant.Name' ),
      Field::inst( 'leaves.Leave_Type' ),
  		Field::inst( 'leaves.Leave_Term' ),
      Field::inst( 'leaves.Start_Date' ),
      Field::inst( 'leaves.End_Date' ),
      Field::inst( 'leaves.No_of_Days' ),
      Field::inst( 'leaves.Reason' ),
      Field::inst( 'leaves.Medical_Claim' ),
      Field::inst( 'leaves.created_at' ),
      Field::inst( 'leavestatuses.UserId' ),
      Field::inst( 'approver.Name' ),
      Field::inst( 'leavestatuses.Leave_Status' ),
      Field::inst( 'leavestatuses.updated_at' ),
      Field::inst( 'leavestatuses.Comment' ),
      Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/Leave/__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'Leave',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )
              )
      )

      ->validator( function ( $editor, $action, $data ) use ($db) {
            if ( $action === Editor::ACTION_EDIT) {

                foreach ( $data['data'] as $pkey => $values ) {
                    $leave = $db->raw()
                              ->bind(':Id', $pkey)
                              ->exec('SELECT UserId FROM leaves WHERE Id = :Id')
                              ->fetch();

                    $datesOverlapped = $db->raw()
                       ->bind(':Start_Date', $values['leaves']['Start_Date'])
                       ->bind(':End_Date', $values['leaves']['End_Date'])
                       ->bind(':Id', $pkey)
                       ->bind(':UserId', $leave['UserId'])
                       ->exec('SELECT * FROM leaves
                        LEFT JOIN (
                            SELECT LeaveId, MAX(Id) as maxid FROM leavestatuses
                                GROUP BY LeaveId
                        ) AS status ON status.LeaveId = leaves.Id
                        LEFT JOIN leavestatuses ON status.maxid = leavestatuses.Id
                        WHERE (GREATEST(STR_TO_DATE(:Start_Date, "%d-%b-%Y"), STR_TO_DATE(leaves.Start_Date, "%d-%b-%Y")) <= LEAST(STR_TO_DATE(:End_Date, "%d-%b-%Y"), STR_TO_DATE(leaves.End_Date, "%d-%b-%Y")))
                        AND leaves.UserId = :UserId AND leaves.Id <> :Id
                        AND leavestatuses.Leave_Status <> "Cancelled" AND leavestatuses.Leave_Status NOT LIKE "%Rejected%"
                        LIMIT 1')
                       ->count();


                       //(StartDate1 <= EndDate2) and (StartDate2 <= EndDate1)AND (Room_Id = :Room_Id)
                    if ($datesOverlapped) {
                         return 'Your leave dates is overlapped with your other applied leave.';
                    }

                    $date1 = new DateTime($values['leaves']["Start_Date"]);
                    $date2 = new DateTime($values['leaves']["End_Date"]);


                    if ($date2 < $date1) {
                      return 'Start date must be before or equal End date';
                    }

                    $userId = $leave['UserId'];

                    $workingDays = $db->raw()
                              ->bind(':Id', $userId)
                              ->exec('SELECT Working_Days FROM users WHERE Id = :Id')
                              ->fetch()['Working_Days'];

                    $datesChanged = $db->raw()
                       ->bind(':Start_Date', $values['leaves']['Start_Date'])
                       ->bind(':End_Date', $values['leaves']['End_Date'])
                       ->bind(':Leave_Type', $values['leaves']['Leave_Type'])
                       ->bind(':Id', $pkey)
                       ->exec('SELECT * FROM leaves where Id = :Id AND (Start_Date != :Start_Date OR End_Date != :End_Date OR Leave_Type != :Leave_Type)  LIMIT 1')
                       ->count();

                    if ($datesChanged) {

                      $db->raw()
                         ->bind(':Id', $pkey)
                         ->exec('DELETE FROM leave_terms where Leave_Id = :Id');


                      // the selected start date
                      $startdate = $values['leaves']["Start_Date"];

                      // calculate the days difference between dates and add one day
                      $datediff = $date2->diff($date1)->format("%a") + 1;

                        // start date
                      $start = strtotime($values['leaves']["Start_Date"]);
                      // end date
                      $end = strtotime($values['leaves']["End_Date"]);


                      // loop until the end date
                      while ($start <= $end) {

                        // default to full
                        $leavePeriod = 'Full';

                        // get start date
                        $current = date("d-M-Y", $start);

                        if ($values['leaves']["Leave_Type"] == 'Maternity Leave' || $values['leaves']["Leave_Type"] == 'Hospitalization Leave') {
                            $leavePeriod = 'Full';
                        } else {

                          $holidayTerritoryId = $db->raw()
                              ->bind(':Id', $userId)
                              ->exec('SELECT HolidayTerritoryId FROM users WHERE Id = :Id')
                              ->fetch()['HolidayTerritoryId'];

                          if ($holidayTerritoryId == null) {
                              $holidayTerritory = $db->raw()
                              ->exec('SELECT Id FROM holidayterritories LIMIT 1')
                              ->fetch();

                              if (count($holidayTerritory)) {
                                  $holidayTerritoryId = $holidayTerritory['Id'];
                                  $holiday = $db->raw()
                                      ->bind(':HolidayTerritoryId', $holidayTerritoryId)
                                      ->exec('SELECT Holiday FROM holidayterritorydays WHERE HolidayTerritoryId = :HolidayTerritoryId AND (str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y") <= str_to_date("' . $current . '","%d-%M-%Y")) AND (str_to_date(holidayterritorydays.End_Date,"%d-%M-%Y") >= str_to_date("' . $current . '","%d-%M-%Y")) LIMIT 1');

                                  if ($holiday->count() > 0) {
                                    // $current;
                                    $h = $db
                                        ->raw()
                                        ->bind(':HolidayTerritoryId', $holidayTerritoryId)
                                        ->exec('SELECT Holiday FROM holidayterritorydays WHERE HolidayTerritoryId = :HolidayTerritoryId AND (str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y") <= str_to_date("' . $current . '","%d-%M-%Y")) AND (str_to_date(holidayterritorydays.End_Date,"%d-%M-%Y") >= str_to_date("' . $current . '","%d-%M-%Y")) LIMIT 1');
                                    $hd = $h->fetch();
                                    // return print_r();
                                    $leavePeriod = 'Holiday: ' . $hd['Holiday'];

                                    $datediff -= 1;
                                  } else {


                                    // get Numeric representation of the day of
                                    // the week,  0 (for Sunday), 6(for Saturday)
                                    $day_type = date("w", $start);

                                    // minus 1 day if its Sunday or Saturday
                                    // minus 1 day if its Sunday or Saturday
                                    if ($day_type == 0 || $day_type == 6) {
                                        // if working days is 5.5 or 6
                                        if ($day_type == 6 && $workingDays > 5) {
                                            if ($values['leaves']["Leave_Type"] == '1 Hour Time Off') {
                                                $leavePeriod = '1 Hour';
                                                 // minus 7 hours
                                                $datediff -= 0.875;
                                            } else if ($values['leaves']["Leave_Type"] == '2 Hours Time Off') {
                                                $leavePeriod = '2 Hours';
                                                 // minus 6 hours
                                                $datediff -= 0.75;
                                            } else {
                                                $leavePeriod = 'Full';
                                            }
                                        } else {
                                            $leavePeriod = 'Non-working Day';
                                             // minus 1 day
                                            $datediff -= 1;
                                        }

                                    } else {
                                      if ($values['leaves']['Leave_Type'] == '1 Hour Time Off') {
                                        // minus 7 hours
                                        $datediff -= 0.875;
                                        $leavePeriod = '1 Hour';

                                      } else if ($values['leaves']['Leave_Type'] == '2 Hours Time Off') {
                                        // minus 6 hours
                                        $datediff -= 0.75;
                                        $leavePeriod = '2 Hours';

                                      } else {
                                        // default to full
                                        $leavePeriod = 'Full';

                                      }
                                    }

                                  }

                              } else {
                                  // $holiday = [];
                                  // fallback to original holidays table
                                  $holiday = $db
                                      ->raw()
                                      ->exec('SELECT Holiday FROM holidays WHERE (str_to_date(holidays.Start_Date,"%d-%M-%Y") <= str_to_date("' . $current . '","%d-%M-%Y")) AND (str_to_date(holidays.End_Date,"%d-%M-%Y") >= str_to_date("' . $current . '","%d-%M-%Y")) LIMIT 1');

                                  if ($holiday->count() > 0) {
                                    // $current;
                                    $h = $db
                                        ->raw()
                                        ->exec('SELECT Holiday FROM holidays WHERE (str_to_date(holidays.Start_Date,"%d-%M-%Y") <= str_to_date("' . $current . '","%d-%M-%Y")) AND (str_to_date(holidays.End_Date,"%d-%M-%Y") >= str_to_date("' . $current . '","%d-%M-%Y")) LIMIT 1');
                                    $hd = $h->fetch();
                                    // return print_r();
                                    $leavePeriod = 'Holiday: ' . $hd['Holiday'];

                                    $datediff -= 1;
                                  } else {


                                    // get Numeric representation of the day of
                                    // the week,  0 (for Sunday), 6(for Saturday)
                                    $day_type = date("w", $start);

                                    // minus 1 day if its Sunday or Saturday
                                    // minus 1 day if its Sunday or Saturday
                                    if ($day_type == 0 || $day_type == 6) {
                                        // if working days is 5.5 or 6
                                        if ($day_type == 6 && $workingDays > 5) {
                                            if ($values['leaves']["Leave_Type"] == '1 Hour Time Off') {
                                                $leavePeriod = '1 Hour';
                                                 // minus 7 hours
                                                $datediff -= 0.875;
                                            } else if ($values['leaves']["Leave_Type"] == '2 Hours Time Off') {
                                                $leavePeriod = '2 Hours';
                                                 // minus 6 hours
                                                $datediff -= 0.75;
                                            } else {
                                                $leavePeriod = 'Full';
                                            }
                                        } else {
                                            $leavePeriod = 'Non-working Day';
                                             // minus 1 day
                                            $datediff -= 1;
                                        }

                                    } else {
                                      if ($values['leaves']['Leave_Type'] == '1 Hour Time Off') {
                                        // minus 7 hours
                                        $datediff -= 0.875;
                                        $leavePeriod = '1 Hour';

                                      } else if ($values['leaves']['Leave_Type'] == '2 Hours Time Off') {
                                        // minus 6 hours
                                        $datediff -= 0.75;
                                        $leavePeriod = '2 Hours';

                                      } else {
                                        // default to full
                                        $leavePeriod = 'Full';

                                      }
                                    }

                                  }
                              }

                          } else {
                              $holiday = $db->raw()
                                  ->bind(':HolidayTerritoryId', $holidayTerritoryId)
                                  ->exec('SELECT Holiday FROM holidayterritorydays WHERE HolidayTerritoryId = :HolidayTerritoryId AND (str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y") <= str_to_date("' . $current . '","%d-%M-%Y")) AND (str_to_date(holidayterritorydays.End_Date,"%d-%M-%Y") >= str_to_date("' . $current . '","%d-%M-%Y")) LIMIT 1');

                              if ($holiday->count() > 0) {
                                // $current;
                                $h = $db
                                    ->raw()
                                    ->bind(':HolidayTerritoryId', $holidayTerritoryId)
                                    ->exec('SELECT Holiday FROM holidayterritorydays WHERE HolidayTerritoryId = :HolidayTerritoryId AND (str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y") <= str_to_date("' . $current . '","%d-%M-%Y")) AND (str_to_date(holidayterritorydays.End_Date,"%d-%M-%Y") >= str_to_date("' . $current . '","%d-%M-%Y")) LIMIT 1');
                                $hd = $h->fetch();
                                // return print_r();
                                $leavePeriod = 'Holiday: ' . $hd['Holiday'];

                                $datediff -= 1;
                              } else {


                                // get Numeric representation of the day of
                                // the week,  0 (for Sunday), 6(for Saturday)
                                $day_type = date("w", $start);

                                // minus 1 day if its Sunday or Saturday
                                // minus 1 day if its Sunday or Saturday
                                if ($day_type == 0 || $day_type == 6) {
                                    // if working days is 5.5 or 6
                                    if ($day_type == 6 && $workingDays > 5) {
                                        if ($values['leaves']["Leave_Type"] == '1 Hour Time Off') {
                                            $leavePeriod = '1 Hour';
                                             // minus 7 hours
                                            $datediff -= 0.875;
                                        } else if ($values['leaves']["Leave_Type"] == '2 Hours Time Off') {
                                            $leavePeriod = '2 Hours';
                                             // minus 6 hours
                                            $datediff -= 0.75;
                                        } else {
                                            $leavePeriod = 'Full';
                                        }
                                    } else {
                                        $leavePeriod = 'Non-working Day';
                                         // minus 1 day
                                        $datediff -= 1;
                                    }

                                } else {
                                  if ($values['leaves']['Leave_Type'] == '1 Hour Time Off') {
                                    // minus 7 hours
                                    $datediff -= 0.875;
                                    $leavePeriod = '1 Hour';

                                  } else if ($values['leaves']['Leave_Type'] == '2 Hours Time Off') {
                                    // minus 6 hours
                                    $datediff -= 0.75;
                                    $leavePeriod = '2 Hours';

                                  } else {
                                    // default to full
                                    $leavePeriod = 'Full';

                                  }
                                }

                              }
                          }
                          // get the holidays dates that in ranges of start and end dates
                          // $holiday = DB::table('holidays')
                          //     ->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'), "<=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                          //     ->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'), ">=", DB::raw('str_to_date("' . $current . '","%d-%M-%Y")'))
                          //     ->get();

                          // $holiday = $db
                          //             ->raw()
                          //             ->exec('SELECT Holiday FROM holidays WHERE (str_to_date(holidays.Start_Date,"%d-%M-%Y") <= str_to_date("' . $current . '","%d-%M-%Y")) AND (str_to_date(holidays.End_Date,"%d-%M-%Y") >= str_to_date("' . $current . '","%d-%M-%Y")) LIMIT 1');
                          // if there is holiday for $current minus 1 day from datediff

                        }
                        $db->raw()
                          ->bind(':Leave_Date', $current)
                          ->bind(':Leave_Id', $pkey)
                          ->bind(':Leave_Period', $leavePeriod)
                          ->exec("INSERT INTO leave_terms (Leave_Period, Leave_Date, Leave_Id) VALUES (:Leave_Period, :Leave_Date, :Leave_Id)");


                        // move to the next day / +1 day
                        $start = strtotime("+1 day", $start);
                      }

                      // no of days
                      // return $datediff;
                      // die(var_dump($datediff));
                      $db->raw()
                        ->bind(':No_of_Days', $datediff)
                        ->bind(':Id', $pkey)
                        ->exec("UPDATE leaves SET No_of_Days = :No_of_Days WHERE Id = :Id");
                    }

                }
            }
        } )

    ->leftJoin( '(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2', 'max2.TargetId', '=', 'leaves.Id')
    ->leftJoin('files', 'files.Id', '=', 'max2.`maxid` and files.`Type`="Leave"')

    ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
    // ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
    ->leftJoin('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max', 'max.LeaveId', '=', 'leaves.Id')
    ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', 'max.maxid')
    ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id');

    if (isset( $_GET['UserId'])) {
      $editor
      ->where('leaves.UserId',$_GET['UserId'] );
    }

    if (isset( $_GET['Status'])) {

      if(strpos($_GET['Status'],"Pending")!==false)
      {
        $editor
        ->where( function ( $q ) {
            $q
                ->where( 'leavestatuses.Leave_Status', $_GET['Status'], 'like' )
                ->or_where( function ( $r ) {
                    $r->where( 'leavestatuses.Leave_Status', null);
                    $r->where( 'leaves.UserId', $_GET['UserId']);
                } );
        } );
      }
      else {
        $editor
        ->where('leavestatuses.Leave_Status',$_GET['Status'],'like' );
      }

    }

    $editor->process( $_POST )->json();
