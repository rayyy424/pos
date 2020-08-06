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
      Field::inst( 'users.StaffId' ),
  		Field::inst( 'users.Name' )->validator( 'Validate::notEmpty' ),
      Field::inst( 'users.Nick_Name' ),
      Field::inst( 'users.Company' ),
      Field::inst( 'users.User_Type' ),
      Field::inst( 'users.Company_Email' ),
      Field::inst( 'users.Personal_Email' ),
      Field::inst( 'users.Contact_No_1' ),
      Field::inst( 'users.Contact_No_2' ),
      Field::inst( 'users.Permanent_Address' ),
      Field::inst( 'users.Current_Address' ),
      Field::inst( 'users.Home_Base' ),
      Field::inst( 'users.Nationality' ),
      Field::inst( 'users.DOB' ),
      Field::inst( 'users.NRIC' ),
      Field::inst( 'users.Passport_No' ),
      Field::inst( 'users.Gender' ),
      Field::inst( 'users.Marital_Status' ),
      Field::inst( 'users.SuperiorId' ),
      Field::inst( 'users.Password' ),
      Field::inst( 'superior.Name' ),
      Field::inst( 'users.Department' ),
      Field::inst( 'users.Position' ),
      Field::inst( 'users.Emergency_Contact_Person' ),
      Field::inst( 'users.Emergency_Contact_No' ),
      Field::inst( 'users.Emergency_Contact_Relationship' ),
      Field::inst( 'users.Emergency_Contact_Address' ),
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
    // ->leftJoin('files', 'files.TargetId', '=', 'users.Id and files.Type="User"')
    ->leftJoin( '(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max', 'max.TargetId', '=', 'users.Id')
    ->leftJoin('files', 'files.Id', '=', 'max.`maxid` and files.`Type`="User"')
    ->leftJoin('users as superior', 'superior.Id', '=', 'users.SuperiorId')
    ->where('users.User_Type','Client')
    ->process( $_POST )
  	->json();
