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

  // if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'edit'))
  // {
  //
  //   foreach ($_POST['data'] as $key => $value) {
  //
  //     if (isset( $_POST['data'][$key]['shellcards']['Quantity']))
  //     {
  //       if ($_POST['data'][$key]['shellcards']['Quantity']>0 && $_POST['data'][$key]['shellcards']['Usage_RM_ltr']>0)
  //       {
  //         $_POST['data'][$key]['shellcards']['Total']=$_POST['data'][$key]['shellcards']['Quantity']*$_POST['data'][$key]['shellcards']['Usage_RM_ltr'];
  //       }
  //
  //     }
  //
  //   }
  //
  // }

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'shellcards' )
  	->fields(
        Field::inst( 'shellcards.Id' ),
        Field::inst( 'shellcards.UserId' ),
        Field::inst( 'shellcards.Company' ),
        Field::inst( 'shellcards.Vehicle_No' ),
        Field::inst( 'shellcards.Account_No' ),
        Field::inst( 'shellcards.Card_No' ),
        Field::inst( 'shellcards.Type' ),
        Field::inst( 'shellcards.Pin_Code' ),
        Field::inst( 'shellcards.Limit_Month' ),
        Field::inst( 'shellcards.Expiry_Date' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'users.Position' ),
        Field::inst( 'shellcards.Remarks' )
      )
      ->leftJoin('users', 'users.Id', '=', 'shellcards.UserId');

      $editor->process( $_POST )->json();
