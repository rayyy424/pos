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

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'allowanceschemeitems' )
  	->fields(
      Field::inst( 'allowanceschemeitems.Id' ),
      Field::inst( 'allowanceschemeitems.AllowanceSchemeId' ),
      Field::inst( 'allowanceschemeitems.Day_Type' ),
  		Field::inst( 'allowanceschemeitems.Start' ),
      Field::inst( 'allowanceschemeitems.End' ),
      Field::inst( 'allowanceschemeitems.Minimum_Hour' )->validator( 'Validate::numeric' ),
      Field::inst( 'allowanceschemeitems.Currency' ),
      Field::inst( 'allowanceschemeitems.Home_Base' )->validator( 'Validate::numeric' ),
      Field::inst( 'allowanceschemeitems.Outstation' )->validator( 'Validate::numeric' ),
      Field::inst( 'allowanceschemeitems.Subsequent_Home_Base' )->validator( 'Validate::numeric' ),
      Field::inst( 'allowanceschemeitems.Subsequent_Outstation' )->validator( 'Validate::numeric' ),
      Field::inst( 'allowanceschemeitems.Remarks' )
    );

      if (isset( $_GET['Id'])) {
        $editor
        ->where('allowanceschemeitems.AllowanceSchemeId',$_GET['Id'] );
      }

      $editor->process( $_POST )->json();
