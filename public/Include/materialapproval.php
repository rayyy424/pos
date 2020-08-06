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
  $editor=Editor::inst( $db, 'material')
  	->fields(
        Field::inst('material.Id'),
        Field::inst('material.MR_No'),
        Field::inst('materialstatus.Status'),
        Field::inst('projects.Project_Name'),
        Field::inst('material.Total'),
        Field::inst('tracker.`Site Name`'),
        Field::inst("material.UserId"),
        Field::inst("materialstatus.ApproverId"),
        Field::inst('requestor.Name')
      )
    ->leftjoin('tracker','tracker.Id','=','material.TrackerId')
    ->leftjoin('users as requestor','requestor.Id','=','material.UserId')
    ->leftjoin('projects','projects.Id','=','material.ProjectId')
    ->leftJoin( '(select Max(Id) as maxid,MaterialId from materialstatus Group By MaterialId) as max', 'max.MaterialId', '=', 'material.Id')
    ->leftJoin('materialstatus', 'materialstatus.Id', '=','max.maxid');

    if(isset($_GET['status'])){
       $editor->where('materialstatus.Status',$_GET['status'],'LIKE');
    }
    if(isset($_GET['id'])){
        $editor->where('materialstatus.ApproverId',$_GET['id']);
    }
   

    $editor->process( $_POST )->json();
