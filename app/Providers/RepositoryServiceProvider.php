<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\TryoutRepositoryInterface;
use App\Repositories\Contracts\TryoutResultRepositoryInterface;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use App\Repositories\Contracts\UserAnswerRepositoryInterface;
use App\Repositories\Contracts\QuestionRepositoryInterface;

use App\Repositories\TryoutRepository;
use App\Repositories\TryoutResultRepository;
use App\Repositories\MaterialRepository;
use App\Repositories\UserAnswerRepository;
use App\Repositories\QuestionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TryoutRepositoryInterface::class, TryoutRepository::class);
        $this->app->bind(TryoutResultRepositoryInterface::class, TryoutResultRepository::class);
        $this->app->bind(MaterialRepositoryInterface::class, MaterialRepository::class);
        $this->app->bind(UserAnswerRepositoryInterface::class, UserAnswerRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}