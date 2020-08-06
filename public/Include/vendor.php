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
  $editor=Editor::inst( $db, 'inventoryvendor')
  	->fields(
        Field::inst('inventoryvendor.Id'),
      Field::inst( 'inventoryvendor.CompanyId' ),
      Field::inst( 'inventoryvendor.Item_Price' ),
      Field::inst('inventoryvendor.InventoryId'),
      Field::inst('companies.Company_Name'),
      Field::inst('inventoryvendor.created_at')
      )
    ->leftJoin('companies','companies.Id','=','inventoryvendor.CompanyId');
    if(isset($_GET['id'])){
      $editor
      ->where('inventoryvendor.InventoryId',$_GET['id']);
    }
   

    $editor->process( $_POST )->json();
