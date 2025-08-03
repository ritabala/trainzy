<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('progress_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('body_measurement_id')->nullable()->constrained()->onDelete('set null');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type');
            $table->string('file_size');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('view_type', ['front', 'side', 'back'])->nullable();
            $table->date('photo_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('progress_photos');
    }
}; 