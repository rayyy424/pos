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
  $editor=Editor::inst( $db, 'staffdeductions' )
  	->fields(
        Field::inst( 'staffdeductions.Id' ),
        Field::inst( 'staffdeductions.UserId' ),
        Field::inst( 'staffdeductions.Type' ),
        Field::inst( 'staffdeductions.Month' ),
        Field::inst( 'staffdeductions.Date' ),
        Field::inst( 'staffdeductions.Description' ),
        Field::inst( 'staffdeductions.FinalAmount' ),
        Field::inst( 'staffdeductions.Amount' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'users.StaffId' ),
        Field::inst( 'users.Department' ),
        Field::inst( 'users.Position' ),
        Field::inst( 'users.Joining_Date' ),
        Field::inst( 'users.Nationality' ),
        Field::inst( 'creator.Name' ),
        Field::inst( 'staffdeductions.created_by' ),
        Field::inst( 'staffdeductions.created_at' ),
        Field::inst( 'staffdeductions.updated_at' )
      )
      ->leftJoin('users', 'users.Id', '=', 'staffdeductions.UserId')
      ->leftJoin('users as creator', 'creator.Id', '=', 'staffdeductions.created_by');

      if (isset( $_GET['Type'])) {
        $editor
        ->where('staffdeductions.Type',$_GET['Type'] );
      }

      if (isset( $_GET['Start'])) {
       $editor
           ->where( function ( $q ){
               $q->where( 'str_to_date(staffdeductions.Date,"%d-%M-%Y")', '>= (str_to_date("'.$_GET['Start'].'","%d-%M-%Y"))', "", false );
               $q->where( 'str_to_date(staffdeductions.Date,"%d-%M-%Y")', '<= (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
           } );
      }

      $editor->process( $_POST )->json();



