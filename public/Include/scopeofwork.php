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
 if (isset($_POST['Id']))
 {
   $id=$_POST['Id'];
 }
 else {
   $id=0;
 }

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'scopeofwork' )
  	->fields(
      Field::inst( 'scopeofwork.Id' ),
      Field::inst( 'scopeofwork.UserId' ),
      Field::inst( 'scopeofwork.Type' ),
      Field::inst( 'scopeofwork.Code' ),
      Field::inst( 'scopeofwork.Scope_Of_Work' ),
      Field::inst( 'scopeofwork.KPI' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'scopeofwork.Incentive_1' ),
      Field::inst( 'scopeofwork.Incentive_2' ),
      Field::inst( 'scopeofwork.Incentive_3' ),
  		Field::inst( 'scopeofwork.Incentive_4' ),
      Field::inst( 'scopeofwork.Incentive_5' )
)
->leftJoin('users', 'users.Id', '=', 'scopeofwork.UserId');

      $editor->process( $_POST )->json();
