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
  $editor=Editor::inst( $db, 'employmenthistories' )
  	->fields(
      Field::inst( 'employmenthistories.Id' ),
      Field::inst( 'employmenthistories.UserId' ),
  		Field::inst( 'employmenthistories.Company' ),
      Field::inst( 'employmenthistories.Company_Address' ),
      Field::inst( 'employmenthistories.Company_Contact_No' ),
      Field::inst( 'employmenthistories.Start_Date' ),
      Field::inst( 'employmenthistories.End_Date' ),
      Field::inst( 'employmenthistories.Position' ),
      Field::inst( 'employmenthistories.Supervisor' ),
      Field::inst( 'employmenthistories.Remarks' ),
      Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/EmploymentHistory/__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'Employment History',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )
              )
      )
      ->leftJoin('files', 'files.TargetId', '=', 'employmenthistories.Id and files.Type="Employment History"')
      ->process( $_POST )
    	->json();
