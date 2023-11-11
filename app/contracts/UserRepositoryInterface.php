<?php

namespace App\contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function getByEmail(string $email): Model|Builder;

    public function getOneByUserName(string $userName): Model|Builder|null;

    public function addFollower(User $user, int $followerId): void;

    public function removeFollower(User $user, int $followerId): void;

    public function getFollowingUsersIds($userId): array;
}
