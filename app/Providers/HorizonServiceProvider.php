<?php

namespace App\Providers;

use Laravel\Horizon\HorizonApplicationServiceProvider;
class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     */
    protected function gate(): void
    {
        //
    }
}
