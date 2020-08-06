<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class License extends Model {

	//
  protected $table = 'licenses';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('licenses', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('UserId');
          $table->string('License_Type');
          $table->integer('ID');
          $table->string('Issue_Date');
          $table->string('Expiry_Date');
          $table->string('License_Status');
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
       Schema::drop('licenses');
  }

}
