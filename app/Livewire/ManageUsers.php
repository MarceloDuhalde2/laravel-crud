<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class ManageUsers extends Component
{
    public $name, $email, $password, $password_confirmation, $role_id;
    public $users, $userId, $roles;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'role_id' => 'required|exists:roles,id',
    ];

    public function mount()
    {
        $this->users = User::with('roles')->get();
        $this->roles = Role::all();
    }

    public function render()
    {
        return view('livewire.manage-users');
    }

    public function create()
    {
        $this->resetInput();
        $this->isEditing = false;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->roles->first()?->id;
        $this->isEditing = true;
    }

    public function save()
    {
        $data = $this->validate($this->isEditing ? [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role_id' => 'required|exists:roles,id',
        ] : $this->rules);

        if ($this->isEditing) {
            $user = User::find($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            $user->syncRoles($this->role_id);
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);
            $user->assignRole($this->role_id);
        }

        $this->users = User::with('roles')->get();
        $this->resetInput();
        session()->flash('success', $this->isEditing ? 'Usuario actualizado.' : 'Usuario creado.');
    }

    public function delete($id)
    {
        User::find($id)->delete();
        $this->users = User::with('roles')->get();
        session()->flash('success', 'Usuario eliminado.');
    }

    private function resetInput()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role_id = '';
        $this->userId = null;
    }
}