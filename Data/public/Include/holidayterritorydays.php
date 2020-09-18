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
  $editor=Editor::inst( $db, 'holidayterritorydays' )
  	->fields(
      Field::inst( 'holidayterritorydays.Id' ),
      Field::inst( 'holidayterritorydays.Holiday' ),
      Field::inst( 'holidayterritorydays.Start_Date' ),
  		Field::inst( 'holidayterritorydays.End_Date' ),
      Field::inst( 'holidayterritorydays.State' ),
      Field::inst( 'holidayterritorydays.Country' ),
      Field::inst( 'holidayterritorydays.HolidayTerritoryId' ));

      if (isset( $_GET['year'])) {
        $editor
        ->where('right(holidayterritorydays.Start_Date,4)',$_GET['year']);
      }

      if (isset( $_GET['id'])) {
        $editor
        ->where('holidayterritorydays.HolidayTerritoryId',$_GET['id']);
      }

      $editor->process( $_POST )->json();
