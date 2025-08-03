<?php

namespace App\Livewire\SuperadminSetting;

use Livewire\Component;
use App\Models\SidebarPromotion;

class SidebarPromotionManagement extends Component
{
    public $showModal = false;
    public $editId = null;
    public $confirmingDeleteId = null;

    protected $listeners = [
        'promotionSaved' => 'closeModal',
    ];

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
        $this->editId = null;
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function deletePromotion()
    {
        SidebarPromotion::find($this->confirmingDeleteId)?->delete();
        $this->confirmingDeleteId = null;
    }

    public function render()
    {
        $promotions = SidebarPromotion::latest()->get();
        return view('livewire.superadmin-setting.sidebar-promotion-management', [
            'promotions' => $promotions,
        ]);
    }
}
