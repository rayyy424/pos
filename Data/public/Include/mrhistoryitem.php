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


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST

  $editor=Editor::inst( $db,'mrhistoryitem')
  	->fields(
        Field::inst('mrhistoryitem.Id'),
        Field::inst('mrhistoryitem.Qty'),
        Field::inst('mrhistoryitem.Price'),
        Field::inst('mrhistoryitem.Type'),
        Field::inst('mrhistoryitem.Reason'),
        Field::inst('inventories.Item_Code'),
        Field::inst('inventories.Description'),
        Field::inst('inventories.Unit'),
        Field::inst('mrhistoryitem.OldPrice'),
        Field::inst('mrhistoryitem.OldQty')
    )->leftjoin('inventories','inventories.Id','=','mrhistoryitem.InventoryId');
    if(isset($_GET['HistoryId'])){
        $editor->where('mrhistoryitem.HistoryId',$_GET['HistoryId']);
    }
    $editor->process( $_POST )->json();
