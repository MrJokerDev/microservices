<?php

namespace App\Providers;

use App\Jobs\ProductCreate;
use App\Jobs\ProductDelete;
use App\Jobs\ProductUpdate;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        \App::bindMethod(ProductCreate::class . '@handle', fn($job) => $job->handle());
        \App::bindMethod(ProductUpdate::class . '@handle', fn($job) => $job->handle());
        \App::bindMethod(ProductDelete::class . '@handle', fn($job) => $job->handle());
    }

}
