<div class="container">
    <h1>Gestión de Usuarios</h1>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">{{ $isEditing ? 'Editar' : 'Crear' }} Usuario</div>
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" wire:model="name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" wire:model="email">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                @if (!$isEditing)
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" wire:model="password">
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" wire:model="password_confirmation">
                    </div>
                @endif
                <div class="mb-3">
                    <label for="role_id" class="form-label">Rol</label>
                    <select class="form-control" wire:model="role_id">
                        <option value="">Selecciona un rol</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ $isEditing ? 'Actualizar' : 'Crear' }}</button>
                @if ($isEditing)
                    <button type="button" class="btn btn-secondary" wire:click="create">Cancelar</button>
                @endif
            </form>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" wire:click="edit({{ $user->id }})">Editar</button>
                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $user->id }})" onclick="return confirm('¿Seguro?')">Eliminar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>