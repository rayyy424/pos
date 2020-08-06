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
  $editor=Editor::inst( $db, 'users' )
  	->fields(
      Field::inst( 'users.Id' ),
      Field::inst( 'users.Status' ),
      Field::inst( 'users.StaffId' ),
  		Field::inst( 'users.Name' )->validator( 'Validate::notEmpty' ),
      Field::inst( 'users.Nick_Name' ),
      Field::inst( 'users.User_Type' ),
      Field::inst( 'users.Company_Email' ),
      Field::inst( 'users.Personal_Email' ),
      Field::inst( 'users.Contact_No_1' ),
      Field::inst( 'users.Contact_No_2' ),
      Field::inst( 'users.Permanent_Address' ),
      Field::inst( 'users.Current_Address' ),
      Field::inst( 'users.Country_Base' ),
      Field::inst( 'users.Home_Base' ),
      Field::inst( 'users.Nationality' ),
      Field::inst( 'users.DOB' ),
      Field::inst( 'users.NRIC' ),
      Field::inst( 'users.Passport_No' ),
      Field::inst( 'users.Gender' ),
      Field::inst( 'users.Marital_Status' ),
      Field::inst( 'users.SuperiorId' ),
      Field::inst( 'superior.Name' ),
      Field::inst( 'users.Team' ),
      Field::inst( 'users.Ext_No' ),
      Field::inst( 'team.Name' ),
      Field::inst( 'users.Company' ),
      Field::inst( 'users.Department' ),
      Field::inst( 'users.Category' ),
      Field::inst( 'users.Entitled_for_OT' ),
      Field::inst( 'users.Working_Days' ),
      Field::inst( 'users.HolidayTerritoryId' ),
      Field::inst( 'holidayterritories.Name' ),
      Field::inst( 'users.Position' ),
      Field::inst( 'users.Grade' ),
      Field::inst( 'users.Joining_Date' ),
      Field::inst( 'users.Confirmation_Date' ),
      Field::inst( 'users.Resignation_Date' ),
      Field::inst( 'users.Internship_Start_Date' ),
      Field::inst( 'users.Internship_End_Date' ),
      Field::inst( 'users.Emergency_Contact_Person' ),
      Field::inst( 'users.Emergency_Contact_No' ),
      Field::inst( 'users.Emergency_Contact_Relationship' ),
      Field::inst( 'users.Emergency_Contact_Address' ),
      Field::inst( 'users.Bank_Name' ),
      Field::inst( 'users.Bank_Account_No' ),
      Field::inst( 'users.Race' ),
      Field::inst( 'users.Religion' ),
      Field::inst( 'users.Place_Of_Birth' ),
      Field::inst( 'users.EPF_No' ),
      Field::inst( 'users.SOCSO_No' ),
      Field::inst( 'users.Income_Tax_No' ),
      Field::inst( 'users.Car_Owner' ),
      Field::inst( 'users.Driving_License' ),
      Field::inst( 'users.Criminal_Activity' ),


      Field::inst( 'files.Web_Path' )
            ->setFormatter( 'Format::ifEmpty', null )
            ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/User/__ID__.__EXTN__' )
                ->db( 'files', 'Id', array(
                    'Type'    => 'User',
                    'TargetId'    => $id,
                    'File_Name'    => Upload::DB_FILE_NAME,
                    'File_Size'    => Upload::DB_FILE_SIZE,
                    'Web_Path'    => Upload::DB_WEB_PATH
                ) )
                ->validator( function ( $file ) {
                    return$file['size'] >= 5000000 ?
                        "Files must be smaller than 5MB" :
                        null;
                } )
                ->allowedExtensions( array( 'png', 'jpg','jpeg', 'gif' ), "Please upload an image" )
            )
    )
    ->on('preEdit', function ($editor, $id, $values) use ($db) {
        date_default_timezone_set("Asia/Kuala_Lumpur");

        if (isset($values['users']['Joining_Date'])) {
            if (trim($values['users']['Joining_Date']) != "") {

              $confirmationSet = $db->raw()
                                ->bind(':Id', $id)
                                ->exec('SELECT Id FROM users WHERE Id = :Id AND (Confirmation_Date <> \'\' AND Confirmation_Date IS NOT NULL) LIMIT 1')
                                ->count();

              if (! $confirmationSet) {

                  $joiningDate = $values['users']['Joining_Date'];

                  $confirmationDate = date('d-M-Y', strtotime("+6 months", strtotime($joiningDate)));

                  $user = $db->raw()
                  ->bind(':Id', $id)
                  ->bind(':Confirmation_Date', $confirmationDate)
                  ->exec('UPDATE users SET Confirmation_Date = :Confirmation_Date WHERE Id = :Id LIMIT 1');

              }

            }
        }
    })
    // ->leftJoin('files', 'files.TargetId', '=', 'users.Id and files.Type="User"')
    ->leftJoin( '(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max', 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', 'max.`maxid` and files.`Type`="User"')
    ->leftJoin('users as superior', 'superior.Id', '=', 'users.SuperiorId')
    ->leftJoin('users as team', 'team.Id', '=', 'users.Team')
    ->leftJoin('holidayterritories', 'holidayterritories.Id', '=', 'users.HolidayTerritoryId');

    if (isset( $_GET['Resigned'])) {

      if($_GET['Resigned']=="1")
      {
        $editor
        ->where('users.Resignation_Date','','!=' );
      }
      else {
        # code...
        $editor
        ->where('users.Resignation_Date','' );
      }

    }

    if (isset( $_GET['Start']) && isset( $_GET['End'])) {
      if( $_GET['Type'] == "Confirm" )
       {
           $editor
           ->where( function ( $q ){
              $q->where( 'str_to_date(users.Confirmation_Date,"%d-%M-%Y")', 'BETWEEN (str_to_date("'.$_GET['Start'].'","%d-%M-%Y")) AND (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
            });
       }
       else if( $_GET['Type'] == "New" )
       {
            $editor
           ->where( function ( $q ){
              $q->where( 'str_to_date(users.Joining_Date,"%d-%M-%Y")', 'BETWEEN (str_to_date("'.$_GET['Start'].'","%d-%M-%Y")) AND (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
            });
       }
       else if( $_GET['Type'] == "Resigned" )
       {
            $editor
           ->where( function ( $q ){
              $q->where( 'str_to_date(users.Resignation_Date,"%d-%M-%Y")', 'BETWEEN (str_to_date("'.$_GET['Start'].'","%d-%M-%Y")) AND (str_to_date("'.$_GET['End'].'","%d-%M-%Y"))', "", false );
            });
       }
    }

    $editor->process( $_POST )->json();
