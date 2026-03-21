<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_channels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('operational_unit_id')
                ->nullable()
                ->constrained('operational_units')
                ->nullOnDelete();

            $table->string('channel_type');
            $table->string('platform_name')->nullable();
            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_channels');
    }
};