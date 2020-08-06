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
  $editor=Editor::inst( $db, 'deliveryform' )
    ->fields(
      Field::inst( 'deliveryform.Id' ),
      Field::inst( 'deliveryform.incentive' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'deliveryform.DO_No' ),
      Field::inst( 'radius.Location_Name' ),
      Field::inst( 'deliverylocation.area' ),
      Field::inst( 'roadtax.Lorry_Size' ),
      Field::inst( 'deliverylocation.price_2ton_to_5ton' ),
      Field::inst( 'deliverylocation.price_5ton_crane' ),

      Field::inst( 'deliverylocation.price_10ton_crane' ),
      Field::inst( 'deliverylocation.price_10ton' ),
      Field::inst( 'deliveryform.Id as Temp' )



    )
    ->leftJoin('users','users.Id','=','deliveryform.DriverId')
    ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId AND deliveryform.roadtaxId != 0 AND roadtax.Type != "TRUCK" AND roadtax.Lorry_Size != ""')
    ->leftJoin('radius','radius.Id','=','deliveryform.Location')
    ->leftJoin('deliverylocation','deliverylocation.area','=','radius.Area AND deliverylocation.type = "charges"')
    ->leftJoin( '(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max', 'max.deliveryform_Id', '=', 'deliveryform.Id')
    ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=','max.maxid AND deliverystatuses.delivery_status_details != "-"');

    if (isset( $_GET['Start'])) {
      $editor
          ->where( function ( $q ){
              $q->where( 'str_to_date(deliveryform.delivery_date,"%d-%M-%Y")', 'BETWEEN (str_to_date("'.$_GET['Start'].'","%d-%M-%Y")) AND (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
          } );
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
    //   if (isset( $_GET['Target'])) {

    //   if(strpos($_GET['Target'],"charges")!==false)
    //   {
    //     $editor
    //     ->where( function ( $q ) {
    //         $q
    //             ->where( 'deliverylocation.type', $_GET['Target'], 'like' )
    //             ->or_where( function ( $r ) {
    //                 $r->where( 'deliverylocation.type', null);
    //             } );
    //     } );
    //   }
    //   else {
    //     $editor
    //     ->where('deliverylocation.type',$_GET['Target'],'like' );
    //   }

    // }
    $editor->process( $_POST )->json();
