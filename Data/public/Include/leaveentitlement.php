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
  $editor=Editor::inst( $db, 'leaveentitlements' )
  	->fields(
      Field::inst( 'leaveentitlements.Id' ),
      Field::inst( 'leaveentitlements.Grade' ),
      Field::inst( 'leaveentitlements.Year' )->validator( 'Validate::numeric' ),
  		Field::inst( 'leaveentitlements.Days' )->validator( 'Validate::numeric' ),
      Field::inst( 'leaveentitlements.Leave_Type' )
    );

      if (isset( $_GET['grade'])) {
        $editor
        ->where('leaveentitlements.Grade',$_GET['grade'] );
      }

      $editor->process( $_POST )->json();
