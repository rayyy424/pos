<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

  //
  protected $table = 'projects';
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
          $table->integer('Project_Manager');
          $table->string('Project_Name');
          $table->string('Country');
          $table->string('Customer');
          $table->string('Operator');
          $table->string('Region');
          $table->string('Type');
          $table->string('Scope');
          $table->string('Project_Description');
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
