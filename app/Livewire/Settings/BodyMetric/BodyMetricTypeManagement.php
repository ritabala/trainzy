<?php

namespace App\Livewire\Settings\BodyMetric;

use Livewire\Component;

class BodyMetricTypeManagement extends Component
{
    public function addBodyMetricType()
    {
        return redirect()->route('settings.body_metrics.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('livewire.settings.body-metric.body-metric-type-management');
    }
} 