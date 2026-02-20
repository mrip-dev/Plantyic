<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('time')->nullable();
            $table->string('duration')->nullable();
            $table->string('priority')->nullable();
            $table->json('tags')->nullable();
            $table->string('status')->nullable();
            $table->date('date');
            $table->string('assignee')->nullable();
            $table->string('project')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
