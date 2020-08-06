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
  $editor=Editor::inst( $db, 'holidayterritories' )
  	->fields(
      Field::inst( 'holidayterritories.Id' ),
      Field::inst( 'holidayterritories.Name' ),
      Field::inst( 'holidayterritories.Description' ),
  		Field::inst( 'holidayterritories.Remark' ),
      Field::inst( 'holidayterritories.created_at' ),
      Field::inst( 'holidayterritories.updated_at' ));

      $editor->process( $_POST )->json();
