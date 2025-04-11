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
    protected $description = 'Lösche alle Dateien vom vorherigen Datum im Ordner mergedFile';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = realpath(storage_path('app/public/mergedFile'));

        if (!File::exists($path)) {
            $this->info("Verzeichnis existiert nicht.: $path");
            return Command::FAILURE;
        }

        $yesterday = Carbon::yesterday();
        // $yesterday = Carbon::now();
        $files = File::files($path);

        foreach ($files as $file) {

            $fileLastModified = Carbon::createFromTimestamp(File::lastModified($file));
            if ($fileLastModified <= $yesterday) {
                if (unlink($file)) {
                    $this->info("Datei gelöscht: {$file->getFilename()}");
                } else {
                    $this->info("Löschen der Datei fehlgeschlagen: {$file->getFilename()}");
                }
            }
        }

        $this->info("Bereinigung der Dateien vom Vortag abgeschlossen.");
        return Command::SUCCESS;
    }
}
