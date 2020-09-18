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
        Mjoin::inst( 'trackertemplate' )
            ->link( 'users.Id', 'templateaccess.UserId' )
            ->link( 'trackertemplate.Id', 'templateaccess.TrackerTemplateId')
            ->order( 'Tracker_Name asc' )
            ->fields(
                Field::inst( 'Id' )
                    ->validator( 'Validate::required' )
                    ->options(function(){
                        global $db;
                        return $db->sql('SELECT Id as value,Combine as label FROM trackertemplate WHERE Tracker_Name!='';')->fetchAll();
                    }),
               Field::inst( 'Tracker_Name' ),
               Field::inst( 'Combine' )
            )
    )
    ->join(
        Mjoin::inst( 'trackertemplate' )
            ->name( 'trackertemplate2' )
            ->link( 'users.Id', 'templatewriteaccess.UserId' )
            ->link( 'trackertemplate.Id', 'templatewriteaccess.TrackerTemplateId' )
            ->order( 'Tracker_Name asc' )
            ->fields(
                Field::inst( 'Id' )
                    ->validator( 'Validate::required' )
                    ->options(function(){
                        global $db;
                        return $db->sql('SELECT Id as value,Combine as label FROM trackertemplate WHERE Tracker_Name!='';')->fetchAll();
                    }),
               Field::inst( 'Tracker_Name' ),
               Field::inst( 'Combine' )
            )
    )
    ->join(
        Mjoin::inst( 'trackertemplate' )
            ->name( 'trackertemplate3' )
            ->link( 'users.Id', 'templatedeleteaccess.UserId' )
            ->link( 'trackertemplate.Id', 'templatedeleteaccess.TrackerTemplateId')
            ->order( 'Tracker_Name asc' )
            ->fields(
                Field::inst( 'Id' )
                    ->validator( 'Validate::required' )
                    ->options(function(){
                        global $db;
                        return $db->sql('SELECT Id as value,Tracker_Name as label FROM trackertemplate WHERE Tracker_Name!='';')->fetchAll();
                    }),
               Field::inst( 'Tracker_Name' ),
               Field::inst( 'Combine' )
            )
    )
      ->process( $_POST )
      ->json();
