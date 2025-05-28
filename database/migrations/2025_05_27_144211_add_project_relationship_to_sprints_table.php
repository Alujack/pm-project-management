<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sprints', function (Blueprint $table) {
            // Add the project_id column with foreign key constraint
            $table->foreignId('project_id')
                ->nullable()
                ->after('ownerID')
                ->constrained('projects')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sprints', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['project_id']);

            // Then drop the column
            $table->dropColumn('project_id');
        });
    }
};