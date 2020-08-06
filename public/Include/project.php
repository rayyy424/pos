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

 if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'edit'))
 {

   foreach ($_POST['data'] as $key => $value) {


     if (isset( $_POST['data'][$key]['projects']['Country']) || isset( $_POST['data'][$key]['projects']['Customer']) ||isset( $_POST['data'][$key]['projects']['Operator']) ||isset( $_POST['data'][$key]['projects']['Region']) ||isset( $_POST['data'][$key]['projects']['Type']) ||isset( $_POST['data'][$key]['projects']['Scope']))
     {

        // $_POST['data'][$key]['projects']['Project_Name']=$_POST['data'][$key]['projects']['Country']."_".$_POST['data'][$key]['projects']['Customer']."_".$_POST['data'][$key]['projects']['Operator']."_".$_POST['data'][$key]['projects']['Region']."_".$_POST['data'][$key]['projects']['Type']."_".$_POST['data'][$key]['projects']['Scope'];
        $_POST['data'][$key]['projects']['Project_Name']=str_replace("__","_",$_POST['data'][$key]['projects']['Project_Name']);
        $_POST['data'][$key]['projects']['Project_Name']=str_replace("___","_",$_POST['data'][$key]['projects']['Project_Name']);
        $_POST['data'][$key]['projects']['Project_Name']=str_replace("____","",$_POST['data'][$key]['projects']['Project_Name']);
        $_POST['data'][$key]['projects']['Project_Name']=str_replace("_____","",$_POST['data'][$key]['projects']['Project_Name']);
        $_POST['data'][$key]['projects']['Project_Name']=str_replace("______","",$_POST['data'][$key]['projects']['Project_Name']);
        $_POST['data'][$key]['projects']['Project_Name']=rtrim($_POST['data'][$key]['projects']['Project_Name'],"_");
        $_POST['data'][$key]['projects']['Project_Name']=ltrim($_POST['data'][$key]['projects']['Project_Name'],"_");
        $_POST['data'][$key]['projects']['Project_Name']=str_replace("__","_",$_POST['data'][$key]['projects']['Project_Name']);

     }
   }

}

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'projects')
  	->fields(
      Field::inst( 'projects.Id' ),
      Field::inst( 'projects.Created_By' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'projects.Project_Manager' ),
      Field::inst( 'projects.Country' ),
      Field::inst( 'projects.Customer' ),
      Field::inst( 'projects.Operator' ),
      Field::inst( 'projects.Region' ),
      Field::inst( 'projects.Type' ),
      Field::inst( 'projects.Scope' ),
      Field::inst( 'users.Name' ),
      Field::inst( 'projects.Project_Description' ),
      Field::inst( 'projects.Remarks' ),
      Field::inst( 'projects.Active' )
      )
    ->leftJoin('users', 'projects.Project_Manager', '=', 'users.Id')
    ->process( $_POST )
  	->json();
