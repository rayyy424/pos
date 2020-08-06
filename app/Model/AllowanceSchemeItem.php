<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AllowanceSchemeItem extends Model {

	//
  protected $table = 'allowanceschemeitems';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('allowanceschemeitems', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('AllowanceSchemeId');
          $table->string('Day_Type');
          $table->string('Start');
          $table->string('End');
          $table->integer('Minimum_Hour');
          $table->string('Currency');
          $table->decimal('Home_Base',5,2);
          $table->decimal('Outstation',5,2);
          $table->decimal('Subsequent_Home_Base',5,2);
          $table->decimal('Subsequent_Outstation',5,2);
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
       Schema::drop('allowanceschemeitems');
  }

}
