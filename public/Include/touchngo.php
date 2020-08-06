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
  //     if (isset( $_POST['data'][$key]['touchngo']['Quantity']))
  //     {
  //       if ($_POST['data'][$key]['touchngo']['Quantity']>0 && $_POST['data'][$key]['touchngo']['Usage_RM_ltr']>0)
  //       {
  //         $_POST['data'][$key]['touchngo']['Total']=$_POST['data'][$key]['touchngo']['Quantity']*$_POST['data'][$key]['touchngo']['Usage_RM_ltr'];
  //       }
  //
  //     }
  //
  //   }
  //
  // }

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'touchngo' )
  	->fields(
        Field::inst( 'touchngo.Id' ),
        Field::inst( 'touchngo.UserId' ),
        Field::inst( 'touchngo.Username' ),
        Field::inst( 'touchngo.User_ID' ),
        Field::inst( 'touchngo.Card_No' ),
        Field::inst( 'touchngo.Vehicle_No' ),
        Field::inst( 'touchngo.Card_Type' ),
        Field::inst( 'touchngo.Registered_Name' ),
        Field::inst( 'touchngo.Plusmiles_Register' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'users.Department' ),
        Field::inst( 'touchngo.Date_Provide' ),
        Field::inst( 'touchngo.Date_Return' ),
        Field::inst( 'touchngo.Date_Terminate' ),
        Field::inst( 'touchngo.Remarks' )
      )
      ->leftJoin('users', 'users.Id', '=', 'touchngo.UserId');

      $editor->process( $_POST )->json();
