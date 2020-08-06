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
        Field::inst( 'creator.Name' ),
        Field::inst( 'staffdeductions.created_by' ),
        Field::inst( 'staffdeductions.created_at' ),
        Field::inst( 'staffdeductions.updated_at' )
      )
      ->on('preRemove', function ($editor, $id, $values) use ($db) {

          $ids = [];

          $deductionrecord = $db->raw()
                                 ->bind(':Id', $id)
                                 ->exec('SELECT Id, Type, TableRowId FROM staffdeductions WHERE Id = :Id AND TableRowId IS NOT NULL')
                                 ->fetch();

          if ($deductionrecord['TableRowId'] != NULL && $deductionrecord['TableRowId'] != "") {

            $rowid = $deductionrecord['TableRowId'];

            if ($deductionrecord['Type'] == 'STAFF LOAN') {
              $db->raw()->exec("DELETE FROM repaymentrecords WHERE Id = $rowid");
            } elseif ($deductionrecord['Type'] == 'PRE-SAVING SCHEME') {
              $db->raw()->exec("DELETE FROM presavingrecords WHERE Id = $rowid");
            }
          }
      })
      ->leftJoin('users', 'users.Id', '=', 'staffdeductions.UserId')
      ->leftJoin('users as creator', 'creator.Id', '=', 'staffdeductions.created_by');

      if (isset( $_GET['UserId'])) {
        $editor
        ->where('staffdeductions.UserId',$_GET['UserId'] );
      }

      if (isset( $_POST['UserId'])) {
        $editor
        ->where('staffdeductions.UserId',$_POST['UserId'] );
      }

      if (isset( $_GET['Start'])) {
       $editor
           ->where( function ( $q ){
               $q->where( 'str_to_date(staffdeductions.Date,"%d-%M-%Y")', '>= (str_to_date("'.$_GET['Start'].'","%d-%M-%Y"))', "", false );
               $q->where( 'str_to_date(staffdeductions.Date,"%d-%M-%Y")', '<= (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
           } );
      }

      $editor->process( $_POST )->json();



