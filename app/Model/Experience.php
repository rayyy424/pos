<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model {

	//
  protected $table = 'experiences';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('experiences', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('UserId');
          $table->string('Project');
          $table->string('Role');
          $table->string('Responsibility');
          $table->string('Achievement');
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
       Schema::drop('experiences');
  }

}
