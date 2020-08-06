<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {

	//
  	protected $table = 'invoices';
    protected $primaryKey = 'Id';

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('invoices', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->string('Invoice_No');
            $table->string('Invoice_Date');
            $table->string('Invoice_Type');
            $table->string('Company');
            $table->string('Invoice_Description');
            $table->string('ProjectId');
            $table->string('Invoice_Amount');
            $table->string('Invoice_Status');
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
