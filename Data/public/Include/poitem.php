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

  $editor=Editor::inst( $db,'materialpoitem')
  	->fields(
      Field::inst('materialpoitem.Id'),
      Field::inst('materialpoitem.Type'),
      Field::inst('materialpoitem.Description'),
      Field::inst('materialpoitem.Add_Description'),
      Field::inst('materialpoitem.Unit'),
      Field::inst('materialpoitem.Qty'),
      Field::inst('materialpoitem.Price'),
      Field::inst('editpo.Reason'),
      Field::inst('users.Name')
    )
   ->leftjoin('materialpo','materialpo.Id','=','materialpoitem.PoId')
   ->leftjoin('(Select Max(Id) as maxid,PoItemId from editpo group by PoItemId) as max','max.PoItemId','=','materialpoitem.Id')
   ->leftjoin('editpo','editpo.Id','=','max.maxid')
   ->leftjoin('users','users.Id','=','editpo.created_by');
  //  ->leftjoin('files','files.Type="Item Quotation" and files.TargetId = materialrequest.Id','','');
    
   if(isset($_GET['poid'])){
       $editor->where('materialpo.Id',$_GET['poid']);
   }
  //  if(isset($_GET['mid'])){
  //    $editor->where('materialrequest.MaterialId',$_GET['mid']);
  //  }
  //  if(isset($_GET['vid'])){
  //    $editor->where('materialrequest.vendorId',$_GET['vid']);
  //  }
  //  if(isset($_GET['type'])){
  //    $editor->where('inventories.Type','MPSB','NOT LIKE');
  //   //  $editor->where('inventories.Type','Transport','NOT LIKE');
  //  }
    $editor->process( $_POST )->json();
