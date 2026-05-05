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
    public function handle(): void
    {
        $temporaryDirectory = (new TemporaryDirectory)->create();
        $tempFilePath = $temporaryDirectory->path('temp.zip');

        $this->info('Temp file path: '.$tempFilePath);
        $tempFolder = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);

        $this->info('Temp folder: '.$tempFolder);
        $this->line('Temp folder tree:');
        $this->renderTree($tempFolder);

        $this->info('Temp folder size: '.$this->getDirectorySize($tempFolder));


        $this->line('Backup temp folder tree:');
        $this->renderTree(storage_path('app/backup-temp'));
        $this->info('Location Backup Temp: ' . storage_path('app/backup-temp'));
        $this->info('Backup temp folder size: '.$this->getDirectorySize(storage_path('app/backup-temp')));
    }

    private function renderTree(string $directory, string $prefix = ''): void
    {
        $entries = array_values(array_filter(scandir($directory) ?: [], function (string $entry): bool {
            return $entry !== '.' && $entry !== '..';
        }));

        $lastIndex = count($entries) - 1;

        foreach ($entries as $index => $entry) {
            $isLast = $index === $lastIndex;
            $path = $directory.DIRECTORY_SEPARATOR.$entry;
            $connector = $isLast ? '└── ' : '├── ';

            $this->line($prefix.$connector.$entry);

            if (is_dir($path)) {
                $nextPrefix = $prefix.($isLast ? '    ' : '│   ');
                $this->renderTree($path, $nextPrefix);
            }
        }
    }

    private function getDirectorySize(string $directory): string
    {
        $sizeOutput = shell_exec('du -sh '.escapeshellarg($directory).' 2>/dev/null');

        if (! is_string($sizeOutput) || trim($sizeOutput) === '') {
            return 'unavailable';
        }

        return explode("\t", trim($sizeOutput))[0];
    }
}
