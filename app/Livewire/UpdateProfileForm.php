<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UpdateProfileForm extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $avatar;
    public $currentAvatar;
    public $phone;
    public $country;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->currentAvatar = $user->avatar; // Assumes an avatar_url accessor or fallback exists
        $this->phone = $user->phone;
        $this->country = $user->country;
    }

    public function updateProfile()
    {
        $user = auth()->user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'],  
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'country' => ['nullable', 'string', 'max:100'],
        ]);

        if ($this->avatar) {
            // Delete old avatar if it exists in storage
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store the new file
            $path = $this->avatar->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $this->name;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->country = $this->country;
        $user->save();

        // Clear file input state variable
        $this->reset('avatar');
        $this->currentAvatar = $user->avatar_url;

        // Dispatch a browser notification alert
        $this->dispatch('profile-updated', message: 'Your profile has been successfully updated.');
    }

    public function render()
    {
        return view('livewire.update-profile-form');
    }
}
