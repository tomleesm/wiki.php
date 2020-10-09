<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTableSettings extends Migration
{
    /**
     * remove table settings
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('settings');
    }

    /**
     * create table settings
     *
     * @return void
     */
    public function down()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key');
            $table->string('value');
            $table->timestamps();
        });
    }
}
