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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('sku')->nullable()->unique();
            $table->string('barcode')->nullable();
            $table->decimal('price', 9, 2);
            $table->decimal('compare_price', 9, 2)->nullable();
            $table->decimal('cost_price', 9, 2)->nullable();
            // $table->integer('qty')->default(0);
            $table->integer('min_order_qty')->default(1);
            $table->integer('max_order_qty')->nullable();
            $table->boolean('is_track_stock')->default(true);
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
