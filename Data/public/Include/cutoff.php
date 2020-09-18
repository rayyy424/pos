<?php


session_start();
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
  $editor=Editor::inst( $db, 'cutoff' )
  	->fields(
      Field::inst( 'cutoff.Id' ),
      Field::inst( 'cutoff.Payment_Month' )
          ->validator('Validate::notEmpty')->validator('Validate::unique'),
      Field::inst( 'cutoff.Start_Date' ),
      Field::inst( 'cutoff.End_Date' )
      )
      ->process( $_POST )
      ->json();
