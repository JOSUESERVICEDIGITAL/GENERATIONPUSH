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
        Schema::create('event_reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Participant
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();

            // Réservation
            $table->string('reference')->unique();

            $table->unsignedInteger('quantity')->default(1);

            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled'
            ])->default('pending');

            // Paiement
            $table->enum('payment_status', [
                'not_required',
                'pending',
                'paid',
                'failed',
                'refunded'
            ])->default('not_required');

            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('XOF');

            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();

            $table->text('notes')->nullable();

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

            $table->index(['event_id', 'status']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_reservations');
    }
};
