<?php

include( "plugin/php/DataTables.php" );

// Alias Editor classes so they are easy to use
use
  DataTables\Editor,
  DataTables\Editor\Field,
  DataTables\Editor\Format,
  DataTables\Editor\Mjoin,
  DataTables\Editor\Upload,
  DataTables\Editor\Options,
  DataTables\Editor\Validate;

/*
 * Example PHP implementation used for the index.html example
 */


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'chartviews' )
  	->fields(
      Field::inst( 'chartviews.Id' ),
      Field::inst( 'chartviews.Chart_View_Name' ),
      Field::inst( 'chartviews.Chart_View_Type' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'chartviews.created_at' )

    )
    ->leftJoin('users', 'users.Id', '=', 'chartviews.Created_By')

    ->process( $_POST )
    ->json();
