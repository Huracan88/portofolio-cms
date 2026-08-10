<?php

namespace App\Livewire\Pages\Projects;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Project $project;

    public function mount(Project $project): void
    {
        abort_unless($project->is_visible, 404);
    }

    public function render()
    {
        return view('livewire.pages.projects.show', [
            'project' => $this->project,
        ])->title($this->project->title);
    }
}
