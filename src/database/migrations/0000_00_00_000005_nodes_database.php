<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NodesDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Global
        Schema::create('alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->unsigned();
            $table->string('name');
            $table->enum('type', ['normal', 'custom'])->default('normal')->nullable();
            $table->string('code')->nullable();
            $table->timestamps();
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
        Schema::create('alert_actions', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->enum('type', ['dashboard','email','sms','app'])->default('dashboard')->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->foreign('parent_id')->references('id')->on('alerts')->onDelete('cascade');
        });
        Schema::create('alert_conditionals', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->integer('field_id')->unsigned();
            $table->boolean('active')->nullable();
            $table->enum('conditional', ['is', 'is_not', 'is_greater', 'is_less', 'where_in'])->default('is')->nullable();
            $table->text('value')->nullable();
            $table->foreign('parent_id')->references('id')->on('alerts')->onDelete('cascade');
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
        });
        Schema::create('alert_users', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('parent_id')->references('id')->on('alerts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned()->default(1);
            $table->string('name');
            $table->string('reply_to')->nullable();
            $table->string('reply_to_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
        Schema::create('email_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('email_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->unique(['email_id','locale']);
            $table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade');
        });
        Schema::create('activities', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->unsigned();
            $table->integer('item_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('username')->nullable();
            $table->string('action')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
        Schema::create('notifications', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->datetime('checked_date')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::create('notification_messages', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->enum('type', ['dashboard','email','sms','app'])->default('dashboard');
            $table->text('message')->nullable();
            $table->boolean('is_sent')->nullable()->default(0);
            $table->foreign('parent_id')->references('id')->on('notifications')->onDelete('cascade');
        });
        Schema::create('inbox', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::create('inbox_users', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('checked')->default(0);
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('inbox')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::create('inbox_messages', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('message')->nullable();
            $table->text('attachments')->nullable();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('inbox')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::create('variables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned()->default(1);
            $table->integer('order')->nullable()->default(0);
            $table->string('name');
            $table->enum('type', ['string', 'text', 'image']);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
        Schema::create('variable_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('variable_id')->unsigned();
            $table->string('locale')->index();
            $table->text('value')->nullable();
            $table->unique(['variable_id','locale']);
            $table->foreign('variable_id')->references('id')->on('variables')->onDelete('cascade');
        });
        Schema::create('temp_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned()->default(1);
            $table->enum('type', ['image','file'])->default('file');
            $table->string('folder');
            $table->string('file');
            $table->timestamps();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
        Schema::create('image_folders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned()->default(1);
            $table->string('name');
            $table->enum('extension', ['jpg','png','gif'])->default('jpg');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
        Schema::create('image_sizes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->default(1);
            $table->string('code');
            $table->enum('type', ['original','resize','fit'])->default('original');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->foreign('parent_id')->references('id')->on('image_folders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_sizes');
        Schema::dropIfExists('image_folders');
        Schema::dropIfExists('temp_files');
        Schema::dropIfExists('variable_translation');
        Schema::dropIfExists('variables');
        Schema::dropIfExists('inbox_messages');
        Schema::dropIfExists('inbox_users');
        Schema::dropIfExists('inbox');
        Schema::dropIfExists('notification_messages');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('email_translation');
        Schema::dropIfExists('emails');
        Schema::dropIfExists('alert_users');
        Schema::dropIfExists('alert_conditionals');
        Schema::dropIfExists('alert_actions');
        Schema::dropIfExists('alerts');
    }
}
