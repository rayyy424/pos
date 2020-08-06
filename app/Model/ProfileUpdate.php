<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileUpdate extends Model
{
    //
    protected $table = 'profileupdates';
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
            $table->string('Changes');
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
