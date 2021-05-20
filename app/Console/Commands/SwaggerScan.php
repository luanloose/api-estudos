<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SwaggerScan extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "swg:scan";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create a swagger file";

    public function handle()
    {
        $path = dirname(dirname(__DIR__));
        $outputPath = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'public/swagger.json';
        $this->info('Scanning ' . $path);
        $openApi = \OpenApi\scan($path);
        header('Content-Type: application/json');
        file_put_contents($outputPath, $openApi->toJson());
        $this->info('Output ' . $outputPath);
    }
}