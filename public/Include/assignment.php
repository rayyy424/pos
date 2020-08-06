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
// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'assignments' )
  	->fields(
      Field::inst( 'assignments.Id' ),
      Field::inst( 'assignments.Status' ),
      Field::inst( 'assignments.Site_Name' ),
      Field::inst( 'assignments.ProjectId' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'assignments.Due_Date' ),
      Field::inst( 'assignments.Staff' ),
      Field::inst( 'assignments.Subcon' ),
      Field::inst( 'assignments.Completed_Date' ),
      Field::inst( 'assignments.Remarks' ),
      Field::inst( 'assignments.created_at' ),
      Field::inst( 'assignments.updated_at' ),
      Field::inst( 'assignments.created_by' ),
      Field::inst( 'users.Name' )
    )
    ->leftJoin('users', 'assignments.created_by', '=', 'users.Id')
    ->leftJoin('projects', 'assignments.ProjectId', '=', 'projects.Id');


    if (isset( $_POST['Name'])) {
    $editor
    ->where('Staff',$_POST['Name'] )
    ->where( function ($q) {
      $q->or_where( 'Subcon', $_POST['Name'] );
    } );

  }

    $editor
      ->process( $_POST )
      ->json();
