<?php

namespace App\Livewire;

use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;

class MediaPicker extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $collection = null;

    public string $event = 'media-selected';

    public function mount(): void
    {
        $this->authorize('viewAny', Media::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function pick(int $id): void
    {
        $this->authorize('viewAny', Media::class);

        $media = Media::find($id);

        if (! $media) {
            return;
        }

        $this->dispatch($this->event, url: $media->url, id: $media->id);
    }

    public function render()
    {
        return view('livewire.media-picker', [
            'media' => Media::query()
                ->when($this->collection, fn ($query): mixed => $query->where('collection', $this->collection))
                ->when($this->search !== '', fn ($query): mixed => $query->where('original_name', 'like', '%'.$this->search.'%'))
                ->orderByDesc('created_at')
                ->paginate(12),
        ]);
    }
}
