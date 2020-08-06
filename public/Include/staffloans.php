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
  $editor=Editor::inst( $db, 'staffloans')
    ->fields(
      Field::inst( 'staffloans.Id' ),
      Field::inst( 'staffloanstatuses.Id' ),
      Field::inst( 'staffloans.Type' ),
      Field::inst( 'staffloans.Purpose' ),
      Field::inst( 'staffloans.Date' ),
      Field::inst( 'staffloans.Total_Requested' ),
      Field::inst( 'staffloans.Total_Approved' ),
      Field::inst( 'staffloans.created_at' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'approver.Name' ),
      Field::inst( 'staffloanstatuses.Status' ),
      Field::inst( 'staffloanstatuses.Remarks' )
    )
    ->leftJoin('users as applicant', 'staffloans.UserId', '=', 'applicant.Id')
    ->leftJoin('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max', 'max.StaffLoanId', '=', 'staffloans.Id')
    ->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', 'max.maxid')

    ->leftJoin('projects', 'staffloans.ProjectId', '=', 'projects.Id')
    ->leftJoin('users as approver', 'staffloanstatuses.UserId', '=', 'approver.Id');

    if (isset( $_GET['UserId'])) {
      $editor
      ->where('staffloans.UserId',$_GET['UserId'] );
    }

    if (isset( $_GET['Status'])) {

      if(strpos($_GET['Status'],"Pending Approval")!==false)
      {
        $editor
        ->where( function ( $q ) {
            $q
                ->where( 'staffloanstatuses.Status', $_GET['Status'], 'like' )
                ->or_where( function ( $r ) {
                    $r->where( 'staffloanstatuses.Status', null);
                    $r->where( 'staffloans.UserId', $_GET['UserId']);
                } );
        } );
      }
      else {
        $editor
        ->where('staffloanstatuses.Status',$_GET['Status'],'like' );
      }

    }

    $editor->process( $_POST )->json();
