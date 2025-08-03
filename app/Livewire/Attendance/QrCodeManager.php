<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Services\QrCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class QrCodeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $roleType = '';
    public $selectedUser = null;
    public $showQrModal = false;
    public $currentQrCode = null;
    public $perPage = 12;
    public $allRoles = [];

    protected $queryString = ['search', 'dateFrom', 'dateTo', 'roleType', 'perPage'];

    public function mount()
    {
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->roleType = '';
        $this->allRoles = Role::where('name', '!=', 'super-admin')->get();
    }

    public function updated($property)
    {
        if (in_array($property, [
            'search', 'dateFrom', 'dateTo', 'roleType'
        ])) {
            $this->resetPage();
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->roleType = '';
        $this->resetPage();
    }

    public function generateQrCode($userId)
    {
        $user = User::findOrFail($userId);
        $qrCodeService = app(QrCodeService::class);
        
        $this->currentQrCode = [
            'user' => $user,
            'qr_code' => $qrCodeService->generateQrCodeBase64($user),
            'token' => $qrCodeService->getOrCreateScanCode($user)
        ];
        
        $this->showQrModal = true;
    }

    public function generateQrCodeImage($userId)
    {
        $user = User::findOrFail($userId);
        return app(QrCodeService::class)->generateQrCodeBase64($user, 200);
    }

    public function downloadQrCode($userId)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('download_qr_code'), 403, __('errors.permission_denied'));

        $user = User::findOrFail($userId);
        $qrCode = $user->qr_code;

        return response()->streamDownload(function () use ($qrCode) {
            echo $qrCode;
        }, $user->name . '-qr-code.png', [
            'Content-Type' => 'image/png'
        ]);
    }

    public function exportAllQrCodes()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('download_qr_code'), 403, __('errors.permission_denied'));

        $users = $this->getFilteredUsers();
        $zip = new ZipArchive();
        $zipFileName = 'qr-codes-' . now()->format('Y-m-d-His') . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($users as $user) {
                $qrCode = $user->qr_code;
                $tempFile = tempnam(sys_get_temp_dir(), 'qr_');
                file_put_contents($tempFile, $qrCode);
                $zip->addFile($tempFile, $user->name . '-qr-code.png');
            }
            $zip->close();

            // Clean up temp files
            foreach ($users as $user) {
                @unlink($tempFile);
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return back()->with('error', __('attendance.export_failed'));
    }

    public function exportPdf()
    {
        $users = $this->getFilteredUsers();
        
        $pdf = PDF::loadView('livewire.attendance.qr-codes-pdf', [
            'users' => $users,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'qr-codes-' . now()->format('Y-m-d') . '.pdf');
    }

    public function closeModal()
    {
        $this->showQrModal = false;
    }

    public function getFilteredUsers()
    {
        $query = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->when($this->roleType, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->roleType);
                });
            });

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.attendance.qr-code-manager', [
            'users' => $this->getFilteredUsers()
        ]);
    }
} 