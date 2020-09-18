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


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'assets')
  	->fields(
      Field::inst( 'assets.Id' ),
      Field::inst( 'assets.UserId' ),
      Field::inst( 'assettrackings.Id' ),
      Field::inst( 'assets.Label' ),
      Field::inst( 'assets.Type' ),
      Field::inst( 'assets.Serial_No' ),
      Field::inst( 'assets.IMEI' ),
      Field::inst( 'assets.Brand' ),
      Field::inst( 'assets.Model_No' ),
      Field::inst( 'assets.Car_No' ),
      Field::inst( 'assets.Replacement_Car_No' ),
      Field::inst( 'assets.Color' ),
      Field::inst( 'assets.Availability' ),
      Field::inst( 'assets.Extra_Detail_1' ),
      Field::inst( 'assets.Extra_Detail_2' ),
      Field::inst( 'assets.Extra_Detail_3' ),
      Field::inst( 'assets.Extra_Detail_4' ),
      Field::inst( 'assets.Extra_Detail_5' ),
      Field::inst( 'assets.Remarks' ),
      Field::inst( 'assets.Ownership' ),
      Field::inst( 'assets.Rental_Company' ),
      Field::inst( 'assets.Rental_Start_Date' ),
      Field::inst( 'assets.Asset_Type' ),
      Field::inst( 'assets.Rental_End_Date' ),
      Field::inst( 'assettrackings.UserId' ),
      Field::inst( 'holder.Name' ),
      Field::inst( 'assettrackings.Date' ),
      Field::inst( 'assettrackings.Remarks' ),
      Field::inst( 'assettrackings.Transfer_To' ),
      Field::inst( 'transfer.Name' ),
      Field::inst( 'assettrackings.Transfer_Date_Time' ),
      Field::inst( 'assettrackings.Acknowledge_Date_Time' ),
      Field::inst( 'assets.Kitchen_Appliances' ),
      Field::inst( 'assets.Supplier_Name' ),
      Field::inst( 'assets.Price' ),
      Field::inst( 'assets.Date_of_Purchase' ),
      Field::inst( 'assets.Company' ),
      Field::inst( 'assets.Location' ),
      Field::inst( 'assets.Description' ),
      Field::inst( 'assets.Rental_Fees' ),
      Field::inst( 'assets.Registered_Fees' ),
      Field::inst( 'assets.Agreenment_Start_Date' ),
      Field::inst( 'assets.Agreenment_End_Date' ),
      Field::inst( 'assets.Termination_of_Agreenment' ),
      Field::inst( 'assets.Asset_Listed' ),
      Field::inst( 'assets.Rental_Date' ),
      Field::inst( 'assets.Rental_Deposit' ),
      Field::inst( 'assets.Service_Provided' ),
      Field::inst( 'assets.APA_Registration_No' ),
      Field::inst( 'assets.Expired_Date' ),
      Field::inst( 'assets.Contact_No' ),
      Field::inst( 'assets.Quantity' ),

      Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/Asset__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'Asset',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )

              )
      )
    ->leftJoin('files', 'files.TargetId', '=', 'assets.Id and files.Type="Asset" AND files.Id IN (select Max(Id) from files where Type="Asset" Group By TargetId)')
    ->leftJoin('(select Max(Id) as maxid,AssetId from assettrackings Group By AssetId) as max', 'max.AssetId', '=', 'assets.Id')
    ->leftJoin('assettrackings', 'assettrackings.Id', '=', 'max.maxid')
    ->leftJoin('users as holder', 'assettrackings.UserId', '=', 'holder.Id')
    ->leftJoin('users as transfer', 'assettrackings.Transfer_To', '=', 'transfer.Id');

  if (isset( $_GET['type'])) {
    $editor
    ->where('assets.Type',$_GET['type'] );
  }

  if (isset( $_GET['userid'])) {
    $editor
    ->where('assettrackings.UserId',$_GET['userid'] )
    ->where( function ($q) {
      $q->or_where( 'assettrackings.Transfer_To', $_GET['userid']);
    } );

  }

    $editor->process( $_POST )->json();
