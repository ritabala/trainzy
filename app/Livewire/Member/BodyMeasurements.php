<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\User;
use App\Models\BodyMeasurement;
use App\Models\BodyMetricType;
use App\Models\BodyMeasurementValue;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use App\Models\ProgressPhoto;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BodyMeasurements extends Component
{
    use WithFileUploads, LivewireAlert;

    public User $user;
    
    // Collection for all metrics data
    public $metricsData = [];
    
    // Metric types from database
    public $metricTypes = [];
    public $metricTypesMap = [];

    public $selectedDate;
    public $newPhoto;
    public $showPhotosModal = false;
    public $progressPhotos = [];

    // Properties for metrics detail view
    public $showMetricDetailModal = false;
    public $selectedMetric = null;
    public $selectedTimePeriod = 'W'; // Default to weekly view (W, M, 6M, Y)
    public $metricHistory = [];
    public $metricName = '';
    public $metricUnit = '';
    public $metricChange = null;
    public $formattedStartDate = '';
    public $formattedEndDate = '';
    public $metricTarget = null;

    // New properties for add metrics modal
    public $showAddMetricsModal = false;
    public $addMetricsValues = [];
    public $addNotes = '';
    public $metricsForForm = [];
    public $temporaryPhotos = [];
    public $selectedPhoto = null;
    public $updatedTemporaryPhotos = [];
    public $photosToDelete = [];

    // New properties for target values
    public $showTargetModal = false;
    public $targetValues = [];

    public $allExistingValuesForDate = [];

    protected $rules = [
        'newPhoto' => 'image|max:5120', // 5MB max
        'addMetricsValues.*' => 'nullable|numeric',
        'addNotes' => 'nullable|string|max:500',
        'temporaryPhotos.*' => 'image|max:5120',
        'targetValues.*' => 'nullable|numeric',
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadMetricTypes();
        $this->selectedDate = Carbon::today();
        $this->loadLatestMeasurements();
        $this->loadPhotos();
        $this->loadTargetValues();
        $this->metricHistory = [];
    }

    public function updatedSelectedDate()
    {
        if (!$this->selectedDate) {
            $this->selectedDate = Carbon::today();
        }
        $this->loadLatestMeasurements();
        $this->loadPhotos(); // Reload photos when date changes
    }

    public function loadMetricTypes()
    {
        $this->metricTypes = BodyMetricType::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        $this->metricTypesMap = $this->metricTypes->keyBy('slug')->all();
    }

    public function loadLatestMeasurements()
    {
        if (!$this->selectedDate) {
            $this->selectedDate = Carbon::today();
        }
        
        $latestMeasurement = $this->user->bodyMeasurements()
                                 ->with('measurementValues.metricType')
                                 ->whereDate('measurement_date', '=', $this->selectedDate->toDateString())
                                 ->orderBy('measurement_date', 'desc')
                                 ->first();
                                 
        if ($latestMeasurement) {
            $previousMeasurement = $this->user->bodyMeasurements()
                                     ->with('measurementValues.metricType')
                                     ->whereDate('measurement_date', '<', $latestMeasurement->measurement_date->toDateString())
                                     ->orderBy('measurement_date', 'asc')
                                     ->first();
        } else {
            $previousMeasurement = null;
        }

        $this->metricsData = [];

        foreach ($this->metricTypes as $metricType) {
            $latestValue = null;
            $changeValue = null;
            $targetValue = $this->user->getTargetValue($metricType->slug);
            
            if ($latestMeasurement) {
                $latestValue = $latestMeasurement->getMetricValue($metricType->slug);
                
                if ($previousMeasurement) {
                    $previousValue = $previousMeasurement->getMetricValue($metricType->slug);
                    $changeValue = $this->calculateChange($latestValue, $previousValue);
                }
            }
            
            $this->metricsData[$metricType->slug] = [
                'id' => $metricType->id,
                'name' => $metricType->name,
                'slug' => $metricType->slug,
                'unit' => $metricType->unit,
                'latest' => $latestValue,
                'change' => $changeValue,
                'target' => $targetValue,
                'display_order' => $metricType->display_order
            ];
        }
    }

    private function calculateChange($latestValue, $previousValue)
    {
        if (!is_null($latestValue) && !is_null($previousValue)) {
            return $latestValue - $previousValue;
        }
        return null;
    }

    private function calculateProgress($start, $current, $target): float
    {
        if (is_null($start) || is_null($current) || is_null($target)) {
            return 0.0;
        }

        if ($start == $target) {
            return 100.0; // Goal already achieved
        }

        if ($target > $start) {
            // Goal is to increase (e.g., weight gain, muscle mass)
            if ($current <= $start) {
                $progress = (($current - $start) / ($target - $start)) * 100;
                return min(0, $progress);
            }
            if ($current >= $target) {
                return 100.0;
            }
            $progress = (($current - $start) / ($target - $start)) * 100;
        } else {
            // Goal is to decrease (e.g., weight loss, body fat)
            if ($current >= $start) {
                $progress = (($start - $current) / ($start - $target)) * 100;
                return min(0, $progress);
            }
            if ($current <= $target) {
                return 100.0;
            }
            $progress = (($start - $current) / ($start - $target)) * 100;
        }

        return max(0, min(100, $progress));
    }

    public function loadPhotos()
    {
        $this->progressPhotos = $this->user->progressPhotos()
            ->whereDate('photo_date', $this->selectedDate->toDateString())
            ->latest()
            ->get();
    }

    public function openPhotosModal()
    {
        $this->showPhotosModal = true;
    }

    public function closePhotosModal()
    {
        $this->showPhotosModal = false;
    }

    public function openMetricDetail($slug)
    {
        $this->selectedMetric = $slug;
        $this->loadMetricHistory();
        $this->showMetricDetailModal = true;
    }

    public function closeMetricDetail()
    {
        $this->showMetricDetailModal = false;
        $this->selectedMetric = null;
        $this->metricHistory = [];
    }

    public function changeTimePeriod($period)
    {
        $this->selectedTimePeriod = $period;
        $this->loadMetricHistory();
    }

    private function getStartDateForTimePeriod()
    {
        $endDate = $this->selectedDate ? Carbon::parse($this->selectedDate) : now();

        return match ($this->selectedTimePeriod) {
            'W'  => $endDate->copy()->subWeek()->addDay(),
            'M'  => $endDate->copy()->subMonth(),
            '6M' => $endDate->copy()->subMonths(6),
            'Y'  => $endDate->copy()->subYear(),
            default => $endDate->copy()->subWeek()->addDay(),
        };
    }

    private function loadMetricHistory()
    {
        if (!$this->selectedMetric) {
            $this->metricHistory = [];
            return;
        }

        $endDate = $this->selectedDate ? Carbon::parse($this->selectedDate) : now();
        $startDate = $this->getStartDateForTimePeriod();

        $measurements = $this->user->bodyMeasurements()
            ->whereDate('measurement_date', '>=', $startDate)
            ->whereDate('measurement_date', '<=', $endDate)
            ->orderBy('measurement_date', 'asc')
            ->get();

        $this->metricHistory = [];

        $metricType = $this->metricTypes->where('slug', $this->selectedMetric)->first();
        if ($metricType) {
            $this->metricName = $metricType->name;
            $this->metricUnit = $metricType->unit;
            $this->metricTarget = $this->user->getTargetValue($this->selectedMetric);
        }

        foreach ($measurements as $measurement) {
            $value = $measurement->getMetricValue($this->selectedMetric);
            if (is_numeric($value)) {
                $this->metricHistory[] = [
                    'date' => $measurement->measurement_date->format('d M'),
                    'value' => (float) $value,
                ];
            }
        }

        if (count($this->metricHistory) >= 2) {
            $first = $this->metricHistory[0]['value'];
            $last = $this->metricHistory[count($this->metricHistory) - 1]['value'];
            $this->metricChange = $last - $first;
        } else {
            $this->metricChange = null;
        }

        $this->formattedStartDate = $startDate->format("d M 'y");
        $this->formattedEndDate = $endDate->endOfDay()->format("d M 'y");
    }

    public function addBodyMetric($metricSlug = null)
    {
        if ($this->showMetricDetailModal) {
            $this->showMetricDetailModal = false;
        }

        $this->reset('addMetricsValues', 'selectedMetric');

        if (!$this->selectedDate) {
            $this->selectedDate = Carbon::today();
        }

        // Load existing measurement data
        $existingMeasurement = $this->user->bodyMeasurements()
            ->with('measurementValues.metricType')
            ->whereDate('measurement_date', $this->selectedDate->toDateString())
            ->first();

        // Load all existing values and notes
        if ($existingMeasurement) {
            $this->addNotes = $existingMeasurement->notes;
            foreach ($existingMeasurement->measurementValues as $value) {
                if ($value->metricType) {
                    $this->allExistingValuesForDate[$value->metricType->slug] = $value->value;
                }
            }
        }

        if ($metricSlug !== null && $this->metricTypes->contains('slug', $metricSlug)) {
            // Scenario 1: Called from Metric Detail Modal - single metric edit
            $this->selectedMetric = $metricSlug;            
            if (isset($this->allExistingValuesForDate[$metricSlug])) {
                $this->addMetricsValues[$metricSlug] = $this->allExistingValuesForDate[$metricSlug];
            }
        } else {
            // Scenario 2: Called from Main Page (Edit/Update)
            $this->selectedMetric = null;            
            foreach ($this->metricTypes as $metricType) {
                $this->addMetricsValues[$metricType->slug] = $this->allExistingValuesForDate[$metricType->slug] ?? null;
            }
        }

        $this->updateMetricsForForm();
        $this->showAddMetricsModal = true;
    }

    public function closeAddMetricsModal()
    {
        $this->showAddMetricsModal = false;
        $this->reset('addMetricsValues', 'addNotes', 'allExistingValuesForDate', 'metricsForForm', 'temporaryPhotos', 'updatedTemporaryPhotos', 'photosToDelete');
        $this->loadPhotos();
    }

    public function closeModal()
    {
        $this->showPhotosModal = false;
        $this->showMetricDetailModal = false;
        $this->showAddMetricsModal = false;
        $this->selectedMetric = null;
        $this->metricHistory = [];
    }

    public function updatedTemporaryPhotos()
    {
        $this->validate([
            'temporaryPhotos.*' => 'image|max:5120',
        ]);

        // Merge with existing photos if any
        if (!empty($this->updatedTemporaryPhotos)) {
            $this->temporaryPhotos = array_merge($this->updatedTemporaryPhotos, $this->temporaryPhotos);
        }        
        $this->updatedTemporaryPhotos = $this->temporaryPhotos;
    }

    public function removeTemporaryPhoto($index)
    {
        unset($this->temporaryPhotos[$index]);
        $this->temporaryPhotos = array_values($this->temporaryPhotos);
    }

    public function saveBodyMeasurements()
    {
        $this->validate([
            'addMetricsValues.*' => 'nullable|numeric',
            'addNotes' => 'nullable|string|max:500',
        ]);
        
        if (!$this->selectedDate) {
            $this->selectedDate = Carbon::today();
        }
        
        $existingMeasurement = $this->user->bodyMeasurements()
            ->whereDate('measurement_date', $this->selectedDate->toDateString())
            ->first();
        
        if ($existingMeasurement) {
            $existingMeasurement->update([
                'notes' => $this->addNotes
            ]);
            
            $existingValues = $existingMeasurement->measurementValues()
                ->with('metricType')
                ->get()
                ->keyBy(function($item) {
                    return $item->metricType->slug ?? '';
                });

            // First, handle updates and new values for selected metrics
            foreach ($this->addMetricsValues as $slug => $value) {
                // Convert empty or null values to 0
                $value = $value === '' || $value === null ? 0 : (float) $value;
                
                $metricType = $this->metricTypes->where('slug', $slug)->first();
                
                if ($metricType) {
                    if (isset($existingValues[$slug])) {
                        $existingValues[$slug]->update([
                            'value' => $value,
                        ]);
                    } else {
                        BodyMeasurementValue::create([
                            'body_measurement_id' => $existingMeasurement->id,
                            'body_metric_type_id' => $metricType->id,
                            'value' => $value,
                        ]);
                    }
                }
            }
            
            $this->alert('success', __('body_metrics.updated'));
        } else {
            // Create new measurement
            $measurement = $this->user->bodyMeasurements()->create([
                'measurement_date' => $this->selectedDate,
                'notes' => $this->addNotes
            ]);

            // Create measurement values
            foreach ($this->addMetricsValues as $slug => $value) {
                // Convert empty or null values to 0
                $value = $value === '' || $value === null ? 0 : (float) $value;
                
                $metricType = $this->metricTypes->where('slug', $slug)->first();
                if ($metricType) {
                    BodyMeasurementValue::create([
                        'body_measurement_id' => $measurement->id,
                        'body_metric_type_id' => $metricType->id,
                        'value' => $value,
                    ]);
                }
            }

            $this->alert('success', __('body_metrics.created'));
        }

        // Handle photo uploads
        if ($this->temporaryPhotos) {
            foreach ($this->temporaryPhotos as $photo) {
                $path = $photo->storeAs(
                    'progress-photos/' . $this->user->id . '/' . $this->selectedDate->format('Y-m-d'),
                    uniqid() . '.' . $photo->getClientOriginalExtension(),
                    'private'
                );

                ProgressPhoto::create([
                    'user_id' => $this->user->id,
                    'file_path' => $path,
                    'file_name' => $photo->getClientOriginalName(),
                    'mime_type' => $photo->getMimeType(),
                    'file_size' => $photo->getSize(),
                    'title' => 'Progress Photo - ' . $this->selectedDate->format('Y-m-d'),
                    'photo_date' => $this->selectedDate,
                ]);
            }
        }

        // Handle photo deletions
        if (!empty($this->photosToDelete)) {
            foreach ($this->photosToDelete as $photoId) {
                $photo = ProgressPhoto::find($photoId);
                if ($photo) {
                    Storage::disk('private')->delete($photo->file_path);
                    $photo->delete();
                }
            }
        }

        $this->loadLatestMeasurements();
        $this->closeAddMetricsModal();
    }

    public function updatedNewPhoto()
    {
        $this->validate([
            'newPhoto.*' => 'image|max:5120'
        ]);

        try {
            foreach ($this->newPhoto as $photo) {
                $path = $photo->storeAs(
                    'progress-photos/' . $this->user->id . '/' . $this->selectedDate->format('Y-m-d'),
                    uniqid() . '.' . $photo->getClientOriginalExtension(),
                    'private'
                );

                ProgressPhoto::create([
                    'user_id' => $this->user->id,
                    'file_path' => $path,
                    'file_name' => $photo->getClientOriginalName(),
                    'mime_type' => $photo->getMimeType(),
                    'file_size' => $photo->getSize(),
                    'title' => 'Progress Photo - ' . $this->selectedDate->format('Y-m-d'),
                    'photo_date' => $this->selectedDate,
                ]);
            }

            $this->newPhoto = null;
            $this->loadPhotos();
            $this->alert('success', __('body_metrics.photo_uploaded'));
        } catch (\Exception $e) {
            $this->alert('error', __('body_metrics.photo_upload_failed') . $e->getMessage());
        }
    }

    public function deletePhoto($photoId)
    {
        try {
            $photo = ProgressPhoto::findOrFail($photoId);
            Storage::disk('private')->delete($photo->file_path); 
            $photo->delete();
            $this->loadPhotos();
            $this->selectedPhoto = null;
            $this->alert('success', __('body_metrics.photo_deleted'));
        } catch (\Exception $e) {
            $this->alert('error', __('body_metrics.photo_delete_failed') . $e->getMessage());
        }
    }

    public function markPhotoForDeletion($photoId)
    {
        try {
            // Add to photosToDelete array
            $this->photosToDelete[] = $photoId;
            
            // Remove from progressPhotos collection
            $this->progressPhotos = $this->progressPhotos->reject(function ($photo) use ($photoId) {
                return $photo->id == $photoId;
            });
            
            $this->selectedPhoto = null;
        } catch (\Exception $e) {
            $this->alert('error', __('body_metrics.photo_mark_failed') . $e->getMessage());
        }
    }

    private function updateMetricsForForm()
    {
        if ($this->selectedMetric) {
            $this->metricsForForm = collect([$this->selectedMetric])
                ->map(function ($slug) {
                    return $this->metricTypesMap[$slug] ?? null;
                })
                ->filter()
                ->values()
                ->all();
        } else {
            $this->metricsForForm = $this->metricTypes->sortBy('display_order')->values()->all();
        }
    }

    public function openTargetModal()
    {
        $this->loadTargetValues();
        $this->showTargetModal = true;
    }

    public function closeTargetModal()
    {
        $this->showTargetModal = false;
        $this->targetValues = [];
    }

    private function loadTargetValues()
    {
        $this->targetValues = [];
        foreach ($this->metricTypes as $metricType) {
            $this->targetValues[$metricType->slug] = $this->user->getTargetValue($metricType->slug);
        }
    }

    public function saveTargetValues()
    {
        $this->validate([
            'targetValues.*' => 'nullable|numeric',
        ]);

        foreach ($this->targetValues as $slug => $value) {
            $metricType = $this->metricTypes->where('slug', $slug)->first();

            if ($metricType) {
                // Cast value to float or null
                $castedValue = ($value === '' || $value === null) ? null : (float) $value;

                $this->user->setTargetValue($metricType->id, $castedValue);
            }
        }

        $this->alert('success', __('body_metrics.save_target'));
        $this->loadLatestMeasurements();
        $this->closeTargetModal();
    }

    
    public function closeMetricDetailModal()
    {
        $this->showMetricDetailModal = false;
        $this->selectedMetric = null;
        $this->metricHistory = [];
    }

    public function render()
    {
        return view('livewire.member.body-measurements');
    }
} 