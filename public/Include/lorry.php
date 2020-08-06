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

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'lorry' )
  	->fields(
      Field::inst( 'lorry.lorryId' ),
      Field::inst( 'roadtax.Lorry_Size' ),
      Field::inst( 'roadtax.Vehicle_No' ),
      Field::inst( 'lorry.Destination' ),
      Field::inst( 'lorry.Status' )

  )
->leftJoin('roadtax', 'roadtax.Id', '=', 'lorry.roadtax_Id');


// if (isset( $_GET['formId'])) {
//       $editor
//       ->where('deliveryitemtemp.formId',$_GET['formId'] );
//     }
// $editor->process( $_POST )->json();
