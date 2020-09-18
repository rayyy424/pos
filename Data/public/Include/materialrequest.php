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
  $editor=Editor::inst( $db, 'material')
  	->fields(
        Field::inst('material.Id'),
        Field::inst('materialstatus.Status'),
        Field::inst('material.Total'),
        Field::inst('tracker.`Site Name`'),
        Field::inst("material.UserId"),
        Field::inst('material.created_at'),
        Field::inst('material.MR_No'),
        Field::inst('users.Name'),
        Field::inst('material.generatePO'),
        Field::inst('request.total'),
        Field::inst('po.sum'),
        Field::inst('mrviewlog.created_at'),
        Field::inst('view.Name'),
        Field::inst('tracker.`Unique Id`')
      )
    ->leftjoin('tracker','tracker.Id','=','material.TrackerId')
    ->leftjoin('users','users.Id','=','material.UserId')
    ->leftJoin( '(select Max(Id) as maxid,MaterialId from materialstatus Group By MaterialId) as max', 'max.MaterialId', '=', 'material.Id')
    ->leftJoin('materialstatus', 'materialstatus.Id', '=','max.maxid')
    ->leftjoin("(Select MaterialId,SUM(Qty*Price) as total from materialrequest left join inventories on materialrequest.InventoryId = inventories.Id  group by materialrequest.MaterialId) as request",
    'request.MaterialId','=','material.Id')
    ->leftjoin('(Select materialpoitem.MaterialId,SUM(Qty*Price) as sum from materialpoitem left join materialpo on materialpo.Id = materialpoitem.PoId where materialpo.Status <> "Cancelled" group by materialpoitem.MaterialId) as po','po.MaterialId','=','material.Id')
    ->leftjoin('(Select Max(Id) as maxid,MaterialId from mrviewlog group by MaterialId) as max1','max1.MaterialId','=','material.Id')
    ->leftjoin('mrviewlog','mrviewlog.Id','=','max1.maxid')
    ->leftjoin('users as view','view.Id','=','mrviewlog.UserId');

    if(isset($_GET['status'])){
       $editor->where('materialstatus.Status',$_GET['status'],'LIKE');
    }
    if(isset($_GET['id'])){
        $editor->where('material.UserId',$_GET['id']);
    }
    if(isset($_GET['TrackerId'])){
        $editor->where('material.TrackerId',$_GET['TrackerId']);
    }

    if(isset($_GET['mr_no'])){
       $editor->where('material.MR_No',$_GET['mr_no'],'LIKE');
    }



    $editor->process( $_POST )->json();
