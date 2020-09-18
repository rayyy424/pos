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
  $editor=Editor::inst( $db, 'materialstatus')
  	->fields(
        Field::inst('materialstatus.Id'),
        Field::inst('materialstatus.Status'),
        Field::inst('materialstatus.created_at'),
        Field::inst('materialstatus.ApproverId'),
        Field::inst('users.Name'),
        Field::inst('materialstatus.MaterialId'),
        Field::inst('materialstatus.Reason')
      )
      ->leftjoin('users','users.Id','=','materialstatus.ApproverId');

    if(isset($_GET['id'])){
        $editor->where('materialstatus.MaterialId',$_GET['id']);
    }
    
  
   

    $editor->process( $_POST )->json();
