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
      Field::inst( 'leavestatuses.Id' ),
      Field::inst( 'leaves.Id' ),
      Field::inst( 'applicant.StaffId' ),
      Field::inst( 'applicant.Name' ),
      Field::inst( 'leaves.Leave_Type' ),
  		Field::inst( 'leaves.Leave_Term' ),
      Field::inst( 'leaves.Start_Date' ),
      Field::inst( 'leaves.End_Date' ),
      Field::inst( 'leaves.No_of_Days' ),
      Field::inst( 'leaves.Reason' ),
      Field::inst( 'leaves.Medical_Claim' ),
      Field::inst( 'leaves.Panel_Claim' ),
      Field::inst( 'leaves.Verified_By_HR' ),
      Field::inst( 'leaves.Medical_Paid_Month' ),
      Field::inst( 'leaves.created_at' ),
      Field::inst( 'leavestatuses.UserId' ),
      Field::inst( 'leaves.ProjectId' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'approver.Name' ),
      Field::inst( 'leavestatuses.Leave_Status' ),
      Field::inst( 'leavestatuses.Comment' ),
      Field::inst( 'leavestatuses.updated_at' ),
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
    // ->leftJoin('leavestatuses', 'leavestatuses.LeaveId', '=', 'leaves.Id')
    ->leftJoin( '(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2', 'max2.TargetId', '=', 'leaves.Id')
    ->leftJoin('files', 'files.Id', '=', 'max2.`maxid` and files.`Type`="Leave"')
    ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
        ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
    ->leftJoin('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max', 'max.LeaveId', '=', 'leaves.Id')
    ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', 'max.maxid')
    ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id');
  //   ->where( function ( $q ) {
  //   $q->where( 'leavestatuses.Id', '(select Max(Id) from leavestatuses Group By LeaveId)', 'IN', false );
  // } );

    if (isset( $_GET['UserId'])) {
      $editor
      ->where('leavestatuses.UserId',$_GET['UserId'] );
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
      else if(strpos($_GET['Status'],"Final Approved")!==false)
      {
        $editor
        ->where( function ( $q ) {
            $q
                ->where( 'leavestatuses.Leave_Status', $_GET['Status'], 'like' )
                ->where( 'str_to_date(leaves.Start_Date,"%d-%M-%Y")', 'BETWEEN (str_to_date("'.$_GET['Start'].'","%d-%M-%Y")) AND (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
        } );
      }
      else {
        $editor
        ->where('leavestatuses.Leave_Status',$_GET['Status'],'like' );
      }

    }

    if (isset( $_GET['Start'])) {
      $editor
          ->where( function ( $q ){
              $q->where( 'str_to_date(leaves.Start_Date,"%d-%M-%Y")', 'BETWEEN (str_to_date("'.$_GET['Start'].'","%d-%M-%Y")) AND (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
          } );
    }

    $editor->process( $_POST )->json();


    // ->leftJoin('leaves', 'leavestatuses.LeaveId', '=', 'leaves.Id')
    // ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
    // ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
    // ->process( $_POST )
  	// ->json();
