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
  $editor=Editor::inst( $db, 'property')
  	->fields(
      Field::inst( 'property.Id' ),
      Field::inst( 'property.Type' ),
      Field::inst( 'property.Address' ),
      Field::inst( 'property.Landlord' ),
      Field::inst( 'property.Company' ),
      Field::inst( 'property.Tenant' ),
      Field::inst( 'property.Department' ),
      Field::inst( 'property.Business' ),
      Field::inst( 'property.Area' ),
      Field::inst( 'property.Property_Type' ),
      Field::inst( 'property.Status' ),
      Field::inst( 'property.Rental' ),
      Field::inst( 'property.TNB' ),
      Field::inst( 'property.Water' ),
      Field::inst( 'property.IWK' ),
      Field::inst( 'property.Start' ),
      Field::inst( 'property.End' ),
      Field::inst( 'property.Security_Deposit' ),
      Field::inst( 'property.Utility_Deposit' ),
      Field::inst( 'property.Termination_Notice' ),
      Field::inst( 'property.Agreement' ),
      Field::inst( 'property.Keys' ),
      Field::inst( 'property.Owner' ),
      Field::inst( 'property.Contact_Person' ),
      Field::inst( 'property.Remarks' ),
      Field::inst( 'files.Web_Path' )
              ->setFormatter( 'Format::ifEmpty', null )
              ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/Property/__ID__.__EXTN__' )
                  ->db( 'files', 'Id', array(
                      'Type'    => 'Property',
                      'TargetId'    => $id,
                      'File_Name'    => Upload::DB_FILE_NAME,
                      'File_Size'    => Upload::DB_FILE_SIZE,
                      'Web_Path'    => Upload::DB_WEB_PATH
                  ) )
              )
      )
    ->leftJoin('files', 'files.TargetId', '=', 'property.Id and files.Type="Property" AND files.Id IN (select Max(Id) from files where files.Type="Property" Group By TargetId)');

    if (isset( $_GET['type'])) {
      $editor
      ->where('property.Type',$_GET['type'] );
    }

    $editor->process( $_POST )->json();
