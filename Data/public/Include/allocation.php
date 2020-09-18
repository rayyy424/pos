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
  $editor=Editor::inst( $db, "userprojects" )
  	->fields(
      Field::inst( 'userprojects.Id' ),
      Field::inst( 'users.StaffId' ),
      Field::inst( 'users.Id' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'users.Position' ),
      Field::inst( 'users.Grade' ),
      Field::inst( 'users.User_Type' ),
      Field::inst( 'users.Home_Base' ),
      Field::inst( 'userability.Ability' ),
      Field::inst( 'userprojects.Assigned_As' ),
      Field::inst( 'userprojects.Start_Date' ),
      Field::inst( 'userprojects.End_Date' )

    )

    ->leftJoin('users', 'userprojects.UserId', '=', 'users.Id')
    ->leftJoin('userability','users.Id','=','userability.UserId')


      ->process( $_POST )
      ->json();
