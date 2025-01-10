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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->longText('address')->nullable();
            $table->string('managing_director')->nullable();
            $table->string('bank_name');
            $table->string('iban_number')->nullable();
            $table->string('bic_number')->nullable();
            $table->string('vat_id')->nullable();
            $table->string('trade_register')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website_url')->nullable();
            $table->text('company_presentation_word')->nullable();
            $table->text('company_presentation_pdf')->nullable();
            $table->text('agile_framework_word')->nullable();
            $table->text('agile_framework_pdf')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
