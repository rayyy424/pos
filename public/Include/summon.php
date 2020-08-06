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
  $editor=Editor::inst( $db, 'summons' )
  	->fields(
        Field::inst( 'summons.Id' ),
        Field::inst( 'summons.DeductionId' ),
        Field::inst( 'summons.UserId' ),
        Field::inst( 'summons.Vehicle_No' ),
        Field::inst( 'summons.Company' ),
    		Field::inst( 'summons.Place' ),
        Field::inst( 'summons.Summon_No' ),
        Field::inst( 'summons.Date' ),
        Field::inst( 'summons.Time' ),
        Field::inst( 'summons.Offense' ),
        Field::inst( 'summons.Amount' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'users.Department' ),
        Field::inst( 'summons.Company_Deduction' ),
        Field::inst( 'summons.Total_Deduction' ),
        Field::inst( 'summons.Employer_Bare' ),
        Field::inst( 'summons.Settlement_Date' ),
        Field::inst( 'summons.Remarks' )
      )
     ->leftJoin('users','users.Id','=','summons.UserId')
     ->leftJoin('deductions','deductions.Id','=','summons.DeductionId');

     if (isset( $_GET['DeductionId'])) {
       $editor
       ->where('summons.DeductionId',$_GET['DeductionId'] );
     }

      $editor->process( $_POST )->json();
