<?php

namespace App\Policies;

use App\Models\Cargos\Cargo;
use App\Policies\Concerns\ChecksScreenPermissions;

class CargoPolicy
{
    use ChecksScreenPermissions;

    public function viewAny(mixed $user): bool
    {
        return $this->resolveScreenPermissions($user, 'cargos')['pode_ler'];
    }

    public function view(mixed $user, Cargo $cargo): bool
    {
        return $this->viewAny($user);
    }

    public function create(mixed $user): bool
    {
        return $this->resolveScreenPermissions($user, 'cargos')['pode_gravar'];
    }

    public function update(mixed $user, Cargo $cargo): bool
    {
        return $this->resolveScreenPermissions($user, 'cargos')['pode_editar'];
    }

    public function delete(mixed $user, Cargo $cargo): bool
    {
        return $this->resolveScreenPermissions($user, 'cargos')['pode_excluir'];
    }
}
