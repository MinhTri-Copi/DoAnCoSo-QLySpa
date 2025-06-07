<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenAI;

class OpenAIServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(OpenAI::class, fn($app) =>
            OpenAI::client(config('services.openai.key'))
        );
    }
}
