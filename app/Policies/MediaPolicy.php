<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Media;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->isManager($user);
    }

    public function view(User $user, Media $media): bool
    {
        return $this->isManager($user);
    }

    public function create(User $user): bool
    {
        return $this->isManager($user);
    }

    public function update(User $user, Media $media): bool
    {
        return $this->isManager($user);
    }

    public function delete(User $user, Media $media): bool
    {
        return $this->isManager($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->isManager($user);
    }

    public function restore(User $user, Media $media): bool
    {
        return $this->isManager($user);
    }

    public function restoreAny(User $user): bool
    {
        return $this->isManager($user);
    }

    public function forceDelete(User $user, Media $media): bool
    {
        return $this->isManager($user);
    }

    public function forceDeleteAny(User $user): bool
    {
        return $this->isManager($user);
    }

    public function replicate(User $user, Media $media): bool
    {
        return $this->isManager($user);
    }

    public function reorder(User $user): bool
    {
        return $this->isManager($user);
    }

    private function isManager(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin']);
    }
}
