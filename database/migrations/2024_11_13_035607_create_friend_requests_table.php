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
        // Schema::create('friend_requests', function (Blueprint $table) {
        //     $table->foreignId('user_id')->constrained();
        //     $table->foreignId('other_id')->constrained('users');
        //     $table->boolean('is_active')->default(true);
        //     $table->boolean('is_accepted')->default(false);
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friend_requests');
    }
};
