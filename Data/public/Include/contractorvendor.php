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
  		Field::inst( 'users.Name' )->validator( 'Validate::notEmpty' ),
      Field::inst( 'users.Email' ),
      Field::inst( 'users.Contact_No' ),
      Field::inst( 'users.Address' ),
      Field::inst( 'users.User_Type' ),
      Field::inst( 'users.Company' ),
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
    ->leftJoin('files', 'files.TargetId', '=', 'users.Id and files.Type="User"')
    ->process( $_POST )
    ->json();
