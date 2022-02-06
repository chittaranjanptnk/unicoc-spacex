<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use App\Utility\GoogleDrive;

use Storage;

class DbBackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backs up your database';
    protected $dbFile = null;
    protected $dbFileName = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->dbFileName = env('DB_DATABASE') . "-backup-" . date('YmdHis') . ".sql";
        $this->dbFile = "app/backup/" . $this->dbFileName;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dbUser = env('DB_USERNAME');
        $dbPassword = env('DB_PASSWORD');
        $dbHost = env('DB_HOST');
        $dbName = env('DB_DATABASE');

        $command = "mysqldump" .
            " --user=" . $dbUser . 
            " --password=" . $dbPassword . 
            " --host=" . $dbHost . 
            " " . $dbName . "  > " . storage_path($this->dbFile);

        exec($command);

        $this->upload();
    }

    /**
     * Upload the file
     * @return void
     */
    protected function upload()
    {
        if (file_exists(storage_path($this->dbFile))) {
            $fileData = [
                'name' => $this->dbFileName,
                'path' => storage_path($this->dbFile),
            ];

            $googleDrive = new GoogleDrive();

            if (!empty($googleDrive)) {
                $uploaded = $googleDrive->uploadFile($fileData);

                if ($uploaded > 0) {
                    \Log::info('Database backup file uploaded successfully.');
                } else {
                    \Log::error('Database backup file could not be uploaded.');
                }
            } else {
                \Log::error('Issue in initializing google drive client.');
            }
        } else {
            \Log::error('No database backup file was found.');
        }
    }
}
