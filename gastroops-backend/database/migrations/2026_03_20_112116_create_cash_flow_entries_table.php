<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_flow_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('operational_unit_id')
                ->nullable()
                ->constrained('operational_units')
                ->nullOnDelete();

            $table->string('entry_type');
            $table->string('category');
            $table->decimal('amount', 10, 2);
            $table->date('entry_date')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_flow_entries');
    }
};