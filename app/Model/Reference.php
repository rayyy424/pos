<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model {

  //
  protected $table = 'references';
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
          $table->integer('UserId');
          $table->string('Reference');
          $table->string('Contact_No');
          $table->string('Company');
          $table->string('Position');
          $table->string('Relationship');
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
       Schema::drop($table);
  }

}
