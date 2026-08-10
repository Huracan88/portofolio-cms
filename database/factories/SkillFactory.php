<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    private static array $skills = [
        ['en' => 'PHP', 'es' => 'PHP', 'icon' => 'php', 'group' => 'backend', 'level' => 5],
        ['en' => 'Laravel', 'es' => 'Laravel', 'icon' => 'laravel', 'group' => 'backend', 'level' => 5],
        ['en' => 'Livewire', 'es' => 'Livewire', 'icon' => 'laravel', 'group' => 'backend', 'level' => 5],
        ['en' => 'WordPress', 'es' => 'WordPress', 'icon' => 'wordpress', 'group' => 'backend', 'level' => 4],
        ['en' => 'JavaScript ES6+', 'es' => 'JavaScript ES6+', 'icon' => 'javascript', 'group' => 'frontend', 'level' => 4],
        ['en' => 'TypeScript', 'es' => 'TypeScript', 'icon' => 'typescript', 'group' => 'frontend', 'level' => 3],
        ['en' => 'Tailwind CSS', 'es' => 'Tailwind CSS', 'icon' => 'tailwindcss', 'group' => 'frontend', 'level' => 5],
        ['en' => 'Bootstrap', 'es' => 'Bootstrap', 'icon' => 'bootstrap', 'group' => 'frontend', 'level' => 4],
        ['en' => 'Alpine.js', 'es' => 'Alpine.js', 'icon' => 'alpinejs', 'group' => 'frontend', 'level' => 4],
        ['en' => 'jQuery', 'es' => 'jQuery', 'icon' => 'jquery', 'group' => 'frontend', 'level' => 4],
        ['en' => 'Flutter', 'es' => 'Flutter', 'icon' => 'flutter', 'group' => 'mobile', 'level' => 4],
        ['en' => 'MySQL / MariaDB', 'es' => 'MySQL / MariaDB', 'icon' => 'mysql', 'group' => 'database', 'level' => 5],
        ['en' => 'PostgreSQL', 'es' => 'PostgreSQL', 'icon' => 'postgresql', 'group' => 'database', 'level' => 3],
        ['en' => 'Microsoft SQL Server', 'es' => 'Microsoft SQL Server', 'icon' => 'microsoftsqlserver', 'group' => 'database', 'level' => 3],
        ['en' => 'Oracle DB', 'es' => 'Oracle DB', 'icon' => 'oracle', 'group' => 'database', 'level' => 3],
        ['en' => 'Linux', 'es' => 'Linux', 'icon' => 'linux', 'group' => 'devops', 'level' => 5],
        ['en' => 'Docker', 'es' => 'Docker', 'icon' => 'docker', 'group' => 'devops', 'level' => 4],
        ['en' => 'Nginx', 'es' => 'Nginx', 'icon' => 'nginx', 'group' => 'devops', 'level' => 4],
        ['en' => 'Apache', 'es' => 'Apache', 'icon' => 'apache', 'group' => 'devops', 'level' => 4],
        ['en' => 'Git & GitHub', 'es' => 'Git & GitHub', 'icon' => 'github', 'group' => 'devops', 'level' => 5],
        ['en' => 'Windows Server', 'es' => 'Windows Server', 'icon' => 'windows8', 'group' => 'devops', 'level' => 3],
        ['en' => 'REST APIs', 'es' => 'APIs REST', 'icon' => null, 'group' => 'tools', 'level' => 5],
        ['en' => 'Advanced Excel', 'es' => 'Excel Avanzado', 'icon' => 'excel', 'group' => 'tools', 'level' => 4],
    ];

    private static int $skillIndex = 0;

    public function definition(): array
    {
        $skill = self::$skills[self::$skillIndex % count(self::$skills)];
        self::$skillIndex++;

        return [
            'name_es' => $skill['es'],
            'name_en' => $skill['en'],
            'level' => $skill['level'],
            'sort_order' => $this->faker->unique()->numberBetween(0, 200),
            'is_visible' => true,
            'icon' => $skill['icon'],
            'group' => $skill['group'],
        ];
    }
}
