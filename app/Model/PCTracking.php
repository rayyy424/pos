<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PCTracking extends Model {

  //
  protected $table = 'pctrackings';
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
          $table->string('PC_Label');
          $table->string('Process_Name');
          $table->string('Title');
          $table->string('Active_Inactive');
          $table->string('Date_Time');
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
