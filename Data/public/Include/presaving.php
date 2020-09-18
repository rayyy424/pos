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
if(!isset($_GET['Year']))
{
  $_GET['Year']=$_POST['Year'];
}

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'presaving' )
  	->fields(
        Field::inst( 'presaving.Id' ),


        Field::inst( 'presaving.Presaving_Scheme' ),
        Field::inst( 'presaving.Presaving_Start_On' )->validator( 'Validate::notEmpty' ),
        Field::inst( 'presaving.Presaving_End_Date' ),
        Field::inst( 'presaving.Presaving_Monthly_Amount' ),

        Field::inst( 'a.Total' ),
        Field::inst( 'b.Total' ),
        Field::inst( 'c.Total' ),
        Field::inst( 'd.Total' ),
        Field::inst( 'presaving.UserId' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'users.Position' ),
        Field::inst( 'users.Joining_Date' ),
        Field::inst( 'users.Passport_No' ),
        Field::inst( 'users.Nationality' ),
        Field::inst( 'users.StaffId' ),
        Field::inst( 'presaving.created_by' ),
        Field::inst( 'creator.Name' ),
        Field::inst( 'presaving.created_at' ),
        Field::inst( 'presaving.updated_at' )
      )
      ->on('postCreate', function ($editor, $id, $values, $row) use ($db) {

              date_default_timezone_set("Asia/Kuala_Lumpur");

              $start = strtotime('19 '.$values["presavingrecords"]["From"]);
              $start = date('d F Y', $start);
              $start = date('d-M-Y', strtotime($start));

              $result = $db->raw()
                 ->bind(':Payment_Date', $start)
                 ->bind(':PresavingId', $id)
                 ->bind(':Amount', $values["presaving"]["Presaving_Monthly_Amount"])
                 ->bind(':Created_At', date('Y-m-d H:i:s'))
                 ->bind(':created_by', $values['presaving']['created_by'])
                 ->exec("INSERT INTO presavingrecords (PresavingId,Type,Payment_Date,Amount,Reason,created_at, created_by) VALUES (:PresavingId,'Saving',:Payment_Date,:Amount,'',:Created_At,:created_by)");

              $insertId = $result->insertId();


              $db->raw()
                 ->bind(':UserId', $values['presaving']['UserId'])
                 ->bind(':Type', 'PRE-SAVING SCHEME')
                 ->bind(':Month', $values['presavingrecords']['From'])
                 ->bind(':Date', $start)
                 ->bind(':Amount', $values['presaving']['Presaving_Monthly_Amount'])
                 ->bind(':Final_Amount', $values['presaving']['Presaving_Monthly_Amount'])
                 ->bind(':Description', '[FROM PRESAVING RECORD]')
                 ->bind(':TableRowId', $insertId)
                 ->bind(':Created_By', $values['presaving']['created_by'])
                 ->bind(':Created_At', date('Y-m-d H:i:s'))
                 ->exec("INSERT INTO staffdeductions (UserId,Type,Month,Date,Amount,FinalAmount,Description,TableRowId,created_by,created_at) VALUES (:UserId,:Type,:Month,:Date,:Amount,:Final_Amount,:Description,:TableRowId,:Created_By,:Created_At)");

      })
      ->on('postRemove', function ($editor, $id, $values) use ($db) {

          $ids = [];

          $presavingrecords = $db->raw()
                                 ->bind(':PresavingId', $id)
                                 ->exec('SELECT Id FROM presavingrecords WHERE PresavingId = :PresavingId')
                                 ->fetchAll();

          foreach($presavingrecords as $record) {
            array_push($ids, $record['Id']);
          }

          if (count($ids)) {

            $idstring = "('" . implode("','", $ids) . "')";

            $db->raw()->exec("DELETE FROM staffdeductions WHERE Type = 'PRE-SAVING SCHEME' AND TableRowId IN $idstring");
            $db->raw()->exec("DELETE FROM presavingrecords WHERE Id IN $idstring");
          }

      })
      ->leftJoin('(SELECT SUM(Amount)as Total,PresavingId FROM presavingrecords WHERE Type="Saving" and presavingrecords.Payment_Date like "%'.$_GET['Year'].'%" GROUP BY PresavingId) a', 'presaving.Id', '=', 'a.PresavingId')
      ->leftJoin('(SELECT SUM(Amount)as Total,PresavingId FROM presavingrecords WHERE Type="Withdraw" and presavingrecords.Payment_Date like "%'.$_GET['Year'].'%" GROUP BY PresavingId) b', 'presaving.Id', '=', 'b.PresavingId')
      ->leftJoin('(SELECT SUM(Amount)as Total,PresavingId FROM presavingrecords WHERE Type="Saving" and year(str_to_date(payment_date,"%d-%M-%Y")) < '.$_GET['Year'].' GROUP BY PresavingId) c', 'presaving.Id', '=', 'c.PresavingId')
      ->leftJoin('(SELECT SUM(Amount)as Total,PresavingId FROM presavingrecords WHERE Type="Withdraw" and year(str_to_date(payment_date,"%d-%M-%Y")) < '.$_GET['Year'].' GROUP BY PresavingId) d', 'presaving.Id', '=', 'd.PresavingId')
      ->leftJoin('users', 'users.Id', '=', 'presaving.UserId')
      ->leftJoin('users as creator', 'creator.Id', '=', 'presaving.created_by');

      $editor->process( $_POST )->json();
