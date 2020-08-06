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
  $editor=Editor::inst( $db, 'nas' )
  	->fields(
      Field::inst( 'nas.Id' ),
      Field::inst( 'nas.UserId' ),
      Field::inst( 'users.Name' ),
  		Field::inst( 'users.Position' ),
      Field::inst( 'users.Department' ),
      Field::inst( 'nas.PC_Name' ),
      Field::inst( 'nas.User_ID' ),
      Field::inst( 'nas.Password' ),
      Field::inst( 'nas.Share_Folders' )

      )

  ->leftJoin('users','users.Id','=','nas.UserId' )
  ->process( $_POST )
  ->json();
