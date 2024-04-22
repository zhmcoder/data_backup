<?php

namespace Andruby\Data\Backup\Console;

use Illuminate\Console\Command;
use OSS\Core\OssException;
use OSS\OssClient;

class BackupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'data-backup:one {local_path} {remote_dir}';

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
        $this->upload();
        $this->info('finish');
    }


    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function upload()
    {
        $local_path = $this->argument('local_path');
        $remote_dir = $this->argument('remote_dir');
        $accessKeyId = env("OSS_ACCESS_KEY_ID");
        $accessKeySecret = env("OSS_ACCESS_KEY_SECRET");
        // Endpoint以杭州为例，其它Region请按实际情况填写。
        $endpoint = config('data_backup.ali_oss.oss_endpoint', 'https://oss-cn-beijing.aliyuncs.com');
        $file_name = basename($local_path);

        $oss_bucket = config('data_backup.ali_oss.oss_bucket', 'zhm-backup');

        $object = $remote_dir . $file_name;

        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $options = array(
                OssClient::OSS_CHECK_MD5 => true,
                OssClient::OSS_PART_SIZE => env("OSS_PART_SIZE", 100 * 1024 * 1024),
            );
            $ossClient->multiuploadFile($oss_bucket, $object, $local_path, $options);
        } catch (OssException $e) {
            $this->info($e->getMessage());
            error_log_info($e->getMessage());
        }

    }
}
