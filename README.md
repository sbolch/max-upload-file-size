# MaxUploadFileSize

> Gets maximum of upload file size.

## Installation

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require subbysnake/max-upload-file-size
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

## Usage

```php
$hash = (new \SubbySnake\MaxUploadFileSize\MaxUploadFileSizeGetter)->get();
// max upload file size rounded (down) with automatic unit

$hash = (new \SubbySnake\MaxUploadFileSize\MaxUploadFileSizeGetter)->get('KB');
// max upload file size rounded (down) in KB
```