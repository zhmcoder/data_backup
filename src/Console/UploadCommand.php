<?php

namespace Andruby\Data\Backup\Console;

use Illuminate\Console\Command;

class UploadCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'data-backup:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the deep-pay package.';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';

    protected $cmd = '';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
//        $this->backup_db();
//        $this->info('dd');
    }


    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function backup_db()
    {
        $DB_HOST = getenv('DB_HOST');
        $DB_DATABASE = getenv('DB_DATABASE');
        $DB_USERNAME = getenv('DB_USERNAME');
        $DB_PASSWORD = getenv('DB_PASSWORD');

        $dumpfname = $DB_DATABASE . "_" . date("Y-m-d_H-i-s") . ".sql";
        $command = config('data_backup.mysql_dump') . " --add-drop-table --host=$DB_HOST --user=$DB_USERNAME ";
        if ($DB_PASSWORD) $command .= "--password=" . $DB_PASSWORD . " ";
        $command .= $DB_DATABASE;
        $command .= " > " . $dumpfname;
        system($command);
        $this->info('finished');
    }
}
