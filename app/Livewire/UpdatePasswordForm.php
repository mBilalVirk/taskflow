<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UpdatePasswordForm extends Component
{
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    public function updatePassword()
    {
        $user = auth()->user();

        $this->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check if entered current password matches database records
        if (! Hash::check($this->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => __('The provided password does not match your current password.'),
            ]);
        }

        // Update with the new hashed password
        $user->update([
            'password' => Hash::make($this->password),
        ]);

        // Clear out fields safely
        $this->reset(['current_password', 'password', 'password_confirmation']);

        // Dispatch success toast notification event
        $this->dispatch('password-updated', message: 'Your password has been securely changed.');
    }

    public function render()
    {
        return view('livewire.update-password-form');
    }
}