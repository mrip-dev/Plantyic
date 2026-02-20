<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('theme')->nullable();
            $table->string('primaryColor')->nullable();
            $table->string('fontFamily')->nullable();
            $table->string('displayFont')->nullable();
            $table->string('borderRadius')->nullable();
            $table->boolean('sidebarCompact')->nullable();
            $table->boolean('animationsEnabled')->nullable();
            $table->string('fontSize')->nullable();
            $table->string('language')->nullable();
            $table->string('dateFormat')->nullable();
            $table->string('timeFormat')->nullable();
            $table->string('weekStartsOn')->nullable();
            $table->boolean('emailNotifications')->nullable();
            $table->boolean('pushNotifications')->nullable();
            $table->boolean('taskReminders')->nullable();
            $table->boolean('weeklyDigest')->nullable();
            $table->boolean('teamUpdates')->nullable();
            $table->boolean('soundEnabled')->nullable();
            $table->boolean('sidebarOnRight')->nullable();
            $table->boolean('showBottomBar')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
