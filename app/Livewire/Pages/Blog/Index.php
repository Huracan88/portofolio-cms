<?php

namespace App\Livewire\Pages\Blog;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'components.pagination';
    }

    public function render()
    {
        return view('livewire.pages.blog.index', [
            'posts' => Post::query()
                ->where('is_published', true)
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->paginate(6),
        ])->title(__('Blog'));
    }
}
