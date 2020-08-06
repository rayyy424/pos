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
  $editor=Editor::inst( $db, 'printer' )
  	->fields(
      Field::inst( 'printer.Id' ),
      Field::inst( 'printer.Name' ),
      Field::inst( 'printer.Floor' ),
      Field::inst( 'printer.Type' ),
      Field::inst( 'printer.Printer_ID' ),
      Field::inst( 'printer.Password' )
    )
      ->where('printer.Type','Pin' );



  $editor->process( $_POST )->json();
