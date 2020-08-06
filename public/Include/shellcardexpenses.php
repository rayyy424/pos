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


  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'shellcardexpenses' )
  	->fields(
        Field::inst( 'shellcardexpenses.Id' ),
        Field::inst( 'shellcards.Id' ),
        Field::inst( 'shellcardexpenses.ShellCardId' ),
        Field::inst( 'shellcardexpenses.Payment_Month' ),
        Field::inst( 'shellcardexpenses.Amount' ),
        Field::inst( 'shellcards.Vehicle_No' ),
        Field::inst( 'shellcards.Card_No' )
      )
      ->leftJoin('shellcards', 'shellcards.Id', '=', 'shellcardexpenses.ShellCardId');

      $editor->process( $_POST )->json();
