<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model {

	//
  protected $table = 'files';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('files', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->string('Type');
          $table->integer('TargetId');
          $table->string('File_Name');
          $table->string('File_Size');
          $table->string('Web_Path');
          $table->string('System_Path');
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
       Schema::drop('files');
  }

}
