<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('username')->nullable();

            // Add foreign key constraint with proper reference
            $table->foreignId('project_id')
                ->constrained('projects')
                ->onDelete('cascade');

            // Add user_id foreign key (nullable for pending invites)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('token')->unique();
            $table->boolean('accepted')->default(false);
            $table->timestamps();

            // Add index for better performance on common queries
            $table->index(['email', 'project_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};