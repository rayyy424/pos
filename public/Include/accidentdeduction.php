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

//  if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'remove'))
//  {
//
//    foreach ($_POST['data'] as $key => $value) {
//
//      foreach ($value as $key2 => $value2) {
//
//        if(is_array($value2))
//        {
//          if($value2["Status"]!="Pending Submission")
//          {
//            $arr=array("error"=>"Cannot remove deduction.");//DATATABLE CLIENT SIDE PARSES
//            echo json_encode($arr);
//            exit();
//
//          }
//        }
//
//      }
//
//    }
//
// }

  // DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'deductions')
  	->fields(
      Field::inst( 'deductions.Id' ),
      Field::inst( 'deductions.Type' ),
      Field::inst( 'deductions.UserId' ),
      Field::inst( 'deductions.Admin_HOD' ),
      Field::inst( 'deductions.CME_HOD' ),
      Field::inst( 'deductions.MD' ),
      Field::inst( 'deductions.GENSET_HOD' ),
      Field::inst( 'deductions.Name' )->validator('Validate::unique'),
      Field::inst( 'deductions.Department' ),
      Field::inst( 'deductions.Status' ),
      Field::inst( 'submitter.Name' ),
      Field::inst( 'deductions.Remarks' ),
      Field::inst( 'deductions.created_at'),
      Field::inst( 'approver_HRA.Name'),
      Field::inst( 'deductions.Admin_Status'),
      Field::inst( 'approver_CME.Name'),
      Field::inst( 'deductions.CME_Status'),
      Field::inst( 'approver_GENSET.Name'),
      Field::inst( 'deductions.GENSET_Status'),
      Field::inst( 'MD.Name'),
      Field::inst( 'deductions.MD_Status')
      )
      ->leftJoin('users as submitter', 'deductions.UserId', '=', 'submitter.Id')
      ->leftJoin('users as approver_HRA', 'deductions.Admin_HOD', '=', 'approver_HRA.Id')
      ->leftJoin('users as approver_CME', 'deductions.CME_HOD', '=', 'approver_CME.Id')
      ->leftJoin('users as approver_GENSET', 'deductions.GENSET_HOD', '=', 'approver_GENSET.Id')
      ->leftJoin('users as MD', 'deductions.MD', '=', 'MD.Id')
      ->where('deductions.Type', 'accident');

    $editor->process( $_POST )->json();
