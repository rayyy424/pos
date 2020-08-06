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
  $editor=Editor::inst( $db, 'siteissue' )
  	->fields(
      Field::inst( 'siteissue.Id' ),
      Field::inst( 'siteissue.Status' ),
      Field::inst( 'siteissue.Site_ID' ),
      Field::inst( 'siteissue.Site_Name' ),
      Field::inst( 'siteissue.ProjectId' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'siteissue.Scope_Of_Work' ),
      Field::inst( 'siteissue.Issue_Description' ),
      Field::inst( 'siteissue.Date' ),
      Field::inst( 'siteissue.Time' ),
      Field::inst( 'siteissue.Person_In_Charge' ),
      Field::inst( 'siteissue.Remarks' ),
      Field::inst( 'siteissue.Solution' ),
      Field::inst( 'siteissue.created_at' ),
      Field::inst( 'siteissue.updated_at' ),
      Field::inst( 'siteissue.created_by' ),
      Field::inst( 'users.Name' )
    )
    ->leftJoin('users', 'siteissue.created_by', '=', 'users.Id')
    ->leftJoin('projects', 'siteissue.ProjectId', '=', 'projects.Id');


    $editor
      ->process( $_POST )
      ->json();
