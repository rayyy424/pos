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
  $editor=Editor::inst( $db, 'domain' )
  	->fields(
      Field::inst( 'domain.Id' ),
      Field::inst( 'domain.Company' ),
      Field::inst( 'domain.Email' ),
      Field::inst( 'domain.Password' ),
      Field::inst( 'domain.NestFrom_Password' ),
      Field::inst( 'domain.Created_On' ),
      Field::inst( 'domain.Request_By' )

    );

    if (isset( $_GET['company'])) {
      $editor
        ->where('domain.Company',$_GET['company'] );
    }

    $editor->process( $_POST )->json();
