<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

	//
  	protected $table = 'comments';
    protected $primaryKey = 'Id';

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('comments', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->int('UserId');
            $table->int('TargetId');
            $table->string('Comment');
            $table->string('Type');
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
         Schema::drop('employments');
    }

}
