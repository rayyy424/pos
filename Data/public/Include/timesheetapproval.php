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

 if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'edit'))
 {

   foreach ($_POST['data'] as $key => $value) {

     if (isset( $_POST['data'][$key]['timesheets']['Time_In']))
     {

       $_POST['data'][$key]['timesheets']['Time_In']=strtoupper($_POST['data'][$key]['timesheets']['Time_In']);
       $_POST['data'][$key]['timesheets']['Time_In']=str_replace("AM"," AM",$_POST['data'][$key]['timesheets']['Time_In']);
       $_POST['data'][$key]['timesheets']['Time_In']=str_replace("  "," ",$_POST['data'][$key]['timesheets']['Time_In']);


     }

     if (isset( $_POST['data'][$key]['timesheets']['Time_Out']))
     {

       $_POST['data'][$key]['timesheets']['Time_Out']=strtoupper($_POST['data'][$key]['timesheets']['Time_Out']);
       $_POST['data'][$key]['timesheets']['Time_Out']=str_replace("AM"," AM",$_POST['data'][$key]['timesheets']['Time_Out']);
       $_POST['data'][$key]['timesheets']['Time_Out']=str_replace("  "," ",$_POST['data'][$key]['timesheets']['Time_Out']);


     }

   }
 }

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'timesheetstatuses')
  	->fields(
      Field::inst( 'timesheetstatuses.Id' ),
      Field::inst( 'timesheets.Id' ),
      Field::inst( 'timesheets.Latitude_In' ),
      Field::inst( 'timesheets.Longitude_In' ),
      Field::inst( 'timesheets.Latitude_Out' ),
      Field::inst( 'timesheets.Longitude_Out' ),
      Field::inst( 'timesheets.UserId' ),
      Field::inst( 'submitter.Name' ),
      Field::inst( 'timesheets.Date' ),
  		Field::inst( 'timesheets.Time_In' ),
      Field::inst( 'timesheets.Time_Out' ),
      Field::inst( 'timesheets.OT1' )->validator( 'Validate::numeric' ),
      Field::inst( 'timesheets.OT2' )->validator( 'Validate::numeric' ),
      Field::inst( 'timesheets.OT3' )->validator( 'Validate::numeric' ),
      Field::inst( 'timesheets.Leader_Member' ),
      Field::inst( 'timesheets.Next_Person' ),
      Field::inst( 'timesheets.Site_Name' ),
      Field::inst( 'timesheets.State' ),
      Field::inst( 'timesheets.Work_Description' ),
      Field::inst( 'timesheets.Allowance' )->validator( 'Validate::numeric' ),
      Field::inst( 'timesheets.Monetary_Comp' )->validator( 'Validate::numeric' ),
      Field::inst( 'timesheets.Check_In_Type' ),
      Field::inst( 'timesheets.Remarks' ),
      Field::inst( 'timesheetstatuses.UserId' ),
      Field::inst( 'approver.Name' ),
      Field::inst( 'timesheetstatuses.Status' ),
      Field::inst( 'timesheetstatuses.updated_at' ),
      Field::inst( 'timesheetstatuses.Comment' ),

      Field::inst( 'checker.Name' ),
      Field::inst( 'timesheetchecked.Timesheet_Status' ),
      Field::inst( 'timesheetchecked.Payment_Status' ),
      Field::inst( 'timesheetchecked.Updated_At' ),

      Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/Timesheet/__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'Timesheet',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )
              )
    )
    ->leftJoin('timesheets', 'timesheetstatuses.TimesheetId', '=', 'timesheets.Id')
    ->leftJoin('timesheetchecked', 'timesheetchecked.TimesheetId', '=', 'timesheets.Id AND timesheetchecked.Id IN (select max(Id) FROM timesheetchecked Group By TimesheetId)')
    ->leftJoin('users as submitter', 'timesheets.UserId', '=', 'submitter.Id')
    ->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
    ->leftJoin('users as checker', 'timesheetchecked.UserId', '=', 'checker.Id')
    ->leftJoin('files', 'files.TargetId', '=', 'timesheets.Id and files.Type="Timesheet"')->where( function ( $q ) {
    $q->where( 'timesheetstatuses.Id', '(select Max(Id) from timesheetstatuses Group By TimesheetId)', 'IN', false );
  } );


  if (isset( $_GET['Approver'])) {
    $editor
    ->where('timesheetstatuses.UserId',$_GET['Approver'] );
  }

    if (isset( $_GET['UserId'])) {
      $editor
      ->where('timesheets.UserId',$_GET['UserId'] );
    }

    if (isset( $_GET['Start'])) {
      $editor
          ->where( function ( $q ){
              $q->where( 'str_to_date(timesheets.Date,"%d-%M-%Y")', 'BETWEEN (str_to_date("'.$_GET['Start'].'","%d-%M-%Y")) AND (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
          } );
    }

    $editor->process( $_POST )->json();
