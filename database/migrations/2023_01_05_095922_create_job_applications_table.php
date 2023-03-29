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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_post_id')->nullable()->constrained('job_posts');
            $table->foreignId('resume_id')->nullable()->constrained('resumes')->onDelete('cascade');
            $table->date('applyed_date')->nullable();
            $table->boolean('withdraw_status')->default(false);
            $table->text('withdraw_reson')->nullable();
            // $table->string('full_name')->nullable();
            // $table->string('email')->nullable();
            // $table->longText('cover_letter')->nullable();
            $table->enum('job_status', ['New', 'Interviewed','Offer Extended','Hired','Archived'])->default('New');
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
};
