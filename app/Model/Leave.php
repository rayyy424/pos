<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model {

	//
  protected $table = 'leaves';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('leaves', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('UserId');
          $table->integer('ProjectId');
          $table->string('Leave_Type');
          $table->string('Leave_Term');
          $table->string('Start_Date');
          $table->string('End_Date');
          $table->string('No_of_Days');
          $table->string('Reason');
          $table->string('Cover_By');
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
       Schema::drop('licenses');
  }

}
