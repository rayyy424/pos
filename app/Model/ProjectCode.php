<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectCode extends Model {

  //
  protected $table = 'projectcodes';
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
          $table->string('Project_Code');
          $table->string('Description');
          $table->string('Remarks');
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
