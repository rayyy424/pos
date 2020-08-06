<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('purchaseorderitems', function ($table) 
        {
            $table->string('Site_Route');
            $table->string('Engineering_No');
            $table->string('Project_Info');
            $table->integer('Quantity');
            $table->string('Unit_Of_Measure');
            $table->decimal('Unit_Price', 12, 2);
            $table->string('Start_Date');
            $table->string('Finishing_Date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
