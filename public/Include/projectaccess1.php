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
  $editor=Editor::inst( $db, 'projects','Id' )
  	->fields(
      Field::inst( 'projects.Id' ),
      Field::inst( 'projects.Project_Name' )
    )
    ->join(
        Mjoin::inst( 'users' )
            ->link( 'projects.Id', 'projectaccess.projectId' )
            ->link( 'users.Id', 'projectaccess.UserId' )
            ->order( 'Project_Name asc' )
            ->fields(
                Field::inst( 'Id' )
                    ->validator( 'Validate::required' )
                    ->options( Options::inst()
                        ->table( 'users' )
                        ->value( 'Id' )
                        ->label( 'Name' )
                    ),
               Field::inst( 'Name' )
            )
    )
      ->process( $_POST )
      ->json();
