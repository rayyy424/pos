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
  $editor=Editor::inst( $db, 'request')
    ->fields(
      Field::inst( 'request.Id' ),
      Field::inst( 'requeststatuses.Id' ),
      Field::inst( 'request.Request_type' ),
      Field::inst( 'request.Others' ),
      Field::inst( 'request.Approver' ),
      Field::inst( 'request.Start_Date' ),
      Field::inst( 'request.End_Date' ),
      Field::inst( 'request.Remarks' ),
      Field::inst( 'request.created_at' ),
      Field::inst( 'approver.Name' ),
      Field::inst( 'requeststatuses.Request_status' ),
      Field::inst( 'requeststatuses.updated_at' ),
      Field::inst( 'requeststatuses.Comment' ),

      Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/Request/__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'Request',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )
              )
      )
    ->leftJoin( '(select Max(Id) as maxid,TargetId from files where Type="Request" Group By Type,TargetId) as max2', 'max2.TargetId', '=', 'request.Id')
    ->leftJoin('files', 'files.Id', '=', 'max2.`maxid` and files.`Type`="Request"')

    ->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
    // ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
    ->leftJoin('(select Max(Id) as maxid,RequestId from requeststatuses Group By RequestId) as max', 'max.RequestId', '=', 'request.Id')
    ->leftJoin('requeststatuses', 'requeststatuses.Id', '=', 'max.maxid')
    ->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id');

    if (isset( $_GET['UserId'])) {
      $editor
      ->where('request.UserId',$_GET['UserId'] );
    }

    if (isset( $_GET['Request_status'])) {

      if(strpos($_GET['Request_status'],"Pending Approval")!==false)
      {
        $editor
        ->where( function ( $q ) {
            $q
                ->where( 'requeststatuses.Request_status', $_GET['Request_status'], 'like' )
                ->or_where( function ( $r ) {
                    $r->where( 'requeststatuses.Request_Status', null);
                    $r->where( 'request.UserId', $_GET['UserId']);
                } );
        } );
      }
      else {
        $editor
        ->where('requeststatuses.Request_status',$_GET['Request_status'],'like' );
      }

    }

    $editor->process( $_POST )->json();
