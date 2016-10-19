<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MasterDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Site
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable()->default(0);
            $table->string('code');
            $table->string('name');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable()->default(0);
            $table->string('name');
            $table->string('domain');
            $table->string('root');
            $table->text('google_verification')->nullable();
            $table->text('analytics')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('site_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description');
            $table->string('keywords');
            $table->unique(['site_id','locale']);
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
        /* Proyectos */
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned()->default(1);
            $table->integer('order')->nullable()->default(0);
            $table->enum('type', ['normal', 'customized'])->default('normal');
            $table->string('image')->nullable();
            $table->string('customized_name')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
        Schema::create('page_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned();
            $table->string('locale')->index();
            $table->string('slug');
            $table->string('name');
            $table->unique(['page_id','locale']);
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned()->default(1);
            $table->enum('menu_type', ['site', 'admin'])->default('site');
            $table->integer('order')->nullable()->default(0);
            $table->integer('level')->nullable()->default(1);
            $table->enum('type', ['normal', 'external', 'blank'])->default('normal');
            $table->integer('parent_id')->nullable();
            $table->string('permission')->nullable();
            $table->integer('page_id')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
        Schema::create('menu_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('link')->nullable();
            $table->unique(['menu_id','locale']);
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
        Schema::create('nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('table_name')->nullable();
            $table->string('model')->nullable();
            $table->enum('location', ['package', 'app'])->default('app');
            $table->enum('type', ['normal', 'child', 'subchild', 'field'])->default('normal');
            $table->string('folder')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('permission')->nullable();
            $table->boolean('dynamic')->default(0);
            $table->boolean('customized')->default(0);
            $table->boolean('translation')->default(0);
            $table->boolean('soft_delete')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('node_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->unsigned();
            $table->string('locale')->index();
            $table->string('singular');
            $table->string('plural');
            $table->unique(['node_id','locale']);
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
        Schema::create('node_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->integer('order')->nullable()->default(0);
            $table->enum('action', ['where','whereNot','whereIn','whereNull','whereNotNull','with','has','orderBy','paginate','customRequest']);
            $table->string('col')->nullable();
            $table->enum('value_type', ['value','relation'])->default('value');
            $table->string('value')->nullable();
            $table->foreign('parent_id')->references('id')->on('nodes')->onDelete('cascade');
        });
        Schema::create('node_extras', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->integer('order')->nullable()->default(0);
            $table->enum('display', ['all','admin','site'])->default('all');
            $table->enum('type', ['graph','parent_graph','action_field'])->default('action_field');
            $table->string('parameter')->nullable();
            $table->string('value_array')->nullable();
            $table->foreign('parent_id')->references('id')->on('nodes')->onDelete('cascade');
        });
        Schema::create('filters', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('category', ['admin','indicator','site','custom'])->default('admin');
            $table->integer('order')->nullable()->default(0);
            $table->enum('display', ['all','user'])->default('all');
            $table->enum('type', ['field','parent_field','custom'])->default('field');
            $table->enum('subtype', ['select','date','string','field'])->default('select');
            $table->integer('node_id')->unsigned();
            $table->string('parameter')->nullable();
            $table->text('action_value')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable()->default(0);
            $table->integer('page_id')->unsigned();
            $table->integer('node_id')->unsigned();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->integer('order')->nullable()->default(0);
            $table->string('name');
            $table->string('trans_name');
            $table->enum('type', ['string','text','select','password','image','file','map','radio','checkbox','date','array','score','hidden','preset','relation','child','subchild','field','title','content'])->default('string');
            $table->enum('display_list', ['show', 'excel', 'none'])->default('show');
            $table->enum('display_item', ['show', 'admin', 'none'])->default('show');
            $table->boolean('multiple')->default(0);
            $table->boolean('translation')->default(0);
            $table->boolean('required')->default(0);
            $table->boolean('new_row')->default(0);
            $table->boolean('preset')->default(0);
            $table->boolean('tooltip')->default(0);
            $table->text('message')->nullable();
            $table->string('permission')->nullable();
            $table->string('child_table')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('parent_id')->references('id')->on('nodes')->onDelete('cascade');
        });
        Schema::create('field_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('field_id')->unsigned();
            $table->string('locale')->index();
            $table->string('label');
            $table->unique(['field_id','locale']);
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
        });
        Schema::create('field_extras', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->enum('type', ['class','field_class','options','folder','append','prepend','placeholder','label','cols','rows','disabled','ponderation']);
            $table->string('value');
            $table->foreign('parent_id')->references('id')->on('fields')->onDelete('cascade');
        });
        Schema::create('field_conditionals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->string('trigger_field');
            $table->enum('trigger_show', ['is','is_not','is_greater','is_less','where_in'])->default('is');
            $table->string('trigger_value');
            $table->foreign('parent_id')->references('id')->on('fields')->onDelete('cascade');
        });
        Schema::create('field_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->string('name');
            $table->boolean('active')->default(1);
            $table->foreign('parent_id')->references('id')->on('fields')->onDelete('cascade');
        });
        Schema::create('field_option_translation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('field_option_id')->unsigned();
            $table->string('locale')->index();
            $table->string('label');
            $table->unique(['field_option_id','locale']);
            $table->foreign('field_option_id')->references('id')->on('field_options')->onDelete('cascade');
        });
        Schema::create('indicators', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->unsigned();
            $table->integer('user_id')->nullable();
            $table->string('name');
            $table->enum('type', ['normal','custom'])->default('normal');
            $table->enum('data', ['count','formula'])->default('count');
            $table->text('formula')->nullable();
            $table->string('custom')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
        Schema::create('indicator_alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->integer('user_id')->nullable();
            $table->integer('goal')->nullable();
            $table->date('initial_date')->nullable();
            $table->date('final_date')->nullable();
            $table->foreign('parent_id')->references('id')->on('indicators')->onDelete('cascade');
        });
        Schema::create('indicator_graphs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->integer('user_id')->nullable();
            $table->enum('graph', ['number','bar','pie','line'])->default('line');
            $table->enum('color', ['blue','red','green','purple','yellow','gray','black'])->default('blue');
            $table->integer('goal')->nullable();
            $table->foreign('parent_id')->references('id')->on('indicators')->onDelete('cascade');
        });
        Schema::create('indicator_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->date('date');
            $table->string('value');
            $table->foreign('parent_id')->references('id')->on('indicators')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indicator_values');
        Schema::dropIfExists('indicator_graphs');
        Schema::dropIfExists('indicator_alerts');
        Schema::dropIfExists('indicators');
        Schema::dropIfExists('field_option_translation');
        Schema::dropIfExists('field_options');
        Schema::dropIfExists('field_conditionals');
        Schema::dropIfExists('field_extras');
        Schema::dropIfExists('field_translation');
        Schema::dropIfExists('fields');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('filters');
        Schema::dropIfExists('node_extras');
        Schema::dropIfExists('node_requests');
        Schema::dropIfExists('node_translation');
        Schema::dropIfExists('nodes');
        Schema::dropIfExists('menu_translation');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('page_translation');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('site_translation');
        Schema::dropIfExists('sites');
        Schema::dropIfExists('languages');
    }
}