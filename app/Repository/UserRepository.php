<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function findUsersByIds(array $userIds)
    {
        return DB::table('usuario_gt')
            ->select('*')
            ->whereIn('user_id', array_keys($userIds))
            ->get();
    }

    public function findActiveUsers()
    {
        return DB::table('usuario_gt')
            ->where('state', '=', '1')
            ->orderBy('display_name', 'asc')
            ->lists('display_name', 'user_id');
    }
}