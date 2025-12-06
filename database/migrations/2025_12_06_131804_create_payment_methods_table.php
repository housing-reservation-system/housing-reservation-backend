<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('method_type', ['Credit_Card', 'Wallet', 'Other']);
            $table->enum('card_brand', ['VISA', 'MASTERCARD', 'OTHER'])->nullable();
            $table->string('last_four_digits', 4);
            $table->string('card_holder_name')->nullable();
            $table->date('expiry_date');
            $table->boolean('is_default')->default(false);
            $table->string('token')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
