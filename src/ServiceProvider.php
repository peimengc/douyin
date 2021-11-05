<?php

namespace Peimengc\Douyin;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Douyin::class, function () {
            return new Douyin();
        });

        $this->app->alias(Douyin::class, 'weather');
    }

    public function provides()
    {
        return [Douyin::class, 'weather'];
    }
}