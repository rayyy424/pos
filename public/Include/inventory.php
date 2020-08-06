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
$editor=Editor::inst( $db, 'inventories' )
->fields(
  Field::inst( 'inventories.Id' ),
  Field::inst( 'inventories.Categories' ),
  Field::inst('inventories.Type'),
  Field::inst( 'inventories.Item_Code' ),
  Field::inst( 'inventories.Description' ),
  Field::inst( 'inventories.Add_Description'),
  Field::inst( 'inventories.Remark' ),
  Field::inst( 'inventories.Unit' ),
  Field::inst('inventories.Acc_No'),
  //Field::inst( 'inventories.Additional_Description' ),
  Field::inst( 'inventories.Warehouse'),
  Field::inst( 'inventories.dimension')
);
// ->leftJoin('stocks', 'stocks.Inventory_Id', '=', 'inventories.Id')
// ->leftJoin('projects', 'projects.Id', '=', 'stocks.ProjectId')
  if (isset( $_GET['option'])) {
    $editor
    ->where('inventories.Option',$_GET['option'] );
  }
$editor->process( $_POST )
->json();