<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\User;
use App\Models\Document;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class DocumentManagement extends Component
{
    use WithFileUploads, LivewireAlert;

    public $user;
    public $documents = [];
    public $documentType;
    public $documentFile;
    public $documentName;
    public $showUploadModal = false;
    public $errorMessage = '';
    public $successMessage = '';

    public $documentTypes = [];

    protected $rules = [
        'documentType' => 'required|string',
        'documentFile' => 'required|file|max:10240', // 10MB max
        'documentName' => 'required|string|max:255',
    ];

    protected $listeners = ['uploadDocument', 'deleteDocument'];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->documentTypes = __('staff.document_types');
        $this->loadDocuments();
    }

    public function loadDocuments()
    {
        $this->documents = $this->user->documents()->latest()->get();
    }

    public function openUploadModal()
    {
        $this->showUploadModal = true;
        $this->reset(['documentType', 'documentFile', 'documentName', 'errorMessage', 'successMessage']);
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->reset(['documentType', 'documentFile', 'documentName']);
        $this->resetValidation();
    }

    public function uploadDocument()
    {
        $this->validate();

        try {
            $path = $this->documentFile->store('documents/staff/' . $this->user->id, 'private');

            $document = new Document([
                'name' => $this->documentName,
                'type' => $this->documentType,
                'file_type' => $this->documentType,
                'file_path' => $path,
                'file_name' => $this->documentFile->getClientOriginalName(),
                'mime_type' => $this->documentFile->getMimeType(),
                'file_size' => $this->documentFile->getSize(),
            ]);

            $this->user->documents()->save($document);

            $this->loadDocuments();
            $this->closeUploadModal();
            $this->alert('success', __('members.doc_upload_success'));
        } catch (\Exception $e) {
            $this->alert('error', __('members.doc_upload_failed', ['message' => $e->getMessage()]));
        }
    }

    public function deleteDocument($documentId)
    {
        try {
            $document = Document::findOrFail($documentId);
            
            if ($document->user_id !== $this->user->id) {
                throw new \Exception(__('errors.unauthorized_action'));
            }

            Storage::disk('private')->delete($document->file_path);
            $document->delete();

            $this->loadDocuments();
            $this->alert('success', __('members.doc_deleted'));
        } catch (\Exception $e) {
            $this->alert('error', __('members.doc_delete_failed', ['message' => $e->getMessage()]));
        }
    }

    public function getDocumentTypesProperty()
    {
        return $this->documentTypes;
    }

    public function render()
    {
        return view('livewire.staff.document-management');
    }
} 