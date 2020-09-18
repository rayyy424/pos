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
  $editor=Editor::inst( $db, 'tasks' )
  	->fields(
        Field::inst( 'tasks.Id' ),
        Field::inst( 'users.Name' ),
        Field::inst( 'assignby.Name' ),
        Field::inst( 'tasks.Current_Task' ),
        Field::inst( 'tasks.Threshold' ),
        Field::inst( 'tasks.assign_date' ),
        Field::inst( 'tasks.target_date' )
      )
     ->leftJoin(DB::raw('(SELECT MAX(Id) as maxid,TaskId FROM taskstatuses GROUP BY TaskId) as max'),'tasks.Id','=','max.TaskId')
    ->leftJoin('taskstatuses','taskstatuses.Id','=',DB::raw('max.`maxid`'))
    ->leftJoin('users','users.Id','=','tasks.UserId')
    ->leftJoin('users as assignby','assignby.Id','=','tasks.assign_by')

     if (isset( $_GET['DeductionId'])) {
       $editor
       ->where('deductionitems.DeductionId',$_GET['DeductionId'] );
     }

      $editor->process( $_POST )->json();
