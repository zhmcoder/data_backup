<?php

return [
    'oss_config' => env('OSS_CONFIG', 'ali_oss'),//ali_oss,qiniu,doguo,
    'ali_oss' => [
        'key' => env('ALI_OSS_KEY'),
        'secret' => env('ALI_OSS_SECRET'),
        'oss_bucket' => 'zhm-backup',
        'oss_endpoint' => 'https://oss-cn-beijing.aliyuncs.com'
    ],
    'upload' => [
        [
            'file_prefix' => 'backup-',
            'file_ext' => '.zip',
            'local_path' => 'path',
            'date_format' => 'Y_m_d',//2024_01_01
            'oss_path' => 'conf',
            'oss_bucket' => 'bucket',
            'expired_day' => 30
        ]
    ],
    'download' => [
        [
            'path' => 'path',
            'date_format' => ''
        ]
    ],
];