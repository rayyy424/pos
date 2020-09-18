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
  $editor=Editor::inst( $db, 'deliverylocation' )
  	->fields(
      Field::inst( 'deliverylocation.Id' ),
      Field::inst( 'deliverylocation.area' ),
      Field::inst( 'deliverylocation.type' ),
      Field::inst( 'deliverylocation.price_2ton_to_5ton' ),
      Field::inst( 'deliverylocation.price_5ton_crane' ),
      Field::inst( 'deliverylocation.price_10ton' ),
      Field::inst( 'deliverylocation.price_10ton_crane' ),
      Field::inst( 'deliverylocation.created_by' ),
      Field::inst( 'users.Name' )
    )
    ->leftJoin('users', 'users.Id', '=', 'deliverylocation.created_by');

      if (isset( $_GET['Target'])) {

      if(strpos($_GET['Target'],"charges")!==false)
      {
        $editor
        ->where( function ( $q ) {
            $q
                ->where( 'deliverylocation.type', $_GET['Target'], 'like' )
                ->or_where( function ( $r ) {
                    $r->where( 'deliverylocation.type', null);
                } );
        } );
      }
      else {
        $editor
        ->where('deliverylocation.type',$_GET['Target'],'like' );
      }

    }
    $editor->process( $_POST )->json();
