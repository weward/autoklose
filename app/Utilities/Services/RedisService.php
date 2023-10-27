<?php

namespace App\Utilities\Services;

use Illuminate\Support\Facades\Redis;

class RedisService
{
    public function storeRecentMessage(string $id, string $messageSubject, string $toEmailAddress): void
    {
        $data = json_encode([
            'message' => $messageSubject,
            'email' => $toEmailAddress
        ]);

        Redis::set($id, $data);
    }

}
