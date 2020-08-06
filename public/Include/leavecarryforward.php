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
  $editor=Editor::inst( $db, 'leavecarryforwards' )
  	->fields(
      Field::inst( 'leavecarryforwards.Id' ),
      Field::inst( 'users.Id' ),
      Field::inst( 'users.StaffId' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'leavecarryforwards.Year' ),
      Field::inst( 'leavecarryforwards.Days' )
    )

    ->leftJoin('users', 'users.Id', '=', 'leavecarryforwards.UserId')
    ->where('users.User_Type','Contractor','!=' );


    if (isset( $_GET['year'])) {
      $editor
      ->where('leavecarryforwards.Year',$_GET['year'] );
    }

      $editor
      ->process( $_POST )
      ->json();
