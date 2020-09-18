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
  $editor=Editor::inst( $db, 'companyloans' )
  	->fields(
        Field::inst( 'companyloans.Id' ),

        Field::inst( 'companyloans.Purpose' ),
        Field::inst( 'companyloans.Bank_Account_No' ),
        Field::inst( 'companyloans.Date' ),

        Field::inst( 'companyloanstatuses.update_at' ),
        // Field::inst( 'companyloans.Repayment_Start_On' ),
        Field::inst( 'companyloans.Total_Requested' ),
        Field::inst( 'companyloans.Total_Approved' ),
        Field::inst( 'companyloans.Status' ),
        Field::inst( 'paid_month.Total' ),
        Field::inst( 'companyloans.CompanyId' ),
        Field::inst( 'company.Company_Name' ),
        Field::inst( 'company.Branch' ),
        Field::inst( 'company.Register_Num' ),
        Field::inst( 'companyloans.Approver' ),
        Field::inst( 'com.Company_Name' ),
        Field::inst( 'com.Register_Num' ),
        Field::inst( 'us.Name' ),

        Field::inst( 'companyloans.created_at' ),
        // Field::inst( 'companyloans.created_by' ),

        Field::inst( 'companyloans.updated_at' )
      )
      ->on('postRemove', function ($editor, $id, $values) use ($db) {

          $ids = [];

          $repaymentrecords = $db->raw()
                                 ->bind(':CompanyLoanId', $id)
                                 ->exec('SELECT Id FROM companyloaninstallments WHERE CompanyLoanId = :CompanyLoanId')
                                 ->fetchAll();

          foreach($repaymentrecords as $record) {
            array_push($ids, $record['Id']);
          }


          if (count($ids)) {

            $idstring = "('" . implode("','", $ids) . "')";

            $db->raw()->exec("DELETE FROM companyloaninstallments WHERE CompanyLoanId = $id");
          }

      })
      ->leftJoin('(SELECT SUM(companyloaninstallments.Amount)as Total,CompanyLoanId FROM companyloaninstallments GROUP BY CompanyLoanId) a', 'companyloans.Id', '=', 'a.CompanyLoanId')
      ->leftJoin('(SELECT SUM(companyloaninstallments.Amount)as Total,CompanyLoanId FROM companyloaninstallments GROUP BY CompanyLoanId) paid_month', 'companyloans.Id', '=', 'paid_month.CompanyLoanId')
      ->leftJoin('company', 'company.Id', '=', 'companyloans.CompanyId')
      ->leftJoin( '(select Max(Id) as maxid,CompanyLoanId from companyloanstatuses Group By CompanyLoanId) as max', 'max.CompanyLoanId', '=', 'companyloans.Id')
      ->leftJoin('companyloanstatuses','max.maxid','=','companyloanstatuses.Id')
      ->leftJoin('company as com','com.Id','=','companyloans.CompanyId')
      ->leftJoin('users as us','us.Id','=','companyloanstatuses.UserId');
      if(isset($_GET['end'])){
        $editor->where(function($q){
          $q->where('str_to_date(companyloans.Date,"%d-%M-%Y")','str_to_date("'.$_GET['end'].'","%d-%M-%Y")','<=',false);
        });
      }
      $editor->process( $_POST )->json();
