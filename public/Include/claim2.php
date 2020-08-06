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
  $editor=Editor::inst( $db, 'claims')
  	->fields(
      Field::inst( 'claims.Id' ),
      Field::inst( 'submitter.Name' ),
      Field::inst( 'claims.Claim_Name' ),
      Field::inst( 'claims.Date' ),
      Field::inst( 'claims.Project_Code' ),
      Field::inst( 'claims.Project' ),
      Field::inst( 'claims.Site_Name' ),
      Field::inst( 'claims.State' ),
      Field::inst( 'claims.Work_Description' ),
      Field::inst( 'claims.Expenses_Type' ),
      Field::inst( 'claims.GST_No' ),
      Field::inst( 'claims.GST_Amount' ),
      Field::inst( 'claims.Total_Amount' ),
      Field::inst( 'claims.Remarks' ),
      Field::inst( 'approver.Name' ),
      Field::inst( 'claimstatuses.Claim_Status' ),
      Field::inst( 'claimstatuses.updated_at' ),
      Field::inst( 'claimstatuses.Comment' )
  	)
    ->leftJoin('users as submitter', 'claims.UserId', '=', 'submitter.Id')
    ->leftJoin('claimstatuses', 'claims.Id', '=', 'claimstatuses.ClaimId')
    ->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
    ->process( $_POST )
  	->json();
