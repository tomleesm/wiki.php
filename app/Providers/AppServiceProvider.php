<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Markdown\Markdown;
use App\Markdown\MarkdownService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('markdown', function () {
            return new MarkdownService;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
