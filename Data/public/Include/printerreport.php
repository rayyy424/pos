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
  $editor=Editor::inst( $db, 'printerreports')
  	->fields(
      Field::inst( 'printerreports.Id' ),
      Field::inst( 'printerreports.Bill_Date' ),
      Field::inst( 'printerreports.Grand_Total' )
    );

    $editor->process( $_POST )->json();
