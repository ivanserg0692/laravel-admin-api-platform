<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news_exports', function (Blueprint $table) {
            $table->id()->unique()->autoIncrement();
            $table->string('export_file');
            $table->string('job_batch_id');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            $table->foreign('job_batch_id')
                ->references('id')
                ->on('job_batches')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_exports');
    }
};
