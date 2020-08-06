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

  if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'edit' || $_POST['action'] == 'create'))
  {
    foreach ($_POST['data'] as $key => $value) {

      // $_POST['data'][$key]['reload']['Balance']=$_POST['data'][$key]['reload2']['Balance']-$_POST['data'][$key]['reload']['Total_Reload']+$_POST['data'][$key]['reload']['Topup'];
      $_POST['data'][$key]['reload']['Balance']=$_POST['data'][$key]['reload']['Balance_Before']+$_POST['data'][$key]['reload']['Total_Reload'];

    }

  }


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'reload' )
  	->fields(
        Field::inst( 'reload.Id' ),
        Field::inst( 'reload.Card_No' ),
        Field::inst( 'reload.Request_By' ),
        Field::inst( 'reload.Project_Code' ),
        Field::inst( 'reloadBy.Name' ),
        Field::inst( 'reloadBy.Department' ),
        Field::inst( 'reload.Reload_By' ),
        Field::inst( 'reload.Date_Reload' ),
        Field::inst( 'reload.Balance_Before' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'users.Department' ),
        Field::inst( 'reload.Total_Reload' ),
        Field::inst( 'reload.Topup' ),
        Field::inst( 'reload.Balance' ),
        // Field::inst( 'reload2.Balance' ),
        Field::inst( 'reload.Remarks' )
      )
      ->leftJoin('users', 'users.Id', '=', 'reload.Request_By')
      ->leftJoin('users as reloadBy', 'reloadBy.Id', '=', 'reload.Reload_By');

      $editor->process( $_POST )->json();
