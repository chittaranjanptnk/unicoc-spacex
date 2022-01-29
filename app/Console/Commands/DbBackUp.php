<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
    protected $dbFile = NULL;
    protected $dbFileName = NULL;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->dbFileName = "backup-" . date('YmdHis') . ".sql";
        $this->dbFile = "app/backup/" . $this->dbFileName;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $dbFile = "backup-" . date('YmdHis') . ".sql";
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
        \Log::info(env('GOOGLE_DRIVE_API_KEY'));
            \Log::info(url('storage/' . $this->dbFile));
        // if (Storage::disk('local')->exists($this->dbFile)) {
            $response = Http::withOptions([
                    'debug' => true,
                    'scope' => 'https://www.googleapis.com/auth/drive.file'
                ])
                // ->withToken(env('GOOGLE_DRIVE_API_KEY'))
                ->attach('attachment', file_get_contents(url('storage/' . $this->dbFile)), $this->dbFileName)
                // ->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=media');
                ->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=media&key=' . env('GOOGLE_DRIVE_API_KEY'));

            if ($response->successful()) {
                \Log::info('Back up file uploaded successfully.');
            } else {
                \Log::error('Back up file could not be uploaded.');
            }

            dd($response);
        /*} else {
            \Log::error('No back up file found.');
        }*/
    }
}
