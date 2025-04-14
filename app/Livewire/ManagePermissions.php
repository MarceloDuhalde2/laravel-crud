<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class ManagePermissions extends Component
{
    public $name, $permissionId;
    public $permissions;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255|unique:permissions,name',
    ];

    public function mount()
    {
        $this->permissions = Permission::all();
    }

    public function render()
    {
        return view('livewire.manage-permissions');
    }

    public function create()
    {
        $this->resetInput();
        $this->isEditing = false;
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $this->permissionId = $id;
        $this->name = $permission->name;
        $this->isEditing = true;
    }

    public function save()
    {
        $data = $this->validate($this->isEditing ? [
            'name' => 'required|string|max:255|unique:permissions,name,' . $this->permissionId,
        ] : $this->rules);

        if ($this->isEditing) {
            Permission::find($this->permissionId)->update(['name' => $this->name]);
        } else {
            Permission::create(['name' => $this->name]);
        }

        $this->permissions = Permission::all();
        $this->resetInput();
        session()->flash('success', $this->isEditing ? 'Permiso actualizado.' : 'Permiso creado.');
    }

    public function delete($id)
    {
        Permission::find($id)->delete();
        $this->permissions = Permission::all();
        session()->flash('success', 'Permiso eliminado.');
    }

    private function resetInput()
    {
        $this->name = '';
        $this->permissionId = null;
    }
}