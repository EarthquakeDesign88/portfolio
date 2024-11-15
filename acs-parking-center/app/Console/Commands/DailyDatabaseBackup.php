<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DailyDatabaseBackup extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Backup the database';

    public function handle()
    {
        // Generate backup file name with current date and time
        $fileName = 'backup_' . date('Ymd_His') . '.sql';
        
        // Define the backup file path
        $backupPath = storage_path('app/public/backups/database/' . $fileName);

        // Execute the mysqldump command to create the backup
        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
            env('DB_HOST'),
            env('DB_PORT'),
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_DATABASE'),
            $backupPath
        );

        // Execute the command
        exec($command, $output, $returnCode);

        // Check if the backup was successful
        if ($returnCode === 0) {
            $this->info('Database backup created successfully: ' . $fileName);
        } else {
            $this->error('Failed to create database backup');
        }
    }
}
