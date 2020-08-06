<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetTracking extends Model {

	//
  protected $table = 'assettrackings';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('assettrackings', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('AssetId');
          $table->integer('ProjectId');
          $table->integer('UserId');
          $table->integer('Transfer_To');
          $table->string('Transfer_Date_Time');
          $table->string('Acknowledge_Date_Time');
          $table->string('Car_No');
          $table->string('Date');
          $table->string('Time');
          $table->string('Purpose');
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
       Schema::drop('assettrackings');
  }

}
