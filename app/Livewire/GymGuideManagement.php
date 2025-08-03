<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GymGuide;

class GymGuideManagement extends Component
{
    public $guides;
    public $showModal = false;
    public $editId = null;
    public $confirmingDeleteId = null;
    public $title;
    public $description;
    public $icon;
    public $link;

    protected $listeners = [
        'guideSaved' => 'refreshGuides',
        'closeModal' => 'closeModal',
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'icon' => 'nullable|string',
        'link' => 'nullable|url',
    ];

    public function mount()
    {
        $this->refreshGuides();
    }

    public function refreshGuides()
    {
        $this->guides = GymGuide::orderByDesc('id')->get();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->editId = null;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $guide = GymGuide::findOrFail($id);
        $this->editId = $id;
        $this->title = $guide->title;
        $this->description = $guide->description;
        $this->icon = $guide->icon;
        $this->link = $guide->link;
        $this->showModal = true;
    }

    public function saveGuide()
    {
        $this->validate();
        if ($this->editId) {
            $guide = GymGuide::findOrFail($this->editId);
            $guide->update([
                'title' => $this->title,
                'description' => $this->description,
                'icon' => $this->icon,
                'link' => $this->link,
            ]);
        } else {
            GymGuide::create([
                'title' => $this->title,
                'description' => $this->description,
                'icon' => $this->icon,
                'link' => $this->link,
            ]);
        }
        $this->showModal = false;
        $this->refreshGuides();
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function deleteGuide()
    {
        if ($this->confirmingDeleteId) {
            GymGuide::find($this->confirmingDeleteId)?->delete();
            $this->confirmingDeleteId = null;
            $this->refreshGuides();
        }
    }

    private function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->icon = '';
        $this->link = '';
        $this->editId = null;
        $this->confirmingDeleteId = null;
    }

    public function render()
    {
        return view('livewire.gym-guide-management');
    }
}
