<?php

include( "plugin/php/DataTables.php" );

// Alias Editor classes so they are easy to use
use
  DataTables\Editor,
  DataTables\Editor\Field,
  DataTables\Editor\Format,
  DataTables\Editor\Mjoin,
  DataTables\Editor\Upload,
  DataTables\Editor\Options,
  DataTables\Editor\Validate;
  
/*
  * Example PHP implementation used for the index.html example
*/
 
if (isset($_POST['UserId']))
{
  $id=$_POST['UserId'];
}
else {
  $id=0;
}

// DataTables PHP library
// Build our Editor instance and process the data coming from _POST
$editor=Editor::inst( $db, 'serviceticket' )
->fields(
  Field::inst( 'Id' ),
  Field::inst( 'service_type' ),
  Field::inst( 'technician_name' ),
  // Field::inst( 'speedfreakinventory.branch' ),
  Field::inst( 'service_summary' )
  //Field::inst( 'inventories.Additional_Description' ),
  // Field::inst( 'inventories.Warehouse')
)
->process( $_POST )
->json();