<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seeker_id');
            $table->foreign('seeker_id')->references('id')->on('seekers')->onDelete('cascade');
            $table->unsignedBigInteger('job_post_id');
            $table->foreign('job_post_id')->references('id')->on('job_posts')->onDelete('cascade');
            $table->text('cover_letter');
            $table->string('resume_path');
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
        Schema::dropIfExists('applications');
    }
}
