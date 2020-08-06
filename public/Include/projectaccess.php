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
  $editor=Editor::inst( $db, 'users','Id' )
  	->fields(
      Field::inst( 'users.Id' ),
      Field::inst( 'users.StaffId' ),
      Field::inst( 'users.Name' )
    )
    ->join(
        Mjoin::inst( 'projects' )
            ->link( 'users.Id', 'projectaccess.UserId' )
            ->link( 'projects.Id', 'projectaccess.projectId' )
            ->order( 'Project_Name asc' )
            ->fields(
                Field::inst( 'Id' )
                    ->validator( 'Validate::required' )
                    ->options( Options::inst()
                        ->table( 'projects' )
                        ->value( 'Id' )
                        ->label( 'Project_Name' )
                    ),
               Field::inst( 'Project_Name' )
            )
    )
      ->process( $_POST )
      ->json();
