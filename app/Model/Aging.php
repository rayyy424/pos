<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Aging extends Model {

	//
  protected $table = 'agings';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('agings', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->boolean('Active');
          $table->boolean('Type');
          $table->integer('ProjectId');
          $table->string('Title');
          $table->string('Start_Date');
          $table->string('End_Date');
          $table->integer('Threshold');
          $table->string('Recurring_Frequency');
          $table->string('Frequency_Unit');
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
       Schema::drop('agings');
  }

}
