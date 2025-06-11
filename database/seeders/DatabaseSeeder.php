<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Project;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data - PostgreSQL version
        DB::table('chats')->delete();
        DB::table('attachments')->delete();
        DB::table('mentions')->delete();
        DB::table('comments')->delete();
        DB::table('issues')->delete();
        DB::table('statuses')->delete();
        DB::table('sprints')->delete();
        DB::table('members')->delete();
        DB::table('projects')->delete();
        DB::table('users')->delete();
        DB::table('invitations')->delete();

        // Reset sequences for PostgreSQL
        $tables = ['users', 'projects', 'sprints', 'statuses', 'issues', 'comments', 'mentions', 'attachments', 'members', 'invitations', 'chats'];
        foreach ($tables as $table) {
            DB::statement("ALTER SEQUENCE {$table}_id_seq RESTART WITH 1");
        }

        // Users - 5 entries
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password168'),
                'profileURL' => 'https://example.com/profiles/admin',
                'avatar' => 'avatars/admin.jpg',
                'cover_photo' => 'covers/admin.jpg',
                'bio' => 'System Administrator',
                'phone' => '1234567890',
                'gender' => 'male',
                'systemRole' => 'admin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'johndoe',
                'email' => 'john@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password168'),
                'profileURL' => 'https://example.com/profiles/john',
                'avatar' => 'avatars/john.jpg',
                'cover_photo' => 'covers/john.jpg',
                'bio' => 'Project Manager',
                'phone' => '9876543210',
                'gender' => 'male',
                'systemRole' => 'user',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'janedoe',
                'email' => 'jane@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password168'),
                'profileURL' => 'https://example.com/profiles/jane',
                'avatar' => 'avatars/jane.jpg',
                'cover_photo' => 'covers/jane.jpg',
                'bio' => 'Frontend Developer',
                'phone' => '5551234567',
                'gender' => 'female',
                'systemRole' => 'user',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'bobsmith',
                'email' => 'bob@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password168'),
                'profileURL' => 'https://example.com/profiles/bob',
                'avatar' => 'avatars/bob.jpg',
                'cover_photo' => 'covers/bob.jpg',
                'bio' => 'Backend Developer',
                'phone' => '5559876543',
                'gender' => 'male',
                'systemRole' => 'user',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'alicegreen',
                'email' => 'alice@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password168'),
                'profileURL' => 'https://example.com/profiles/alice',
                'avatar' => 'avatars/alice.jpg',
                'cover_photo' => 'covers/alice.jpg',
                'bio' => 'QA Engineer',
                'phone' => '5554567890',
                'gender' => 'female',
                'systemRole' => 'user',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('users')->insert($users);

        // Projects - 5 entries
        $projects = [
            [
                'name' => 'E-commerce Platform',
                'key' => 'ECOM',
                'accessibility' => 'public',
                'ownerID' => 1,
                'teamID' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mobile App Development',
                'key' => 'MOB',
                'accessibility' => 'public',
                'ownerID' => 2,
                'teamID' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Website Redesign',
                'key' => 'WEB',
                'accessibility' => 'public',
                'ownerID' => 3,
                'teamID' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'API Development',
                'key' => 'API',
                'accessibility' => 'public',
                'ownerID' => 4,
                'teamID' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Data Analytics Dashboard',
                'key' => 'DASH',
                'accessibility' => 'public',
                'ownerID' => 5,
                'teamID' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('projects')->insert($projects);

        // Members - 5 entries (project members with different roles)
        $members = [
            [
                'role' => 'owner',
                'userID' => 1,
                'projectID' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role' => 'admin',
                'userID' => 2,
                'projectID' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role' => 'developer',
                'userID' => 3,
                'projectID' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role' => 'developer',
                'userID' => 4,
                'projectID' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role' => 'admin',
                'userID' => 5,
                'projectID' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('members')->insert($members);

        // Statuses - 5 entries
        $statuses = [
            ['name' => 'To Do', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'In Progress', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'In Review', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Done', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Blocked', 'created_at' => now(), 'updated_at' => now()]
        ];

        DB::table('statuses')->insert($statuses);

        // Sprints - 5 entries with project relationships
        $sprints = [
            [
                'name' => 'Sprint 1 - ECOM',
                'startDate' => Carbon::now()->subDays(14),
                'endDate' => Carbon::now(),
                'sprintGoal' => 'Complete initial setup and user authentication',
                'ownerID' => 1,
                'project_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sprint 2 - ECOM',
                'startDate' => Carbon::now(),
                'endDate' => Carbon::now()->addDays(14),
                'sprintGoal' => 'Implement product catalog and shopping cart',
                'ownerID' => 2,
                'project_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mobile App Sprint 1',
                'startDate' => Carbon::now()->subDays(7),
                'endDate' => Carbon::now()->addDays(7),
                'sprintGoal' => 'Build basic app structure and navigation',
                'ownerID' => 3,
                'project_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Website Redesign Sprint 1',
                'startDate' => Carbon::now()->subDays(5),
                'endDate' => Carbon::now()->addDays(9),
                'sprintGoal' => 'Create new UI components and layouts',
                'ownerID' => 4,
                'project_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'API Development Sprint 1',
                'startDate' => Carbon::now()->subDays(3),
                'endDate' => Carbon::now()->addDays(11),
                'sprintGoal' => 'Build core API endpoints and documentation',
                'ownerID' => 5,
                'project_id' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('sprints')->insert($sprints);

        // Issues - 5 entries with proper relationships
        $issues = [
            [
                'title' => 'Setup authentication system',
                'description' => 'Implement user registration, login, and password reset functionality',
                'startDate' => Carbon::now()->subDays(10),
                'endDate' => Carbon::now()->subDays(5),
                'duration' => 5,
                'statusID' => 4, // Done
                'sprintID' => 1,
                'projectID' => 1,
                'userID' => 1,
                'assigneeID' => 2,
                'assignerID' => 1,
                'priority' => 'high',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Create product model',
                'description' => 'Design database schema for products with categories and variants',
                'startDate' => Carbon::now()->subDays(4),
                'endDate' => Carbon::now()->addDays(3),
                'duration' => 7,
                'statusID' => 2, // In Progress
                'sprintID' => 2,
                'projectID' => 1,
                'userID' => 1,
                'assigneeID' => 3,
                'assignerID' => 1,
                'priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Design app navigation',
                'description' => 'Create bottom tab navigation with 5 main sections',
                'startDate' => Carbon::now()->subDays(5),
                'endDate' => Carbon::now()->addDays(2),
                'duration' => 7,
                'statusID' => 3, // In Review
                'sprintID' => 3,
                'projectID' => 2,
                'userID' => 2,
                'assigneeID' => 4,
                'assignerID' => 2,
                'priority' => 'high',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Create new homepage design',
                'description' => 'Design modern homepage with hero section and featured content',
                'startDate' => Carbon::now()->subDays(3),
                'endDate' => Carbon::now()->addDays(4),
                'duration' => 7,
                'statusID' => 2, // In Progress
                'sprintID' => 4,
                'projectID' => 3,
                'userID' => 3,
                'assigneeID' => 3,
                'assignerID' => 3,
                'priority' => 'high',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Build REST API endpoints',
                'description' => 'Create CRUD endpoints for user management and data operations',
                'startDate' => Carbon::now()->subDays(2),
                'endDate' => Carbon::now()->addDays(5),
                'duration' => 7,
                'statusID' => 1, // To Do
                'sprintID' => 5,
                'projectID' => 4,
                'userID' => 4,
                'assigneeID' => 5,
                'assignerID' => 4,
                'priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('issues')->insert($issues);

        // Comments - 5 entries linked to issues
        $comments = [
            [
                'value' => 'I\'ve completed the login form UI, working on the backend now.',
                'userID' => 2,
                'issueID' => 1,
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8)
            ],
            [
                'value' => 'Great progress! Don\'t forget to include email validation.',
                'userID' => 1,
                'issueID' => 1,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7)
            ],
            [
                'value' => 'Should we use a tab-based navigation or a drawer?',
                'userID' => 4,
                'issueID' => 3,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4)
            ],
            [
                'value' => 'I need product images to complete this design. Where can I get them?',
                'userID' => 3,
                'issueID' => 4,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            [
                'value' => 'API documentation should follow OpenAPI 3.0 standards.',
                'userID' => 5,
                'issueID' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('comments')->insert($comments);

        // Mentions - 5 entries with user and email references
        $mentions = [
            [
                'project_id' => 1,
                'mentioning_user_id' => 2,
                'mentioned_user_id' => 1,
                'mentioned_email' => null,
                'message' => '@admin, can you review the auth implementation?',
                'read' => true,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6)
            ],
            [
                'project_id' => 2,
                'mentioning_user_id' => 4,
                'mentioned_user_id' => 2,
                'mentioned_email' => null,
                'message' => '@johndoe, I need clarification on the navigation requirements.',
                'read' => true,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3)
            ],
            [
                'project_id' => 3,
                'mentioning_user_id' => 3,
                'mentioned_user_id' => 5,
                'mentioned_email' => null,
                'message' => '@alicegreen, can you help with testing the new design?',
                'read' => false,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            [
                'project_id' => 4,
                'mentioning_user_id' => 5,
                'mentioned_user_id' => null,
                'mentioned_email' => 'contractor@example.com',
                'message' => '@contractor@example.com, please review the API specifications.',
                'read' => false,
                'created_at' => Carbon::now()->subHours(12),
                'updated_at' => Carbon::now()->subHours(12)
            ],
            [
                'project_id' => 1,
                'mentioning_user_id' => 1,
                'mentioned_user_id' => 3,
                'mentioned_email' => null,
                'message' => '@janedoe, the product model needs frontend integration.',
                'read' => false,
                'created_at' => Carbon::now()->subHours(6),
                'updated_at' => Carbon::now()->subHours(6)
            ]
        ];

        DB::table('mentions')->insert($mentions);

        // Attachments - 5 entries linked to issues and projects
        $attachments = [
            [
                'name' => 'auth-flow.png',
                'path' => 'attachments/auth-flow.png',
                'mime_type' => 'image/png',
                'size' => 1024,
                'projectId' => 1,
                'issue_id' => 1,
                'user_id' => 2,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7)
            ],
            [
                'name' => 'product-schema.pdf',
                'path' => 'attachments/product-schema.pdf',
                'mime_type' => 'application/pdf',
                'size' => 2048,
                'projectId' => 1,
                'issue_id' => 2,
                'user_id' => 3,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3)
            ],
            [
                'name' => 'navigation-prototype.mp4',
                'path' => 'attachments/navigation-prototype.mp4',
                'mime_type' => 'video/mp4',
                'size' => 8192,
                'projectId' => 2,
                'issue_id' => 3,
                'user_id' => 4,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            [
                'name' => 'homepage-wireframe.sketch',
                'path' => 'attachments/homepage-wireframe.sketch',
                'mime_type' => 'application/octet-stream',
                'size' => 4096,
                'projectId' => 3,
                'issue_id' => 4,
                'user_id' => 3,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ],
            [
                'name' => 'api-specs.json',
                'path' => 'attachments/api-specs.json',
                'mime_type' => 'application/json',
                'size' => 512,
                'projectId' => 4,
                'issue_id' => 5,
                'user_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('attachments')->insert($attachments);

        // Invitations - 5 entries with project relationships
        $invitations = [
            [
                'email' => 'pending1@example.com',
                'username' => 'pending_user1',
                'project_id' => 1,
                'user_id' => null,
                'token' => Str::random(32),
                'accepted' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'pending2@example.com',
                'username' => null,
                'project_id' => 2,
                'user_id' => null,
                'token' => Str::random(32),
                'accepted' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'jane@example.com',
                'username' => 'janedoe',
                'project_id' => 2,
                'user_id' => 3,
                'token' => Str::random(32),
                'accepted' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2)
            ],
            [
                'email' => 'bob@example.com',
                'username' => 'bobsmith',
                'project_id' => 5,
                'user_id' => 4,
                'token' => Str::random(32),
                'accepted' => true,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(1)
            ],
            [
                'email' => 'alice@example.com',
                'username' => 'alicegreen',
                'project_id' => 3,
                'user_id' => 5,
                'token' => Str::random(32),
                'accepted' => false,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3)
            ]
        ];

        DB::table('invitations')->insert($invitations);

        // Chats - 5 entries linked to issues
        $chats = [
            [
                'issue_id' => 1,
                'user_id' => 1,
                'message' => 'Authentication system is looking good. Any blockers?',
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6)
            ],
            [
                'issue_id' => 1,
                'user_id' => 2,
                'message' => 'No blockers, just working on password reset functionality.',
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6)
            ],
            [
                'issue_id' => 2,
                'user_id' => 3,
                'message' => 'Product model schema is ready for review.',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ],
            [
                'issue_id' => 3,
                'user_id' => 4,
                'message' => 'Navigation prototype is complete. Please test on different devices.',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            [
                'issue_id' => 5,
                'user_id' => 5,
                'message' => 'Starting work on user management endpoints first.',
                'created_at' => Carbon::now()->subHours(4),
                'updated_at' => Carbon::now()->subHours(4)
            ]
        ];

        DB::table('chats')->insert($chats);
    }
}