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
  $editor=Editor::inst( $db, 'licenses' )
  	->fields(
      Field::inst( 'licenses.Id' ),
      Field::inst( 'licenses.UserId' ),
      Field::inst( 'licenses.Type' ),
      Field::inst( 'licenses.License_Type' ),
      Field::inst( 'licenses.Description' ),
      Field::inst( 'licenses.Remarks' ),
      Field::inst( 'users.Department' ),
  		Field::inst( 'users.Name' ),
      Field::inst( 'licenses.Identity_No' ),
      Field::inst( 'licenses.Expiry_Date' ),
      
      Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/License/__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'License',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )
              )
      )
      ->leftJoin('files', 'files.TargetId', '=', 'licenses.Id and files.Type="License"')
      ->leftJoin('users', 'users.Id', '=', 'licenses.UserId');

      if (isset( $_GET['type'])) {
        $editor
        ->where('licenses.Type',$_GET['type'] );
      }

      $editor->process( $_POST )->json();
