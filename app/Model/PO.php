<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PO extends Model {

	//
  protected $table = 'purchaseorderitems';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('po', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->string('PO_No',20);
          $table->string('PO_Date',20);
          $table->string('PO_Type',15);
          $table->string('Company',100);
          $table->string('PO_Description',255);
          $table->string('PO_Status',100);
          $table->string('Payment_Term',255);
          $table->integer('Cut');
          $table->integer('ProjectId');
          $table->string('Remarks');
          $table->string('Job_Type',100);
          $table->string('Item_Description');
          $table->string('Scope_of_Work');
          $table->string('Project_Code',20);
          $table->string('Work_Order_ID',100);
          $table->string('Site_ID',20);
          $table->decimal('Amount',10,2);


          $table->decimal('First_Cut',10,2);
          $table->string('First_Cut_Completed_Date',20);
          $table->string('First_Cut_Invoice_No',20);
          $table->string('First_Cut_Forecast_Invoice_Date',20);

          $table->decimal('Second_Cut',10,2);
          $table->string('Second_Cut_Completed_Date',20);
          $table->string('Second_Cut_Invoice_No',20);
          $table->string('Second_Cut_Forecast_Invoice_Date',20);

          $table->decimal('Third_Cut',10,2);
          $table->string('Third_Cut_Completed_Date',20);
          $table->string('Third_Cut_Invoice_No',20);
          $table->string('Third_Cut_Forecast_Invoice_Date',20);

          $table->decimal('Forth_Cut',10,2);
          $table->string('Forth_Cut_Completed_Date',20);
          $table->string('Forth_Cut_Invoice_No',20);
          $table->string('Fourth_Cut_Forecast_Invoice_Date',20);

          $table->decimal('Fifth_Cut',10,2);
          $table->string('Fifth_Cut_Completed_Date',20);
          $table->string('Fifth_Cut_Invoice_No',20);
          $table->string('Fifth_Cut_Forecast_Invoice_Date',20);

          $table->integer('PO_Line_No');
          $table->string('Site_Code',20);
          $table->string('Site_Name',100);
          $table->string('Engineering_No',20);
          $table->string('Project_Name',100);
          $table->string('ProjectCode',20);

          $table->integer('Quantity_Request');
          $table->integer('Due_Quantity');
          $table->string('Unit',20);
          $table->decimal('Unit_Price', 10, 2);
          $table->decimal('Line_Account', 10, 2);

          $table->string('Start_Date',20);
          $table->string('End_Date',20);

          $table->integer('Shipment_Num');
          $table->string('Vendor_Code',20);
          $table->string('Vendor_Name',100);

          $table->string('Sub_Contractor_No',100);
          $table->string('Payment_Method',100);

          $table->string('Center_Area',20);

          $table->string('ESAR_Date',20);
          $table->string('ESAR_STATUS',100);

          $table->string('PAC_Date',20);
          $table->string('PAC_STATUS',100);

          $table->integer('created_by');

          $table->timestamps();
      });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
       Schema::drop('purchaseorderitems');
  }

}
