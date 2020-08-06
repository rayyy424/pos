<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AllowanceScheme extends Model {

	//
  protected $table = 'allowanceschemes';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('allowanceschemes', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('Created_By');
          $table->string('Scheme_Name');
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
       Schema::drop('allowanceschemes');
  }

}
