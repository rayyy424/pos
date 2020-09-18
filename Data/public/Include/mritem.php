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

  $editor=Editor::inst( $db,'materialrequest')
  	->fields(
       Field::inst('materialrequest.Id'),
       Field::inst('inventories.Item_Code'),
       Field::inst('inventories.Description'),
       Field::inst('inventories.Acc_No'),
       Field::inst('materialrequest.Qty'),
       Field::inst('inventories.Unit'),
       Field::inst('inventoryvendor.Item_Price'),
       Field::inst('inventories.Type'),
       Field::inst('inventories.Id'),
       Field::inst('files.Web_Path'),
       Field::inst('materialrequest.Add_Description'),
       Field::inst('materialrequest.Price')
    )
   ->leftjoin('inventories','inventories.Id','=','materialrequest.InventoryId')
   ->leftjoin('inventoryvendor','inventoryvendor.Id','=','materialrequest.vendorId')
   ->leftjoin('materialpo','materialpo.MaterialId=materialrequest.MaterialId and materialpo.VendorId=inventoryvendor.CompanyId','','')
   ->leftjoin('files','files.Type="Item Quotation" and files.TargetId = materialrequest.Id','','');
    
   if(isset($_GET['poid'])){
       $editor->where('materialpo.Id',$_GET['poid']);
   }
   if(isset($_GET['mid'])){
     $editor->where('materialrequest.MaterialId',$_GET['mid']);
   }
   if(isset($_GET['vid'])){
     $editor->where('materialrequest.vendorId',$_GET['vid']);
   }
   if(isset($_GET['type'])){
     $editor->where('inventories.Type','MPSB','NOT LIKE');
   }
   $raw="SELECT materialrequest.Id,inventories.Description,inventories.Acc_No,materialrequest.Qty,inventories.Unit,inventories.Item_Code,
   inventoryvendor.Item_Price,inventories.Type,inventories.Id as InvId,materialrequest.Price from materialrequest
   LEFT JOIN inventories on inventories.Id = materialrequest.InventoryId
   LEFT JOIN inventoryvendor on inventoryvendor.Id = materialrequest.vendorId
   
   where materialrequest.MaterialId = ".$_GET['mid']." 
   group by materialrequest.InventoryId
   ";
  $r=$db->sql($raw)->fetchAll();
  $arr=array("data"=>$r,"options"=>'',"files"=>'');
  echo json_encode($arr);
    // $editor->process( $_POST )->json();
