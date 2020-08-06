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

  // if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'edit'))
  // {
  //
  //   foreach ($_POST['data'] as $key => $value) {
  //
  //     if (isset( $_POST['data'][$key]['shellcards']['Quantity']))
  //     {
  //       if ($_POST['data'][$key]['shellcards']['Quantity']>0 && $_POST['data'][$key]['shellcards']['Usage_RM_ltr']>0)
  //       {
  //         $_POST['data'][$key]['shellcards']['Total']=$_POST['data'][$key]['shellcards']['Quantity']*$_POST['data'][$key]['shellcards']['Usage_RM_ltr'];
  //       }
  //
  //     }
  //
  //   }
  //
  // }

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'vehicleevent' )
  	->fields(
        Field::inst( 'vehicleevent.VehicleId' ),
        Field::inst( 'vehicleevent.Event' ),
        Field::inst( 'vehicleevent.Start_Date' ),
        Field::inst( 'vehicleevent.End_Date' ),
        Field::inst( 'roadtax.available' )
      )
      ->leftJoin('roadtax','roadtax.Id','=','vehicleevent.VehicleId');

      $editor->process( $_POST )->json();
