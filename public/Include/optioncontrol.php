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
  $editor=Editor::inst( $db, 'options' )
  	->fields(
      Field::inst( 'options.Id' ),
      Field::inst( 'options.UserId' ),
      Field::inst( 'options.Table' ),
  		Field::inst( 'options.Field' ),
      Field::inst( 'options.Option' ),
      Field::inst( 'options.Extra' ),
      Field::inst( 'options.Section' ),
      Field::inst( 'options.Description' ),
      Field::inst( 'options.Update_Column' )
    )

    ->join(
          Mjoin::inst( 'projects' )
              ->link( 'options.Id', 'optionprojects.OptionId' )
              ->link( 'projects.Id', 'optionprojects.ProjectId' )
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
      );

      if (isset( $_GET['type'])) {

        if( $_GET['type']=="Report Store")
        {
          $editor
          ->where('options.Field','Document_Type');
        }
        else {
          // code...
          $editor
          ->where('options.Table',$_GET['type'] );
        }

      }

      $editor->process( $_POST )->json();
