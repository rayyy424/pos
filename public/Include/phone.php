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
  $editor=Editor::inst( $db, 'phones')
  	->fields(
      Field::inst( 'phones.Id' ),
      Field::inst( 'phones.UserId' ),
      Field::inst( 'phones.Registered_Name' ),
      Field::inst( 'phones.Type' ),
      Field::inst( 'phones.Account_No' ),
      Field::inst( 'phones.Phone_No' ),
      Field::inst( 'phones.Current_Holder' ),
      Field::inst( 'phones.Department' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'users.Department' ),
      Field::inst( 'phones.Package' ),
      Field::inst( 'phones.Remarks' )
    )
    ->leftJoin('users','users.Id','=','phones.UserId');

    if (isset( $_GET['type'])) {
      $editor
      ->where('phones.Type',$_GET['type'] );
    }

    $editor->process( $_POST )->json();
