<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AccountSettings extends Component
{
    use LivewireAlert;

    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $messages = [];

    public function rules()
    {
        return [
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers() 
                ->symbols()
            ],
            'new_password_confirmation' => ['required'],
        ];
    }

    public function mount()
    {
        $this->messages = [
            'current_password.required' => __('header.pwd_validation.current_password.required'),
            'current_password.current_password' => __('header.pwd_validation.current_password.current_password'),
            'new_password.required' => __('header.pwd_validation.new_password.required'),
            'new_password.confirmed' => __('header.pwd_validation.new_password.confirmed'),
            'new_password.min' => __('header.pwd_validation.new_password.min'),
            'new_password.regex' => __('header.pwd_validation.new_password.regex'),
            'new_password_confirmation.required' => __('header.pwd_validation.new_password_confirmation.required'),
        ];
    } 

    public function updatePassword()
    {
        // Validate all fields at once with complete validation rules
        $validatedData = $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ],
            'new_password_confirmation' => ['required'],
        ], $this->messages);

        // Update password
        $user = Auth::user();
        $user->password = bcrypt($this->new_password);
        $user->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->alert('success', __('header.pwd_updated_success'));
    }

    public function render()
    {
        return view('livewire.account-settings');
    }
}
