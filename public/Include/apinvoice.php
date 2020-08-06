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
  $editor=Editor::inst( $db, 'materialpo' )
  	->fields(
        Field::inst( 'materialpo.Id' ),
        Field::inst( 'materialpo.created_at' ),
        Field::inst( 'materialpo.DocDate' ),
        Field::inst( 'vendor.CreditorCode' ),
        Field::inst( 'materialpo.SupplierInvoiceNo' ),
        Field::inst( 'tracker.Project_Code' ),
        Field::inst( 'company.Company_Name' ),
        Field::inst( 'users.name' ),

      )
      ->leftJoin('material', 'material.Id', '=', 'materialpo.MaterialId')
      ->leftJoin('tracker', 'tracker.Id', '=', 'material.TrackerId')
      ->leftJoin('companies as company', 'company.Id', '=', 'materialpo.CompanyId')
      ->leftJoin('companies as vendor', 'company.Id', '=', 'materialpo.VendorId')
      ->leftJoin('users', 'users.Id', '=', 'materialpo.created_by');

      $editor->process( $_POST )->json();
