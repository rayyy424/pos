<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model {

  //
  protected $table = 'skills';
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
          $table->string('Skill');
          $table->string('Level');
          $table->string('Description');
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
