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
  $editor=Editor::inst( $db, 'claimitems')
  	->fields(
      Field::inst( 'claimitems.Id' ),
      Field::inst( 'claimitems.claimId' ),
      Field::inst( 'claimitems.Date' ),
      Field::inst( 'claimitems.Site_Name' ),
      Field::inst( 'claimitems.State' ),
      Field::inst( 'claimitems.Work_Description' ),
      Field::inst( 'claimitems.Expenses_Type' ),
      Field::inst( 'claimitems.GST_No' ),
      Field::inst( 'claimitems.GST_Amount' ),
      Field::inst( 'claimitems.Total_Amount' ),
      Field::inst( 'claimitems.Remarks' ),
      Field::inst( 'claimitemstatuses.UserId' ),
      Field::inst( 'approver.Name' ),
      Field::inst( 'claimitemstatuses.Status' ),
      Field::inst( 'claimitemstatuses.Comment' )
    )
    ->leftJoin('claimitemstatuses', 'claimitems.Id', '=', 'claimitemstatuses.claimItemId')
    ->leftJoin('users as approver', 'claimitemstatuses.UserId', '=', 'approver.Id');

    if (isset( $_GET['ClaimId'])) {
      $editor
      ->where('claimitems.ClaimId',$_GET['ClaimId'] );
    }

    $editor->process( $_POST )->json();
