<?php

namespace App\Repository;

use App\Models\Action;
use Illuminate\Database\Eloquent\Builder;

class ActionRepository
{

    /**
     *
     */
    public static function findByUser(int $userId): Builder
    {
        return  Action::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->orWhereHas('services', function ($query) use ($userId) {
            $query->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            });
        });
    }

}
