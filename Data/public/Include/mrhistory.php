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


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST

  $editor=Editor::inst( $db,'mrhistory')
  	->fields(
        Field::inst('mrhistory.MR_No'),
        Field::inst('mrhistory.created_at'),
        Field::inst('users.Name'),
        Field::inst('mrhistory.Id')
    )->leftjoin('users','users.Id','=','mrhistory.created_by');
    if(isset($_GET['materialId'])){
        $editor->where('mrhistory.MaterialId',$_GET['materialId']);
    }
    $editor->process( $_POST )->json();
