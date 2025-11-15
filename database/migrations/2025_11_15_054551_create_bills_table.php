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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->date('bill_date');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('paid');
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('bill_number');
            $table->index('bill_date');
            $table->index('customer_id');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
