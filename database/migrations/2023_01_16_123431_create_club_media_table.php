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
        Schema::create('club_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained();
            $table->string('type')->nullable();
            $table->text('title');
            $table->string('date_from')->nullable();
            $table->string('date_to')->nullable();
            $table->string('notice_date')->nullable();
            $table->string('news_date')->nullable();
            $table->enum('status',['Active','Inactive'])->default('Inactive');
            $table->longtext('description');
            $table->string('c_address')->nullable();
            $table->string('c_phone')->nullable();
            $table->string('c_email')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
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
        Schema::dropIfExists('club_media');
    }
};
