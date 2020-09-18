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
  $editor=Editor::inst( $db, 'fionrecord' )
  	->fields(
        Field::inst( 'fionrecord.Id' ),
        Field::inst( 'fionrecord.Date' )->validator( 'Validate::notEmpty' ),
        Field::inst( 'fionrecord.Type' )->validator( 'Validate::notEmpty' ),

        Field::inst( 'fionrecord.Amount' )->validator( 'Validate::notEmpty' ),
        Field::inst( 'fionrecord.Remarks' ),

        Field::inst( 'fionrecord.created_at' ),
        Field::inst( 'fionrecord.created_by' ),
        Field::inst( 'creator.Name' ),
        Field::inst( 'fionrecord.updated_at' )
      )
      ->leftJoin('users as creator', 'creator.Id', '=', 'fionrecord.created_by');

      if (isset( $_GET['Start'])) {
        $editor
        ->where( function ( $q ){
            $q->where( 'str_to_date(fionrecord.Date,"%d-%M-%Y")', 'BETWEEN (str_to_date("'.$_GET['Start'].'","%d-%M-%Y")) AND (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
        } );
      }

      $editor->process( $_POST )->json();
