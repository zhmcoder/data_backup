<?php

namespace Andruby\Data\Backup\Console;

use Andruby\Data\Backup\BackupServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'data-backup:install';

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
        $this->initResources();
    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function initDatabase()
    {
        DB::unprepared($this->laravel['files']->get(__DIR__ . "/stubs/deep_pay.sql"));
    }

    private function initResources()
    {
        $this->call('vendor:publish', ['--provider' => BackupServiceProvider::class]);
    }
}
