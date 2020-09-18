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

/*
 * Example PHP implementation used for the index.html example
 */
 if (isset($_POST['Id']))
 {
   $id=$_POST['Id'];
 }
 else {
   $id=0;
 }

 if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'edit'))
 {

   foreach ($_POST['data'] as $key => $value) {

     if (isset( $_POST['data'][$key]['claims']['Mileage']))
     {
       if ($_POST['data'][$key]['claims']['Mileage']>0)
       {
         $_POST['data'][$key]['claims']['Total_Expenses']=$_POST['data'][$key]['claims']['Mileage']*0.5;
         $_POST['data'][$key]['claims']['Expenses_Type']="Mileage (RM)";
       }

     }

     if (isset( $_POST['data'][$key]['claims']['Total_Expenses']) || isset( $_POST['data'][$key]['claims']['GST_Amount']))
     {

        $_POST['data'][$key]['claims']['Total_Without_GST']=$_POST['data'][$key]['claims']['Total_Expenses']-$_POST['data'][$key]['claims']['GST_Amount'];

     }

     if (isset( $_POST['data'][$key]['claims']['Total_Expense']) || isset( $_POST['data'][$key]['claims']['Petrol_SmartPay']) || isset( $_POST['data'][$key]['claims']['Advance']))
     {

        $_POST['data'][$key]['claims']['Total_Amount']=$_POST['data'][$key]['claims']['Total_Expenses']-$_POST['data'][$key]['claims']['Petrol_SmartPay']-$_POST['data'][$key]['claims']['Advance'];

     }

   }

}


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'claimstatuses')
  	->fields(
      Field::inst( 'claimstatuses.Id' ),
      Field::inst( 'claims.Id' ),
      Field::inst( 'claimsheets.UserId' ),
      Field::inst( 'submitter.Name' ),
      Field::inst( 'claims.Date' ),
      Field::inst( 'claims.Site_Name' ),
      Field::inst( 'claims.State' ),
      Field::inst( 'claims.Work_Description' ),
      Field::inst( 'claims.Next_Person' ),
      Field::inst( 'claims.Car_No' ),
      Field::inst( 'claims.Mileage' ),
      Field::inst( 'claims.Expenses_Type' ),

      Field::inst( 'claims.Total_Expenses' )->validator( 'Validate::numeric' ),
      Field::inst( 'claims.Petrol_SmartPay' )->validator( 'Validate::numeric' ),
      Field::inst( 'claims.Advance' )->validator( 'Validate::numeric' ),
      Field::inst( 'allowance.Allowance' )->validator( 'Validate::numeric' ),

      Field::inst( 'claims.Total_Amount' )->validator( 'Validate::numeric' ),
      Field::inst( 'claims.GST_Amount' )->validator( 'Validate::numeric' ),
      Field::inst( 'claims.Total_Without_GST' )->validator( 'Validate::numeric' ),
      Field::inst( 'claims.Receipt_No' ),
      Field::inst( 'claims.Company_Name' ),
      Field::inst( 'claims.GST_No' ),

      Field::inst( 'claims.Remarks' ),
      Field::inst( 'claimstatuses.UserId' ),
      Field::inst( 'approver.Name' ),
      Field::inst( 'claimstatuses.Status' ),
      Field::inst( 'claimstatuses.Comment' ),
      Field::inst( 'claimstatuses.updated_at' )
    )
    ->leftJoin('claims', 'claimstatuses.ClaimId', '=', 'claims.Id')
    ->leftJoin('claimsheets', 'claims.ClaimSheetId', '=', 'claimsheets.Id')
    ->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
    ->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
    ->leftJoin('(Select * from timesheets where UserId='.$_GET['UserId'].' AND Date in (Select claims.Date from claims where ClaimsheetId='.$_GET['Id'].')) as allowance','allowance.Date','=','claims.Date AND claims.Id in (Select min(claims.Id) from claims left join claimsheets on claims.ClaimsheetId=claimsheets.Id Where ClaimsheetId='.$_GET['Id'].' Group By Date)')
    ->where( function ( $q ) {
    $q->where( 'claimstatuses.Id', '(select Max(Id) from claimstatuses Group By ClaimId)', 'IN', false );
  } );

  if (isset( $_GET['Approver'])) {
    $editor
    ->where('claimstatuses.UserId',$_GET['Approver'] );
  }

  if (isset( $_GET['Id'])) {
    $editor
    ->where('claimsheets.Id',$_GET['Id'] );
  }

    $editor->process( $_POST )->json();
