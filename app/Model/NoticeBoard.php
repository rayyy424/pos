<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class NoticeBoard extends Model {

	//
  protected $table = 'noticeboards';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('noticeboards', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('UserId');
          $table->string('Title');
          $table->string('Content');
          $table->boolean('Email_Notification');
          $table->string('Start_Date');
          $table->string('End_Date');
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
       Schema::drop('noticeboards');
  }

}
