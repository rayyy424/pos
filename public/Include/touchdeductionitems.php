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

 if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'edit' || $_POST['action'] == 'create') )
 {

   foreach ($_POST['data'] as $key => $value) {

     if (isset( $_POST['data'][$key]['deductionitems']['Total_Deduction']))
     {
       if ($_POST['data'][$key]['deductionitems']['Total_Deduction']>0)
       {
             $_POST['data'][$key]['deductionitems']['Penalty']=$_POST['data'][$key]['deductionitems']['Total_Deduction'] * 5;
             // $_POST['data'][$key]['printer']['Total']= $_POST['data'][$key]['printer']['Quantity']*0.500;

       }
     }
   }
 }


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'deductionitems')
  	->fields(
      Field::inst( 'deductionitems.Id' ),
      Field::inst( 'deductionitems.DeductionId' ),
      Field::inst( 'deductionitems.UserId' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'deductionitems.Date' ),
      Field::inst( 'deductionitems.Time' ),
      Field::inst( 'deductionitems.Card_Serial' ),
      Field::inst( 'deductionitems.Entry_location' ),
      Field::inst( 'deductionitems.Amount' ),
      Field::inst( 'deductionitems.Total_Deduction' ),
      Field::inst( 'deductionitems.Penalty' )

    )
    ->leftJoin('users', 'users.Id', '=', 'deductionitems.UserId')
    ->leftJoin('deductions','deductions.Id','=','deductionitems.DeductionId');

    if (isset( $_GET['DeductionId'])) {
      $editor
      ->where('deductionitems.DeductionId',$_GET['DeductionId'] );
    }

    $editor->process( $_POST )->json();
