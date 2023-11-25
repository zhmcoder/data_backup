<?php

return array(
    'mysql_dump' => env('MYSQL_DUMP','/usr/local/mysql/bin/mysqldump'),
    'backup_dir' => env('BACKUP_DIR','/opt/backup/mysql/')
);
