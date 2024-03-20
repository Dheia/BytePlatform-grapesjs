<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->integer('from_user')->nullable();
            $table->json('to_user')->nullable();
            $table->json('to_role')->nullable();
            $table->json('meta_data')->nullable();
            $table->string('type')->nullable();
            $table->string('view')->nullable();
            $table->timestamps();
        });
        Schema::create('notification_users', function (Blueprint $table) {
            $table->bigIncrements('notification_id');
            $table->integer('user_id');
            $table->dateTime('read_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_users');
    }
};
