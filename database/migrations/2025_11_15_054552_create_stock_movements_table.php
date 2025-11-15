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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->enum('type', ['in', 'out']); // in = stock added, out = stock sold
            $table->integer('quantity');
            $table->integer('balance_after'); // stock balance after this movement
            $table->string('reference_type')->nullable(); // e.g., 'App\Models\Bill'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('product_id');
            $table->index(['reference_type', 'reference_id']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
