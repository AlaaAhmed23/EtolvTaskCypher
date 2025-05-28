<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\SchoolRepositoryInterface;
use App\Interfaces\StudentRepositoryInterface;
use App\Interfaces\SubjectRepositoryInterface;
use App\Repositories\SchoolRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SchoolRepositoryInterface::class, SchoolRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
    }
    public function boot(): void
    {
        
    }
}