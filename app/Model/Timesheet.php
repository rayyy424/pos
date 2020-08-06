<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model {

	//
  	protected $table = 'timesheets';
    protected $primaryKey = 'Id';

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
      Schema::create('timesheets', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('UserId');
          $table->timestamp('Date');
          $table->string('Leader/Member');
          $table->string('Next_Person');
          $table->string('Project_Code_Id');
          $table->string('Project');
          $table->string('Site_Name');
          $table->string('Address');
          $table->decimal('Latitude_In', 12, 8);
          $table->decimal('Longitude_In', 12, 8);
          $table->decimal('Latitude_Out', 12, 8);
          $table->decimal('Longitude_Out', 12, 8);
          $table->string('State');
          $table->string('Work_Description');
          $table->string('Check_In_Type');
          $table->string('Time_In');
          $table->string('Time_Out');
          $table->decimal('Allowance', 10, 2);
          $table->string('Reason');
          $table->string('Remarks');
          $table->string('Source');
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
         Schema::drop('timesheets');
    }

}
