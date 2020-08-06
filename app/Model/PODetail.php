<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItems extends Model {

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
      Schema::create('purchaseorderitems', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('PO_Item');
          $table->string('Item_Description');
          $table->string('Scope_of_Work');
          $table->string('Project_Code');
          $table->string('Work_Order_ID');
          $table->string('Site_ID');
          $table->string('Scope_of_Work');
          $table->string('Site_Route');
          $table->string('Engineering_No');
          $table->string('Project_Info');
          $table->integer('Quantity');
          $table->string('Unit_Of_Measure');
          $table->decimal('Unit_Price', 12, 2);
          $table->string('Amount');
          $table->string('Start_Date');
          $table->string('Finishing_Date');
          $table->string('First_Cut');
          $table->string('First_Cut_Completed_Date');
          $table->string('First_Cut_Invoice_No');
          $table->string('First_Cut_Forecast_Invoice_Date');

          $table->string('Second_Cut');
          $table->string('Second_Cut_Completed_Date');
          $table->string('Second_Cut_Invoice_No');
          $table->string('Second_Cut_Forecast_Invoice_Date');

          $table->string('Third_Cut');
          $table->string('Third_Cut_Completed_Date');
          $table->string('Third_Cut_Invoice_No');
          $table->string('Third_Cut_Forecast_Invoice_Date');

          $table->string('Forth_Cut');
          $table->string('Forth_Cut_Completed_Date');
          $table->string('Forth_Cut_Invoice_No');
          $table->string('Fourth_Cut_Forecast_Invoice_Date');

          $table->string('Fifth_Cut');
          $table->string('Fifth_Cut_Completed_Date');
          $table->string('Fifth_Cut_Invoice_No');
          $table->string('Fifth_Cut_Forecast_Invoice_Date');

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
