<?php

include( "plugin/php/DataTables.php" );

// Alias Editor classes so they are easy to use
use
plugin\php\Database\Driver\Mysql\Query,
DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Mjoin,
DataTables\Editor\Upload,
DataTables\Editor\Validate;
/*
 * Example PHP implementation used for the index.html example
 */

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'purchaseorderitems' )
  	->fields(
      Field::inst( 'purchaseorders.Id' )->set( false ),
      Field::inst( 'purchaseorders.PO_No' ),
      Field::inst( 'purchaseorders.PO_Date' ),
  	  Field::inst( 'purchaseorders.PO_Type' ),
      Field::inst( 'purchaseorders.PO_VO' ),
      Field::inst( 'purchaseorders.Company' ),
      Field::inst( 'purchaseorders.Job_Type' ),
      Field::inst( 'purchaseorders.Payment_Term' ),
      Field::inst( 'purchaseorders.Cut' ),
      Field::inst( 'purchaseorders.PO_Description' ),
      Field::inst( 'purchaseorders.PO_Status' ),
      Field::inst( 'purchaseorders.ProjectId' ),
      Field::inst( 'projects.Project_Name' ),
      Field::inst( 'purchaseorders.First_Cut' ),
      Field::inst( 'purchaseorders.Second_Cut' ),
      Field::inst( 'purchaseorders.Third_Cut' ),
      Field::inst( 'purchaseorders.Fourth_Cut' ),
      Field::inst( 'purchaseorders.Fifth_Cut' ),
      Field::inst( 'purchaseorderitems.Id' ),
      Field::inst( 'purchaseorderitems.PO_Id' ),
      Field::inst( 'purchaseorderitems.PO_Item' ),
      Field::inst( 'purchaseorderitems.Item_Description' ),
  	  Field::inst( 'purchaseorderitems.Scope_of_Work' ),
      Field::inst( 'purchaseorderitems.Project_Code' ),
      Field::inst( 'purchaseorderitems.Work_Order_ID' ),
      Field::inst( 'purchaseorderitems.Site_ID' ),
      Field::inst( 'purchaseorderitems.Amount' ),
      Field::inst( 'purchaseorderitems.First_Cut' ),
      Field::inst( 'purchaseorderitems.First_Cut_Completed_Date' ),
      Field::inst( 'purchaseorderitems.First_Cut_Invoice_No' ),
      Field::inst( 'purchaseorderitems.First_Cut_Forecast_Invoice_Date' ),

      Field::inst( 'purchaseorderitems.Second_Cut' ),
      Field::inst( 'purchaseorderitems.Second_Cut_Completed_Date' ),
      Field::inst( 'purchaseorderitems.Second_Cut_Invoice_No' ),
      Field::inst( 'purchaseorderitems.Second_Cut_Forecast_Invoice_Date' ),

      Field::inst( 'purchaseorderitems.Third_Cut' ),
      Field::inst( 'purchaseorderitems.Third_Cut_Completed_Date' ),
      Field::inst( 'purchaseorderitems.Third_Cut_Invoice_No' ),
      Field::inst( 'purchaseorderitems.Third_Cut_Forecast_Invoice_Date' ),

      Field::inst( 'purchaseorderitems.Fourth_Cut' ),
      Field::inst( 'purchaseorderitems.Fourth_Cut_Completed_Date' ),
      Field::inst( 'purchaseorderitems.Fourth_Cut_Invoice_No' ),
      Field::inst( 'purchaseorderitems.Fourth_Cut_Forecast_Invoice_Date' ),

      Field::inst( 'purchaseorderitems.Fifth_Cut' ),
      Field::inst( 'purchaseorderitems.Fifth_Cut_Completed_Date' ),
      Field::inst( 'purchaseorderitems.Fifth_Cut_Invoice_No' ),
      Field::inst( 'purchaseorderitems.Fifth_Cut_Forecast_Invoice_Date' ),
      Field::inst( 'purchaseorderitems.Remarks' ))
      ->leftJoin('purchaseorders','purchaseorders.Id','=','purchaseorderitems.PO_Id')
      ->leftJoin('projects', 'purchaseorders.ProjectId', '=', 'projects.Id');

      if (isset($_GET['ProjectId']))
      {
          $editor->where( function ( $q ) {
          $q->where( 'purchaseorders.ProjectId',$_GET['ProjectId']);
        } );
      }

      if (isset($_GET['Project_Code']))
      {
          $editor->where( function ( $q ) {
          $q->where( 'purchaseorders.Id', '(select PO_Id from purchaseorderitems where Project_Code="'.$_GET["Project_Code"].'")', 'IN', false );
        } );

      }

      if (isset($_GET['Work_Order_ID']))
      {
        $editor->where( function ( $q ) {
        $q->where( 'purchaseorders.Id', '(select PO_Id from purchaseorderitems where Work_Order_ID="'.$_GET["Work_Order_ID"].'")', 'IN', false );
      } );

      }


  $editor->on('postCreate',function( $editor, $id, $values, $row ) {

            $RAW_SQL_QUERY="select Max(ID) as maxid from purchaseorders";
      			$r=$editor->db() ->sql($RAW_SQL_QUERY)->fetchAll();

            foreach ($r as $key => $value) {

              # code...
              $editor->db()
                  ->query('update', 'purchaseorderitems')
                  ->set('PO_Id',$value["maxid"],false)
                  ->where('Id', $id)
                  ->exec();
            }

            $RAW_SQL_QUERY='select `purchaseorderitems`.`PO_Id`, `purchaseorderitems`.`PO_Id`, `purchaseorderitems`.`Id`, `purchaseorderitems`.`Project_Code`, `purchaseorderitems`.`Work_Order_ID`, `purchaseorderitems`.`Site_ID`, `projects`.`Project_Name`, `purchaseorders`.`PO_No`, `purchaseorders`.`PO_Type`, `purchaseorders`.`Company`, `purchaseorders`.`PO_Date`, `purchaseorders`.`Job_Type`, `purchaseorders`.`Cut`, `purchaseorders`.`PO_Status`, `purchaseorderitems`.`Scope_of_Work`, `purchaseorderitems`.`Item_Description`, FORMAT(purchaseorderitems.Amount,2) AS Amount, `purchaseorderitems`.`First_Cut`, `purchaseorderitems`.`First_Cut_Completed_Date`, `purchaseorderitems`.`First_Cut_Invoice_No`, `purchaseorderitems`.`First_Cut_Forecast_Invoice_Date`, `purchaseorderitems`.`Second_Cut`, `purchaseorderitems`.`Second_Cut_Completed_Date`, `purchaseorderitems`.`Second_Cut_Invoice_No`, `purchaseorderitems`.`Second_Cut_Forecast_Invoice_Date`, `purchaseorderitems`.`Third_Cut`, `purchaseorderitems`.`Third_Cut_Completed_Date`, `purchaseorderitems`.`Third_Cut_Invoice_No`, `purchaseorderitems`.`Third_Cut_Forecast_Invoice_Date`, `purchaseorderitems`.`Fourth_Cut`, `purchaseorderitems`.`Fourth_Cut_Completed_Date`, `purchaseorderitems`.`Fourth_Cut_Invoice_No`, `purchaseorderitems`.`Fourth_Cut_Forecast_Invoice_Date`, `purchaseorderitems`.`Fifth_Cut`, `purchaseorderitems`.`Fifth_Cut_Completed_Date`, `purchaseorderitems`.`Fifth_Cut_Invoice_No`, `purchaseorderitems`.`Fifth_Cut_Forecast_Invoice_Date`, `purchaseorderitems`.`Remarks`
            from `purchaseorders`
            left join `purchaseorderitems` on `purchaseorderitems`.`PO_Id` = `purchaseorders`.`Id`
            left join `projects` on `purchaseorders`.`ProjectId` = `projects`.`Id`
            where `purchaseorderitems`.`Id`='.$id;

            $r=$editor->db() ->sql($RAW_SQL_QUERY)->fetchAll();
            $arr=array("data"=>$r,"options"=>'',"files"=>'');//DATATABLE CLIENT SIDE PARSES
            echo json_encode($arr);
            exit();

        });

        $editor->process( $_POST )->json();
