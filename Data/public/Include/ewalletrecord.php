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
  $editor=Editor::inst( $db, 'ewallet' )
  	->fields(
        Field::inst( 'ewallet.Id' ),
        Field::inst( 'ewallet.Date' )->validator( 'Validate::notEmpty' ),
        Field::inst( 'ewallet.Type' )->validator( 'Validate::notEmpty' ),
        Field::inst( 'ewallet.Expenses_Type' ),
        Field::inst( 'ewallet.Amount' )->validator( 'Validate::notEmpty' ),
        Field::inst( 'ewallet.Remarks' ),
        Field::inst( 'ewallet.UserId' ),
        Field::inst( 'ewallet.TrackerId' ),
        Field::inst( 'ewallet.created_at' ),
        Field::inst( 'ewallet.created_by' ),
        Field::inst( 'creator.Name' ),
        Field::inst( 'creator.Company' ),
        Field::inst( 'ewallet.updated_at' ),
        Field::inst('verify.Name'),
        Field::inst('ewallet.verified_at')
      )
      ->leftJoin('tracker', 'ewallet.TrackerId', '=', 'tracker.Id')
      ->leftJoin('users as creator', 'creator.Id', '=', 'ewallet.created_by')
      ->leftjoin('users as verify','verify.Id','=','ewallet.verified_by');

      if (isset( $_POST['UserId'])) {
        $editor
        ->where('ewallet.UserId',$_POST['UserId'] );
      }

      if (isset( $_GET['UserId'])) {
        $editor
        ->where('ewallet.UserId',$_GET['UserId'] );
      }

      if (isset( $_POST['TrackerId'])) {
        $editor
        ->where('ewallet.TrackerId',$_POST['TrackerId'] );
      }

      if (isset( $_GET['TrackerId'])) {
        $editor
        ->where('ewallet.TrackerId',$_GET['TrackerId'] );
      }

      if (isset( $_GET['Expenses_Type'])) {
        $editor
        ->where('ewallet.Expenses_Type',$_GET['Expenses_Type'] );
      }

      if (!isset( $_GET['Expenses_Type'])) {
        $editor
        ->where('ewallet.Expenses_Type','Shell Card','!=' );
      }


      if (isset( $_GET['Start'])) {
        $editor
        ->where( function ( $q ){
            $q->where( 'str_to_date(ewallet.Date,"%d-%M-%Y")', 'BETWEEN (str_to_date("'.$_GET['Start'].'","%d-%M-%Y")) AND (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
        } );
      }

      if (isset( $_GET['UserId'])) {
        $editor
        ->where('ewallet.UserId',$_GET['UserId'] );
      }

      $editor->process( $_POST )->json();
