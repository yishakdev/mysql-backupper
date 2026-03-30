<?php

require __DIR__.'/vendor/autoload.php';

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$backupDir = __DIR__.'/backups';

if (! is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$backupFileName = 'backup_'.date('Y-m-d_H-i-s').'.sql';
$backupFilePath = $backupDir.'/'.$backupFileName;

$command = sprintf(
    'mysqldump -h %s -P %s -u %s -p%s %s > %s',
    escapeshellarg($_ENV['DATABASE_HOST']),
    escapeshellarg($_ENV['DATABASE_PORT']),
    escapeshellarg($_ENV['DATABASE_USER']),
    escapeshellarg($_ENV['DATABASE_PASSWORD']),
    escapeshellarg($_ENV['DATABASE_NAME']),
    escapeshellarg($backupFilePath)
);
exec($command);

$s3Client = new S3Client([
    'version' => 'latest',
    'region' => $_ENV['AWS_REGION'],
    'credentials' => [
        'key' => $_ENV['AWS_ACCESS_KEY_ID'],
        'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
    ],
]);

try {
    $s3Client->putObject([
        'Bucket' => $_ENV['AWS_BUCKET_NAME'],
        'Key' => $backupFileName,
        'SourceFile' => $backupFilePath,
        'StorageClass' => 'STANDARD_IA',
    ]);
    echo "Backup uploaded successfully to S3.\n";
} catch (AwsException $e) {
    echo 'Error uploading backup to S3: '.$e->getMessage()."\n";
}
