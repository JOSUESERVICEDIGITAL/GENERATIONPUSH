<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('message', 160);
            $table->enum('audience', ['all', 'members', 'leaders'])->default('all');
            $table->unsignedInteger('recipients_count')->default(0);
            $table->enum('status', ['draft', 'scheduled', 'sent'])->default('draft');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_campaigns');
    }
};
