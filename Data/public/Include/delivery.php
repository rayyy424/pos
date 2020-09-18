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

  $editor=Editor::inst($db,'deliveryform')
  ->debug(true)
  ->fields(
    Field::inst('deliveryform.Id'),
    Field::inst('deliverystatuses.Id'),
    Field::inst('requestor.Name'),
    Field::inst('deliveryform.delivery_date'),
    Field::inst('deliveryform.delivery_time'),
    Field::inst('deliveryform.pickup_date'),
    Field::inst('deliveryform.pick_up_time'),
    Field::inst('roadtax.Vehicle_No'),
    Field::inst('roadtax.Lorry_Size'),
    Field::inst('driver.Name'),
    Field::inst('deliveryform.DO_No'),
    Field::inst('deliveryform.Location'),
    Field::inst('radius.Location_Name'),
    Field::inst('deliveryform.roadtaxId'),
    Field::inst('deliveryform.PIC_Name'),
    Field::inst('deliveryform.approve'),
    Field::inst('deliveryform.PIC_Contact'),
    Field::inst('deliveryform.Remarks'),
    Field::inst('deliveryform.created_at'),
    Field::inst('deliveryform.Purpose'),
    Field::inst('deliverystatuses.updated_at'),
    Field::inst('deliverystatuses.created_at'),
    Field::inst('deliverystatuses.remarks'),
    Field::inst('options.Option'),
    Field::inst('deliverystatuses.delivery_status'),
    Field::inst('deliverystatuses.delivery_status_details')

  )
  ->leftJoin('options','deliveryform.Purpose','=','options.Id')
  ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
  ->leftJoin('users as driver','deliveryform.DriverId','=','driver.Id')
  ->leftJoin('companies as company','deliveryform.company_id','=','company.Id')
  // ->leftJoin('companies as client','deliveryform.clientId','=','client.Id')
  ->leftJoin('radius','radius.Id','=','deliveryform.Location')
   ->leftJoin('users as requestor', 'deliveryform.RequestorId', '=', 'requestor.Id')
   // ->leftJoin('users as approver','deliveryform.ApproverId','=','approver.Id')
  // ->leftJoin('deliverystatuses','deliverystatuses.deliveryform_Id','=','deliveryform.Id');
  ->leftJoin( '(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max', 'max.deliveryform_Id', '=', 'deliveryform.Id')
  ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=','max.maxid');

if (isset( $_GET['UserId'])) {
      $editor
      ->where('deliveryform.RequestorId',$_GET['UserId'] );
}

if (isset( $_GET['Status'])) {

      if(strpos($_GET['Status'],"Pending")!==false)
      {
        $editor
        ->where( function ( $q ) {
            $q
                ->where( 'deliverystatuses.delivery_status', $_GET['Status'], 'like' )
                ->or_where( function ( $r ) {
                    $r->where( 'deliverystatuses.delivery_status', null);
                    $r->where( 'deliveryform.RequestorId', $_GET['UserId']);
                } );
        } );
      }
      else {
        $editor
        ->where('deliverystatuses.delivery_status',$_GET['Status'],'like' );
      }

    }

    $editor->process( $_POST )->json();




