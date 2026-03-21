<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utility_bills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('operational_unit_id')
                ->nullable()
                ->constrained('operational_units')
                ->nullOnDelete();

            $table->string('utility_type');
            $table->date('billing_period_start')->nullable();
            $table->date('billing_period_end')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('usage_value', 10, 2)->nullable();
            $table->string('provider_name')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utility_bills');
    }
};