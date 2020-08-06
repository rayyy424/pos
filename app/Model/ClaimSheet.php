<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ClaimSheet extends Model {

	//
  protected $table = 'claimsheets';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('claimsheets', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('UserId');
          $table->string('Claim_Sheet_Name');
          $table->string('Status');
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
       Schema::drop('claimsheets');
  }

}
