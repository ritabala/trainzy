<?php

namespace App\Livewire;

use Livewire\Component;

class SidebarMenuItem extends Component
{
    public string $title;
    public string $icon;
    public ?string $link = null;
    public bool $hasDropdown = false;
    public array $dropdownItems = [];

    public function mount(
        string $title,
        string $icon,
        ?string $link = null,
        bool $hasDropdown = false,
        array $dropdownItems = []
    ) {
        $this->title = $title;
        $this->icon = $icon;
        $this->link = $link;
        $this->hasDropdown = $hasDropdown;
        $this->dropdownItems = $dropdownItems;
    }

    public function render()
    {
        return view('livewire.sidebar-menu-item');
    }

    public function isActive(): bool
    {
        $currentRoute = request()->route()?->getName();

        // Direct link
        if ($this->link) {
            if (strpos($this->link, '.') !== false) {
                // Compare only the top-level group (e.g., "staff" in "staff.index")
                $linkSegments = explode('.', $this->link);
                $currentSegments = explode('.', $currentRoute);

                return $linkSegments[0] === $currentSegments[0];
            }

            // Exact match for route names without dots
            return $currentRoute === $this->link;
        }

        // Dropdown check
        if ($this->hasDropdown) {
            foreach ($this->dropdownItems as $item) {
                if ($this->isDropdownItemActive($item)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isDropdownItemActive($item): bool
    {
        $currentRoute = request()->route()?->getName();

        if (!$currentRoute || !isset($item['link'])) {
            return false;
        }

        $link = $item['link'];

        // Map dropdown parents to child route prefixes
        $customDropdownMappings = [
            'settings.' => [
                'settings.products.',
                'settings.taxes.',
                'settings.body_metrics.',
                'settings.staff_types.',
                'settings.billing.',
            ],
            'finance' => [
                'invoices.',
                'payments.',
            ],
            'activity_classes' => [
                'activity-classes.',
            ],
            'memberships' => [
                'memberships.',
            ],
            'attendance.' => [
                'attendance.members.',
                'attendance.staff.',
                'attendance.qr-codes.',
                'attendance.scan.'
            ]
        ];

        // Handle dropdown parent highlighting (keep dropdown open)
        foreach ($customDropdownMappings as $parentKey => $childrenPrefixes) {
            if ($link === $parentKey) {
                foreach ($childrenPrefixes as $prefix) {
                    if (str_starts_with($currentRoute, $prefix)) {
                        return true;
                    }
                }
            }
        }

        // Handle dropdown children highlighting (child stays active)
        foreach ($customDropdownMappings as $parentKey => $childrenPrefixes) {
            foreach ($childrenPrefixes as $prefix) {
                if (str_starts_with($link, $prefix) && str_starts_with($currentRoute, $prefix)) {
                    return true;
                }
            }
        }

        // Special case for reports: exact match only
        if (str_starts_with($link, 'reports.')) {
            return $currentRoute === $link;
        }

        // Fallback: highlight if current route starts with item link
        return str_starts_with($currentRoute, $link);
    }
}
