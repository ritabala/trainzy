<?php

namespace App\Livewire\Member;

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
        $this->documentTypes = __('members.document_types');
        $this->user = $user;
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
        // Don't reset error and success messages when closing modal
    }

    public function uploadDocument()
    {
        $this->validate();

        try {
            $path = $this->documentFile->store('documents/member/' . $this->user->id, 'private');

            Document::create([
                'user_id' => $this->user->id,
                'name' => $this->documentName,
                'type' => $this->documentType,
                'file_type' => $this->documentType,
                'file_path' => $path,
                'file_name' => $this->documentFile->getClientOriginalName(),
                'mime_type' => $this->documentFile->getMimeType(),
                'file_size' => $this->documentFile->getSize(),
            ]);

            $this->closeUploadModal();
            $this->loadDocuments();
            $this->alert('success', __('members.doc_upload_success'));
            $this->errorMessage = '';
        } catch (\Exception $e) {
            $this->alert('error', __('members.doc_upload_failed', ['message' => $e->getMessage()]));
            $this->successMessage = '';
        }
    }

    public function deleteDocument($documentId)
    {
        try {
            $document = Document::findOrFail($documentId);
            
            // Delete file from storage
            Storage::disk('private')->delete($document->file_path);
            
            // Delete document record
            $document->delete();
            
            $this->loadDocuments();
            $this->alert('success', __('members.doc_deleted'));
            $this->errorMessage = '';
        } catch (\Exception $e) {
            $this->alert('error', __('members.doc_delete_failed', ['message' => $e->getMessage()]));
            $this->successMessage = '';
        }
    }

    public function downloadDocument($documentId)
    {
        $document = Document::findOrFail($documentId);
        
        // Check if user has permission to download this document
        if ($document->user_id !== $this->user->id && !auth()->user()->isAdmin()) {
            abort(403, __('errors.unauthorized_action'));
        }
        return Storage::disk('private')->download($document->file_path, $document->file_name);
    }

    public function render()
    {
        return view('livewire.member.document-management');
    }
} 