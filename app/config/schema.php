<?php

namespace App\config\schema;

use Illuminate\Database\Capsule\Manager as Capsule;

function load()
{
   /* if (!Capsule::schema()->hasTable('users')) {
        Capsule::schema()->create('users', function ($table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }
    if (!Capsule::schema()->hasTable('posts')) {
        Capsule::schema()->create('posts', function ($table) {
            $table->bigIncrements('id');
            $table->string('state')->nullable();
            $table->string('title');
            $table->text('body');
            $table->bigInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->timestamps();
        });
    }
    if (!Capsule::schema()->hasTable('post_comments')) {
        Capsule::schema()->create('post_comments', function ($table) {
            $table->bigIncrements('id');
            $table->string('body');
            $table->bigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts');
            $table->bigInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    if (!Capsule::schema()->hasTable('post_likes')) {
        Capsule::schema()->create('post_likes', function ($table) {
            $table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts');
            $table->bigInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->timestamps();
        });
    }*/
    if (!Capsule::schema()->hasTable('ancets')) {
        Capsule::schema()->create('ancets', function ($table){
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unique();
            $table->string('name')->nullable();
            $table->string('city')->nullable();
            $table->text('about')->nullable();
            $table->string('price')->nullable();
            $table->string('contact')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    if (!Capsule::schema()->hasTable('cities')) {
        Capsule::schema()->create('cities', function($table){
            $table->bigIncrements('id');
            $table->bigInteger('telegram_id')->unique();
            $table->string('city')->nullable();
            $table->timestamps();
        });
    }

    if (!Capsule::schema()->hasTable('ancetimgs')) {
        Capsule::schema()->create('ancetimgs', function($table){
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unique();
            $table->string('img', 2000)->nullable();
            $table->integer('count_img')->nullable();
            $table->timestamps();
        });
    }

}
