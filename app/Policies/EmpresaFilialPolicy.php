<?php

namespace App\Policies;

use App\Models\EmpresaFilial;
use App\Policies\Concerns\ChecksScreenPermissions;

class EmpresaFilialPolicy
{
    use ChecksScreenPermissions;

    public function viewAny(mixed $user): bool
    {
        return $this->hasScreenAccess($user, 'empresa/filiais');
    }

    public function view(mixed $user, EmpresaFilial $empresaFilial): bool
    {
        return $this->viewAny($user);
    }

    public function create(mixed $user): bool
    {
        return $this->hasScreenAccess($user, 'empresa/filiais');
    }

    public function update(mixed $user, EmpresaFilial $empresaFilial): bool
    {
        return $this->hasScreenAccess($user, 'empresa/filiais');
    }

    public function delete(mixed $user, EmpresaFilial $empresaFilial): bool
    {
        return $this->hasScreenAccess($user, 'empresa/filiais');
    }
}
