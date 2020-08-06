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
  $editor=Editor::inst( $db, 'chartcolumns' )
  	->fields(
      Field::inst( 'chartcolumns.Id' ),
      Field::inst( 'chartcolumns.ChartViewId' ),
      Field::inst( 'chartcolumns.Column_Name' ),
      Field::inst( 'chartcolumns.Display_Name' ),
      Field::inst( 'chartcolumns.Count_Type' ),
      Field::inst( 'chartcolumns.Condition' ),
      Field::inst( 'chartcolumns.Series_Color' ),
      Field::inst( 'chartcolumns.Series_Type' ),

      Field::inst( 'chartcolumns.created_at' )
    )
    ->leftJoin('chartviews', 'chartviews.Id', '=', 'chartcolumns.ChartViewId');

    if (isset( $_GET['ChartViewId'])) {
      $editor
      ->where('chartcolumns.ChartViewId',$_GET['ChartViewId'] );
    }

    $editor
    ->process( $_POST )
    ->json();
