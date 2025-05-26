<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;


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
            $table->string('avatar')->nullable(); // New field
            $table->string('cover_photo')->nullable(); // New field
            $table->string('bio')->nullable(); // New field
            $table->string('phone')->nullable(); // New field
            $table->string('gender')->nullable();
            $table->enum('systemRole', ['user', 'admin'])->default('user');
            $table->rememberToken()->nullable();
            $table->timestamps();
            $table->text('email_verification_token')->nullable()->after('email_verified_at');
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
            $table->foreignId('assignerID')->nullable()->constrained('users');
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
        Schema::create('mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('mentioning_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mentioned_user_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->boolean('read')->default(false);
            $table->timestamps();
        });
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path');
            $table->string('mime_type');
             $table->foreignId('issue_id')->nullable()->constrained('issues')->after('projectId');
            $table->unsignedBigInteger('size');
            $table->foreignId('projectId')->constrained('projects')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->comment('User who uploaded the file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('mentions');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('issues');
        Schema::dropIfExists('statuses');
        Schema::dropIfExists('sprints');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users');
    }

};
