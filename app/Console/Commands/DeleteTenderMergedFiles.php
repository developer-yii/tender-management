<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteTenderMergedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:delete-tender-merged-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all files from the previous date in the mergedFile folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = realpath(storage_path('app/public/mergedFile'));

        if (!File::exists($path)) {
            $this->info("Directory does not exist: $path");
            return Command::FAILURE;
        }

        $yesterday = Carbon::yesterday();
        // $yesterday = Carbon::now();
        $files = File::files($path);

        foreach ($files as $file) {

            $fileLastModified = Carbon::createFromTimestamp(File::lastModified($file));
            if ($fileLastModified <= $yesterday) {
                if (unlink($file)) {
                    $this->info("Deleted file: {$file->getFilename()}");
                } else {
                    $this->info("Failed to delete file: {$file->getFilename()}");
                }
            }
        }

        $this->info("Completed cleaning up files for the previous day.");
        return Command::SUCCESS;
    }
}
