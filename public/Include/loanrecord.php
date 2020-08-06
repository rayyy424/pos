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

  date_default_timezone_set("Asia/Kuala_Lumpur");

  $start            = strtotime('today');
  // 19 last month
  $firstMonthStart  = strtotime("+18 day", strtotime("first day of last month", $start));
  // 19 this month
  $secondMonthStart = strtotime("+18 day", strtotime("first day of this month", $start));

  if ($start < $secondMonthStart) {
      $dateFrom = date('d-M-Y',$firstMonthStart);
  } else {
      $dateFrom = date('d-M-Y',$secondMonthStart);
  }

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'repaymentrecords' )
  	->fields(
        Field::inst( 'repaymentrecords.Id' ),
        Field::inst( 'repaymentrecords.StaffLoanId' ),
        Field::inst( 'repaymentrecords.Payment_Date' ),
        Field::inst( 'repaymentrecords.Amount' ),
        Field::inst( 'repaymentrecords.created_at' ),
        Field::inst( 'repaymentrecords.updated_at' ),
        Field::inst( 'paid_month.Total' )
      )
    ->leftJoin('(SELECT SUM(Amount)as Total,StaffLoanId FROM repaymentrecords WHERE str_to_date(Payment_Date,"%d-%M-%Y") <= str_to_date("'.$dateFrom.'","%d-%M-%Y") GROUP BY StaffLoanId) paid_month', 'repaymentrecords.StaffLoanId', '=', 'paid_month.StaffLoanId')
    ->on('postCreate', function ($editor, $id, $values, $row) use ($db) {

        date_default_timezone_set("Asia/Kuala_Lumpur");
        $userid = $db->raw()
           ->bind(':Id', $values['repaymentrecords']['StaffLoanId'])
           ->exec("SELECT UserId FROM staffloan WHERE Id = :Id")
           ->fetch();

        $db->raw()
           ->bind(':UserId', $userid['UserId'])
           ->bind(':Type', 'STAFF LOAN')
           ->bind(':Month', date('F Y', strtotime($values['repaymentrecords']['Payment_Date'])))
           ->bind(':Date', $values['repaymentrecords']['Payment_Date'])
           ->bind(':Amount', $values['repaymentrecords']['Amount'])
           ->bind(':Final_Amount', $values['repaymentrecords']['Amount'])
           ->bind(':Description', '[FROM LOAN RECORD]')
           ->bind(':TableRowId', $id)
           ->bind(':Created_By', $userid['UserId'])
           ->bind(':Created_At', date('Y-m-d H:i:s'))
           ->exec("INSERT INTO staffdeductions (UserId,Type,Month,Date,Amount,FinalAmount,Description,TableRowId,created_by,created_at) VALUES (:UserId,:Type,:Month,:Date,:Amount,:Final_Amount,:Description,:TableRowId,:Created_By,:Created_At)");

      })
      ->on('postRemove', function ($editor, $id, $values) use ($db) {

          $db->raw()->exec("DELETE FROM staffdeductions WHERE Type = 'STAFF LOAN' AND TableRowId = $id");

      });

      if (isset( $_GET['Id'])) {
        $editor
        ->where('repaymentrecords.StaffLoanId',$_GET['Id'] );
      }

      if (isset( $_POST['Id'])) {
        $editor
        ->where('repaymentrecords.StaffLoanId',$_POST['Id'] );
      }

      $editor->process( $_POST )->json();
