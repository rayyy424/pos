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
  $editor=Editor::inst( $db, 'agreement')
  	->fields(
      Field::inst( 'agreement.Id' ),
      Field::inst( 'agreement.Type' ),
      Field::inst( 'agreement.Department' ),
      Field::inst( 'agreement.Date_of_Agreement' ),
      Field::inst( 'agreement.Description_of_Agreement' ),
      Field::inst( 'agreement.Expiry_Date' ),
      Field::inst( 'agreement.Remarks' ),
      Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/Agreement/__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'Agreement',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )
              )
      )
      ->leftJoin('files', 'files.TargetId', '=', 'agreement.Id and files.Type="Agreement" AND files.Id IN (select Max(Id) from files where files.Type="Agreement" Group By TargetId)');


    if (isset( $_GET['type'])) {
      $editor
      ->where('agreement.Type',$_GET['type'] );
    }

    $editor->process( $_POST )->json();
