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
  $editor=Editor::inst( $db, 'purchaseorders' )
  	->fields(
      Field::inst( 'purchaseorders.Id' ),
      Field::inst( 'purchaseorders.PO_No' )
          ->validator('Validate::notEmpty')->validator('Validate::unique'),
      Field::inst( 'purchaseorders.PO_Date' ),
  	  Field::inst( 'purchaseorders.PO_Type' ),
      Field::inst( 'purchaseorders.PO_VO' ),
      Field::inst( 'purchaseorders.Company' ),
      Field::inst( 'purchaseorders.Job_Type' ),
      Field::inst( 'purchaseorders.Payment_Term' ),
      Field::inst( 'purchaseorders.Cut' ),
      Field::inst( 'purchaseorders.PO_Description' ),
      Field::inst( 'purchaseorders.PO_Status' ),
      Field::inst( 'sumtable.Amount as purchaseorders.PO_Amount' ),
      Field::inst( 'purchaseorders.ProjectId' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'purchaseorders.First_Cut' ),
      Field::inst( 'purchaseorders.Second_Cut' ),
      Field::inst( 'purchaseorders.Third_Cut' ),
      Field::inst( 'purchaseorders.Fourth_Cut' ),
      Field::inst( 'purchaseorders.Fifth_Cut' ),
      Field::inst( 'purchaseorders.Remarks' ))
      ->leftJoin('projects', 'purchaseorders.ProjectId', '=', 'projects.Id')
      ->leftJoin('(select PO_Id,SUM(purchaseorderitems.Amount) As Amount FROM purchaseorderitems GROUP BY PO_Id) sumtable', 'purchaseorders.Id', '=', 'sumtable.PO_Id');

      if (isset($_GET['ProjectId']))
      {
          $editor->where( function ( $q ) {
          $q->where( 'purchaseorders.ProjectId',$_GET['ProjectId']);
        } );
      }

      if (isset($_GET['Project_Code']))
      {
          $editor->where( function ( $q ) {
          $q->where( 'purchaseorders.Id', '(select PO_Id from purchaseorderitems where Project_Code="'.$_GET["Project_Code"].'")', 'IN', false );
        } );

      }

      if (isset($_GET['Work_Order_ID']))
      {
        $editor->where( function ( $q ) {
        $q->where( 'purchaseorders.Id', '(select PO_Id from purchaseorderitems where Work_Order_ID="'.$_GET["Work_Order_ID"].'")', 'IN', false );
      } );

      }

    // ->where('users.User_Type','Contractor','<>')
    $editor
    ->process( $_POST )
  	->json();
