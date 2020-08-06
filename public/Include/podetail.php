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
 if (isset($_POST['UserId']))
 {
   $id=$_POST['UserId'];
 }
 else {
   $id=0;
 }

 if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'edit'))
 {

   foreach ($_POST['data'] as $key => $value) {

     if (isset( $_POST['data'][$key]['purchaseorderitems']['Amount']))
     {
       if ($_POST['data'][$key]['purchaseorderitems']['Amount']>0)
       {
         $_POST['data'][$key]['purchaseorderitems']['First_Cut']=number_format($_POST['data'][$key]['purchaseorderitems']['Amount'] * str_replace('%', '', $_POST['firstcut']) / 100,2);
         $_POST['data'][$key]['purchaseorderitems']['Second_Cut']=number_format($_POST['data'][$key]['purchaseorderitems']['Amount'] * str_replace('%', '', $_POST['secondcut']) / 100,2);
         $_POST['data'][$key]['purchaseorderitems']['Third_Cut']=number_format($_POST['data'][$key]['purchaseorderitems']['Amount'] * str_replace('%', '', $_POST['thirdcut']) / 100,2);
         $_POST['data'][$key]['purchaseorderitems']['Fourth_Cut']=number_format($_POST['data'][$key]['purchaseorderitems']['Amount'] * str_replace('%', '', $_POST['fourthcut']) / 100,2);
         $_POST['data'][$key]['purchaseorderitems']['Fifth_Cut']=number_format($_POST['data'][$key]['purchaseorderitems']['Amount'] * str_replace('%', '', $_POST['fifthcut']) / 100,2);
       }

     }


   }

 }



// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'purchaseorderitems' )
  	->fields(
      Field::inst( 'purchaseorderitems.Id' ),
      Field::inst( 'purchaseorderitems.PO_Id' ),
      Field::inst( 'purchaseorderitems.PO_Item' ),
      Field::inst( 'purchaseorderitems.Item_Description' ),
  	  Field::inst( 'purchaseorderitems.Scope_of_Work' ),
      Field::inst( 'purchaseorderitems.Project_Code' ),
      Field::inst( 'purchaseorderitems.Work_Order_ID' ),
      Field::inst( 'purchaseorderitems.Site_ID' ),
      Field::inst( 'purchaseorderitems.Amount' ),
      Field::inst( 'purchaseorderitems.First_Cut' ),
      Field::inst( 'purchaseorderitems.First_Cut_Completed_Date' ),
      Field::inst( 'purchaseorderitems.First_Cut_Invoice_No' ),
      Field::inst( 'purchaseorderitems.First_Cut_Forecast_Invoice_Date' ),

      Field::inst( 'purchaseorderitems.Second_Cut' ),
      Field::inst( 'purchaseorderitems.Second_Cut_Completed_Date' ),
      Field::inst( 'purchaseorderitems.Second_Cut_Invoice_No' ),
      Field::inst( 'purchaseorderitems.Second_Cut_Forecast_Invoice_Date' ),

      Field::inst( 'purchaseorderitems.Third_Cut' ),
      Field::inst( 'purchaseorderitems.Third_Cut_Completed_Date' ),
      Field::inst( 'purchaseorderitems.Third_Cut_Invoice_No' ),
      Field::inst( 'purchaseorderitems.Third_Cut_Forecast_Invoice_Date' ),

      Field::inst( 'purchaseorderitems.Fourth_Cut' ),
      Field::inst( 'purchaseorderitems.Fourth_Cut_Completed_Date' ),
      Field::inst( 'purchaseorderitems.Fourth_Cut_Invoice_No' ),
      Field::inst( 'purchaseorderitems.Fourth_Cut_Forecast_Invoice_Date' ),

      Field::inst( 'purchaseorderitems.Fifth_Cut' ),
      Field::inst( 'purchaseorderitems.Fifth_Cut_Completed_Date' ),
      Field::inst( 'purchaseorderitems.Fifth_Cut_Invoice_No' ),
      Field::inst( 'purchaseorderitems.Fifth_Cut_Forecast_Invoice_Date' ),
      Field::inst( 'purchaseorderitems.Remarks' )
      );


    if (isset( $_GET['PO_Id'])) {
      $editor
      ->where('purchaseorderitems.PO_Id',$_GET['PO_Id']);
    }


  $editor->process( $_POST )->json();
