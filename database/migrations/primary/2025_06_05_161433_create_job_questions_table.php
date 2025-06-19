<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('job_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->enum('question_type', ['text', 'radio', 'checkbox']);
            $table->json('options')->nullable(); // for radio/checkbox types
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_questions');
    }
};
