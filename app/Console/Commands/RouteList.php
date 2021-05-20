<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RouteList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:list';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List of routes.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        global $app;
        $headers = ['method', 'uri', 'uses', 'name', 'middleware'];
        $body = [];
        foreach ($app->router->getRoutes() as $route) {
            $body[] = $this->getRouteData($route);
        }
        $this->table($headers, $body);
    }

    /**
     * @param $route
     * @return array
     */
    protected function getRouteData($route)
    {
        return [
            !empty($route['method']) ? $route['method'] : 'undefined',
            !empty($route['uri']) ? $route['uri'] : 'undefined',
            !empty($route['action']['uses']) ? $route['action']['uses'] : 'undefined',
            !empty($route['action']['as']) ? $route['action']['as'] : 'undefined',
            !empty($route['action']['middleware']) ? implode(',', $route['action']['middleware']) : 'undefined',
        ];
    }
}