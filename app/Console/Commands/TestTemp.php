<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\TemporaryDirectory\TemporaryDirectory;


class TestTemp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $temporaryDirectory = (new TemporaryDirectory())->create();
        $tempFilePath = $temporaryDirectory->path('temp.zip');

        $this->info('Temp file path: ' . $tempFilePath);
        $tempFolder = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);

        $this->info('Temp folder: ' . $tempFolder);

    }
}
