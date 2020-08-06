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
  $editor=Editor::inst( $db, 'projectrequirements' )
  	->fields(
      Field::inst( 'projectrequirements.Id' ),
      Field::inst( 'projectrequirements.ProjectId' ),
      Field::inst( 'projectrequirements.Type' ),
      Field::inst( 'projectrequirements.Requirement' )->validator( 'Validate::numeric' ),
  		Field::inst( 'projectrequirements.Start_Date' ),
      Field::inst( 'projectrequirements.End_Date' )
    );

    if (isset( $_GET['ProjectId'])) {
      $editor
      ->where('projectrequirements.ProjectId',$_GET['ProjectId'] );
    }

    $editor->process( $_POST )->json();
