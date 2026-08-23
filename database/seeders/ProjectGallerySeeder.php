<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Project;
use App\Models\ProjectGalleryImage;
use Illuminate\Database\Seeder;

class ProjectGallerySeeder extends Seeder
{
    public function run(): void
    {
        $media = Media::query()->orderBy('id')->get();

        if ($media->isEmpty()) {
            return;
        }

        $projects = Project::query()
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        $captions = [
            ['es' => 'Vista general del sistema', 'en' => 'System overview'],
            ['es' => 'Detalle de la interfaz', 'en' => 'Interface detail'],
            ['es' => 'Flujo de trabajo principal', 'en' => 'Main workflow'],
        ];

        $mediaCount = $media->count();

        foreach ($projects as $projectIndex => $project) {
            $offset = ($projectIndex * 3) % $mediaCount;

            $assigned = collect(range(0, 2))
                ->map(fn (int $slot): Media => $media->get(($offset + $slot) % $mediaCount))
                ->values();

            foreach ($assigned as $slot => $item) {
                ProjectGalleryImage::firstOrCreate(
                    ['project_id' => $project->id, 'media_id' => $item->id],
                    [
                        'caption_es' => $captions[$slot]['es'],
                        'caption_en' => $captions[$slot]['en'],
                        'sort_order' => $slot + 1,
                    ]
                );
            }
        }
    }
}
