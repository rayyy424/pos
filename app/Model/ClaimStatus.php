<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ClaimStatus extends Model {

	//
  protected $table = 'claimstatuses';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('claimstatuses', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('ClaimId');
          $table->integer('UserId');
          $table->string('Status');
          $table->string('Comment');
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
       Schema::drop('claimstatuses');
  }

}
