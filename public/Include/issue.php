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

if (isset($_POST['Id']))
{
  $id=$_POST['Id'];
}
else {
  $id=0;
}

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'siteissue')
  	->fields(
      Field::inst( 'siteissue.Id' ),
      Field::inst( 'siteissue.Site_ID' ),
      Field::inst( 'siteissue.Site_Name' ),
      Field::inst( 'siteissue.Scope_of_Work' ),
      Field::inst( 'siteissue.Issue_Description' ),
  		Field::inst( 'siteissue.Status' ),
      Field::inst( 'siteissue.Person_In_Charge' ),
      Field::inst( 'siteissue.Date' ),
      Field::inst( 'siteissue.Time' ),
      Field::inst( 'siteissue.Remarks' ),
      Field::inst( 'siteissue.Solution' ),
      Field::inst( 'siteissue.created_by' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'siteissue.ProjectId' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'siteissue.created_at' ),
      Field::inst( 'siteissue.updated_at' ))
    ->leftJoin('users', 'siteissue.created_by', '=', 'users.Id')
    ->leftJoin('projects', 'siteissue.ProjectId', '=', 'projects.Id');

    if (isset( $_GET['ProjectId'])) {
      $editor
      ->where('siteissue.ProjectId',$_GET['ProjectId'] );
    }

    // if(!isset( $_GET['Site_Name']))
    // {
    //   $_GET['Site_Name']="";
    // }
    //
    // if(!isset( $_GET['Site_ID']))
    // {
    //   $_GET['Site_ID']="";
    // }

    if(isset( $_GET['Site_Name']))
    {
      if($_GET['Site_Name']=="" && $_GET['Site_ID']=="")
      {

        $editor
        ->where('siteissue.ID',"-1" );

      }

      if($_GET['Site_Name']!="" && $_GET['Site_ID']=="")
      {

        $editor
        ->where('siteissue.Site_Name','%'.$_GET['Site_Name'].'%','like' );

      }

      if($_GET['Site_Name']=="" && $_GET['Site_ID']!="")
      {

        $editor
        ->where('siteissue.Site_ID','%'.$_GET['Site_ID'].'%','like' );

      }

    }

    $editor->process( $_POST )->json();


    // ->leftJoin('leaves', 'leavestatuses.LeaveId', '=', 'leaves.Id')
    // ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
    // ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
    // ->process( $_POST )
  	// ->json();
