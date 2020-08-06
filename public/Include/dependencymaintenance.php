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
  $editor=Editor::inst( $db, 'dependencyrules' )
  	->fields(
      Field::inst( 'dependencyrules.Id' ),
      Field::inst( 'dependencyrules.UserId' ),
      Field::inst( 'dependencyrules.Active' ),
      Field::inst( 'dependencyrules.ProjectId' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'dependencyrules.Sequence' ),
      Field::inst( 'dependencyrules.Title' ),

      Field::inst( 'dependencyrules.Column1' ),
      Field::inst( 'dependencyrules.Column1_Status' ),
      Field::inst( 'dependencyrules.Column2' ),
      Field::inst( 'dependencyrules.Column2_Status' ),
      Field::inst( 'dependencyrules.Column3' ),
      Field::inst( 'dependencyrules.Column3_Status' ),

      Field::inst( 'dependencyrules.Target_Column' ),
      Field::inst( 'dependencyrules.Target_Status' ),


      Field::inst( 'creator.Name' )
    )
    ->join(
        MJoin::inst( 'users' )
          ->name( 'notify' )
            ->link( 'dependencyrules.Id' , 'dependencynotification.DependencyRulesId')
            ->link( 'users.Id' , 'dependencynotification.UserId')
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
    ->leftJoin('users as creator', 'dependencyrules.UserId', '=', 'creator.Id')
    ->leftJoin('projects', 'dependencyrules.ProjectId', '=', 'projects.Id');

    if (isset( $_GET['ProjectId'])) {
      $editor
      ->where('ProjectId',$_GET['ProjectId'] );
    }

    if (isset( $_POST['ProjectId'])) {
      $editor
      ->where('ProjectId',$_POST['ProjectId'] );
    }

    $editor
      ->process( $_POST )
      ->json();
