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
        Schema::table('mentions', function (Blueprint $table) {
            $table->string('mentioned_email')->nullable()->after('mentioned_user_id');
            
            // Make mentioned_user_id nullable since we might have email instead
            $table->unsignedBigInteger('mentioned_user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentions', function (Blueprint $table) {
            $table->dropColumn('mentioned_email');
            // Note: You might want to handle the nullable change reversal here
        });
    }
};