<?php

use Kirby\Filesystem\F;

Kirby::plugin('bnomei/dropbox-backup', [
    'options' => [
        'token' => fn() => env('DROPBOX_API_TOKEN'), // Dropbox API token or closure to .env
        'target-dir' => '/backups',
        'cache' => true,
    ],
    'commands' => [
        'dropbox-backup:push' => [
            'description' => 'Push the current backup to Dropbox',
            'args' => [],
            'command' => function ($cli) {
                /*
                 * use in cronjob like this:
                 * janitor:backupzip -o storage/backups/backup.zip --quiet; php vendor/bin/kirby dropbox-backup:push
                 */
                $backupFile = kirby()->roots()->accounts() . '/../backups/backup.zip';
                if (!F::exists($backupFile)) {
                    $cli->error('Backup file not found: ' . $backupFile);

                    return;
                }

                $lock = kirby()->cache('bnomei.dropbox-backup')->get('lock');
                if ($lock) {
                    $cli->error('Backup is currently being pushed to Dropbox');

                    return;
                }

                $cli->out('Starting upload to Dropbox...');
                // lock for only a certain time in minutes
                kirby()->cache('bnomei.dropbox-backup')->set('lock', true, 60);

                $targetDir = option('bnomei.dropbox-backup.target-dir');
                $targetPath = $targetDir . '/' . basename($backupFile);
                $cli->out('local:  ' . $backupFile);
                $cli->out('remote: ' . $targetPath);

                $token = option('bnomei.dropbox-backup.token');
                if (!is_string($token) && is_callable($token)) {
                    $token = $token();
                }

                $fp = fopen($backupFile, 'rb');
                $size = filesize($backupFile);

                $ch = curl_init('https://content.dropboxapi.com/2/files/upload');
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Content-Type: application/octet-stream',
                    'Dropbox-API-Arg: {"path":"' . $targetPath . '", "mode":"add"}',
                ]);
                curl_setopt($ch, CURLOPT_PUT, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_INFILE, $fp);
                curl_setopt($ch, CURLOPT_INFILESIZE, $size);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                fclose($fp);

                kirby()->cache('bnomei.dropbox-backup')->remove('lock');

                if ($response === true) {
                    $cli->success('Backup pushed to Dropbox');
                } else {
                    $cli->error($response);
                }
            },
        ],
    ],
]);
