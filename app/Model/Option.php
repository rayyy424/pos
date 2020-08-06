<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model {

  //
  protected $table = 'options';
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
          $table->string('Table');
          $table->string('Field');
          $table->string('Option');
          $table->string('Extra');
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
