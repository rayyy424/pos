<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TimesheetStatus extends Model {

	//
  protected $table = 'timesheetstatuses';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('timesheetstatuses', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('TimesheetId');
          $table->integer('UserId');
          $table->string('Status');
          $table->string('Comment');
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
       Schema::drop('timesheetstatuses');
  }

}
