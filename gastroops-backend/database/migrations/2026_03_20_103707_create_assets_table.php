<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('operational_unit_id')
                ->nullable()
                ->constrained('operational_units')
                ->nullOnDelete();

            $table->string('name');
            $table->string('asset_type');
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->date('purchase_date')->nullable();
            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};