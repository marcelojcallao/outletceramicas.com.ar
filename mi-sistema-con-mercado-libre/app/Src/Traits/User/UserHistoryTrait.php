<?php

namespace App\Src\Traits\User;

trait UserHistoryTrait
{
    public function history($user, $history_text)
    {
        return [
            'data' => [
                'type' => $history_text,
                'user'=>  $user,
            ]
        ];
    }
}
