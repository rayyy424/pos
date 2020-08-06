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
  $editor=Editor::inst( $db, 'autodate' )
  	->fields(
      Field::inst( 'autodate.Id' ),
      Field::inst( 'autodate.UserId' ),
      Field::inst( 'autodate.Active' ),
      Field::inst( 'autodate.ProjectId' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'autodate.Type' ),
      Field::inst( 'autodate.Date_1' ),
      Field::inst( 'autodate.Date_2' ),
      Field::inst( 'autodate.Days' ),
      Field::inst( 'creator.Name' )
    )
    ->leftJoin('users as creator', 'autodate.UserId', '=', 'creator.Id')
    ->leftJoin('projects', 'autodate.ProjectId', '=', 'projects.Id');

    if (isset( $_GET['ProjectId'])) {
      $editor
      ->where('ProjectId',$_GET['ProjectId'] );
    }


    $editor
      ->process( $_POST )
      ->json();
