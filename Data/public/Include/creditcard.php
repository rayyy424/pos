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
  $editor=Editor::inst( $db, 'creditcards')
  	->fields(
      Field::inst( 'creditcards.Id' ),
      Field::inst( 'creditcards.Type' ),
      Field::inst( 'creditcards.Owner' ),
      Field::inst( 'creditcards.Statement_Date' ),
      Field::inst( 'creditcards.Statement_Due' ),
      Field::inst( 'creditcards.Current_Balance' )->validator( 'Validate::numeric' ),
      Field::inst( 'creditcards.Payment_Date' ),
      Field::inst( 'creditcards.Payment_Type' ),
      Field::inst( 'creditcards.Amount' )->validator( 'Validate::numeric' )
    );

    if (isset( $_GET['owner'])) {
      $editor
      ->where('creditcards.Owner',$_GET['owner'] );
    }

  

    $editor->process( $_POST )->json();
