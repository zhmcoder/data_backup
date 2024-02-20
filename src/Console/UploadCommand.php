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
        $this->info('dd');
    }


    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function upload()
    {
        $accessKeyId = getenv("OSS_ACCESS_KEY_ID");
        $accessKeySecret = getenv("OSS_ACCESS_KEY_SECRET");
        // Endpoint以杭州为例，其它Region请按实际情况填写。
        $endpoint = "https://oss-cn-beijing.aliyuncs.com";
        $upload = config('data_backup.upload');
        foreach ($upload as $upload_config) {
//            $upload_config = config('data_backup.upload')[0];
            $file_full_path = $upload_config['local_path'] .
                $upload_config['file_prefix'] . date($upload_config['date_format'])
                . $upload_config['file_ext'];
            $file_name = $upload_config['file_prefix'] . date($upload_config['date_format'])
                . $upload_config['file_ext'];

            $bucket = $upload_config['oss_bucket'];

            $object = $upload_config['oss_path'] . $file_name;
            $options = array(
                OssClient::OSS_CHECK_MD5 => true,
                OssClient::OSS_PART_SIZE => getenv("OSS_PART_SIZE", 100 * 1024 * 1024),
            );
            try {
                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

                $ossClient->multiuploadFile($bucket, $object, $file_full_path, $options);
            } catch (OssException $e) {
                error_log_info($e->getMessage());
            }
        }
    }
}
