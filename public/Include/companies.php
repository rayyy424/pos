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
if (isset($_POST['Id']))
{
  $id=$_POST['Id'];
}
else {
  $id=0;
}


// DataTables PHP library

// Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'companies' )
    ->fields(
      Field::inst( 'companies.Id' ),
      Field::inst( 'companies.Status' ),
      Field::inst( 'companies.Company_Account' ),
      Field::inst( 'companies.Company_Name' ),
      Field::inst( 'companies.Register_Num' ),
      Field::inst( 'companies.Company_Code' ),
      Field::inst( 'companies.CreditorCode' ),
      Field::inst( 'companies.Initial' ),
      Field::inst( 'companies.Person_In_Charge' ),
      Field::inst( 'companies.attention' ),
      Field::inst( 'companies.type' ),
      Field::inst( 'companies.subcon' ),
      Field::inst( 'companies.bank' ),
      Field::inst( 'companies.bank_acct' ),
      Field::inst( 'companies.Contact_No' ),
      Field::inst( 'companies.Office_No' ),
      Field::inst( 'companies.Fax_No' ),
      Field::inst( 'companies.Email' ),
      Field::inst( 'companies.Address' ),
      Field::inst( 'companies.Client' ),
      Field::inst( 'companies.Subsidiary' ),
      Field::inst( 'companies.Supplier' ),
      Field::inst( 'companies.Remarks' ),
      Field::inst( 'companies.created_at' ),
      Field::inst( 'companies.updated_at' ),
      Field::inst( 'files.Web_Path' )
            ->setFormatter( 'Format::ifEmpty', null )
            ->upload( Upload::inst( realpath(__DIR__ . '/..').'/private/upload/Company Logo/__ID__.__EXTN__' )
                ->db( 'files', 'Id', array(
                    'Type'    => 'Company Logo',
                    'TargetId'    => $id,
                    'File_Name'    => Upload::DB_FILE_NAME,
                    'File_Size'    => Upload::DB_FILE_SIZE,
                    'Web_Path'    => Upload::DB_WEB_PATH
                ) )
                ->validator( function ( $file ) {
                    return $file['size'] >= 5000000 ?
                        "Files must be smaller than 5MB" :
                        null;
                } )
                ->allowedExtensions( array( 'png', 'jpg','jpeg', 'gif' ), "Please upload an image" )
      )
    )
    ->leftJoin( '(select Max(Id) as maxid,TargetId from files where Type="Company Logo" Group By Type,TargetId) as max', 'max.TargetId', '=', 'companies.Id')
    ->leftJoin('files', 'files.Id', '=', 'max.`maxid` and files.`Type`="Company Logo"');

    $editor->on('postCreate',function($editor,$id,$value){
        $editor->db()->sql('Update files set TargetId='.$id.' where files.Type="Company Logo" and files.Id="'.$value['files']['Web_Path'].'"');
      });


    //->process( $_POST )
    //->json();
    $editor->process( $_POST )->json();
