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
  $editor=Editor::inst( $db, 'materialrequest')
  	->fields(
        Field::inst('inventories.Item_Code'),
        Field::inst('inventories.Description'),
        Field::inst('materialrequest.Qty'),
        Field::inst('materialrequest.Price'),
        Field::inst('materialrequest.Id')
      )
    ->leftjoin('inventories','inventories.Id','=','materialrequest.InventoryId');

    if(isset($_GET['id'])){
        $editor->where('materialrequest.MaterialId',$_GET['id']);
    }
  
   

    $editor->process( $_POST )->json();
