<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Counter
{
    public function increment(string $key, array $tags = null): int
    {
        // Implement a counter for how many people are currently on o blogPost
        $sessionId = session()->getId();
        $counterKey = "{$key}-counter";
        $usersKey = "{$key}-users";

        $users = Cache::tags(['blog-post'])->get($usersKey, []);
        $usersUpdate = [];
        $difference = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= 1) {
                $difference -= 1;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        // Check if current user was in the list of users that are on a
        // blogPost, the list is fetched from the cache
        if (!array_key_exists($sessionId, $users) 
            || $now->diffInMinutes($users[$sessionId]) >= 1
        ) {
            $difference += 1;
        }

        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);
        if (!Cache::tags(['blog-post'])->has($counterKey)) {
            Cache::tags(['blog-post'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $difference);
        }

        $counter = Cache::tags(['blog-post'])->get($counterKey);

        return $counter;
    }
}