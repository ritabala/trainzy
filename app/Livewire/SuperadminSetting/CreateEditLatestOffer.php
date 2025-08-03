<?php

namespace App\Livewire\SuperadminSetting;

use Livewire\Component;
use App\Models\LatestOffer;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CreateEditLatestOffer extends Component
{
    use LivewireAlert;
    public $promotionId;
    public $type = '';
    public $title = '';
    public $description = '';
    public $expires_at = '';
    public $trainer_count = '';
    public $savings = '';

    protected $rules = [
        'type' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'expires_at' => 'nullable|date',
        'trainer_count' => 'nullable|integer',
        'savings' => 'nullable|numeric',
    ];

    public function mount($promotionId = null)
    {
        $this->promotionId = $promotionId;
        if ($promotionId) {
            $offer = LatestOffer::find($promotionId);
            if ($offer) {
                $this->type = $offer->type;
                $this->title = $offer->title;
                $this->description = $offer->description;
                $this->expires_at = $offer->expires_at ? $offer->expires_at->format('Y-m-d\TH:i') : '';
                $this->trainer_count = $offer->trainer_count;
                $this->savings = $offer->savings;
            }
        }
    }

    public function save()
    {
        $this->validate();
        LatestOffer::updateOrCreate(
            ['id' => $this->promotionId],
            [
                'type' => $this->type,
                'title' => $this->title,
                'description' => $this->description,
                'expires_at' => $this->expires_at,
                'trainer_count' => $this->trainer_count,
                'savings' => $this->savings,
            ]
        );
        $this->resetValidation();
        $this->dispatch('offerSaved');
        $this->dispatch('closeModal');
        $this->alert('success', 'Offer saved successfully');
    }

    public function render()
    {
        return view('livewire.superadmin-setting.create-edit-latest-offer');
    }
}
