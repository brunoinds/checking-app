<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;


class EmptyTempFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:empty-temp-folder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Empty the temporary folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $temporaryDirectory = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);

        //Check before if the directory string is not empty to avoid deleting the root directory:
        if (empty($temporaryDirectory) || $temporaryDirectory === '/' || $temporaryDirectory === '\\') {
            $this->error('Invalid temporary directory. Cannot proceed with deletion.');
            Log::error('Invalid temporary directory. Cannot proceed with deletion. Temporary directory: "' . $temporaryDirectory . '"');
            return 1;
        }

        $this->info('Temporary directory: ' . $temporaryDirectory);

        $cmd = 'rm -rf '.$temporaryDirectory.'/[0-9]*-[0-9]*';

        $this->info('Deleting temporary directories with command: ' . $cmd);


        $process = Process::fromShellCommandline($cmd);
        $process->run();

        $this->info('Temporary directories were emptied successfully');
    }
}
