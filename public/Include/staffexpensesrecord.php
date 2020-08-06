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
  $editor=Editor::inst( $db, 'staffexpenses' )
  	->fields(
        Field::inst( 'staffexpenses.Id' ),
        Field::inst( 'staffexpenses.UserId' ),
        Field::inst( 'staffexpenses.Type' ),
        Field::inst( 'staffexpenses.Year' ),

        Field::inst( 'staffexpenses.Date' ),
        Field::inst( 'staffexpenses.Amount' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'creator.Name' ),
        Field::inst( 'staffexpenses.created_by' ),
        Field::inst( 'staffexpenses.created_at' ),
        Field::inst( 'staffexpenses.updated_at' )
      )
      ->leftJoin('users', 'users.Id', '=', 'staffexpenses.UserId')
      ->leftJoin('users as creator', 'creator.Id', '=', 'staffexpenses.created_by');

      if (isset( $_GET['UserId'])) {
        $editor
        ->where('staffexpenses.UserId',$_GET['UserId'] );
      }

      if (isset( $_POST['UserId'])) {
        $editor
        ->where('staffexpenses.UserId',$_POST['UserId'] );
      }

      if (isset( $_GET['Start'])) {
       $editor
           ->where( function ( $q ){
               $q->where( 'str_to_date(staffexpenses.Date,"%d-%M-%Y")', '>= (str_to_date("'.$_GET['Start'].'","%d-%M-%Y"))', "", false );
               $q->where( 'str_to_date(staffexpenses.Date,"%d-%M-%Y")', '<= (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
           } );
      }

      $editor->process( $_POST )->json();



