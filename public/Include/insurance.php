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
  $editor=Editor::inst( $db, 'insurances')
  	->fields(
      Field::inst( 'insurances.Id' ),
      Field::inst( 'insurances.Type' ),
      Field::inst( 'insurances.Insured_Name' ),
      Field::inst( 'insurances.Situation' ),
      Field::inst( 'insurances.Address' ),
      Field::inst( 'insurances.Insured_Person' ),
      Field::inst( 'insurances.Insurance_Expiry' ),
      Field::inst( 'insurances.Insurance_Company' ),
      Field::inst( 'insurances.Company' ),
      Field::inst( 'insurances.Account_No' ),
      Field::inst( 'insurances.Policy_No' ),
      Field::inst( 'insurances.Type_of_Policy' ),
      Field::inst( 'insurances.Plan_Covered' ),
      Field::inst( 'insurances.Class' ),
      Field::inst( 'insurances.Benefits' ),
      Field::inst( 'insurances.Ratings' ),
      Field::inst( 'insurances.Sum_Insured' ),
      Field::inst( 'insurances.Total_Premium' ),
      Field::inst( 'insurances.Business' ),
      Field::inst( 'insurances.Area' ),
      Field::inst( 'insurances.Insurance_Type' ),
      Field::inst( 'insurances.Status' ),
      Field::inst( 'insurances.Installment_Rental' ),
      Field::inst( 'insurances.Start_Date' ),
      Field::inst( 'insurances.End_Date' ),
      Field::inst( 'insurances.Contact_Person' ),
      Field::inst( 'insurances.Contact_No' ),
      Field::inst( 'insurances.Section' ),
      Field::inst( 'insurances.Client' ),
      Field::inst( 'insurances.Purchase_Date' ),
      Field::inst( 'insurances.Brand' ),
      Field::inst( 'insurances.Serial_No' ),
      Field::inst( 'insurances.Engine_Model' ),
      Field::inst( 'insurances.Engine_No' ),
      Field::inst( 'insurances.Financier' ),
      Field::inst( 'insurances.Capacity' ),
      Field::inst( 'insurances.Remarks' ),
      Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/Insurance/__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'Insurance',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )
              )

    )
    ->leftJoin('files', 'files.TargetId', '=', 'insurances.Id and files.Type="Insurance"');

    if (isset( $_GET['type'])) {
      $editor
      ->where('insurances.Type',$_GET['type'] );
    }

    $editor->process( $_POST )->json();
