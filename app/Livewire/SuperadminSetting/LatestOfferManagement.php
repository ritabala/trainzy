<?php

namespace App\Livewire\SuperadminSetting;

use Livewire\Component;
use App\Models\LatestOffer;

class LatestOfferManagement extends Component
{
    public $offers;
    public $showModal = false;
    public $editId = null;
    public $confirmingDeleteId = null;

    protected $listeners = [
        'offerSaved' => 'refreshOffers',
        'closeModal' => 'closeModal',
    ];

    public function mount()
    {
        $this->refreshOffers();
    }

    public function refreshOffers()
    {
        $this->offers = LatestOffer::orderByDesc('id')->get();
    }

    public function openCreate()
    {
        $this->editId = null;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $this->editId = $id;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function deleteOffer()
    {
        if ($this->confirmingDeleteId) {
            LatestOffer::find($this->confirmingDeleteId)?->delete();
            $this->confirmingDeleteId = null;
            $this->refreshOffers();
        }
    }

    public function render()
    {
        return view('livewire.superadmin-setting.latest-offer-management');
    }
}
