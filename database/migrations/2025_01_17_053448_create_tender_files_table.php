<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tender_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_id')->nullable();
            $table->string('type')->nullable();
            $table->string('folder_name')->nullable();
            $table->string('original_file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('docx_preview')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_files');
    }
};
