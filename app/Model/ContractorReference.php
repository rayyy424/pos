<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractorReference extends Model {

	//
  	protected $table = 'contractorreferences';
    protected $primaryKey = 'Id';

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('contractorreferences', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('UserId');
            $table->integer('ProjectId');
            $table->integer('Project_Manager');
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
         Schema::drop('contractorreferences');
    }

}
