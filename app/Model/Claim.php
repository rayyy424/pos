<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model {

	//
  	protected $table = 'claims';
    protected $primaryKey = 'Id';

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('claims', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('ClaimSheetId');
            $table->string('Date');
            $table->string('Project_Code_Id');
            $table->string('ProjectId');
            $table->string('Site_Name');
            $table->string('State');
            $table->string('Work_Description');
            $table->string('Next_Person');
            $table->string('Car_No');
            $table->integer('Mileage');
            $table->string('Expenses_Type');
            $table->decimal('Total_Expenses',10,2);
            $table->decimal('Petrol_SmartPay',10,2);
            $table->decimal('Advance',10,2);
            $table->decimal('Total_Amount',10,2);
            $table->decimal('GST_Amount', 10, 2);
            $table->decimal('Total_Without_GST', 10, 2);
            $table->string('Receipt_No');
            $table->string('Company_Name');
            $table->string('GST_No');
            $table->string('Remarks');
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
         Schema::drop('claims');
    }

}
