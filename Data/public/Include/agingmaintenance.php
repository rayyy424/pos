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
  $editor=Editor::inst( $db, 'agings' )
  	->fields(
      Field::inst( 'agings.Id' ),
      Field::inst( 'agings.UserId' ),
      Field::inst( 'agings.Active' ),
      Field::inst( 'agings.Title' ),
      Field::inst( 'agings.Type' ),
      Field::inst( 'agings.Start_Date' ),
      Field::inst( 'agings.End_Date' ),
      Field::inst( 'agings.Threshold' ),
      Field::inst( 'agings.Recurring_Frequency' ),
      Field::inst( 'agings.Frequency_Unit' ),
      Field::inst( 'agings.Sequence' ),
      Field::inst( 'agings.Remarks' ),
      Field::inst( 'creator.Name' )
    )
    ->leftJoin('users as creator', 'agings.UserId', '=', 'creator.Id')

    ->join(
        MJoin::inst( 'users' )
            ->link( 'agings.Id' , 'agingsubscribers.AgingId')
            ->link( 'users.Id' , 'agingsubscribers.UserId')
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
