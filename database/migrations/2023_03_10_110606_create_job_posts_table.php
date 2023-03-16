<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('content');
            $table->string('logo');
            $table->unsignedBigInteger('employer_id');
            $table->foreign('employer_id')->references('id')->on('employers')->onDelete('cascade');
            $table->unsignedBigInteger('job_description_id');
            $table->foreign('job_description_id')->references('id')->on('job_descriptions')->onDelete('cascade');
            $table->unsignedBigInteger('job_skill_id');
            $table->foreign('job_skill_id')->references('id')->on('job_skills')->onDelete('cascade');
            $table->dateTime('application_deadline');
            $table->boolean('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_posts');
    }
}
