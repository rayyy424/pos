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
  $editor=Editor::inst( $db, 'radius' )
    ->fields(
      Field::inst( 'radius.Id' ),
      Field::inst( 'radius.Location_Name' ),
      Field::inst( 'radius.Area' ),
      Field::inst( 'radius.Latitude' ),
      Field::inst( 'radius.Longitude' ),
      Field::inst( 'radius.Client' ),
      Field::inst( 'radius.Code' ),
      Field::inst( 'radius.Start_Date' ),
      Field::inst( 'radius.Completion_Date' ),
      Field::inst( 'radius.ProjectId' ),
      Field::inst( 'projects.Project_Name' )
    )
    ->leftJoin('projects','projects.Id','=','radius.ProjectId')
    ->process( $_POST )
    ->json();
