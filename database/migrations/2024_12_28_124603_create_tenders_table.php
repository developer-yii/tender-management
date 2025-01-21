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
            $table->string('tender_name')->nullable();
            $table->longText('title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('vergabestelle')->nullable();
            $table->longText('place_of_execution')->nullable();
            $table->integer('abgabeform')->nullable();
            $table->integer('status')->default(1)->comment('1=received, 2=into_consideration, 3=in_progress');
            $table->timestamp('period_from')->nullable();
            $table->timestamp('period_to')->nullable();
            $table->timestamp('offer_period_expiration')->nullable();
            $table->timestamp('binding_period')->nullable();
            $table->timestamp('question_ask_last_date')->nullable();
            $table->string('procurement_regulations')->nullable();
            $table->string('procurement_procedures')->nullable();
            $table->integer('is_subdivision_lots')->default(0)->comment('0=no, 1=yes');
            $table->integer('is_side_offers_allowed')->default(0)->comment('0=no, 1=yes');
            $table->integer('is_main_offers_allowed')->default(0)->comment('0=no, 1=yes');
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
