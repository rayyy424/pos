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
$editor=Editor::inst( $db, 'speedfreakinventory' )
->fields(
  Field::inst( 'speedfreakinventory.Id' ),
  Field::inst( 'speedfreakinventory.name' ),
  Field::inst( 'speedfreakinventory.machinery_no' ),
  Field::inst( 'speedfreakinventory.description' ),
  Field::inst( 'companies.Company_Name' ),
  Field::inst( 'speedfreakinventory.type' ),
  Field::inst( 'speedfreakinventory.barcode' ),
  Field::inst( 'speedfreakinventory.model' ),
  Field::inst( 'speedfreakinventory.price' ),
  Field::inst( 'speedfreakinventory.supplier'),
  Field::inst( 'speedfreakinventory.qty_balance' ),
  Field::inst( 'speedfreakinventory.branch' ),
  Field::inst( 'speedfreakinventory.status' ),
  Field::inst( 'inventorypricehistory.price' )
  //Field::inst( 'inventories.Additional_Description' ),
  // Field::inst( 'inventories.Warehouse')
)
->leftJoin('companies','companies.Id','=','speedfreakinventory.supplier')
->leftjoin('speedfreakinventory_history','speedfreakinventory_history.speedfreakInventoryId','=','speedfreakinventory.Id')
->leftJoin('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max','max.inventoryId','=','speedfreakinventory.Id')
->leftJoin('inventorypricehistory','inventorypricehistory.Id','=','max.maxid')
->process( $_POST )
->json();