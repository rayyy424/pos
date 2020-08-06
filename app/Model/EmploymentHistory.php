<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class EmploymentHistory extends Model {

	//
  	protected $table = 'employmenthistories';
    protected $primaryKey = 'Id';

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('employmenthistories', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('UserId');
            $table->string('Company');
            $table->string('Company_Address');
            $table->string('Company_Contact_No');
            $table->string('Start_Date');
            $table->string('End_Date');
            $table->string('Position');
            $table->string('Supervisor');
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
         Schema::drop('employmenthistories');
    }

}
