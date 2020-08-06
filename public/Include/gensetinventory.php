<?php

include( "plugin/php/DataTables.php" );

// Alias Editor classes so they are easy to use
use
  DataTables\Editor,
  DataTables\Editor\Field,
  DataTables\Editor\Format,
  DataTables\Editor\Mjoin,
  DataTables\Editor\Upload,
  DataTables\Editor\Options,
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

// DataTables PHP library
// Build our Editor instance and process the data coming from _POST
$editor=Editor::inst( $db, 'gensetinventory' )
->fields(
  Field::inst( 'gensetinventory.Id' ),
  Field::inst( 'gensetinventory.name' ),
  Field::inst( 'gensetinventory.machinery_no' ),
  Field::inst( 'gensetinventory.description' ),
  Field::inst( 'companies.Company_Name' ),
  Field::inst( 'gensetinventory.type' ),
  Field::inst( 'gensetinventory.barcode' ),
  Field::inst( 'gensetinventory.model' ),
  Field::inst( 'gensetinventory.price' ),
  Field::inst( 'gensetinventory.supplier'),
  Field::inst( 'gensetinventory.qty_balance' ),
  Field::inst( 'gensetinventory.branch' ),
  Field::inst( 'gensetinventory.status' ),
  Field::inst( 'inventorypricehistory.price' )
  //Field::inst( 'inventories.Additional_Description' ),
  // Field::inst( 'inventories.Warehouse')
)
->leftJoin('companies','companies.Id','=','gensetinventory.supplier')
->leftjoin('gensetinventory_history','gensetinventory_history.gensetinventoryId','=','gensetinventory.Id')
->leftJoin('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max','max.inventoryId','=','gensetinventory.Id')
->leftJoin('inventorypricehistory','inventorypricehistory.Id','=','max.maxid')
->process( $_POST )
->json();