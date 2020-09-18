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
  $editor=Editor::inst( $db, 'trainings' )
  	->fields(
      Field::inst( 'trainings.Id' ),
      Field::inst( 'trainings.UserId' ),
  		Field::inst( 'trainings.Training' ),
      Field::inst( 'trainings.Description' ),
      Field::inst( 'trainings.Organizer' ),
      Field::inst( 'trainings.Training_Date' ),
      Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/Training__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'Training',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )
              )
      )
      ->leftJoin('files', 'files.TargetId', '=', 'trainings.Id and files.Type="Training"')
      ->process( $_POST )
      ->json();
