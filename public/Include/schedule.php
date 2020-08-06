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
 if (isset($_POST['Id']))
 {
   $id=$_POST['Id'];
 }
 else {
   $id=0;
 }

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'schedules','Id' )
  	->fields(
      Field::inst( 'schedules.Id' ),
      Field::inst( 'schedules.Event' ),
      Field::inst( 'schedules.Start_Date' ),
      Field::inst( 'schedules.End_Date' ),
      Field::inst( 'schedules.Venue' ),
      Field::inst( 'schedules.Time' ),
  		Field::inst( 'schedules.Remarks' ),
      Field::inst( 'schedules.Assigned_By' ),
      Field::inst( 'Assign.Name' )

    )
    ->leftJoin('users as Assign', 'Assign.Id', '=', 'schedules.Assigned_By')
    ->join(
        Mjoin::inst( 'users' )
            ->link( 'schedules.Id', 'schedulecandidates.ScheduleId' )
            ->link( 'users.Id', 'schedulecandidates.UserId' )
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
    )
      ->process( $_POST )
      ->json();
