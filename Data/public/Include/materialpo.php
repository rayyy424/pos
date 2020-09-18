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

  $editor=Editor::inst( $db,'materialpo')
  	->fields(
        Field::inst('materialpo.PO_No'),
        Field::inst('material.MR_No'),
        Field::inst('materialpo.Id'),
        Field::inst('vendor.Company_Name'),
        Field::inst('item.total'),
        Field::inst('company.Company_Name'),
        Field::inst('materialpo.CompanyId'),
        Field::inst('materialpo.created_at'),
        Field::inst('created.Name'),
        Field::inst('materialpo.Terms'),
        Field::inst('materialpo.Delivery_Date'),
        Field::inst('item.Type'),
        Field::inst('materialpo.Reason'),
        Field::inst('cancel.Name'),
        Field::inst('materialpo.updated_at'),
        Field::inst('vendor.Company_Account')
      
    )
    ->leftjoin('users as created','created.Id','=','materialpo.created_by')
    ->leftjoin('material','material.Id','=','materialpo.MaterialId')
    ->leftjoin('tracker','tracker.Id','=','material.TrackerId')
    ->leftjoin('(SELECT Type,PoId,SUM(Round(Qty*Price,2)) as total from materialpoitem group by PoId) as item','item.PoId','=','materialpo.Id')
    ->leftjoin('companies as vendor','vendor.Id','=','materialpo.VendorId')
    ->leftjoin('companies as company','company.Id','=','materialpo.CompanyId')
    ->leftjoin('users as cancel','cancel.Id','=','materialpo.cancel_by');

    if(isset($_GET['mid'])){
      if($_GET['mid'] != 0)
        $editor->where('materialpo.MaterialId',$_GET['mid']);
    }
    if(isset($_GET['userid'])){
      $editor->where('materialpo.created_by',$_GET['userid']);
    }
    if(isset($_GET['start']) && isset($_GET['end'])){
      $editor->where(function($q){
        $q->where( 'UNIX_TIMESTAMP(materialpo.created_at)', 'BETWEEN '.strtotime($_GET['start']).' AND '.strtotime("+1 day",strtotime($_GET['end'])), "", false );
      });
    }
    if(isset($_GET['status'])){
        if($_GET['status'] == ""){
          $editor->where('materialpo.Status',"");
        }else{
          $editor->where('materialpo.Status',$_GET['status']);
        }
    }
    $editor->process( $_POST )->json();
