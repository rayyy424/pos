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
  $editor=Editor::inst( $db, 'tracker' )
  	->fields(
      Field::inst( 'tracker.Id' ),
      Field::inst( 'files.File_Name' ),
  		Field::inst( 'files.Web_Path' ),
      Field::inst( 'tracker.Site_ID' ),
      Field::inst( 'tracker.Site_Name' ),
      Field::inst( 'tracker.Region' ),
      Field::inst( 'submitter.Name' ),
      Field::inst( 'files.created_at' )
      )
      ->leftJoin('files', 'tracker.Id', '=',  'files.TargetId AND Type="Tracker" AND files.Document_Type="'.$_GET['Type'].'"')
      ->leftJoin('users as submitter', 'files.UserId', '=', 'submitter.Id')
      ->where( 'files.Id', null, '!=')
      ->process( $_POST )
      ->json();
