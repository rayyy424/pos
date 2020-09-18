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
  $editor=Editor::inst( $db, 'requeststatuses')
    ->fields(
      Field::inst( 'request.Id' ),
      Field::inst( 'requeststatuses.Id' ),
      Field::inst( 'request.Request_type' ),
      Field::inst( 'request.Others' ),
      Field::inst( 'request.Start_Date' ),
      Field::inst( 'request.End_Date' ),
      Field::inst( 'request.Approver' ),
      Field::inst( 'request.Remarks' ),
      Field::inst( 'request.created_at' ),
      Field::inst( 'approver.Name' ),
      Field::inst( 'applicant.Name' ),
      Field::inst( 'applicant.Position' ),
      Field::inst( 'requeststatuses.Request_status' ),
      Field::inst( 'requeststatuses.updated_at' ),
      Field::inst( 'requeststatuses.Comment' )
      )
    ->leftJoin('request', 'request.Id', '=', 'requeststatuses.RequestId')
    ->leftJoin( '(select Max(Id) as maxid,TargetId from files where Type="Request" Group By Type,TargetId) as max2', 'max2.TargetId', '=', 'request.Id')
    ->leftJoin('files', 'files.Id', '=', 'max2.`maxid` and files.`Type`="Request"')
    ->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
    ->leftJoin('(select Max(Id) as maxid,RequestId from requeststatuses Group By RequestId) as max', 'max.RequestId', '=', 'request.Id')
    ->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
    ->where( function ( $q ) {
    $q->where( 'requeststatuses.Id', '(select Max(Id) from requeststatuses Group By RequestId)', 'IN', false );
  } );

    if (isset( $_GET['UserId'])) {
      $editor
      ->where('requeststatuses.UserId',$_GET['UserId'] );
    }

    if (isset( $_GET['Request_status'])) {

      if(strpos($_GET['Request_status'],"Pending")!==false)
      {
        $editor
        ->where( function ( $q ) {
            $q
                ->where( 'requeststatuses.Request_status', $_GET['Request_status'], 'like' )
                ->or_where( function ( $r ) {
                    $r->where( 'requeststatuses.Request_status', null);
                    $r->where( 'request.UserId', $_GET['UserId']);
                } );
        } );
      }
      else if(strpos($_GET['Request_status'],"Final Approved")!==false)
      {
        $editor
        ->where( function ( $q ) {
            $q
                ->where( 'requeststatuses.Request_status', $_GET['Request_status'], 'like' );
        } );
      }
      else {
        $editor
        ->where('requeststatuses.Request_status',$_GET['Request_status'],'like' );
      }

    }

    $editor->process( $_POST )->json();


    // ->leftJoin('request', 'requeststatuses.RequestId', '=', 'request.Id')
    // ->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
    // ->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
    // ->process( $_POST )
    // ->json();
