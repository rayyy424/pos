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
 if (isset($_POST['Id']))
 {
   $id=$_POST['Id'];
 }
 else {
   $id=0;
 }

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'invoices','Id' )
  	->fields(
      Field::inst( 'invoices.Id' ),
      Field::inst( 'invoices.Invoice_No' ),
      Field::inst( 'invoices.Invoice_Date' ),
  	  Field::inst( 'invoices.Invoice_Type' ),
      Field::inst( 'invoices.Company' ),
      Field::inst( 'invoices.Invoice_Remarks' ),
      Field::inst( 'invoices.Invoice_Amount' )->validator( 'Validate::numeric' ),
      Field::inst( 'invoices.Invoice_Labour_Charge' ),
      Field::inst( 'invoices.Invoice_Status' )
    )
    ->leftJoin('invoiceitems','invoices.Id', '=' ,'invoiceitems.InvoicesId')
    ->leftJoin('inventories','inventories.Id', '=' ,'invoiceitems.InventoryId')
    ->leftJoin('inventorysalesprice','inventorysalesprice.inventoryId', '=' ,'invoiceitems.InventoryId')
    ->join(
        Mjoin::inst( 'speedfreakinventory' )
            ->link( 'invoices.Id', 'invoiceitems.InvoicesId' )
            ->link( 'speedfreakinventory.Id', 'invoiceitems.InventoryId' )
            // ->link( 'inventorysalesprice.price', 'invoiceitems.Itemprice')
            ->order( 'name asc' )
            ->fields(
                Field::inst( 'Id' )
                    ->validator( 'Validate::required' )
                    ->options( Options::inst()
                        ->table( 'speedfreakinventory' )
                        ->value( 'Id' )
                        ->label( 'name' )
                    ),
                Field::inst( 'name' )
            )
    )
    ->process( $_POST )
  	->json();
