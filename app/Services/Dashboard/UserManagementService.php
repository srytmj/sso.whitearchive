<?php

namespace App\Services\Dashboard;

use App\Models\Role;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserManagementService
{
    public function list(): LengthAwarePaginator
    {
        return User::with('role')->orderByDesc('created_at')->paginate(20);
    }

    public function toggleActive(User $user, User $actor): void
    {
        if ($user->id === $actor->id) {
            abort(422, 'Tidak bisa menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => ! $user->is_active]);
    }

    public function assignRole(User $user, int $roleId): void
    {
        Role::findOrFail($roleId);
        $user->update(['role_id' => $roleId]);
    }
}
