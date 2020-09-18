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
  $editor=Editor::inst( $db, 'servicecontact' )
  	->fields(
      Field::inst( 'servicecontact.Id' ),
      Field::inst( 'servicecontact.Company' ),
      Field::inst( 'servicecontact.Services' ),
      Field::inst( 'servicecontact.Contact_Person' ),
      Field::inst( 'servicecontact.Contact_No' )

    )

    ->process( $_POST )->json();
