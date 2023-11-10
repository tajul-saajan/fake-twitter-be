<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserRepository
{
    public function getByEmail(string $email): Model|Builder
    {
        return User::query()->where('email', $email)->first();
    }

    public function getOneByUserName(string $userName): Model|Builder|null
    {
        return User::query()->where('user_name', $userName)->first();
    }

    public function addFollower(User $user, int $followerId): void
    {
        $user->followers()->syncWithoutDetaching([
            $followerId => [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
