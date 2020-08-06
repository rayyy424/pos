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
  $editor=Editor::inst( $db, 'users' )
  	->fields(
      Field::inst( 'users.Id' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'users.AllowanceSchemeId' ),
  		Field::inst( 'allowanceschemes.Scheme_Name' ),
      Field::inst( 'users.User_Type' ),
      Field::inst( 'users.Department' ),
      Field::inst( 'users.Position' ))
      ->leftJoin('allowanceschemes', 'users.AllowanceSchemeId', '=', 'allowanceschemes.Id')
      ->process( $_POST )
      ->json();
