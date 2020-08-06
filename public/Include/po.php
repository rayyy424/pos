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

 if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'edit'))
 {

   foreach ($_POST['data'] as $key => $value) {

     if ($_POST['data'][$key]['po']['First_Milestone_Percentage']!="" && $_POST['data'][$key]['po']['Amount']>0)
     {

       $_POST['data'][$key]['po']['First_Milestone_Amount']=round($_POST['data'][$key]['po']['Amount']* $_POST['data'][$key]['po']['First_Milestone_Percentage']/100,2);
       $_POST['data'][$key]['po']['First_Milestone_Amount_With_GST']=round($_POST['data'][$key]['po']['Amount']* $_POST['data'][$key]['po']['First_Milestone_Percentage']/100,2)*1.06;

     }

     if ($_POST['data'][$key]['po']['Second_Milestone_Percentage']!="" && $_POST['data'][$key]['po']['Amount']>0)
     {

       $_POST['data'][$key]['po']['Second_Milestone_Amount']=round($_POST['data'][$key]['po']['Amount']* $_POST['data'][$key]['po']['Second_Milestone_Percentage']/100,2);
       $_POST['data'][$key]['po']['Second_Milestone_Amount_With_GST']=round($_POST['data'][$key]['po']['Amount']* $_POST['data'][$key]['po']['Second_Milestone_Percentage']/100,2)*1.06;

     }

     if ($_POST['data'][$key]['po']['Third_Milestone_Percentage']!="" && $_POST['data'][$key]['po']['Amount']>0)
     {

       $_POST['data'][$key]['po']['Third_Milestone_Amount']=round($_POST['data'][$key]['po']['Amount']* $_POST['data'][$key]['po']['Third_Milestone_Percentage']/100,2);
       $_POST['data'][$key]['po']['Third_Milestone_Amount_With_GST']=round($_POST['data'][$key]['po']['Amount']* $_POST['data'][$key]['po']['Third_Milestone_Percentage']/100,2)*1.06;

     }

     if ($_POST['data'][$key]['po']['Fourth_Milestone_Percentage']!="" && $_POST['data'][$key]['po']['Amount']>0)
     {

       $_POST['data'][$key]['po']['Fourth_Milestone_Amount']=round($_POST['data'][$key]['po']['Amount']* $_POST['data'][$key]['po']['Fourth_Milestone_Percentage']/100,2);
       $_POST['data'][$key]['po']['Fourth_Milestone_Amount_With_GST']=round($_POST['data'][$key]['po']['Amount']* $_POST['data'][$key]['po']['Fourth_Milestone_Percentage']/100,2)*1.06;

     }

     if ($_POST['data'][$key]['po']['Fifth_Milestone_Percentage']!="" && $_POST['data'][$key]['po']['Amount']>0)
     {

       $_POST['data'][$key]['po']['Fifth_Milestone_Amount']=round($_POST['data'][$key]['po']['Amount']* $_POST['data'][$key]['po']['Fifth_Milestone_Percentage']/100,2);
       $_POST['data'][$key]['po']['Fifth_Milestone_Amount_With_GST']=round($_POST['data'][$key]['po']['Amount']* $_POST['data'][$key]['po']['Fifth_Milestone_Percentage']/100,2)*1.06;

     }



   }

 }
// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'po' )
  	->fields(
      Field::inst( 'po.Id' ),
      Field::inst( 'po.Huawei_ID' ),
      Field::inst( 'po.PO_Status' ),
      Field::inst( 'po.Status' ),
      Field::inst( 'po.ROR_Status' ),

      Field::inst( 'po.PO_No' ),
      Field::inst( 'po.PR_No' ),
      Field::inst( 'po.Cut' ),
      Field::inst( 'po.PO_Line_No' ),
      Field::inst( 'po.Shipment_Num' ),
      Field::inst( 'po.Item_Code' ),
      Field::inst( 'po.Credit_Note' ),
      Field::inst( 'po.PO_Date' ),
      Field::inst( 'po.PO_Type' ),

      Field::inst( 'po.PO_Description' ),
      Field::inst( 'po.Scope_of_Work' ),
      Field::inst( 'po.Item_Description' ),
  	  Field::inst( 'po.Company' ),
      Field::inst( 'po.Work_Order_ID' ),
      Field::inst( 'po.Site_ID' ),
      Field::inst( 'po.Site_Code' ),
      Field::inst( 'po.Site_Name' ),
      Field::inst( 'po.Region' ),
      Field::inst( 'po.Payment_Term' ),
      Field::inst( 'po.ProjectId' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'po.Project_Code' ),
      Field::inst( 'po.Engineering_No' ),
      Field::inst( 'po.ProjectCode' ),
      Field::inst( 'po.Project' ),
      Field::inst( 'po.Center_Area' ),

      Field::inst( 'po.First_Milestone_Percentage' ),
      Field::inst( 'po.First_Milestone_Amount' ),
      Field::inst( 'po.First_Milestone_Amount_With_GST' ),
      Field::inst( 'po.First_Milestone_Completed_Date' ),
      Field::inst( 'po.First_Milestone_Invoice_No' ),
      Field::inst( 'po.First_Milestone_Invoice_Upload_Date' ),
      Field::inst( 'po.First_Milestone_Forecast_Invoice_Date' ),

      Field::inst( 'po.Second_Milestone_Percentage' ),
      Field::inst( 'po.Second_Milestone_Amount' ),
      Field::inst( 'po.Second_Milestone_Amount_With_GST' ),
      Field::inst( 'po.Second_Milestone_Completed_Date' ),
      Field::inst( 'po.Second_Milestone_Invoice_No' ),
      Field::inst( 'po.Second_Milestone_Invoice_Upload_Date' ),
      Field::inst( 'po.Second_Milestone_Forecast_Invoice_Date' ),

      Field::inst( 'po.Third_Milestone_Percentage' ),
      Field::inst( 'po.Third_Milestone_Amount' ),
      Field::inst( 'po.Third_Milestone_Amount_With_GST' ),
      Field::inst( 'po.Third_Milestone_Completed_Date' ),
      Field::inst( 'po.Third_Milestone_Invoice_No' ),
      Field::inst( 'po.Third_Milestone_Invoice_Upload_Date' ),
      Field::inst( 'po.Third_Milestone_Forecast_Invoice_Date' ),

      Field::inst( 'po.Fourth_Milestone_Percentage' ),
      Field::inst( 'po.Fourth_Milestone_Amount' ),
      Field::inst( 'po.Fourth_Milestone_Amount_With_GST' ),
      Field::inst( 'po.Fourth_Milestone_Completed_Date' ),
      Field::inst( 'po.Fourth_Milestone_Invoice_No' ),
      Field::inst( 'po.Fourth_Milestone_Invoice_Upload_Date' ),
      Field::inst( 'po.Fourth_Milestone_Forecast_Invoice_Date' ),

      Field::inst( 'po.Fifth_Milestone_Percentage' ),
      Field::inst( 'po.Fifth_Milestone_Amount' ),
      Field::inst( 'po.Fifth_Milestone_Amount_With_GST' ),
      Field::inst( 'po.Fifth_Milestone_Completed_Date' ),
      Field::inst( 'po.Fifth_Milestone_Invoice_No' ),
      Field::inst( 'po.Fifth_Milestone_Invoice_Upload_Date' ),
      Field::inst( 'po.Fifth_Milestone_Forecast_Invoice_Date' ),

      Field::inst( 'po.Quantity_Request' ),
      Field::inst( 'po.Due_Quantity' ),

      Field::inst( 'po.Unit' ),
      Field::inst( 'po.Unit_Price' ),
      Field::inst( 'po.Amount' ),
      Field::inst( 'po.Amount_With_GST' ),
      Field::inst( 'po.Currency' ),
      Field::inst( 'po.Line_Account' ),
      Field::inst( 'po.Start_Date' ),
      Field::inst( 'po.End_Date' ),
      Field::inst( 'po.Acceptance_Date' ),
      Field::inst( 'po.Vendor_Code' ),
      Field::inst( 'po.Vendor_Name' ),
      Field::inst( 'po.Sub_Contract_No' ),
      Field::inst( 'po.Payment_Method' ),

      Field::inst( 'po.ESAR_Document_Submitted_Date' ),
      Field::inst( 'po.ESAR_Date' ),
      Field::inst( 'po.ESAR_Status' ),

      Field::inst( 'po.PAC_Document_Submitted_Date' ),
      Field::inst( 'po.PAC_Date' ),
      Field::inst( 'po.PAC_Status' ),

      Field::inst( 'po.created_by'),
      Field::inst( 'users.Name' ),
      Field::inst( 'po.created_at' ),

      Field::inst( 'pm.Name' ),
      Field::inst( 'po.PM_Accepted_At' ),
      Field::inst( 'po.PM_Status' ),
      Field::inst( 'po.PM_Remarks' ),
      Field::inst( 'finance.Name' ),
      Field::inst( 'po.Finance_Accepted_At' ),
      Field::inst( 'po.Finance_Status' ),
      Field::inst( 'po.Finance_Remarks' ),
      Field::inst( 'po.Remarks' ),
      Field::inst( 'po.updated_at' ))
      ->leftJoin('projects', 'po.ProjectId', '=', 'projects.Id')
      ->leftJoin('users', 'po.created_by', '=', 'users.Id')
      ->leftJoin('users as pm', 'po.PM_Accepted_By', '=', 'pm.Id')
      ->leftJoin('users as finance', 'po.Finance_Accepted_By', '=', 'finance.Id');

      if (isset($_GET['ProjectCode']))
      {
          $editor->where( function ( $q ) {
          $q->where( 'po.Project_Code',$_GET['ProjectCode']);
        } );
      }

      if (isset($_GET['ProjectId']))
      {
          $editor->where( function ( $q ) {
          $q->where( 'po.ProjectId',$_GET['ProjectId']);
        } );
      }

      if (isset($_GET['PO_No']))
      {
          $editor->where( function ( $q ) {
          $q->where( 'po.PO_No',$_GET['PO_No']);
        } );
      }

      if (isset($_GET['Ids']))
      {

        $editor->where( function ( $q ) {
        $q->where( 'po.Id', '('.$_GET['Ids'].')', 'IN', false );
      } );
      }

      if (isset($_GET['Type']))
      {
        if($_GET['Type']=="Accepted")
        {
          $editor->where( function ( $q ) {
          $q->where( 'po.PM_Status',"Accepted");
        } );

        }
        else if($_GET['Type']=="Pending PM")
        {
          $editor->where( function ( $q ) {
          $q->where( 'po.PM_Status',"");
        } );

        }
        else if($_GET['Type']=="No Project PO")
        {
          $editor->where( function ( $q ) {
          $q->where( 'po.ProjectId',0);
        } );

        }
        else if($_GET['Type']=="Pending Finance")
        {
          $editor->where( function ( $q ) {
          $q->where( 'po.PM_Status',"Accepted");
        } );

        }
        else if($_GET['Type']=="Rejected")
        {
          $editor->where( function ( $q ) {
              $q
                  ->where( 'po.PM_Status', 'Rejected')
                  ->or_where( function ( $r ) {
                      $r->where( 'po.Finance_Status', 'Rejected' );
                  } );
          } );

        }
        else if($_GET['Type']=="All")
        {


        }
        else if($_GET['Type']=="ESAR")
        {

          $editor->where( function ( $q ) {
              $q
                  ->where( 'po.ESAR_Date', '')
                  ->and_where( function ( $r ) {
                      $r->or_where( 'po.First_Milestone_Completed_Date', '','!=');
                      $r->or_where( 'po.Second_Milestone_Completed_Date', '','!=');
                      $r->or_where( 'po.Third_Milestone_Completed_Date', '','!=');
                      $r->or_where( 'po.Fourth_Milestone_Completed_Date', '','!=');
                      $r->or_where( 'po.Fifth_Milestone_Completed_Date', '','!=');
                  } );

          } );


        }
        else if($_GET['Type']=="PAC")
        {

          $editor->where( function ( $q ) {
              $q
                  ->where( 'po.ESAR_Date', '','!=')
                  ->and_where( function ( $r ) {
                      $r->where( 'po.PAC_Date', '' );
                  } );
          } );


        }

      }


  $editor->process( $_POST )->json();
