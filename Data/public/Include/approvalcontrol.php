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
 if (isset($_POST['Id']))
 {
   $id=$_POST['Id'];
 }
 else {
   $id=0;
 }

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'approvalsettings' )
  	->fields(
      Field::inst( 'approvalsettings.Id' ),
      Field::inst( 'approvalsettings.Country' ),
      Field::inst( 'approvalsettings.Type' ),
      Field::inst( 'approvalsettings.UserId' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'approvalsettings.Level' ),
      Field::inst( 'approvalsettings.Created_By' )
    )
    ->leftJoin('users', 'approvalsettings.UserId', '=', 'users.Id');

      if (isset( $_GET['type'])) {
        $editor
        ->where('approvalsettings.Type',$_GET['type'] );
      }

      $editor->process( $_POST )->json();
