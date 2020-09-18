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
  $editor=Editor::inst( $db, 'userability' )
  	->fields(
      Field::inst( 'userability.Id' ),
      Field::inst( 'users.StaffId' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'userability.Ability' )
    )

    ->leftJoin('users', 'users.Id', '=', 'userability.UserId')

      ->process( $_POST )
      ->json();
