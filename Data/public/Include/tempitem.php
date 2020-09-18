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

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'deliveryitemtemp' )
  	->fields(
      Field::inst( 'deliveryitemtemp.Id' ),
      Field::inst( 'deliveryitemtemp.formId' ),
      Field::inst( 'deliveryitemtemp.inventoryId' ),
      Field::inst( 'inventories.Item_Code' ),
      Field::inst( 'inventories.Description' ),
      Field::inst( 'inventories.Unit' ),
      Field::inst( 'stocks.Quantity' ),
      Field::inst( 'deliveryitemtemp.Qty_request' )

  )
->leftJoin('inventories', 'inventories.Id', '=', 'deliveryitemtemp.inventoryId')
->leftJoin('stocks','stocks.inventory_Id','=','inventories.Id');

if (isset( $_GET['formId'])) {
      $editor
      ->where('deliveryitemtemp.formId',$_GET['formId'] );
    }
$editor->process( $_POST )->json();

