<p align="center">
  <img src="https://github.com/lotfio/pssh/blob/master/docs/logo.png" alt="skeleton Preview">
  <p align="center">
    <img src="https://img.shields.io/badge/Licence-MIT-ffd32a.svg" alt="License">
    <img src="https://img.shields.io/badge/PHP-8-808e9b.svg" alt="PHP version">
    <img src="https://img.shields.io/badge/Version-0.1.0-f53b57.svg" alt="Version">
    <img src="https://img.shields.io/badge/coverage-10%25-27ae60.svg" alt="Coverage">
    <img src="https://travis-ci.org/lotfio/pssh.svg?branch=master" alt="Build Status">
    <img src="https://github.styleci.io/repos/206574643/shield?branch=master" alt="StyleCi">
    </p>
  <p align="center">
    <strong>:key:  easier php ssh  :key:</strong>
  </p>
</p>

### :fire: Introduction :
introduction

### :pushpin: Requirements :
- [SSH2 extension](https://www.php.net/manual/en/book.ssh2.php)
- PHP 7.2 or newer versions
- PHPUnit >= 8 (for testing purpose)

### :rocket: Installation & Use :
```php
composer require lotfio/pssh
```

### :pencil2: SSH:
```php
<?php

// basic usage example

require 'vendor/autoload.php';

$config = [
    'host'    => 'my-host',
    'auth'    =>  new Pssh\Auth\AuthUserPass('lotfio', 'secret')
];

$ssh  = new Pssh\Pssh($config);
echo $ssh->exec("date");  // Sat Nov 14 08:10:20 PM CET 2020

```

### :gear: confguration:
 - config keys
```php
  // additional details
  $config = [
    'host'      => 'server host',
    'port'      => 'server port || default 22',
    'auth'      => 'authentication method: AuthUserPass, AuthKeys, AuthHostBasedFile, AuthAgent or AuthNone.',
    'timeout'   => 'timeout when trying to connect',
    'methods'   => 'connection method checkout ssh2_connect form more details'
    'callbacks' => 'connection callbacks checkout ssh2_connect form more details'
  ];
```
 - **available SSH methods:**
    - `$ssh->exec(string $command):  string` execute a shell command.
    - `$ssh->fingerprint(int $type): string` get connection finger print.
    - `$ssh->methodsNegotiated(): array`     return an array of negotiated methods.
    - `$ssh->addPublicKey(): bool`           add a public key.
    - `$ssh->removePublicKey(): bool`        remove public key.
    - `$ssh->listPublicKeys(): array`        list public keys.
    - `$ssh->tunnel(): resource`             open an ssh tunnel.

 ### :pencil2: SFTP:
```php
<?php

require 'vendor/autoload.php';

$config = [
    'host'    => 'my-host',
    'auth'    =>  new Pssh\Auth\AuthUserPass('lotfio', 'secret')
];

$ssh  = new Pssh\Pssh($config);
$sftp = new Pssh\Psftp($ssh);

$sftp->copyToServer('local-file', 'remote-file');

```
 - **available SSH methods:**
  - `$sftp->copyToServer(): bool`       copy a file from local to remote server.
  - `$sftp->copyFromServer(): bool`     copy a file from remote server to local.
  - `$sftp->chmod(): bool`              change mod file or dir.
  - `$sftp->mkdir(): bool`              make directory.
  - `$sftp->rmdir(): bool`              remove directory.
  - `$sftp->symlink(): bool`            create a symlink.
  - `$sftp->symlinkTarget(): string`    read symlink target.
  - `$sftp->symlinkStat(): array`       stating a symlink.
  - `$sftp->realpath(): string`         get realpath.
  - `$sftp->rename(): bool`             rename file.
  - `$sftp->stat(): array`              get file stat.
  - `$sftp->unlink(): bool`             delete file.

### :computer: Contributing

- Thank you for considering to contribute to ***Package***. All the contribution guidelines are mentioned [here](CONTRIBUTING.md).

### :page_with_curl: ChangeLog

- Here you can find the [ChangeLog](CHANGELOG.md).

### :beer: Support the development

- Share ***Package*** and lets get more stars and more contributors.
- If this project helped you reduce time to develop, you can give me a cup of coffee :) : **[Paypal](https://www.paypal.me/lotfio)**. 💖

### :clipboard: License

- ***Package*** is an open-source software licensed under the [MIT license](LICENSE).
