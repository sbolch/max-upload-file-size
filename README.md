# MaxUploadFileSize

> Gets maximum of upload file size.

## Installation

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require sbolch/max-upload-file-size
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

## Usage

```php
$maxSize = (new \sbolch\MaxUploadFileSize\MaxUploadFileSizeGetter)->get();
// max upload file size rounded (down) with automatic unit

$maxSize = (new \sbolch\MaxUploadFileSize\MaxUploadFileSizeGetter)->get('KB');
// max upload file size rounded (down) in KB

$maxSize = (new \sbolch\MaxUploadFileSize\MaxUploadFileSizeGetter)
    ->setFormat($decimals = 0, $decimalPoint = '.', $thousandsSeparator = ' ', $showUnit = false)
    ->get('KB');
// max upload fine size rounded (down) in KB as formatted string
// setFormat called with defaults only for showing parameters
```