<?php

namespace App\Livewire\SuperadminSetting;

use Livewire\Component;
use App\Models\SidebarPromotion;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CreateEditSidebarPromotion extends Component
{
    use LivewireAlert;
    public $promotionId;
    public $type = '';
    public $title = '';
    public $description = '';
    public $expires_in = '';
    public $trainer_count = '';
    public $savings = '';

    protected function rules()
    {
        return [
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'expires_in' => 'nullable|string|max:255',
            'trainer_count' => 'nullable|integer',
            'savings' => 'nullable|string|max:255',
        ];
    }

    public function mount($promotionId = null)
    {
        $this->promotionId = $promotionId;
        if ($promotionId) {
            $promotion = SidebarPromotion::findOrFail($promotionId);
            $this->type = $promotion->type;
            $this->title = $promotion->title;
            $this->description = $promotion->description;
            $this->expires_in = $promotion->expires_in;
            $this->trainer_count = $promotion->trainer_count;
            $this->savings = $promotion->savings;
        }
    }

    public function save()
    {
        $this->validate();
        SidebarPromotion::updateOrCreate(
            ['id' => $this->promotionId],
            [
                'type' => $this->type,
                'title' => $this->title,
                'description' => $this->description,
                'expires_in' => $this->expires_in,
                'trainer_count' => $this->trainer_count,
                'savings' => $this->savings,
            ]
        );
        $this->dispatch('promotionSaved');
        $this->alert('success', 'Promotion saved successfully');
    }

    public function render()
    {
        return view('livewire.superadmin-setting.create-edit-sidebar-promotion');
    }
}
