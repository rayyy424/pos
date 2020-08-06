<?php

include( "plugin/php/DataTables.php" );

// Alias Editor classes so they are easy to use
use
  DataTables\Editor,
  DataTables\Editor\Field,
  DataTables\Editor\Format,
  DataTables\Editor\Mjoin,
  DataTables\Editor\Upload,
  DataTables\Editor\Validate,
  DataTables\Editor\ValidateOptions;


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

  $editor=Editor::inst($db,'deliveryitem')
  ->debug(true)
  ->fields(
    Field::inst('deliveryitem.Id'),
    Field::inst('deliveryitem.approve_qty'),
    Field::inst('deliveryitem.inventoryId'),
    Field::inst('inventories.Item_Code'),
    Field::inst('inventories.Description'),
    Field::inst('inventories.Categories'),
    Field::inst('inventories.Unit'),
    Field::inst('deliveryitem.Qty_request'),
    Field::inst('deliveryitem.Qty_received'),
    Field::inst('deliveryitem.Qty_send'),
    Field::inst('deliveryitem.remarks'),
    Field::inst('deliveryitem.add_desc'),
    Field::inst('deliveryitem.formId'),
    Field::inst('deliveryitem.status'),
    Field::inst('deliverystatuses.delivery_status'),
    Field::inst('deliverystatuses.delivery_status_details'),
    Field::inst('deliveryform.Id'),
    Field::inst('deliveryitem.available')
  )
  ->leftJoin('inventories', 'deliveryitem.inventoryId', '=','inventories.Id')
  ->leftjoin('deliveryform','deliveryform.Id','=','deliveryitem.formId')
  ->leftJoin( '(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max', 'max.deliveryform_Id', '=', 'deliveryform.Id')
  ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=','max.maxid');
if (isset( $_GET['formId'])) {
    $editor
  ->where('deliveryitem.formId',$_GET['formId']);
} 
// $editor->where( 'deliveryitem.available', 0, '!=' );

//     Field::inst('deliveryitem.formId'),
//     Field::inst('inventories.Unit'),
//     Field::inst('inventories.Item_Code'),
//     Field::inst('inventories.Description'),
//     Field::inst('inventories.Remark'),
//     Field::inst('inventories.Categories'),
//     Field::inst('deliveryitem.inventoryId'),
//     Field::inst('deliveryitem.Purpose'),
//     Field::inst('deliveryitem.Qty_request'),
//     Field::inst('deliveryitem.Qty_send'),
//     Field::inst('deliveryitem.Qty_received')
//   )
//   ->leftjoin('deliveryform','deliveryitem.formId','=','deliveryform.Id')
//   ->leftjoin('inventories','deliveryitem.inventoryId','=','inventories.Id');
// if(isset($_GET['formId']))
// {
//   $editor->where('deliveryitem.formId',$_GET['formId'],'=');
// }                                


    $editor->process( $_POST )->json();




