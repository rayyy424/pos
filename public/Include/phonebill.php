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
  $editor=Editor::inst( $db, 'phonebills')
  	->fields(
      Field::inst( 'phonebills.Id' ),
      Field::inst( 'phonebills.UserId' ),
      Field::inst( 'phonebills.Registered_Name' ),
      Field::inst( 'phonebills.Type' ),
      Field::inst( 'phonebills.Account_No' ),
      Field::inst( 'phonebills.Bill_No' ),
      Field::inst( 'phonebills.Phone_No' ),
      Field::inst( 'phonebills.Current_Holder' ),
      Field::inst( 'phonebills.Department' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'users.Department' ),
      Field::inst( 'phonebills.Package' ),
      Field::inst( 'phonebills.Amount' )->validator( 'Validate::numeric' ),
      Field::inst( 'phonebills.GST' )->validator( 'Validate::numeric' ),
      Field::inst( 'phonebills.Total' )->validator( 'Validate::numeric' ),
      Field::inst( 'phonebills.Bill_Date' ),
      Field::inst( 'phonebills.Due_Date' ),
      Field::inst( 'phonebills.Credit_Card_No' ),
      Field::inst( 'phonebills.Transaction_Date' ),
      Field::inst( 'phonebills.Transfer_Amount' ),
      Field::inst( 'phonebills.Remarks' )
    )
    ->leftJoin('users','users.Id','=','phonebills.UserId');

    if (isset( $_GET['type'])) {
      $editor
      ->where('phonebills.Type',$_GET['type'] );
    }

    $editor->process( $_POST )->json();
