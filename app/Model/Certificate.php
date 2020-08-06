<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model {

  //
  protected $table = 'certificates';
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
          $table->string('Certificate');
          $table->string('Certificate_Date');
          $table->string('Valid_Until');
          $table->string('Description');
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
