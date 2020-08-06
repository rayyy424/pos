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
  $editor=Editor::inst( $db, 'deliverytracking')
  	->fields(
      Field::inst( 'deliverytracking.Id' ),
      Field::inst( 'deliverytracking.Date' ),
      Field::inst( 'deliverytracking.Time' ),
      Field::inst( 'deliverytracking.Location' ),
      Field::inst( 'deliverytracking.Activity' ),
      // Field::inst( 'agreement.Expiry_Date' ),
      // Field::inst( 'agreement.Remarks' ),
      // Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/DeliveryTracking/__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'Agreement',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )
              )
      )
      ->leftJoin('files', 'files.TargetId', '=', 'deliverytracking.Id and files.Type="DeliveryTracking" AND files.Id IN (select Max(Id) from files where files.Type="DeliveryTracking" Group By TargetId)');


    if (isset( $_GET['type'])) {
      $editor
      ->where('deliverytracking.Type',$_GET['type'] );
    }

    $editor->process( $_POST )->json();
