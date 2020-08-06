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

if(isset($_GET['projectid']))
{
  $projectid=$_GET['projectid'];
}

if(isset($_POST['projectid']))
{
  $projectid=$_POST['projectid'];
}

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
            ->link( 'trackertemplate.Id', 'templateaccess.TrackerTemplateId and ProjectId='.$projectid )
            ->order( 'Tracker_Name asc' )
            ->fields(
                Field::inst( 'Id' )
                    ->validator( 'Validate::required' )
                    ->options(function(){
                        global $db;
                        global $projectid;
                        return $db->sql('SELECT Id as value,Combine as label FROM trackertemplate WHERE ProjectId='.$projectid.';')->fetchAll();
                    }),
               Field::inst( 'Tracker_Name' ),
               Field::inst( 'Combine' )
            )
    )
    ->join(
        Mjoin::inst( 'trackertemplate' )
            ->name( 'trackertemplate2' )
            ->link( 'users.Id', 'templatewriteaccess.UserId' )
            ->link( 'trackertemplate.Id', 'templatewriteaccess.TrackerTemplateId and ProjectId='.$projectid )
            ->order( 'Tracker_Name asc' )
            ->fields(
                Field::inst( 'Id' )
                    ->validator( 'Validate::required' )
                    ->options(function(){
                        global $db;
                        global $projectid;
                        return $db->sql('SELECT Id as value,Combine as label FROM trackertemplate WHERE ProjectId='.$projectid.';')->fetchAll();
                    }),
               Field::inst( 'Tracker_Name' ),
               Field::inst( 'Combine' )
            )
    )
    ->join(
        Mjoin::inst( 'trackertemplate' )
            ->name( 'trackertemplate3' )
            ->link( 'users.Id', 'templatedeleteaccess.UserId' )
            ->link( 'trackertemplate.Id', 'templatedeleteaccess.TrackerTemplateId and ProjectId='.$projectid )
            ->order( 'Tracker_Name asc' )
            ->fields(
                Field::inst( 'Id' )
                    ->validator( 'Validate::required' )
                    ->options(function(){
                        global $db;
                        global $projectid;
                        return $db->sql('SELECT Id as value,Tracker_Name as label FROM trackertemplate WHERE ProjectId='.$projectid.';')->fetchAll();
                    }),
               Field::inst( 'Tracker_Name' ),
               Field::inst( 'Combine' )
            )
    )
      ->process( $_POST )
      ->json();
