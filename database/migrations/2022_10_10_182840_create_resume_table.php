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
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->date('birthdate');
            $table->enum('gender',['Male','Female','Others'])->default('Male');
            $table->enum('religion',['Islam','Buddhism','Christianity','jainism','Hinduism','Sikhism','Others'])->default('Islam');
            $table->enum('marital_status',['Single','Unmarried','Married'])->default('Single');
            $table->string('nationality');
            $table->integer('national_id');
            $table->string('ewu_id_no');
            $table->string('passport_number')->nullable();
            $table->date('passport_issue_date')->nullable();
            $table->string('personal_number');
            $table->string('office_number')->nullable();
            $table->string('email');
            $table->string('blood_group');
            $table->string('linkedin_profile')->nullable();
            $table->string('facebook_profile')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_designation')->nullable();
            $table->string('father_organization')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_designation')->nullable();
            $table->string('mother_organization')->nullable();
            $table->longText('career_summary')->nullable();
            $table->longText('special_qualfication')->nullable();
            $table->string('keyword')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
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
        Schema::dropIfExists('personal_resume');
    }
};
