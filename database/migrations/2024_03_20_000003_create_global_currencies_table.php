<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('global_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('symbol');
            $table->integer('decimal_places');
            $table->string('decimal_point');
            $table->string('thousands_separator');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('global_currencies');
    }
}; 