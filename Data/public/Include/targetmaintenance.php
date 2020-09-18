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
  $editor=Editor::inst( $db, 'targets' )
  	->fields(
      Field::inst( 'targets.Id' ),
      Field::inst( 'targets.UserId' ),
      Field::inst( 'targets.Active' ),
      Field::inst( 'targets.Title' ),
      Field::inst( 'targets.Target_Field' ),
      Field::inst( 'targets.Target_Date' ),
      Field::inst( 'targets.Target' ),
      Field::inst( 'creator.Name' )
    )
    ->leftJoin('users as creator', 'targets.UserId', '=', 'creator.Id')

    ->join(
        MJoin::inst( 'users' )
            ->link( 'users.Id' , 'targetsubscribers.UserId')
            ->link( 'agings.Id' , 'targetsubscribers.TargetId')
            ->order( 'Name asc' )
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
    );

    $editor
      ->process( $_POST )
      ->json();
