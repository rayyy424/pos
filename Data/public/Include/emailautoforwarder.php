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
  $editor=Editor::inst( $db, 'emailautoforwarder' )
  	->fields(
      Field::inst( 'emailautoforwarder.Id' ),
      Field::inst( 'emailautoforwarder.Domain' ),
      Field::inst( 'emailautoforwarder.Group_Email' ),
      Field::inst( 'emailautoforwarder.User' )

    );

      if (isset( $_GET['domain'])) {
        $editor
          ->where('emailautoforwarder.Domain',$_GET['domain']);
      }



  $editor->process( $_POST )->json();
