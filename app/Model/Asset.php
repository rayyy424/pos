<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model {

	//
  protected $table = 'assets';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('assets', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->string('Label');
          $table->string('Type');
          $table->string('Serial_No');
          $table->string('IMEI');
          $table->string('Brand');
          $table->string('Model_No');
          $table->string('Car_No');
          $table->string('Color');
          $table->string('Availability');
          $table->string('Extra_Detail_1');
          $table->string('Extra_Detail_2');
          $table->string('Extra_Detail_3');
          $table->string('Extra_Detail_4');
          $table->string('Extra_Detail_5');
          $table->string('Remarks');
          $table->string('Ownership');
          $table->string('Rental_Company');
          $table->string('Rental_Start_Date');
          $table->string('Rental_End_Date');
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
       Schema::drop('assets');
  }

}
