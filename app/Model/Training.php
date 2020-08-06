<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Training extends Model {

  //
  protected $table = 'trainings';
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
          $table->string('Training');
          $table->string('Description');
          $table->string('Organizer');
          $table->string('Training_Date');
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
