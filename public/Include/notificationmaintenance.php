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
  $editor=Editor::inst( $db, 'notificationtype','Id' )
  	->fields(
      Field::inst( 'notificationtype.Id' ),
      Field::inst( 'notificationtype.Notification_Name' ),
      Field::inst( 'notificationtype.Description' ),
      Field::inst( 'notificationtype.Notification_Subject' ),
      Field::inst( 'notificationtype.Notification_Content' )
    )

    ->join(
        MJoin::inst( 'users' )
            ->link( 'notificationtype.Id' , 'notificationsubscriber.NotificationTypeId')
            ->link( 'users.Id' , 'notificationsubscriber.UserId')
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
