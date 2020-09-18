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

 if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'edit' || $_POST['action'] == 'create'))
 {

   foreach ($_POST['data'] as $key => $value) {

     if (isset( $_POST['data'][$key]['utility']['GST_Charges']))
     {
       if ($_POST['data'][$key]['utility']['GST_Charges']>=0)
       {
             $_POST['data'][$key]['utility']['Total_Amount']=$_POST['data'][$key]['utility']['Monthly_Charges'] + $_POST['data'][$key]['utility']['GST_Charges'];
             // $_POST['data'][$key]['printer']['Total']= $_POST['data'][$key]['printer']['Quantity']*0.500;

       }
     }
   }
 }


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'utility')
  	->fields(
      Field::inst( 'utility.Id' ),
      Field::inst( 'utility.Type' ),
      Field::inst( 'utility.Branch' ),
      Field::inst( 'utility.Bill_Account_No' ),
      Field::inst( 'utility.Bill_Date' ),
      Field::inst( 'utility.Bill_Due_Date' ),
      Field::inst( 'utility.Monthly_Charges' )->validator( 'Validate::numeric' ),
      Field::inst( 'utility.GST_Charges' )->validator( 'Validate::numeric' ),
      Field::inst( 'utility.Total_Amount' )->validator( 'Validate::numeric' ),
      Field::inst( 'utility.Payment_Type' ),
      Field::inst( 'utility.Date_Paid' ),
      Field::inst( 'utility.Paid_Amount' ),
      Field::inst( 'utility.Remarks' )
    );

  if (isset( $_GET['type'])) {
    $editor
    ->where('utility.Type',$_GET['type'] );
  }

  if (isset( $_GET['branch'])) {
    $editor
    ->where('utility.Branch',$_GET['branch'] );
  }

    $editor->process( $_POST )->json();
