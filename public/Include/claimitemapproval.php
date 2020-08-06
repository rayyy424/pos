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
  $editor=Editor::inst( $db, 'claimitemstatuses')
  	->fields(
      Field::inst( 'claimitemstatuses.Id' ),
      Field::inst( 'claimitemstatuses.ClaimItemId' ),
      Field::inst( 'claims.UserId' ),
      Field::inst( 'claimitems.Date' ),
      Field::inst( 'claimitems.Project_Code_Id' ),
      Field::inst( 'projectcodes.Project_Code' ),
      Field::inst( 'claimitems.ProjectId' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'claimitems.Site_Name' ),
      Field::inst( 'claimitems.State' ),
      Field::inst( 'projects.Project_Manager' ),
      Field::inst( 'pm.Name' ),
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
    ->leftJoin('claimitems', 'claimitems.Id', '=', 'claimitemstatuses.ClaimItemId')
    ->leftJoin('projects', 'claimitems.ProjectId', '=', 'projects.Id')
    ->leftJoin('projectcodes', 'claimitems.Project_Code_Id', '=', 'projectcodes.Id')
    ->leftJoin('users as pm', 'projects.Project_Manager', '=', 'pm.Id')
    ->leftJoin('users as approver', 'claimitemstatuses.UserId', '=', 'approver.Id');

    if (isset( $_GET['ClaimId'])) {
      $editor
      ->where('claimitems.ClaimId',$_GET['ClaimId'] )
      ->where('approver.Id',$_GET['UserId'] );
    }

    $editor->process( $_POST )->json();
