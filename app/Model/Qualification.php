<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model {

  //
  protected $table = 'qualifications';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create($table, function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('UserId');
          $table->string('Institution');
          $table->string('Major');
          $table->string('Qualification_Level');
          $table->string('Qualification_Description');
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
       Schema::drop($table);
  }

}
