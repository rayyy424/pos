<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveStatus extends Model {

	//
  protected $table = 'leavestatuses';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('leavestatuses', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('LeaveId');
          $table->integer('UserId');
          $table->string('Leave_Status');
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
       Schema::drop('leavestatuses');
  }

}
