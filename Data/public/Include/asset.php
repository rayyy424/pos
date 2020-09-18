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


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'assets')
  	->fields(
      Field::inst( 'assets.Id' ),
      Field::inst( 'assets.Label' ),
      Field::inst( 'assets.Type' ),
      Field::inst( 'assets.Availability' ),
      Field::inst( 'assets.Description' ),
      Field::inst( 'assets.Serial_No' ),
      Field::inst( 'assets.Model_No' ),
      Field::inst( 'assets.Car_No' ),
      Field::inst( 'assets.Software_License' ),
      Field::inst( 'assets.Rental_Company' ),
      Field::inst( 'assets.Remarks' ),
      Field::inst( 'assets.Ownership' ),
      Field::inst( 'assets.Rental_Start_Date' ),
      Field::inst( 'assets.Rental_End_Date' )
    )
    ->where('assets.Type',$_GET['type'] )
    ->process( $_POST )->json();
