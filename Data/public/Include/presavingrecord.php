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

if(!isset($_GET['created_by']))
{
  $_GET['created_by']=$_POST['created_by'];
}

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'presavingrecords' )
  	->fields(
        Field::inst( 'presavingrecords.Id' ),
        Field::inst( 'presavingrecords.PresavingId' ),
        Field::inst( 'presavingrecords.Type' ),
        Field::inst( 'presavingrecords.Payment_Date' ),
        Field::inst( 'presavingrecords.Amount' ),
        Field::inst( 'presavingrecords.Reason' ),
        Field::inst( 'presavingrecords.created_at' ),
        Field::inst( 'presavingrecords.created_by' ),
        Field::inst( 'creator.Name' ),
        Field::inst( 'presavingrecords.updated_at' )
      )
      ->on('postCreate', function ($editor, $id, $values, $row) use ($db) {

            if ($values['presavingrecords']['Type'] == 'Saving') {
                date_default_timezone_set("Asia/Kuala_Lumpur");

                $presaving = $db->raw()
                 ->bind(':Id', $id)
                 ->exec("SELECT PresavingId FROM presavingrecords WHERE Id = :Id")
                 ->fetch();

                $userid = $db->raw()
                 ->bind(':Id', $presaving["PresavingId"])
                 ->exec("SELECT UserId FROM presaving WHERE Id = :Id")
                 ->fetch();

                $date = strtotime($values["presavingrecords"]["Payment_Date"]);
                $date = date('F Y', $date);


                $db->raw()
                   ->bind(':UserId', $userid['UserId'])
                   ->bind(':Type', 'PRE-SAVING SCHEME')
                   ->bind(':Month', $date)
                   ->bind(':Date', $values['presavingrecords']['Payment_Date'])
                   ->bind(':Amount', $values['presavingrecords']['Amount'])
                   ->bind(':Final_Amount', $values['presavingrecords']['Amount'])
                   ->bind(':Description', '[FROM PRESAVING RECORD]')
                   ->bind(':TableRowId', $id)
                   ->bind(':Created_By', $_GET['created_by'])
                   ->bind(':Created_At', date('Y-m-d H:i:s'))
                   ->exec("INSERT INTO staffdeductions (UserId,Type,Month,Date,Amount,FinalAmount,Description,TableRowId,created_by,created_at) VALUES (:UserId,:Type,:Month,:Date,:Amount,:Final_Amount,:Description,:TableRowId,:Created_By,:Created_At)");
            }


      })
      ->on('postRemove', function ($editor, $id, $values) use ($db) {

          $db->raw()->exec("DELETE FROM staffdeductions WHERE Type = 'PRE-SAVING SCHEME' AND TableRowId = $id");

      })
      ->leftJoin('users as creator', 'creator.Id', '=', 'presavingrecords.created_by');

      if (isset( $_GET['Id'])) {
        $editor
        ->where('presavingrecords.PresavingId',$_GET['Id'] );
      }

      if (isset( $_POST['Id'])) {
        $editor
        ->where('presavingrecords.PresavingId',$_POST['Id'] );
      }

      if (isset( $_GET['Year'])) {
        $editor
        ->where('presavingrecords.Payment_Date','%'.$_GET['Year'].'%','like' );
      }

      $editor->process( $_POST )->json();
