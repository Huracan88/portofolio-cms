<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            ['name_es' => 'PHP', 'name_en' => 'PHP', 'level' => 5, 'sort_order' => 1, 'icon' => 'php', 'group' => 'backend'],
            ['name_es' => 'Laravel', 'name_en' => 'Laravel', 'level' => 5, 'sort_order' => 2, 'icon' => 'laravel', 'group' => 'backend'],
            ['name_es' => 'Livewire', 'name_en' => 'Livewire', 'level' => 5, 'sort_order' => 3, 'icon' => 'laravel', 'group' => 'backend'],
            ['name_es' => 'WordPress', 'name_en' => 'WordPress', 'level' => 4, 'sort_order' => 4, 'icon' => 'wordpress', 'group' => 'backend'],
            ['name_es' => 'JavaScript ES6+', 'name_en' => 'JavaScript ES6+', 'level' => 4, 'sort_order' => 10, 'icon' => 'javascript', 'group' => 'frontend'],
            ['name_es' => 'TypeScript', 'name_en' => 'TypeScript', 'level' => 3, 'sort_order' => 11, 'icon' => 'typescript', 'group' => 'frontend'],
            ['name_es' => 'Tailwind CSS', 'name_en' => 'Tailwind CSS', 'level' => 5, 'sort_order' => 12, 'icon' => 'tailwindcss', 'group' => 'frontend'],
            ['name_es' => 'Bootstrap', 'name_en' => 'Bootstrap', 'level' => 4, 'sort_order' => 13, 'icon' => 'bootstrap', 'group' => 'frontend'],
            ['name_es' => 'Alpine.js', 'name_en' => 'Alpine.js', 'level' => 4, 'sort_order' => 14, 'icon' => 'alpinejs', 'group' => 'frontend'],
            ['name_es' => 'jQuery', 'name_en' => 'jQuery', 'level' => 4, 'sort_order' => 15, 'icon' => 'jquery', 'group' => 'frontend'],
            ['name_es' => 'Flutter', 'name_en' => 'Flutter', 'level' => 4, 'sort_order' => 20, 'icon' => 'flutter', 'group' => 'mobile'],
            ['name_es' => 'MySQL / MariaDB', 'name_en' => 'MySQL / MariaDB', 'level' => 5, 'sort_order' => 25, 'icon' => 'mysql', 'group' => 'database'],
            ['name_es' => 'PostgreSQL', 'name_en' => 'PostgreSQL', 'level' => 3, 'sort_order' => 26, 'icon' => 'postgresql', 'group' => 'database'],
            ['name_es' => 'Microsoft SQL Server', 'name_en' => 'Microsoft SQL Server', 'level' => 3, 'sort_order' => 27, 'icon' => 'microsoftsqlserver', 'group' => 'database'],
            ['name_es' => 'Oracle DB', 'name_en' => 'Oracle DB', 'level' => 3, 'sort_order' => 28, 'icon' => 'oracle', 'group' => 'database'],
            ['name_es' => 'Linux', 'name_en' => 'Linux', 'level' => 5, 'sort_order' => 30, 'icon' => 'linux', 'group' => 'devops'],
            ['name_es' => 'Docker', 'name_en' => 'Docker', 'level' => 4, 'sort_order' => 31, 'icon' => 'docker', 'group' => 'devops'],
            ['name_es' => 'Nginx', 'name_en' => 'Nginx', 'level' => 4, 'sort_order' => 32, 'icon' => 'nginx', 'group' => 'devops'],
            ['name_es' => 'Apache', 'name_en' => 'Apache', 'level' => 4, 'sort_order' => 33, 'icon' => 'apache', 'group' => 'devops'],
            ['name_es' => 'Git & GitHub', 'name_en' => 'Git & GitHub', 'level' => 5, 'sort_order' => 34, 'icon' => 'github', 'group' => 'devops'],
            ['name_es' => 'Windows Server', 'name_en' => 'Windows Server', 'level' => 3, 'sort_order' => 35, 'icon' => 'windows8', 'group' => 'devops'],
            ['name_es' => 'APIs REST', 'name_en' => 'REST APIs', 'level' => 5, 'sort_order' => 40, 'icon' => null, 'group' => 'tools'],
            ['name_es' => 'Excel Avanzado', 'name_en' => 'Advanced Excel', 'level' => 4, 'sort_order' => 41, 'icon' => 'excel', 'group' => 'tools'],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['name_en' => $skill['name_en']],
                $skill
            );
        }
    }
}
