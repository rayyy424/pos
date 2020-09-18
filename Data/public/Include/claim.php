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

     if ($_POST['data'][$key]['claims']['Currency']!="" && $_POST['data'][$key]['claims']['Rate']>0)
     {

       $_POST['data'][$key]['claims']['Total_Amount']=($_POST['data'][$key]['claims']['Total_Expenses']-$_POST['data'][$key]['claims']['Petrol_SmartPay']-$_POST['data'][$key]['claims']['Advance'])*$_POST['data'][$key]['claims']['Rate'];
       $_POST['data'][$key]['claims']['Total_Without_GST']=$_POST['data'][$key]['claims']['Total_Expenses']-$_POST['data'][$key]['claims']['GST_Amount'];

     }
     else {

       if (isset( $_POST['data'][$key]['claims']['Petrol_SmartPay']))
       {
         if ($_POST['data'][$key]['claims']['Petrol_SmartPay']>0)
         {
           $_POST['data'][$key]['claims']['Mileage']="0";
           $_POST['data'][$key]['claims']['Expenses_Type']="Petrol SmartPay";
           $_POST['data'][$key]['claims']['Total_Expenses']=$_POST['data'][$key]['claims']['Petrol_SmartPay'];
         }

       }

       if (isset( $_POST['data'][$key]['claims']['Mileage']))
       {
         if ($_POST['data'][$key]['claims']['Mileage']>0)
         {
            if ($_POST['data'][$key]['claims']['Transport_Type']=="Car")
            {
               $_POST['data'][$key]['claims']['Total_Expenses']=$_POST['data'][$key]['claims']['Mileage']*0.5;
               $_POST['data'][$key]['claims']['Expenses_Type']="Mileage (RM)";
               $_POST['data'][$key]['claims']['Petrol_SmartPay']="0";
            }
            else {
              $_POST['data'][$key]['claims']['Total_Expenses']=$_POST['data'][$key]['claims']['Mileage']*0.25;
              $_POST['data'][$key]['claims']['Expenses_Type']="Mileage (RM)";
              $_POST['data'][$key]['claims']['Petrol_SmartPay']="0";
            }
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

 }

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'claims')
  	->fields(
      Field::inst( 'claims.Id' ),
      Field::inst( 'claims.ClaimSheetId' ),
      Field::inst( 'claims.Date' ),
      Field::inst( 'claims.Depart_From' ),
      Field::inst( 'claims.Destination' ),
      Field::inst( 'claims.Site_Name' ),
      Field::inst( 'claims.State' ),
      Field::inst( 'claims.Work_Description' ),
      Field::inst( 'claims.Next_Person' ),
      Field::inst( 'claims.Transport_Type' ),
      Field::inst( 'claims.Car_No' ),
      Field::inst( 'claims.Mileage' )->validator( 'Validate::numeric' ),
      Field::inst( 'claims.Currency' ),
      Field::inst( 'claims.Rate' ),
      Field::inst( 'claims.Expenses_Type' ),

      Field::inst( 'claims.Total_Expenses' )->validator( 'Validate::numeric' ),
      Field::inst( 'claims.Petrol_SmartPay' )->validator( 'Validate::numeric' ),
      Field::inst( 'claims.Advance' )->validator( 'Validate::numeric' ),

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
    ->leftJoin('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max', 'max.ClaimId', '=', 'claims.Id')
    ->leftJoin('claimstatuses', 'claimstatuses.Id', '=', 'max.maxid')
    ->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
    ->leftJoin('files', 'files.TargetId', '=', 'claims.Id and files.Type="Claim"');

    if (isset( $_GET['ClaimSheetId'])) {
      $editor
      ->where('claims.ClaimSheetId',$_GET['ClaimSheetId']);
    }

    if (isset( $_GET['Status'])) {

      if(strpos($_GET['Status'],"Pending")!==false)
      {
        $editor
        ->where( function ( $q ) {
            $q
                ->where( 'claimstatuses.Status', $_GET['Status'], 'like' )
                ->or_where( function ( $r ) {
                    $r->where( 'claimstatuses.Status', null);
                } );
        } );
      }
      else {
        $editor
        ->where('claimstatuses.Status',$_GET['Status'],'like' );
      }

    }

    $editor->process( $_POST )->json();
