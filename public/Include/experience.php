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
  $editor=Editor::inst( $db, 'experiences' )
  	->fields(
      Field::inst( 'experiences.Id' ),
      Field::inst( 'experiences.UserId' ),
  		Field::inst( 'experiences.Project' ),
      Field::inst( 'experiences.Role' ),
      Field::inst( 'experiences.Responsibility' ),
      Field::inst( 'experiences.Achievement' ),
      Field::inst( 'experiences.Start_Date' ),
      Field::inst( 'experiences.End_Date' ),
      Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/Experience/__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'Experience',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )
              )
      )
      ->leftJoin('files', 'files.TargetId', '=', 'experiences.Id and files.Type="Experience"')
      ->process( $_POST )
      ->json();
