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

     if (isset( $_POST['data'][$key]['printer']['Printer_Model']))
     {
       if ($_POST['data'][$key]['printer']['Printer_Model']=="Canon IRC 3580 Color")
       {
             $_POST['data'][$key]['printer']['UP']=0.500;
             // $_POST['data'][$key]['printer']['Total']= $_POST['data'][$key]['printer']['Quantity']*0.500;

       }
       else if($_POST['data'][$key]['printer']['Printer_Model']=="Canon IRC 3580 B/W" || $_POST['data'][$key]['printer']['Printer_Model']=="Canon IR 3570 B/W")
       {
             $_POST['data'][$key]['printer']['UP']=0.030;
             // $_POST['data'][$key]['printer']['Total']= $_POST['data'][$key]['printer']['Quantity']*0.030;
       }
       else if($_POST['data'][$key]['printer']['Printer_Model']=="Monthly Services Maintenance Fees")
       {
         if (isset($_POST['data'][$key]['printer']['Floor']))
         {
           if ($_POST['data'][$key]['printer']['Floor'] == "Ground Floor" || $_POST['data'][$key]['printer']['Floor'] == "First Floor")
           {
               $_POST['data'][$key]['printer']['UP']=0.00;
               $_POST['data'][$key]['printer']['Quantity']="0";
               $_POST['data'][$key]['printer']['Total_Without_GST']=150;
               $_POST['data'][$key]['printer']['Total']=150*1.06;
           }
           else
           {
               $_POST['data'][$key]['printer']['UP']="0.00";
               $_POST['data'][$key]['printer']['Quantity']="0";
               $_POST['data'][$key]['printer']['Total_Without_GST']=99;
               $_POST['data'][$key]['printer']['Total']=99*1.06;
           }

         }
       }

    }

    if (isset( $_POST['data'][$key]['printer']['Quantity']))
    {
      if ($_POST['data'][$key]['printer']['Quantity']>0)
      {
        $_POST['data'][$key]['printer']['Total_Without_GST']= $_POST['data'][$key]['printer']['Quantity']*$_POST['data'][$key]['printer']['UP'];
        $_POST['data'][$key]['printer']['Total']= $_POST['data'][$key]['printer']['Quantity']*$_POST['data'][$key]['printer']['UP']*1.06;
      }

    }


   }

 }

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'printer' )
  	->fields(
      Field::inst( 'printer.Id' ),
      Field::inst( 'printer.Floor' ),
      Field::inst( 'printer.Printer_Model' ),
      Field::inst( 'printer.Quantity' ),
      Field::inst( 'printer.Type' ),
      Field::inst( 'printer.Bill_Month' ),
      Field::inst( 'printer.Start_Date' ),
      Field::inst( 'printer.End_Date' ),
      Field::inst( 'printer.Total' ),
      Field::inst( 'printer.Total_Without_GST' ),
      Field::inst( 'printer.UP' )
    )
      ->where('printer.Type','Report' );

      if (isset( $_GET['Bill_Month'])) {
        if(strpos($_GET['Bill_Month'],"All")!==false)
        {
        }
        else{
          $editor
          ->where('printer.Bill_Month',$_GET['Bill_Month']);
        }
      }




  $editor->process( $_POST )->json();
