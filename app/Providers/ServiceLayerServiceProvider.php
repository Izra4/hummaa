<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Service Contracts
use App\Services\Contracts\TryoutServiceInterface;
use App\Services\Contracts\MaterialServiceInterface;
use App\Services\Contracts\UserAnswerServiceInterface;
use App\Services\Contracts\QuestionServiceInterface;

// Service Implementations
use App\Services\TryoutService;
use App\Services\MaterialService;
use App\Services\UserAnswerService;
use App\Services\QuestionService;

class ServiceLayerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TryoutServiceInterface::class, TryoutService::class);
        $this->app->bind(MaterialServiceInterface::class, MaterialService::class);
        $this->app->bind(UserAnswerServiceInterface::class, UserAnswerService::class);
        $this->app->bind(QuestionServiceInterface::class, QuestionService::class);
    }

    public function boot(): void
    {
        //
    }
}