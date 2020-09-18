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
  $editor=Editor::inst( $db, 'filingsystem')
  	->fields(
      Field::inst( 'filingsystem.Id' ),
      Field::inst( 'filingsystem.Type' ),
      Field::inst( 'filingsystem.Box_No' ),
      Field::inst( 'filingsystem.Box_File' ),
      Field::inst( 'filingsystem.File_Type' ),
      Field::inst( 'filingsystem.Company' ),
      Field::inst( 'filingsystem.Description' ),
      Field::inst( 'filingsystem.File_No' ),
      Field::inst( 'filingsystem.Date' ),
      Field::inst( 'filingsystem.Year' ),
      Field::inst( 'filingsystem.Prepared_By' ),
      Field::inst( 'filingsystem.Destruction_Date' ),
      Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/Filing System/__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'Filing System',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )
              )
      )
    ->leftJoin('files', 'files.TargetId', '=', 'filingsystem.Id and files.Type="Filing System" AND files.Id IN (select Max(Id) from files where files.Type="Filing System" Group By TargetId)');

    if (isset( $_GET['type'])) {
      $editor
      ->where('filingsystem.Type',$_GET['type'] );
    }

    $editor->process( $_POST )->json();
