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


  if (isset($_POST['Id']))
  {
    $id=$_POST['Id'];
  }
  else {
    $id=0;
  }
// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'roadtax' )
  	->fields(
        Field::inst( 'roadtax.Id' ),
        Field::inst( 'roadtax.UserId' ),
        Field::inst( 'roadtax.UserId2' ),
        Field::inst( 'roadtax.UserId3' ),
        Field::inst( 'roadtax.Option' ),
        Field::inst( 'roadtax.Vehicle_No' ),
        Field::inst( 'roadtax.Lorry_Dimension' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'driver.Name' ),
        Field::inst( 'driver2.Name' ),
        Field::inst( 'users.Department' ),
        Field::inst( 'roadtax.RoadTax_Expire_Date' ),
        Field::inst( 'roadtax.Insurance_Expiry_Date' ),
        Field::inst( 'roadtax.Insurance_Company' ),
        Field::inst( 'roadtax.Asset_Listed' ),
        Field::inst( 'roadtax.With_ShellCard' ),
        Field::inst( 'roadtax.Custodian' ),
        Field::inst( 'roadtax.Maker' ),
        Field::inst( 'roadtax.Model' ),
        Field::inst( 'roadtax.Year' ),
        Field::inst( 'roadtax.Owner' ),
        Field::inst( 'roadtax.Type' ),
        Field::inst( 'roadtax.Lorry_Size'),
        Field::inst( 'roadtax.Original_Reg_Card' ),
        Field::inst( 'roadtax.Availability' ),
        Field::inst( 'roadtax.Purchase_Date' ),
        Field::inst( 'roadtax.Financier' ),
        Field::inst( 'roadtax.Account_No' ),
        Field::inst( 'roadtax.Hire_Purchase' ),
        Field::inst( 'roadtax.First_Installment' ),
        Field::inst( 'roadtax.Monthly_Installment' ),
        Field::inst( 'roadtax.Personal_Accident' ),
        Field::inst( 'roadtax.Puspakom_Expiry' ),
        Field::inst( 'roadtax.SPAD_Expiry' ),
        Field::inst( 'roadtax.dimension' ),
        Field::inst( 'roadtax.PMA_Expiry' ),
        Field::inst( 'roadtax.NCD' ),
        Field::inst( 'roadtax.Loading' ),
        Field::inst( 'roadtax.Sum_Insured' ),
        Field::inst( 'roadtax.Windscreen' ),
        Field::inst( 'roadtax.Remarks' ),
        Field::inst( 'shellcards.Card_No' ),
        Field::inst( 'shellcards.Id' )
      )

      ->leftJoin('users', 'users.Id', '=', 'roadtax.UserId')
      ->leftJoin('users as driver', 'driver.Id', '=', 'roadtax.UserId2')
      ->leftJoin('users as driver2', 'driver2.Id', '=', 'roadtax.UserId3')
      ->leftJoin('shellcards', 'shellcards.Vehicle_No', '=', 'roadtax.Vehicle_No');

      if (isset( $_GET['option'])) {
        $editor
        ->where('roadtax.Option',$_GET['option'] );
      }

      $editor->process( $_POST )->json();
