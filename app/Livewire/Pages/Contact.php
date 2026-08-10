<?php

namespace App\Livewire\Pages;

use App\Models\ContactMessage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class Contact extends Component
{
    #[Validate('required|min:2|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('nullable|min:2|max:255')]
    public string $subject = '';

    #[Validate('required|min:10|max:5000')]
    public string $message = '';

    public string $website = '';

    public bool $sent = false;

    public function submit(): void
    {
        if (! empty($this->website)) {
            $this->sent = true;
            $this->reset(['name', 'email', 'subject', 'message', 'website']);

            return;
        }

        $this->validate();

        ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject ?: null,
            'message' => $this->message,
            'is_read' => false,
        ]);

        $this->sent = true;
        $this->reset(['name', 'email', 'subject', 'message', 'website']);
    }

    public function render()
    {
        return view('livewire.pages.contact')
            ->title(__('Contact'));
    }
}
