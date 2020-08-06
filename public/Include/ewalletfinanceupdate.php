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
  $editor=Editor::inst( $db, 'ewallet' )
  	->fields(
        Field::inst( 'ewallet.Id' ),
        Field::inst( 'ewallet.Date' )->validator( 'Validate::notEmpty' ),
        Field::inst( 'ewallet.Type' )->validator( 'Validate::notEmpty' ),
        Field::inst( 'ewallet.Expenses_Type' ),
        Field::inst( 'ewallet.Amount' )->validator( 'Validate::notEmpty' ),
        Field::inst( 'ewallet.DealWith' ),
        Field::inst( 'ewallet.DocNo' ),
        Field::inst( 'ewallet.Remarks' ),
        Field::inst( 'ewallet.UserId' ),
        Field::inst( 'ewallet.ProjectId' ),
        Field::inst( 'projects.Project_Name' ),
        Field::inst( 'ewallet.TrackerId' ),
        Field::inst( 'tracker.Project_Code' ),
        Field::inst( 'ewallet.created_at' ),
        Field::inst( 'ewallet.created_by' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'users.StaffId' ),
        Field::inst( 'creator.Name' ),
        Field::inst( 'ewallet.updated_at' )
      )
      ->leftJoin('projects', 'ewallet.ProjectId', '=', 'projects.Id')
      ->leftJoin('tracker', 'ewallet.TrackerId', '=', 'tracker.Id')
      ->leftJoin('users', 'users.Id', '=', 'ewallet.UserId')
      ->leftJoin('users as creator', 'creator.Id', '=', 'ewallet.created_by');

      if (isset( $_POST['UserId'])) {
        $editor
        ->where('ewallet.UserId',$_POST['UserId'] );
      }

      $editor->process( $_POST )->json();
