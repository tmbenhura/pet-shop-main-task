<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\TokenManager;
use App\Contracts\JwtTokenManager;
use Illuminate\Support\ServiceProvider;

class JwtServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        JwtTokenManager::class => TokenManager::class,
    ];
}
