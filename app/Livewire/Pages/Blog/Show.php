<?php

namespace App\Livewire\Pages\Blog;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Post $post;

    public function mount(Post $post): void
    {
        abort_unless($post->is_published && $post->published_at?->lte(now()), 404);
    }

    public function render()
    {
        return view('livewire.pages.blog.show', [
            'post' => $this->post,
        ])->title($this->post->title);
    }
}
