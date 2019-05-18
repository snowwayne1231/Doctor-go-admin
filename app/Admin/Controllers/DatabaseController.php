<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Storage;
use Response;

class DatabaseController extends Controller
{

    protected $process;

    public function mysqldump(Request $request) {
        $this->process = new Process(sprintf(
            'mysqldump -u %s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path('app/backup.sql')
        ));

        $headers = array(
            'Content-Type' => 'application/json; charset=utf-8'
        );

        $name = 'backup_'.now().'.sql';
        
        $data = $this->handle();

        return $data == 'success'
            ? Storage::disk('local')->download('backup.sql', $name)
            : Response::json(iconv("big5", "UTF-8", $data), 200, $headers);
    }

    public function handle() {
        try {
            $this->process->mustRun();

            // $this->info('The backup has been proceed successfully.');
            return 'success';
        } catch (ProcessFailedException $exception) {
            // $this->error('The backup process has been failed.');
            return $exception->getMessage();
        }
    }
}
