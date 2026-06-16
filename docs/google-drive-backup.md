# Google Drive Backup Setup

This project uses:

- `spatie/laravel-backup` to create application backups
- `yaza/laravel-google-drive-storage` to register a Google Drive filesystem disk
- `google/apiclient` to authenticate the Google Drive client

The current repository is already configured to store backup archives on Google Drive through the `google` filesystem disk.

## What Is Already Configured In This Repo

### Composer packages

These packages are already present in `composer.json`:

```json
{
  "require": {
    "google/apiclient": "^2.19",
    "spatie/laravel-backup": "^9.3",
    "yaza/laravel-google-drive-storage": "^4.1"
  }
}
```

### Filesystem disk

The `google` disk is defined in [`config/filesystems.php`](../config/filesystems.php):

```php
'google' => [
    'driver' => 'google',
    'clientId' => env('GOOGLE_DRIVE_CLIENT_ID'),
    'clientSecret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
    'accessToken' => env('GOOGLE_DRIVE_ACCESS_TOKEN'), // optional
    'refreshToken' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
    'folder' => env('GOOGLE_DRIVE_FOLDER'),
],
```

### Backup destination

The backup destination is configured in [`config/backup.php`](../config/backup.php) to store backups on the `google` disk:

```php
'destination' => [
    'disks' => [
        'google',
    ],
],
```

### Backup source

The backup configuration is set up to back up the default database connection:

```php
'databases' => [
    env('DB_CONNECTION', 'mysql'),
],
```

### Service provider

There is no custom `app/Providers/GoogleDriveServiceProvider.php` file in this repository.

That is fine because `yaza/laravel-google-drive-storage` registers the Google driver through its own package service provider and extends Laravel's filesystem with a `google` driver.

## Installation Steps

If you need to install the packages again in a fresh environment, run:

```bash
composer require spatie/laravel-backup
composer require google/apiclient
composer require yaza/laravel-google-drive-storage
```

After installing, clear and rebuild the config cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan package:discover
```

## Google Drive Prerequisites

Before backups can be uploaded to Google Drive, you need:

1. A Google Cloud project
2. Google Drive API enabled
3. OAuth client credentials
4. A refresh token with permission to write to the target Drive folder
5. The target Google Drive folder ID

The disk configuration uses these environment keys:

- `GOOGLE_DRIVE_CLIENT_ID`
- `GOOGLE_DRIVE_CLIENT_SECRET`
- `GOOGLE_DRIVE_ACCESS_TOKEN`  
  Optional, depending on how you generated the token
- `GOOGLE_DRIVE_REFRESH_TOKEN`
- `GOOGLE_DRIVE_FOLDER`

## Recommended `.env` Entries

Add the following to your `.env` file:

```env
GOOGLE_DRIVE_CLIENT_ID=your-google-client-id
GOOGLE_DRIVE_CLIENT_SECRET=your-google-client-secret
GOOGLE_DRIVE_ACCESS_TOKEN=optional-access-token
GOOGLE_DRIVE_REFRESH_TOKEN=your-refresh-token
GOOGLE_DRIVE_FOLDER=your-google-drive-folder-id
```

If you use a separate JSON credential file for Google Drive auth, keep it outside public access and store it under `storage/app/` or another private path.

## Backup Configuration Explained

### `config/backup.php`

This project currently uses the following notable settings:

```php
'backup' => [
    'name' => '',

    'source' => [
        'files' => [
            'include' => [
                // base_path(),
            ],
            'exclude' => [
                base_path('vendor'),
                base_path('node_modules'),
            ],
            'follow_links' => false,
            'ignore_unreadable_directories' => false,
            'relative_path' => null,
        ],
        'databases' => [
            env('DB_CONNECTION', 'mysql'),
        ],
    ],

    'destination' => [
        'compression_method' => ZipArchive::CM_DEFAULT,
        'compression_level' => 9,
        'filename_prefix' => '',
        'disks' => [
            'google',
        ],
    ],

    'temporary_directory' => storage_path('app/backup-temp'),
    'password' => env('BACKUP_ARCHIVE_PASSWORD'),
    'encryption' => 'default',
    'tries' => 1,
    'retry_delay' => 0,
],
```

### What this means

- Backups are stored on Google Drive instead of the local disk
- `vendor` and `node_modules` are excluded from file backups
- The default database connection is included in the backup
- Backup files are compressed before upload
- Temporary archive files are created in `storage/app/backup-temp`

## How To Run A Backup

Run a backup manually with:

```bash
php artisan backup:run
```

To clean old backups:

```bash
php artisan backup:clean
```

To check backup health:

```bash
php artisan backup:monitor
```

## Optional Scheduler Setup

If you want the backup to run automatically, add the commands to Laravel's scheduler.

Example cron entry:

```cron
* * * * * php /path/to/your/project/artisan schedule:run >> /dev/null 2>&1
```

Then schedule the backup commands in your Laravel scheduler so they run at the interval you want.

## Validation Checklist

After configuring the credentials, verify the setup with this checklist:

1. `GOOGLE_DRIVE_CLIENT_ID` is valid
2. `GOOGLE_DRIVE_CLIENT_SECRET` is valid
3. `GOOGLE_DRIVE_REFRESH_TOKEN` can access Google Drive
4. `GOOGLE_DRIVE_FOLDER` points to an existing folder
5. `config/filesystems.php` contains the `google` disk
6. `config/backup.php` stores backups on the `google` disk
7. `php artisan backup:run` completes successfully
8. A backup file appears in the target Google Drive folder

## Troubleshooting

### Backups are not being uploaded

- Confirm the `google` disk exists in `config/filesystems.php`
- Confirm the environment variables are present and correct
- Clear cached config with `php artisan config:clear`
- Rebuild package discovery with `php artisan package:discover`

### Authentication fails

- Regenerate the refresh token
- Confirm the Google Drive API is enabled
- Confirm the OAuth client has access to the correct Drive account

### Backup file exists locally but not on Drive

- Check that `config/backup.php` uses `google` in `destination.disks`
- Check for runtime errors in the Laravel logs
- Confirm the Google Drive folder ID is correct

## Files Relevant To This Setup

- [`config/backup.php`](../config/backup.php)
- [`config/filesystems.php`](../config/filesystems.php)
- [`composer.json`](../composer.json)
- [`bootstrap/providers.php`](../bootstrap/providers.php)

