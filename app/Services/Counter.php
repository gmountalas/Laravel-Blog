<?php

namespace App\Services;

// use Illuminate\Support\Facades\Cache;

use App\Contracts\CounterContract;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Session\Session;

class Counter implements CounterContract
{
    private $timeout;
    private $cache;
    private $session;
    private $supportsTags;
    
    public function __construct(Cache $cache, Session $session ,int $timeout)
    {
        $this->cache = $cache;
        $this->session = $session;
        $this->timeout = $timeout;
        $this->supportsTags = method_exists($cache, 'tags');
    }

    public function increment(string $key, array $tags = null): int
    {
        // Implement a counter for how many people are currently on o blogPost
        $sessionId = $this->session->getId();
        $counterKey = "{$key}-counter";
        $usersKey = "{$key}-users";

        // For Dependency injection and Contract, check if the Cache provided has
        // the method tags() (Only Redis does), and also if the array $tags isn't null
        $cache = $this->supportsTags && $tags !== null 
            ? $this->cache->tags($tags) : $this->cache;

        // $users = Cache::tags(['blog-post'])->get($usersKey, []);
        // Replace Cache Facade with Dependency injection Contract
        $users = $cache->get($usersKey, []);

        $usersUpdate = [];
        $difference = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= $this->timeout) {
                $difference -= 1;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        // Check if current user was in the list of users that are on a
        // blogPost, the list is fetched from the cache
        if (!array_key_exists($sessionId, $users) 
            || $now->diffInMinutes($users[$sessionId]) >= $this->timeout
        ) {
            $difference += 1;
        }

        $usersUpdate[$sessionId] = $now;
        // Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);
        // Replace Cache Facade with Dependency injection Contract
        $cache->forever($usersKey, $usersUpdate);
        // if (!Cache::tags(['blog-post'])->has($counterKey)) {
        // Replace Cache Facade with Dependency injection Contract
        if (!$cache->has($counterKey)) {
            // Cache::tags(['blog-post'])->forever($counterKey, 1);
            // Replace Cache Facade with Dependency injection Contract
            $cache->forever($counterKey, 1);
        } else {
            // Cache::tags(['blog-post'])->increment($counterKey, $difference);
            // Replace Cache Facade with Dependency injection Contract
            $cache->increment($counterKey, $difference);
        }

        // $counter = Cache::tags(['blog-post'])->get($counterKey);
        // Replace Cache Facade with Dependency injection Contract
        $counter = $cache->get($counterKey);

        return $counter;
    }
}