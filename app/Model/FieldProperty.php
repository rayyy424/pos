<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldProperty extends Model {

	//
  protected $table = 'fieldproperties';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('fieldproperties', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->int('UserId');
          $table->string('Table');
          $table->string('Field_Name');
          $table->string('Data_Type');
          $table->string('Field_Type');
          $table->string('Required');
          $table->string('Hidden');
          $table->boolean('System_Defined');
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
       Schema::drop('fieldproperties');
  }


}
