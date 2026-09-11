<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {

            $table->id();

            $table->string('title');

            $table->text('description')->nullable();

            $table->enum('category', [
                'teachers',
                'administrators',
                'parents'
            ]);

            $table->string('recipient_name');

            $table->string('recipient_phone')->nullable();

            $table->string('recipient_email')->nullable();

            $table->string('original_file');

            $table->string('signed_file')->nullable();

            $table->string('access_token', 64)->unique();

            $table->enum('status', [
                'pending',
                'signed',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->timestamp('signed_at')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->ipAddress('ip_address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
