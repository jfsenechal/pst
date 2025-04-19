<?php

namespace App\Repository;

use App\Models\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ActionRepository
{

    /**
     *
     */
    public static function findByUser(int $userId): Builder
    {
        return Action::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->orWhereHas('leaderServices', function ($query) use ($userId) {
            $query->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            });
        });
    }

    /**
     * @param int $actionId
     * @return Collection
     */
    public static function findAgentEmails(int $actionId): Collection
    {
        return Action::where('id', $actionId)
            ->with('users')
            ->first()
            ->users
            ->pluck('email')
            ->unique()
            ->values();
    }

}
