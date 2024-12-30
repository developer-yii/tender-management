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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('responsible_person_id')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('email')->nullable();
            $table->integer('status')->default(1)->comment('1=received, 2=into_consideration, 3=in_progress');
            $table->timestamp('period_from')->nullable();
            $table->timestamp('period_to')->nullable();
            $table->timestamp('offer_period_expiration')->nullable();
            $table->timestamp('binding_period')->nullable();
            $table->timestamp('question_ask_last_date')->nullable();
            $table->decimal('location_lat', 10, 7)->nullable();
            $table->decimal('location_long', 10, 7)->nullable();
            $table->string('procurement_regulations')->nullable();
            $table->string('procurement_procedures')->nullable();
            $table->enum('is_subdivision_lots', ['true', 'false'])->default('true');
            $table->enum('is_side_offers_allowed', ['true', 'false'])->default('true');
            $table->enum('is_main_offers_allowed', ['true', 'false'])->default('true');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
