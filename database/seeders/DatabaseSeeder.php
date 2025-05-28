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
        DB::table('attachments')->delete();
        DB::table('mentions')->delete();
        DB::table('comments')->delete();
        DB::table('issues')->delete();
        DB::table('statuses')->delete();
        DB::table('sprints')->delete();
        DB::table('projects')->delete();
        DB::table('users')->delete();
        DB::table('invitations')->delete();

        // Reset sequences for PostgreSQL
        $tables = ['users', 'projects', 'sprints', 'statuses', 'issues', 'comments', 'mentions', 'attachments'];
        foreach ($tables as $table) {
            DB::statement("ALTER SEQUENCE {$table}_id_seq RESTART WITH 1");
        }

        // Users
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

        // Projects
        $projects = [
            [
                'name' => 'E-commerce Platform',
                'key' => 'ECOM',
                'accessibility' => 'private',
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
                'accessibility' => 'private',
                'ownerID' => 3,
                'teamID' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('projects')->insert($projects);

        // Statuses
        $statuses = [
            ['name' => 'To Do', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'In Progress', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'In Review', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Done', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Blocked', 'created_at' => now(), 'updated_at' => now()]
        ];

        DB::table('statuses')->insert($statuses);

        // Sprints
        $sprints = [
            [
                'name' => 'Sprint 1',
                'startDate' => Carbon::now()->subDays(14),
                'endDate' => Carbon::now(),
                'sprintGoal' => 'Complete initial setup and user authentication',
                'ownerID' => 1,
                'project_id' => 1, // Add project_id reference
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sprint 2',
                'startDate' => Carbon::now(),
                'endDate' => Carbon::now()->addDays(14),
                'sprintGoal' => 'Implement product catalog and shopping cart',
                'ownerID' => 2,
                'project_id' => 1, // Same project as Sprint 1
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mobile App Sprint 1',
                'startDate' => Carbon::now()->subDays(7),
                'endDate' => Carbon::now()->addDays(7),
                'sprintGoal' => 'Build basic app structure and navigation',
                'ownerID' => 3,
                'project_id' => 2, // Different project for mobile app
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('sprints')->insert($sprints);

        // Issues
        $issues = [
            // E-commerce Platform issues
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
                'title' => 'Implement shopping cart',
                'description' => 'Users should be able to add/remove items and see cart total',
                'startDate' => null,
                'endDate' => null,
                'duration' => null,
                'statusID' => 1, // To Do
                'sprintID' => 2,
                'projectID' => 1,
                'userID' => 1,
                'assigneeID' => null,
                'assignerID' => 1,
                'priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Mobile App issues
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
                'title' => 'Implement API integration',
                'description' => 'Connect app to backend API for data fetching',
                'startDate' => null,
                'endDate' => null,
                'duration' => null,
                'statusID' => 1, // To Do
                'sprintID' => 3,
                'projectID' => 2,
                'userID' => 2,
                'assigneeID' => 5,
                'assignerID' => 2,
                'priority' => 'low',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Website Redesign issues
            [
                'title' => 'Create new homepage design',
                'description' => 'Design modern homepage with hero section and featured content',
                'startDate' => Carbon::now()->subDays(3),
                'endDate' => Carbon::now()->addDays(4),
                'duration' => 7,
                'statusID' => 2, // In Progress
                'sprintID' => null,
                'projectID' => 3,
                'userID' => 3,
                'assigneeID' => 3,
                'assignerID' => 3,
                'priority' => 'high',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('issues')->insert($issues);

        // Comments
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
                'issueID' => 4,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4)
            ],
            [
                'value' => 'Let\'s go with bottom tabs for the main sections and a drawer for secondary options.',
                'userID' => 2,
                'issueID' => 4,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3)
            ],
            [
                'value' => 'I need product images to complete this design. Where can I get them?',
                'userID' => 3,
                'issueID' => 6,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ]
        ];

        DB::table('comments')->insert($comments);

        // Mentions
        $mentions = [
            [
                'project_id' => 1,
                'mentioning_user_id' => 2,
                'mentioned_user_id' => 1,
                'message' => '@admin, can you review the auth implementation?',
                'read' => true,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6)
            ],
            [
                'project_id' => 2,
                'mentioning_user_id' => 4,
                'mentioned_user_id' => 2,
                'message' => '@johndoe, I need clarification on the navigation requirements.',
                'read' => true,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3)
            ],
            [
                'project_id' => 3,
                'mentioning_user_id' => 3,
                'mentioned_user_id' => 5,
                'message' => '@alicegreen, can you help with testing the new design?',
                'read' => false,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ]
        ];

        DB::table('mentions')->insert($mentions);

        // Attachments
        $attachments = [
            [
                'name' => 'auth-flow.png',
                'path' => 'attachments/auth-flow.png',
                'mime_type' => 'image/png',
                'size' => 1024,
                'projectId' => 1,
                'issue_id' => 1, // Related to "Setup authentication system" issue
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
                'issue_id' => 2, // Related to "Create product model" issue
                'user_id' => 3,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3)
            ],
            [
                'name' => 'homepage-wireframe.sketch',
                'path' => 'attachments/homepage-wireframe.sketch',
                'mime_type' => 'application/octet-stream',
                'size' => 4096,
                'projectId' => 3,
                'issue_id' => 6, // Related to "Create new homepage design" issue
                'user_id' => 3,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ],
            [
                'name' => 'navigation-prototype.mp4',
                'path' => 'attachments/navigation-prototype.mp4',
                'mime_type' => 'video/mp4',
                'size' => 8192,
                'projectId' => 2,
                'issue_id' => 4, // Related to "Design app navigation" issue
                'user_id' => 4,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            [
                'name' => 'api-specs.json',
                'path' => 'attachments/api-specs.json',
                'mime_type' => 'application/json',
                'size' => 512,
                'projectId' => 2,
                'issue_id' => 5, // Related to "Implement API integration" issue
                'user_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('attachments')->insert($attachments);

        $invitations = [
            // Pending invitations (accepted = false)
            [
                'email' => 'pending1@example.com',
                'username' => 'pending_user1',
                'project_id' => 1,
                'token' => Str::random(32),
                'accepted' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'pending2@example.com',
                'username' => null, // No username set yet
                'project_id' => 2,
                'token' => Str::random(32),
                'accepted' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Accepted invitations (accepted = true)
            [
                'email' => 'janedoe@example.com', // Matches existing user
                'username' => 'janedoe',
                'project_id' => 1,
                'token' => Str::random(32),
                'accepted' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2)
            ],
            [
                'email' => 'bobsmith@example.com', // Matches existing user
                'username' => 'bobsmith',
                'project_id' => 3,
                'token' => Str::random(32),
                'accepted' => true,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(1)
            ],

            // Another pending invitation
            [
                'email' => 'newuser@example.com',
                'username' => null,
                'project_id' => 2,
                'token' => Str::random(32),
                'accepted' => false,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3)
            ]
        ];

        DB::table('invitations')->insert($invitations);
    }
}