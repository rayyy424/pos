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
  $editor=Editor::inst( $db, 'deliveryform' )
    ->debug(true)
  	->fields(
      Field::inst( 'deliveryform.Id' ),
      Field::inst( 'deliveryform.roadId' ),
      Field::inst( 'deliveryform.delivery_date' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'deliveryform.DO_No' ),
      Field::inst( 'radius.Location_Name' ),
      Field::inst( 'companies.Company_Name' ),
      Field::inst( 'deliveryform.charges_rate' ),
      Field::inst( 'deliveryform.charges' ),
      Field::inst( 'deliveryform.incentive_rate' ),
      Field::inst( 'deliveryform.basicincentive' ),
      Field::inst( 'deliveryform.ontime' ),
      Field::inst( 'deliveryform.incentive' )
    )
    ->leftJoin('users','users.Id','=','deliveryform.DriverId')
    ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId AND deliveryform.roadtaxId != 0 AND roadtax.Type != "TRUCK" AND roadtax.Lorry_Size != ""')
    ->leftJoin('radius','radius.Id','=','deliveryform.Location')
    ->leftJoin('companies','deliveryform.company_id','=','companies.Id')
    ->leftJoin( '(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max', 'max.deliveryform_Id', '=', 'deliveryform.Id')
    ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=','max.maxid');

    $editor
    ->where('deliveryform.delivery_date','>=',$_GET['start'])
    ->where('deliveryform.delivery_date','<=',$_GET['end']);

    $editor->process( $_POST )->json();
