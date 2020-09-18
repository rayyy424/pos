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
  $editor=Editor::inst( $db, 'staffloans' )
  	->fields(
        Field::inst( 'staffloans.Id' ),

        Field::inst( 'staffloans.Purpose' ),
        Field::inst( 'staffloans.Date' ),

        Field::inst( 'staffloanstatuses.update_at' ),
        // Field::inst( 'staffloans.Repayment_Start_On' ),
        Field::inst( 'staffloans.Total_Requested' ),
        Field::inst( 'staffloans.Total_Approved' ),
        Field::inst( 'paid_month.Total' ),
        Field::inst( 'staffloans.UserId' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'users.Position' ),
        Field::inst( 'users.StaffId' ),
        Field::inst( 'staffloans.Approver' ),
        Field::inst( 'us.Name' ),
        Field::inst( 'us.StaffId' ),

        Field::inst( 'staffloans.created_at' ),
        // Field::inst( 'staffloans.created_by' ),

        Field::inst( 'staffloans.updated_at' )
      )
      ->on('postRemove', function ($editor, $id, $values) use ($db) {

          $ids = [];

          $repaymentrecords = $db->raw()
                                 ->bind(':StaffLoanId', $id)
                                 ->exec('SELECT Id FROM repaymentrecords WHERE StaffLoanId = :StaffLoanId')
                                 ->fetchAll();

          foreach($repaymentrecords as $record) {
            array_push($ids, $record['Id']);
          }

          if (count($ids)) {

            $idstring = "('" . implode("','", $ids) . "')";

            $db->raw()->exec("DELETE FROM staffdeductions WHERE Type = 'STAFF LOAN' AND TableRowId IN $idstring");
            $db->raw()->exec("DELETE FROM repaymentrecords WHERE StaffLoanId = $id");
          }

      })
      ->leftJoin('(SELECT SUM(Amount)as Total,StaffLoanId FROM repaymentrecords GROUP BY StaffLoanId) a', 'staffloans.Id', '=', 'a.StaffLoanId')
      ->leftJoin('(SELECT SUM(Amount)as Total,StaffLoanId FROM repaymentrecords WHERE str_to_date(Payment_Date,"%d-%M-%Y") <= str_to_date("'.$dateFrom.'","%d-%M-%Y") GROUP BY StaffLoanId) paid_month', 'staffloans.Id', '=', 'paid_month.StaffLoanId')
      ->leftJoin('users', 'users.Id', '=', 'staffloans.UserId')
      ->leftJoin( '(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max', 'max.StaffLoanId', '=', 'staffloans.Id')
      ->leftJoin('staffloanstatuses','max.maxid','=','staffloanstatuses.Id')
      ->leftJoin('users as us','us.Id','=','staffloanstatuses.UserId');
      if(isset($_GET['end'])){
        $editor->where(function($q){
          $q->where('str_to_date(staffloans.Date,"%d-%M-%Y")','str_to_date("'.$_GET['end'].'","%d-%M-%Y")','<=',false);
        });
      }
      $editor->process( $_POST )->json();
