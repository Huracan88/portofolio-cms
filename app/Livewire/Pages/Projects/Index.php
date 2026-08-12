<?php

namespace App\Livewire\Pages\Projects;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'sector')]
    public ?string $selectedSector = null;

    public function setSector(?string $sector): void
    {
        $this->selectedSector = $sector;
        $this->resetPage();
    }

    public function paginationView(): string
    {
        return 'components.pagination';
    }

    public function render()
    {
        $query = Project::where('is_visible', true)->orderBy('sort_order');

        if ($this->selectedSector) {
            $query->where('sector', $this->selectedSector);
        }

        return view('livewire.pages.projects.index', [
            'projects' => $query->paginate(6),
        ])->title(__('Projects'));
    }
}
