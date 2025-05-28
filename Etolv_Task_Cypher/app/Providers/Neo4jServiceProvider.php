<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Authentication\Authenticate;
use Laudis\Neo4j\Formatter\SummarizedResultFormatter;
use Laudis\Neo4j\Formatter\OGMFormatter;
use Laudis\Neo4j\Client;

class Neo4jServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $host = env('NEO4J_BOLT_URI', 'bolt://localhost:7687');
        $user = env('NEO4J_USER', 'neo4j');
        $pass = env('NEO4J_PASSWORD', 'neo4j123');
        $this->app->singleton(Client::class, function ($app) use ($host, $user, $pass) {
            $baseFormatter = OGMFormatter::create();
            $formatter = new SummarizedResultFormatter($baseFormatter);
            $auth = Authenticate::basic($user, $pass);
            return ClientBuilder::create()
                ->withDriver('bolt', $host, $auth)
                ->build($formatter);
        });
        $this->app->alias(\Laudis\Neo4j\Client::class, 'Neo4jClient');    
    }
    public function boot(): void
    {
        
    }
}