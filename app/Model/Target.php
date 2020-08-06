<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Target extends Model {

	//
  protected $table = 'targets';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('targets', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->boolean('Active');
          $table->integer('ProjectId');
          $table->string('Title');
          $table->string('Target_Field');
          $table->string('Target_Date');
          $table->integer('Target');
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
       Schema::drop('targets');
  }

}
