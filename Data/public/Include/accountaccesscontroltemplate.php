<?php

include( "plugin/php/DataTables.php" );

// Alias Editor classes so they are easy to use
use
  DataTables\Editor,
  DataTables\Editor\Field,
  DataTables\Editor\Format,
  DataTables\Editor\Mjoin,
  DataTables\Editor\Upload,
  DataTables\Editor\Validate;

/*
 * Example PHP implementation used for the index.html example
 */
// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'users' )
  	->fields(
      Field::inst( 'users.Id' ),
      Field::inst( 'users.Status' ),
      Field::inst( 'users.Internship_Status' ),
      Field::inst( 'users.Internship_End_Date' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'users.StaffId' )->validator('Validate::unique'),
      Field::inst( 'users.SuperiorId' ),
      Field::inst( 'superior.Name' ),
      Field::inst( 'users.Country_Base' ),
      Field::inst( 'users.Home_Base' ),
      Field::inst( 'users.AccessControlTemplateId' ),
  		Field::inst( 'accesscontroltemplates.Template_Name' ),
      Field::inst( 'users.User_Type' ),
      Field::inst( 'users.Position' ),
      Field::inst( 'users.Active' ),
      Field::inst( 'users.Admin' ),
      Field::inst( 'users.Approved' ))
      ->leftJoin('accesscontroltemplates', 'users.AccessControlTemplateId', '=', 'accesscontroltemplates.Id')
      ->leftJoin('users as superior', 'users.SuperiorId', '=', 'superior.Id');

        if (isset( $_GET['type'])) {
          $editor
          ->where('users.User_Type',$_GET['type'] );
        }

        $editor->process( $_POST )->json();
