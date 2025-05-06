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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profileURL')->nullable();
            $table->string('gender')->nullable();
            $table->enum('systemRole', ['user', 'admin'])->default('user');
            $table->rememberToken()->nullable();
            $table->timestamps();
        });
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->string('accessibility');
            $table->foreignId('ownerID')->constrained('users');
            $table->integer('teamID');
            $table->timestamps();
        });
        Schema::create('sprints', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('startDate');
            $table->dateTime('endDate');
            $table->string('sprintGoal')->nullable();
            $table->foreignId('ownerID')->constrained('users');
            $table->timestamps();
        });
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('startDate')->nullable();
            $table->dateTime('endDate')->nullable();
            $table->integer('duration')->nullable();
            $table->foreignId('statusID')->constrained('statuses');
            $table->foreignId('sprintID')->nullable()->constrained('sprints');
            $table->foreignId('projectID')->constrained('projects');
            $table->foreignId('userID')->constrained('users');
            $table->foreignId('assigneeID')->nullable()->constrained('users');
            $table->foreignId('assignerID')->constrained('users');
            $table->string('priority')->nullable();
            $table->timestamps();
        });
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('value');
            $table->foreignId('userID')->constrained('users');
            $table->foreignId('issueID')->constrained('issues');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('sprints');
        Schema::dropIfExists('statuses');
        Schema::dropIfExists('issues');
        Schema::dropIfExists('comments');
    }
};
