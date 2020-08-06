<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrders extends Model {

	//
  	protected $table = 'purchaseorders';
    protected $primaryKey = 'Id';

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('purchaseorders', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('PurchaseOrderId');
            $table->string('PO_Date');
            $table->string('PO_Type');
            $table->string('Job_Type');
            $table->string('Company');
            $table->string('PO_Description');
            $table->string('PO_Status');
            $table->string('ProjectId');
            $table->string('First_Cut');
            $table->string('Second_Cut');
            $table->integer('Third_Cut');
            $table->string('Forth_Cut');
            $table->string('Fifth_Cut');
            $table->string('Payment_Term');
            $table->string('Remarks');
            $table->integer('UserId');
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
         Schema::drop('purchaseorders');
    }

}
