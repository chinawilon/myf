<?php

namespace App\ServiceProviders;

use App\Contracts\AI;
use App\Services\A;
use App\Services\L;
use Wilon\Myf\Core\Application;
use Wilon\Myf\Core\ServicerProvider;

class AppServiceProvider extends ServicerProvider
{

    public function register(): void
    {
        $this->app->bind(AI::class, function(Application $app) {
            return $app->make(A::class, ['i' =>100]);
        });

        $this->app->alias('log', L::class);

    }
}