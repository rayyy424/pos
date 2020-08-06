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
  $editor=Editor::inst( $db, 'projectcodes')
  	->fields(
      Field::inst( 'projectcodes.Id' ),
      Field::inst( 'projectcodes.ProjectId' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'projectcodes.Site_ID' ),
      Field::inst( 'projectcodes.Site_Name' ),
      Field::inst( 'projectcodes.Project_Code' ),
      Field::inst( 'projectcodes.Description' ),
      Field::inst( 'projectcodes.Created_By' ),
      Field::inst( 'users.Name' ))
    ->leftJoin('projects', 'projectcodes.ProjectId', '=', 'projects.Id')
    ->leftJoin('users', 'projectcodes.Created_By', '=', 'users.Id');

    if (isset( $_GET['projectids'])) {
        $editor->where( function ( $q ) {
        $q->where( 'projectcodes.ProjectId', '(SELECT ID FROM projects WHERE ID IN ('.$_GET['projectids'].'))', 'IN', false );
      } );
    }

    $editor
    ->process( $_POST )
  	->json();
