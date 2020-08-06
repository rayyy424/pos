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

 if (isset($_GET['UserId']))
 {
   $_POST['UserId']=$_GET['UserId'];
 }

 if (isset($_GET['Id']))
 {
   $_POST['Id']=$_GET['Id'];
 }



// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'advancestatuses')
  	->fields(
      Field::inst( 'users.Name' ),
      Field::inst( 'advances.Purpose' ),
      Field::inst( 'advances.Destination' ),
      Field::inst( 'advances.Start_Date' ),
      Field::inst( 'advances.End_Dat' ),
      Field::inst( 'advances.Mode_Of_Transport' ),
      Field::inst( 'advances.Car_No' ),
      Field::inst( 'Approver' )
    )
    ->leftJoin('users','users.Id','=','advances.UserId')
    ->leftJoin('advancestatuses','advances.Id','=','advancestatuses.AdvanceId')
    ->leftJoin('users as approver','approver.Id','=','advancestatuses.UserId')

  if (isset( $_GET['Approver'])) {
    $editor
    ->where('advancestatuses.UserId',$_GET['Approver'] );
  }

  if (isset( $_GET['Id'])) {
    $editor
    ->where('advances.Id',$_GET['Id'] );
  }

    $editor->process( $_POST )->json();
