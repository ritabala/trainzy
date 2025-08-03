<?php

namespace App\Livewire\UserManagement;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;
use App\Models\User;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Traits\ClearsUserCache;
use Illuminate\Support\Str;

class ManageUserRoles extends Component
{
    use WithPagination, LivewireAlert, ClearsUserCache;

    public $search = '';
    public $name = '';
    public $permissions = [];
    public $roleId;
    public $showModal = false;
    public $displayName = '';

    protected $rules = [
        'name' => 'required|min:3',
        'permissions' => 'array',
    ];

    public function mount()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_roles'), 403);
        $this->permissions = [];
    }

    public function editRole($roleId)
    {
        $this->resetValidation();
        $this->roleId = $roleId;
        $role = Role::findById($roleId, 'web');
        $this->name = $role->name; 
        $this->displayName = $role->display_name;
        $this->permissions = $role->permissions->pluck('name')->toArray();
        $this->showModal = true;
    }

    public function saveRole()
    {
        $this->validate();

        try {
            $role = $this->roleId
                ? tap(Role::findById($this->roleId, 'web'))->update(['name' => $this->name])
                : Role::create(['name' => $this->name], 'web');

            $role->syncPermissions($this->permissions);

            $usersWithRole = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role->name);
            })->get();
            foreach ($usersWithRole as $user) {
                $this->clearUserCache($user);
            }          
            
            $this->showModal = false;
            $this->alert('success', $this->roleId ? __('user.role_updated') : __('user.role_created'));
            $this->reset(['name', 'permissions', 'roleId']);
        } catch (\Exception $e) {
            $this->alert('error', __('user.role_error'));
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'permissions', 'roleId']);
        $this->resetValidation();
    }

    public function render()
    {
        $rolesQuery = Role::query();

        // Only super-admins can see the super-admin role
        if (!auth()->user()->isSuperAdmin()) {
            $rolesQuery->where('name', '!=', 'super-admin');
        }

        if ($this->search) {
            $rolesQuery->where('display_name', 'like', '%' . $this->search . '%');
        } 
        
        $rolesQuery->where('name', 'like', '%-'.gym()->id.'%');

        $roles = $rolesQuery->orderBy('name', 'asc')->paginate(10);

        // Get available permissions, filtering out super-admin permissions for non-super-admin users
        $permissionsQuery = Permission::query();
        if (!auth()->user()->isSuperAdmin()) {
            $permissionsQuery->whereNotIn('name', self::getSuperAdminPermissionNames());
        }

        return view('livewire.user-management.manage-user-roles', [
            'roles' => $roles,
            'availablePermissions' => $permissionsQuery->get()
        ]);
    }

}
