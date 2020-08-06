<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalSetting extends Model {

	//
  protected $table = 'approvalsettings';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('approvalsettings', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('UserId');
          $table->integer('Created_By');
          $table->string('Country');
          $table->integer('ProjectId');
          $table->string('Type');
          $table->string('Level');
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
       Schema::drop('approvalsettings');
  }

}
