<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channel_fees', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sales_channel_id')
                ->constrained('sales_channels')
                ->cascadeOnDelete();

            $table->decimal('percentage', 8, 2)->nullable();
            $table->decimal('fixed_amount', 10, 2)->nullable();
            $table->string('period')->default('monthly');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_fees');
    }
};