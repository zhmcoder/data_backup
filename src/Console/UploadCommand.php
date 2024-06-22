<?php

namespace Andruby\Data\Backup\Console;

use Illuminate\Console\Command;
use OSS\Core\OssException;
use OSS\OssClient;

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
    protected $description = 'data backup upload oss.';

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
        $accessKeyId = env("OSS_ACCESS_KEY_ID");
        $accessKeySecret = env("OSS_ACCESS_KEY_SECRET");
        // Endpoint以杭州为例，其它Region请按实际情况填写。
        $endpoint = "https://oss-cn-beijing.aliyuncs.com";
        $upload = config('data_backup.upload');
        foreach ($upload as $upload_config) {
            $file_full_path = $upload_config['local_path'] .
                $upload_config['file_prefix'] . date($upload_config['date_format'])
                . $upload_config['file_ext'];
            $file_name = $upload_config['file_prefix'] . date($upload_config['date_format'])
                . $upload_config['file_ext'];

            $bucket = $upload_config['oss_bucket'];

            $object = $upload_config['oss_path'] . $file_name;
            $options = array(
                OssClient::OSS_CHECK_MD5 => true,
                OssClient::OSS_PART_SIZE => env("OSS_PART_SIZE", 100 * 1024 * 1024),
            );
            try {
                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
                $ossClient->multiuploadFile($bucket, $object, $file_full_path, $options);
                if (array_key_exists('expired_day', $upload_config)) {
                    $expired_day = $upload_config['expired_day'];
                } else {
                    $expired_day = 30;
                }
                $delete_file = $upload_config['file_prefix'] .
                    date($upload_config['date_format'], strtotime('-' . $expired_day . ' day'))
                    . $upload_config['file_ext'];
                $this->info($delete_file);
                //delete
                $delete_object = $upload_config['oss_path'] . $delete_file;
                $ossClient->deleteObject($bucket, $delete_object);
            } catch (OssException $e) {
                error_log_info($e->getMessage());
            }
        }
    }
}
