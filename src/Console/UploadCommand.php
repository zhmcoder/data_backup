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

        // 填写Bucket名称，例如examplebucket。
        $bucket = "zhm-backup";
        // 填写Object完整路径，例如exampledir/exampleobject.txt。Object完整路径中不能包含Bucket名称。

        $file_name = '公有规则模板.xlsx';
        $object = "xunji/" . $file_name;
        // <yourLocalFile>由本地文件路径加文件名包括后缀组成，例如/users/local/myfile.txt。
        // 填写本地文件的完整路径，例如D:\\localpath\\examplefile.txt。如果未指定本地路径，则默认从示例程序所属项目对应本地路径中上传文件。
        $filePath = storage_path($file_name);

        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

            $ossClient->uploadFile($bucket, $object, $filePath);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }

    }
}
