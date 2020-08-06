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

 if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'edit'))
 {

   foreach ($_POST['data'] as $key => $value) {

     if(isset( $_POST['data'][$key]['timesheets']['Time_In'] ))
     {
       if ($_POST['data'][$key]['timesheets']['Time_In']!="")
       {

         $_POST['data'][$key]['timesheets']['Time_In']=trim($_POST['data'][$key]['timesheets']['Time_In']);
       }
     }

   }

 }


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'timesheets')
    ->debug(true)
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
      Field::inst( 'timesheets.total_distance' ),
      // Field::inst( 'timesheets.OT1' ),
      // Field::inst( 'timesheets.OT2' ),
      // Field::inst( 'timesheets.OT3' ),
      // Field::inst( 'timesheets.OT_Verified' ),
      Field::inst( 'timesheets.Remarks' ),
      Field::inst( 'files.Web_Path' ),
      Field::inst( 'timesheets.Deduction' )
    )
    ->leftJoin('users', 'timesheets.UserId', '=', 'users.Id')
    ->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
    ->leftJoin('leaves','leaves.UserId','=','users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")')
    ->leftJoin('(select Max(Id) as maxid,LeaveId,Leave_Status from leavestatuses Group By LeaveId) as maxleave', 'maxleave.LeaveId', '=', 'leaves.Id')
    ->leftJoin('leavestatuses', 'leavestatuses.Id', '=','maxleave.maxid')
    ->leftJoin('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser', 'maxuser.TargetId', '=', 'users.Id')
    ->leftJoin('files', 'files.Id', '=', 'maxuser.maxid and files.Type="User"');
    if(!isset($_GET['PM']))
    {
      $_GET['PM']=0;
    }

    if ($_GET['PM'] == 1 && $_GET['Admin'] == 0) {
      $editor
      ->where('users.Department','%CME%' ,'like');

    }
    else if (isset( $_GET['Admin']) && $_GET['Admin'] == 0) {
      if (isset( $_GET['Departments'])) {

        $editor->where( function ( $q ) {
            $q->where("(users.Department","('".implode("','",explode(',',$_GET['Departments']))."'))",'IN', false);
        } );
      }
    }



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

    if (isset( $_GET['User'])) {
      if($_GET['User'] != "All")
      {
        $editor
            ->where( function ( $q ){
                $q->where('users.Id',$_GET['User'],'=');
            } );
      }
    }

      // $editor
      // ->where('users.Resignation_Date','','=' );

      $editor
      ->where( function ( $q ) {
        $q->where( 'users.Id', '(855, 883,902)', 'NOT IN', false );
      } );

      $editor
      ->where( function ( $q ) {
        $q->where( 'users.StaffId', '', '!=');
      } );

    $editor->process( $_POST )->json();
