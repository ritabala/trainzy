<?php

namespace App\Livewire\Settings\BodyMetric;

use App\Models\BodyMetricType;
use Livewire\Component;
use Illuminate\Support\Str;

class CreateEditBodyMetricType extends Component
{
    public $name;
    public $slug;
    public $description;
    public $unit;
    public $is_active = true;
    public $display_order;
    public $editingBodyMetricTypeId;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:body_metric_types,slug',
        'description' => 'nullable|string',
        'unit' => 'nullable|string|max:50',
        'is_active' => 'boolean',
        'display_order' => 'required|integer|min:0',
    ];

    public $messages = [];

    public function mount(BodyMetricType $bodyMetricType = null)
    {        
        if ($bodyMetricType && $bodyMetricType->exists) {
            $this->editBodyMetricType($bodyMetricType);
        }

        $this->messages = [
            'name.required' => __('body_metrics.validate_messages.name.required'),
            'name.max' => __('body_metrics.validate_messages.name.max'),
            'slug.required' => __('body_metrics.validate_messages.slug.required'),
            'slug.unique' => __('body_metrics.validate_messages.slug.unique'),
            'unit.max' => __('body_metrics.validate_messages.unit.max'),
            'display_order.required' => __('body_metrics.validate_messages.display_order.required'),
            'display_order.integer' => __('body_metrics.validate_messages.display_order.integer'),
            'display_order.min' => __('body_metrics.validate_messages.display_order.min'),
        ];
    }

    public function editBodyMetricType(BodyMetricType $bodyMetricType)
    {
        $this->isEditing = true;
        $this->editingBodyMetricTypeId = $bodyMetricType->id;
        $this->name = $bodyMetricType->name;
        $this->slug = $bodyMetricType->slug;
        $this->description = $bodyMetricType->description;
        $this->unit = $bodyMetricType->unit;
        $this->is_active = $bodyMetricType->is_active;
        $this->display_order = $bodyMetricType->display_order;
    }

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }

    public function store()
    {
        $this->validate();

        try {
            BodyMetricType::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'unit' => $this->unit,
                'is_active' => $this->is_active,
                'display_order' => $this->display_order,
            ]);

            session()->flash('success', __('body_metrics.body_metric_created_successfully'));
            return $this->redirect(route('settings.body_metrics.index'));
        } catch (\Exception $e) {
            session()->flash('error', __('body_metrics.failed_to_create_body_metric_type'));
            return;
        }
    }

    public function update()
    {
        if (!$this->editingBodyMetricTypeId) {
            session()->flash('error', __('body_metrics.invalid_body_metric_type_id'));
            return;
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:body_metric_types,slug,' . $this->editingBodyMetricTypeId,
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'display_order' => 'required|integer|min:0',
        ]);

        try {
            $bodyMetricType = BodyMetricType::findOrFail($this->editingBodyMetricTypeId);
            $bodyMetricType->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'unit' => $this->unit,
                'is_active' => $this->is_active,
                'display_order' => $this->display_order,
            ]);

            session()->flash('success', __('body_metrics.body_metric_updated_successfully'));
            return $this->redirect(route('settings.body_metrics.index'));
        } catch (\Exception $e) {
            session()->flash('error', __('body_metrics.failed_to_update_body_metric_type'));
            return;
        }
    }

    public function render()
    {
        return view('livewire.settings.body-metric.create-edit-body-metric-type');
    }
} 