<?php

    date_default_timezone_set("Asia/Kuala_Lumpur");

    include( "plugin/php/DataTables.php" );

    // Alias Editor classes so they are easy to use
    use
        DataTables\Editor,
        DataTables\Editor\Field,
        DataTables\Editor\Format,
        DataTables\Editor\Mjoin,
        DataTables\Editor\Upload,
        DataTables\Editor\Validate;


    // Build our Editor instance and process the data coming from _POST
    $editor = Editor::inst( $db, 'users')->fields(
        Field::inst( 'users.Id' ),
        Field::inst( 'users.StaffId' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'users.NRIC' ),
        Field::inst( 'users.Joining_Date' ),
        Field::inst( 'users.Confirmation_Date' ),
        Field::inst( 'users.Position' ),
        Field::inst( 'users.Grade' ),
        Field::inst( 'users.Company' ),
        Field::inst( 'users.Payslip_Password' ),
        Field::inst( 'users.Password_Last_Notified' )
    );

    if (isset( $_GET['IncludeResigned'])) {

          // not include resigned
          if (! ($_GET['IncludeResigned'] == 'true')) {
            // dont include resigned
            $today = date('d-M-Y', strtotime('today'));
            $editor
                ->where( function ( $q ) use ($today) {
                    $q->where('(str_to_date(users.Resignation_Date,"%d-%M-%Y")','str_to_date("'.$today.'","%d-%M-%Y") OR users.Resignation_Date = "")', ">=", false );
                } );
          }
    } else {
      // dont include resigned
      $today = date('d-M-Y', strtotime('today'));
      $editor
          ->where( function ( $q ) use ($today) {
              $q->where('(str_to_date(users.Resignation_Date,"%d-%M-%Y")','str_to_date("'.$today.'","%d-%M-%Y") OR users.Resignation_Date = "")', ">=", false );
          } );
    }

    if (isset( $_GET['IncludeInactive'])) {

          if (! ($_GET['IncludeInactive'] == 'true')) {
            $editor
                ->where( function ( $q ) {
                    $q->where('users.Active',1);
                } );
          }
    } else {

      $today = date('d-M-Y', strtotime('today'));
      $editor
          ->where( function ( $q ) {
              $q->where('users.Active',1);
          } );
    }

    $editor->process( $_POST )->json();
