# Kirby Dropbox Backup 

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby-dropbox-backup?color=ae81ff)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby-dropbox-backup?color=272822)
[![Twitter](https://flat.badgen.net/badge/twitter/bnomei?color=66d9ef)](https://twitter.com/bnomei)

Copy an existing backup to Dropbox.

## Install

Using composer:

```bash
composer require bnomei/kirby-dropbox-backup
```

## Commercial Usage

> <br>
> <b>Support open source!</b><br><br>
> This plugin is free but if you use it in a commercial project please consider to sponsor me or make a donation.<br>
> If my work helped you to make some cash it seems fair to me that I might get a little reward as well, right?<br><br>
> Be kind. Share a little. Thanks.<br><br>
> &dash; Bruno<br>
> &nbsp;

| M | O | N | E | Y |
|---|----|---|---|---|
| [Github sponsor](https://github.com/sponsors/bnomei) | [Patreon](https://patreon.com/bnomei) | [Buy Me a Coffee](https://buymeacoff.ee/bnomei) | [Paypal dontation](https://www.paypal.me/bnomei/15) | [Hire me](mailto:b@bnomei.com?subject=Kirby) |

## Dropbox

1. Create a new [Dropbox Access Token]( https://dropbox.tech/developers/generate-an-access-token-for-your-own-account)
2. Use plugin settings to set target folder and access token.

**site/config/config.php**
```php
<?php

return [
    // other options...
    
    'bnomei.dropbox-backup.target-dir' => '/backup', // default
    'bnomei.dropbox-backup.token' => 'MY-TOKEN', // or with closure to .env file
    'bnomei.dropbox-backup.token' => fn() => env('DROPBOX_API_TOKEN'),
];
```

> TIP: You can use my [Kirby3 Dotenv Plugin](https://github.com/bnomei/kirby3-dotenv) to store the token in a `.env` file.

## CRON job

Either you have a custom way to create an backup or you use my [Janitor plugin](https://github.com/bnomei/kirby3-janitor) to create one. The following example uses the latter.

```shell
php vendor/bin/kirby janitor:backupzip -o storage/backups/backup.zip --quiet; php vendor/bin/kirby dropbox-backup:push;
```

> TODO: adjust the backup OUT-path to your needs with the -o option.

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby-dropbox-backup/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
