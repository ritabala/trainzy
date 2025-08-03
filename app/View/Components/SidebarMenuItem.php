<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class SidebarMenuItem extends Component
{
    public $title;
    public $icon;
    public $link;
    public $hasDropdown;
    public $dropdownItems;
    public $isActive;
    public $isDropdownItemActive;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $title = null,
        $icon = null,
        $link = null,
        $hasDropdown = false,
        $dropdownItems = []
    ) {
        $this->title = $title;
        $this->icon = $icon;
        $this->link = $link;
        $this->hasDropdown = $hasDropdown;
        $this->dropdownItems = $dropdownItems;
        $this->isActive = $this->checkIfActive();
        $this->isDropdownItemActive = $this->checkIfDropdownItemActive();
    }

    /**
     * Check if the current menu item is active
     */
    private function checkIfActive()
    {
        if (!$this->link) {
            return false;
        }

        $currentRoute = Route::currentRouteName();
        return $currentRoute === $this->link;
    }

    /**
     * Check if any dropdown item is active
     */
    private function checkIfDropdownItemActive()
    {
        if (!$this->hasDropdown || empty($this->dropdownItems)) {
            return false;
        }

        $currentRoute = Route::currentRouteName();
        return collect($this->dropdownItems)->contains(function ($item) use ($currentRoute) {
            return $item['link'] === $currentRoute;
        });
    }

    /**
     * Check if a specific dropdown item is active
     */
    public function isDropdownItemActive($item)
    {
        $currentRoute = Route::currentRouteName();
        return $item['link'] === $currentRoute;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.sidebar-menu-item');
    }
}
