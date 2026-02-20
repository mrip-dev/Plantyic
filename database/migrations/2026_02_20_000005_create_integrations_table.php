<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('connected')->default(false);
            $table->json('scopes')->nullable();
            $table->date('lastSync')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('integrations');
    }
};
