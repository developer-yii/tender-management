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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('category_name')->nullable();
            $table->string('title')->nullable();
            $table->string('logo')->nullable();
            $table->date('valid_from_date')->nullable();
            $table->date('valid_to_date')->nullable();
            $table->longText('description')->nullable();
            $table->string('certificate_word')->nullable();
            $table->string('certificate_pdf')->nullable();
            $table->string('docx_preview')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
