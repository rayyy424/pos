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

    $editor=Editor::inst( $db,'removepo')
    ->fields(
        Field::inst('removepo.Id'),
        Field::inst('removepo.Description'),
        Field::inst('removepo.Add_Description'),
        Field::inst('removepo.Reason'),
        Field::inst('removepo.Qty'),
        Field::inst('removepo.Price'),
        Field::inst('removepo.Unit'),
        Field::inst('users.Name')
    )
   ->leftjoin('users','users.Id','=','removepo.created_by');
    if(isset($_GET['poid'])){
        $editor->where('removepo.PoId',$_GET['poid']);
    }
 
    $editor->process( $_POST )->json();
