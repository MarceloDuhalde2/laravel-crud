<div class="container">
    <h1>Gestión de Permisos</h1>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">{{ $isEditing ? 'Editar' : 'Crear' }} Permiso</div>
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" wire:model="name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
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
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" wire:click="edit({{ $permission->id }})">Editar</button>
                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $permission->id }})" onclick="return confirm('¿Seguro?')">Eliminar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>