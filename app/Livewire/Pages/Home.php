<?php

namespace App\Livewire\Pages;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Home extends Component
{
    public function render()
    {
        $profile = Profile::getSingleton();

        return view('livewire.pages.home', [
            'profile' => $profile,
            'skills' => Skill::where('is_visible', true)->orderBy('sort_order')->get()->groupBy('group'),
            'experiences' => Experience::orderBy('sort_order')->get(),
            'featuredProjects' => Project::with('skills')->where('is_visible', true)->where('is_featured', true)->orderBy('sort_order')->get(),
            'educations' => Education::orderBy('sort_order')->get(),
            'languages' => Language::orderBy('sort_order')->get(),
        ])->title('Andrés Pinto — Fullstack Developer');
    }
}
