<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ManageRoles extends Component
{
    public $name, $roleId, $permission_ids = [];
    public $roles, $permissions;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'permission_ids' => 'array',
        'permission_ids.*' => 'exists:permissions,id',
    ];

    public function mount()
    {
        $this->roles = Role::with('permissions')->get();
        $this->permissions = Permission::all();
    }

    public function render()
    {
        return view('livewire.manage-roles');
    }

    public function create()
    {
        $this->resetInput();
        $this->isEditing = false;
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $id;
        $this->name = $role->name;
        $this->permission_ids = $role->permissions->pluck('id')->toArray();
        $this->isEditing = true;
    }

    public function save()
    {
        $data = $this->validate($this->isEditing ? [
            'name' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
            'permission_ids' => 'array',
            'permission_ids.*' => 'exists:permissions,id',
        ] : $this->rules);

        if ($this->isEditing) {
            $role = Role::find($this->roleId);
            $role->update(['name' => $this->name]);
            $role->syncPermissions($this->permission_ids);
        } else {
            $role = Role::create(['name' => $this->name]);
            $role->syncPermissions($this->permission_ids);
        }

        $this->roles = Role::with('permissions')->get();
        $this->resetInput();
        session()->flash('success', $this->isEditing ? 'Rol actualizado.' : 'Rol creado.');
    }

    public function delete($id)
    {
        Role::find($id)->delete();
        $this->roles = Role::with('permissions')->get();
        session()->flash('success', 'Rol eliminado.');
    }

    private function resetInput()
    {
        $this->name = '';
        $this->permission_ids = [];
        $this->roleId = null;
    }
}