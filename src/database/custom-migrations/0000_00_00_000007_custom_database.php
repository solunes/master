<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Custom
        Schema::create('social_networks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned()->default(1);
            $table->integer('order')->default(0);
            $table->string('code');
            $table->string('url');
            $table->timestamps();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
        Schema::create('registry_a', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->enum('status', ['not_approved','approved','banned'])->default('not_approved');
            $table->string('company_name');
            $table->enum('company_type', ['production', 'service']);
            $table->enum('guayaquil_zone', ['canton_1','canton_2','canton_3','canton_4','canton_5','canton_6','canton_7','canton_8','canton_9','canton_10','canton_11','canton_12','canton_13','canton_14','canton_15','canton_16','canton_17','canton_18','canton_19','canton_20','canton_21']);
            $table->string('address');
            $table->string('phone');
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->string('contact_name');
            $table->string('contact_position');
            $table->string('contact_email');
            $table->string('alternative_email')->nullable();
            $table->string('ruc')->nullable();
            $table->text('interest_in_participation');
            $table->enum('ra1_type', ['environment_license', 'environment_registry', 'environment_certificate', 'other_permission'])->nullable();
            $table->string('ra1_other')->nullable();
            $table->text('ra1_file')->nullable();
            $table->enum('ra2_type', ['environment_audit', 'environment_report', 'environment_guide'])->nullable();
            $table->text('ra2_file')->nullable();
            $table->timestamps();
        });
        Schema::create('registry_b', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->enum('status', ['not_approved','approved','banned'])->default('not_approved');
            $table->string('participant_name');
            $table->enum('clasification', ['natural', 'ong', 'academic_institution', 'civil_organization']);
            $table->enum('guayaquil_belongs', ['canton_1','canton_2','canton_3','canton_4','canton_5','canton_6','canton_7','canton_8','canton_9','canton_10','canton_11','canton_12','canton_13','canton_14','canton_15','canton_16','canton_17','canton_18','canton_19','canton_20','canton_21']);
            $table->string('address');
            $table->string('phone');
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->string('contact_name');
            $table->string('contact_position');
            $table->string('contact_email');
            $table->string('alternative_email')->nullable();
            $table->string('ruc_optional')->nullable();
            $table->string('proposal_title');
            $table->text('proposal_summary');
            $table->text('proposal_objective');
            $table->text('interest_in_participation');
            $table->timestamps();
        });
        Schema::create('postulation_a', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('registry_a_id')->unsigned();
            $table->enum('status', ['holding','sent','not_approved','approved'])->default('holding');
            $table->date('send_date')->nullable();
            $table->integer('total_ponderation')->default(0);
            $table->boolean('pa2_1_bool');
            $table->text('pa2_1_file')->nullable();
            $table->text('pa2_1_desc')->nullable();
            $table->boolean('pa2_2_bool');
            $table->text('pa2_2_file')->nullable();
            $table->text('pa2_2_desc')->nullable();
            $table->boolean('pa2_3_bool');
            $table->text('pa2_3_file')->nullable();
            $table->text('pa2_3_desc')->nullable();
            $table->boolean('pa2_4_bool');
            $table->text('pa2_4_file')->nullable();
            $table->text('pa2_4_desc')->nullable();
            $table->boolean('pa2_5_bool');
            $table->text('pa2_5_file')->nullable();
            $table->text('pa2_5_desc')->nullable();
            $table->boolean('pa2_6_bool');
            $table->text('pa2_6_file')->nullable();
            $table->text('pa2_6_desc')->nullable();
            $table->boolean('pa2_7_bool');
            $table->text('pa2_7_file')->nullable();
            $table->text('pa2_7_desc')->nullable();
            $table->boolean('pa2_8_bool');
            $table->text('pa2_8_file')->nullable();
            $table->text('pa2_8_desc')->nullable();
            $table->boolean('pa2_9_bool');
            $table->text('pa2_9_file')->nullable();
            $table->text('pa2_9_desc')->nullable();
            $table->boolean('pa2_10_bool');
            $table->text('pa2_10_file')->nullable();
            $table->text('pa2_10_desc')->nullable();
            $table->boolean('pa2_11_1_bool');
            $table->text('pa2_11_1_file')->nullable();
            $table->text('pa2_11_1_desc')->nullable();
            $table->text('pa2_11_1_methology')->nullable();
            $table->boolean('pa2_11_2_bool');
            $table->text('pa2_11_2_file')->nullable();
            $table->text('pa2_11_2_desc')->nullable();
            $table->boolean('pa2_11_3_bool');
            $table->text('pa2_11_3_file')->nullable();
            $table->text('pa2_11_3_desc')->nullable();
            $table->boolean('pa2_12_1_bool');
            $table->text('pa2_12_1_file')->nullable();
            $table->text('pa2_12_1_desc')->nullable();
            $table->text('pa2_12_1_methology')->nullable();
            $table->boolean('pa2_12_2_bool');
            $table->text('pa2_12_2_file')->nullable();
            $table->text('pa2_12_2_desc')->nullable();
            $table->boolean('pa2_12_3_bool');
            $table->text('pa2_12_3_file')->nullable();
            $table->text('pa2_12_3_desc')->nullable();
            $table->boolean('pa3_1_bool');
            $table->text('pa3_1_file')->nullable();
            $table->text('pa3_1_desc')->nullable();
            $table->boolean('pa3_2_bool');
            $table->text('pa3_2_file')->nullable();
            $table->text('pa3_2_desc')->nullable();
            $table->boolean('pa3_3_bool');
            $table->text('pa3_3_file')->nullable();
            $table->text('pa3_3_desc')->nullable();
            $table->boolean('pa3_4_bool');
            $table->text('pa3_4_file')->nullable();
            $table->text('pa3_4_desc')->nullable();
            $table->boolean('pa3_5_bool');
            $table->text('pa3_5_file')->nullable();
            $table->text('pa3_5_desc')->nullable();
            $table->boolean('pa3_6_bool');
            $table->text('pa3_6_file')->nullable();
            $table->text('pa3_6_desc')->nullable();
            $table->boolean('pa3_7_bool');
            $table->text('pa3_7_file')->nullable();
            $table->text('pa3_7_desc')->nullable();
            $table->boolean('pa3_8_bool');
            $table->text('pa3_8_file')->nullable();
            $table->text('pa3_8_desc')->nullable();
            $table->timestamps();
            $table->foreign('registry_a_id')->references('id')->on('registry_a')->onDelete('cascade');
        });
        Schema::create('postulation_b', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('registry_b_id')->unsigned();
            $table->enum('status', ['holding','sent','not_approved','approved'])->default('holding');
            $table->date('send_date')->nullable();
            $table->enum('pb1', ['energy','transport','waste','hydric_resources','agriculture','industrial','biodiversity']);
            $table->string('pb2')->nullable();
            $table->text('pb3')->nullable();
            $table->text('pb4')->nullable();
            $table->text('pb5')->nullable();
            $table->text('pb6')->nullable();
            $table->text('pb7')->nullable();
            $table->text('pb8_1')->nullable();
            $table->text('pb8_2')->nullable();
            $table->text('pb8_3')->nullable();
            $table->text('pb9')->nullable();
            $table->string('pb10')->nullable();
            $table->string('pb11_total')->nullable();
            $table->string('pb11_investment')->nullable();
            $table->string('pb11_operation')->nullable();
            $table->string('pb11_other')->nullable();
            $table->string('pb11_counterpart')->nullable();
            $table->text('pb12')->nullable();
            $table->string('pb_cv_file')->nullable();
            $table->timestamps();
            $table->foreign('registry_b_id')->references('id')->on('registry_b')->onDelete('cascade');
        });
        Schema::create('deadlines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->unsigned();
            $table->integer('order')->default(0);
            $table->string('name');
            $table->date('deadline');
            $table->string('expired_message');
            $table->timestamps();
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
        Schema::create('titles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->unsigned();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
        Schema::create('title_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('title_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['title_id','locale']);
            $table->foreign('title_id')->references('id')->on('titles')->onDelete('cascade');
        });
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->unsigned();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
        Schema::create('content_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('content_id')->unsigned();
            $table->string('locale')->index();
            $table->text('content');
            $table->unique(['content_id','locale']);
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
        });
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->unsigned();
            $table->integer('order')->default(0);
            $table->string('name');
            $table->string('image');
            $table->timestamps();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
        Schema::create('agendas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->unsigned();
            $table->integer('order')->default(0);
            $table->string('event');
            $table->string('name');
            $table->text('content')->nullable();
            $table->timestamps();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
        Schema::create('sponsors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->unsigned();
            $table->integer('order')->default(0);
            $table->string('name');
            $table->string('image');
            $table->string('link')->nullable();
            $table->timestamps();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
        Schema::create('form_contact', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->text('message');
            $table->timestamps();
        });
        Schema::create('contact', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->unsigned()->default(1);
            $table->integer('order')->default(0);
            $table->string('name');
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_contact');
        Schema::dropIfExists('contact');
        Schema::dropIfExists('sponsors');
        Schema::dropIfExists('agendas');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('content_translation');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('title_translation');
        Schema::dropIfExists('titles');
        Schema::dropIfExists('deadlines');
        Schema::dropIfExists('postulation_b');
        Schema::dropIfExists('postulation_a');
        Schema::dropIfExists('registry_b');
        Schema::dropIfExists('registry_a');
        Schema::dropIfExists('social_networks');
    }
}
