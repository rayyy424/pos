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


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'timesheetitems')
  	->fields(
      Field::inst( 'timesheetitems.Id' ),
      Field::inst( 'timesheetitems.TimesheetId' ),
      Field::inst( 'timesheetitems.Date' ),
      Field::inst( 'timesheetitems.Leader_Member' ),
      Field::inst( 'timesheetitems.Project_Code_Id' ),
      Field::inst( 'projectcodes.Project_Code' ),
      Field::inst( 'timesheetitems.ProjectId' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'timesheetitems.Site_Name' ),
      Field::inst( 'timesheetitems.State' ),
      Field::inst( 'projects.Project_Manager' ),
      Field::inst( 'pm.Name' ),
      Field::inst( 'timesheetitems.Check_In_Type' ),
      Field::inst( 'timesheetitems.Time_In' ),
      Field::inst( 'timesheetitems.Time_Out' ),
      Field::inst( 'timesheetitems.Allowance' ),
      Field::inst( 'timesheetitems.Reason' ),
      Field::inst( 'timesheetitems.Remarks' ),
      Field::inst( 'timesheetitemstatuses.UserId' ),
      Field::inst( 'approver.Name' ),
      Field::inst( 'timesheetitemstatuses.Status' ),
      Field::inst( 'timesheetitemstatuses.Comment' )
    )
    ->leftJoin('projects', 'timesheetitems.ProjectId', '=', 'projects.Id')
    ->leftJoin('projectcodes', 'timesheetitems.Project_Code_Id', '=', 'projectcodes.Id')
    ->leftJoin('users as pm', 'projects.Project_Manager', '=', 'pm.Id')
    ->leftJoin('timesheetitemstatuses', 'timesheetitems.Id', '=', 'timesheetitemstatuses.TimesheetItemId')
    ->leftJoin('users as approver', 'timesheetitemstatuses.UserId', '=', 'approver.Id');

    if (isset( $_GET['TimesheetId'])) {
      $editor
      ->where('timesheetitems.TimesheetId',$_GET['TimesheetId'] );
    }

    $editor->process( $_POST )->json();
