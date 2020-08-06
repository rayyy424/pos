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
 if (isset($_POST['UserId']))
 {
   $id=$_POST['UserId'];
 }
 else {
   $id=0;
 }

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'invoices' )
  	->fields(
      Field::inst( 'invoices.Id' ),
      Field::inst( 'invoices.Invoice_No' ),
      Field::inst( 'invoices.Invoice_Date' ),
  	  Field::inst( 'invoices.Invoice_Type' ),
      Field::inst( 'invoices.Company' ),
      Field::inst( 'invoices.Invoice_Description' ),
      Field::inst( 'invoices.Invoice_Amount' )->validator( 'Validate::numeric' ),
      Field::inst( 'invoices.Invoice_Status' ),
      Field::inst( 'invoices.ProjectId' ),
      Field::inst( 'projects.Project_Name' ))
      ->leftJoin('projects', 'invoices.ProjectId', '=', 'projects.Id')
    // ->where('users.User_Type','Contractor','<>')
    ->process( $_POST )
  	->json();
