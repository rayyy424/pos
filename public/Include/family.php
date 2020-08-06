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
  $editor=Editor::inst( $db, 'family' )
  	->fields(
      Field::inst( 'family.Id' ),
      Field::inst( 'family.UserId' ),
      Field::inst( 'family.Name' ),
      Field::inst( 'family.NRIC' ),
  		Field::inst( 'family.Gender' ),
      Field::inst( 'family.Age' ),
      Field::inst( 'family.Relationship' ),
      Field::inst( 'family.Occupation' ),
      Field::inst( 'family.Company_School_Name' ),
      Field::inst( 'family.Contact_No' )
)

      ->process( $_POST )
      ->json();
