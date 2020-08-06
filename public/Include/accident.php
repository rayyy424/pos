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

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'deductionitems' )
  	->fields(
        Field::inst( 'deductionitems.Id' ),
        Field::inst( 'deductionitems.DeductionId' ),
        Field::inst( 'deductionitems.UserId' ),
        Field::inst( 'deductionitems.Car_No' ),
        Field::inst( 'deductionitems.Date' ),
        Field::inst( 'deductionitems.Time' ),
        Field::inst( 'deductionitems.Amount' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'users.Department' ),
        Field::inst( 'deductionitems.Total_Deduction' ),
        Field::inst( 'deductionitems.Victim' )
      )
     ->leftJoin('users','users.Id','=','deductionitems.UserId')
     ->leftJoin('deductions','deductions.Id','=','deductionitems.DeductionId');

     if (isset( $_GET['DeductionId'])) {
       $editor
       ->where('deductionitems.DeductionId',$_GET['DeductionId'] );
     }

      $editor->process( $_POST )->json();
