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

 if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'remove'))
 {

   foreach ($_POST['data'] as $key => $value) {

     foreach ($value as $key2 => $value2) {

       if(is_array($value2))
       {
         if($value2["Status"]!="Pending Submission")
         {
           $arr=array("error"=>"Cannot remove claimsheet.");//DATATABLE CLIENT SIDE PARSES
           echo json_encode($arr);
           exit();

         }
       }

     }

   }

}

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'claimsheets')
  	->fields(
      Field::inst( 'claimsheets.Id' ),
      Field::inst( 'claimsheets.UserId' ),
      Field::inst( 'claimsheets.Claim_Sheet_Name' )->validator('Validate::unique'),
      Field::inst( 'claimsheets.Status' ),
      Field::inst( 'claimsheets.Remarks' ),
      Field::inst( 'claimsheets.created_at'));

    if (isset( $_GET['Status'])) {
      $editor
      ->where('claimsheets.Status',$_GET['Status'] ,'like')
      ->where('claimsheets.UserId',$_GET['UserId'] );
    }

    if (!isset( $_GET['Status']) && isset( $_GET['UserId'])) {

      $editor->where( function ( $q ) {
      $q->where( 'claimsheets.UserId',$_GET['UserId'] )
        ->and_where( function ( $r ) {
            $r->or_where( 'claimsheets.Status', 'Submitted for Approval' );
            $r->or_where( 'claimsheets.Status', 'Recalled' );
            $r->or_where( 'claimsheets.Status', 'Pending Submission' );
        } );
    } );

      // $editor
      // ->where('claimsheets.Status',$_GET['Statuses'] ,'In')
      // ->where('claimsheets.UserId',$_GET['UserId'] );
    }

    $editor->process( $_POST )->json();
