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
  $editor=Editor::inst( $db, 'salary' )
  	->fields(
      Field::inst( 'salary.Id' ),
      Field::inst( 'salary.UserId' ),
  		Field::inst( 'salary.Salary' ),
      Field::inst( 'salary.Remarks' ),
      Field::inst( 'salary.Created_By' ),
      Field::inst( 'salary.created_at' )

      )
      ->process( $_POST )
      ->json();
