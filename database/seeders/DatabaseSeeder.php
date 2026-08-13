<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            SkillSeeder::class,
            ProfileSeeder::class,
            EducationSeeder::class,
            LanguageSeeder::class,
            ExperienceSeeder::class,
        ]);

        if (app()->environment('local', 'testing')) {
            $admin = User::updateOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Admin',
                    'password' => 'password',
                    'email_verified_at' => now(),
                ]
            );
            $admin->assignRole('super_admin', 'admin');

            $editor = User::updateOrCreate(
                ['email' => 'editor@example.com'],
                [
                    'name' => 'Editor',
                    'password' => 'password',
                    'email_verified_at' => now(),
                ]
            );
            $editor->assignRole('editor');
        }

        $this->call([
            ProjectSeeder::class,
            PostSeeder::class,
            ContactMessageSeeder::class,
            MediaSeeder::class,
        ]);
    }
}
