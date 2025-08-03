<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class RecordAttendance extends Component
{
    public $token;
    public $user;
    public $status = '';
    public $message = '';

    public function mount($token)
    {
        $this->token = $token;
        $this->user = User::where('scan_code', $token)->first();
        
        if (!$this->user) {
            return redirect()->route('attendance.qr-codes')
                ->with('error', __('attendance.invalid_qr_code'));
        }

        try {
            $role = $this->user->roles->first()->name;
            $roleWithoutGym = explode('-', $role)[0];

            // Record attendance
            Attendance::create([
                'user_id' => $this->user->id,
                'gym_id' => gym()->id,
                'role_type' => $roleWithoutGym,
                'status' => 'present',
                'check_in_at' => Carbon::now(),
                'method' => 'scanner',
                'notes' => 'Attendance recorded by QR code scanner'
            ]);

            // Redirect based on role
            if (in_array($role, ['staff-' . gym()->id, 'admin-' . gym()->id])) {
                return redirect()->route('attendance.staff.index')
                    ->with('message', __('attendance.recorded_successfully', ['name' => $this->user->name]));
            } else {
                return redirect()->route('attendance.members.index')
                    ->with('message', __('attendance.recorded_successfully', ['name' => $this->user->name]));
            }
        } catch (\Exception $e) {
            return redirect()->route('attendance.qr-codes')
                ->with('error', __('attendance.recording_failed'. $e->getMessage()));
        }
    }

    public function render()
    {
        return null;
    }
} 