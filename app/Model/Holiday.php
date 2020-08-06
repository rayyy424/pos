<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model {

	//
  protected $table = 'holidays';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('holidays', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->string('Holiday');
          $table->string('Start_Date');
          $table->string('End_Date');
          $table->string('State');
          $table->string('Country');
      });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
       Schema::drop('holidays');
  }

}
