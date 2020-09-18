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
  $editor=Editor::inst( $db, "userprojects" ,'userprojects.Id')
  	->fields(
      Field::inst( 'userprojects.Id' ),
      Field::inst( 'users.StaffId' ),
      Field::inst( 'users.Id' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'userability.Ability' ),
      Field::inst( 'userprojects.Assigned_As' ),
      Field::inst( 'userprojects.Start_Date' ),
      Field::inst( 'userprojects.End_Date' )

    )

    ->leftJoin('users', 'userprojects.UserId', '=', 'users.Id')
    ->leftJoin('userability','users.Id','=','userability.UserId');

    if (isset( $_GET['role'])) {
      $editor
      ->where('userability.Ability', $_GET['role']);
    }

      $editor->process( $_POST )->json();
