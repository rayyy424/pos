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

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'holidays' )
  	->fields(
      Field::inst( 'holidays.Id' ),
      Field::inst( 'holidays.Holiday' ),
      Field::inst( 'holidays.Start_Date' ),
  		Field::inst( 'holidays.End_Date' ),
      Field::inst( 'holidays.State' ),
      Field::inst( 'holidays.Country' ));

      if (isset( $_GET['year'])) {
        $editor
        ->where('right(holidays.Start_Date,4)',$_GET['year']);
      }

      $editor->process( $_POST )->json();
