<?php

namespace sbolch\MaxUploadFileSize;

class MaxUploadFileSizeGetter {
    private const UNITS = 'bkmgtpezy';

    private bool $format;
    private string $decimals;
    private string $decimalPoint;
    private string $thousandsSeparator;
    private bool $showUnit;

    public function __construct() {
        $this->format = false;
    }

    /**
     * @return int|string
     */
    public function get(string $unit = null) {
        $maxSize = 0;

        $postMaxSize = $this->parseSize(ini_get('post_max_size'));
        $uploadMaxSize = $this->parseSize(ini_get('upload_max_filesize'));

        if($uploadMaxSize > 0 && ($uploadMaxSize <= $postMaxSize || $postMaxSize == 0)) {
            $maxSize = $uploadMaxSize;
        } elseif($postMaxSize > 0 && ($postMaxSize <= $uploadMaxSize || $uploadMaxSize == 0)) {
            $maxSize = $postMaxSize;
        }

        if($maxSize == 0) {
            return $this->format ? 'unlimited' : 0;
        }

        if($unit) {
            $maxSize = floor($maxSize / pow(1024, stripos(self::UNITS, $unit[0])));
        } else {
            $i = 0;
            while($maxSize >= 1024) {
                $maxSize /= 1024;
                $i++;
            }
            $maxSize = floor($maxSize);
            $unit = strtoupper(str_replace('bb', 'b', self::UNITS . 'b'));
        }

        if(!$this->format) {
            return $maxSize;
        }

        return number_format($maxSize, $this->decimals, $this->decimalPoint, $this->thousandsSeparator)
            . ($this->showUnit ? " $unit" : '');
    }

    public function setFormat(int $decimals = 0, string $decimalPoint = '.', string $thousandsSeparator = ',', bool $showUnit = false): self {
        $this->format = true;
        $this->decimals = $decimals;
        $this->decimalPoint = $decimalPoint;
        $this->thousandsSeparator = $thousandsSeparator;
        $this->showUnit = $showUnit;
        return $this;
    }

    private function parseSize($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/\D/', '', $size);

        if($unit) {
            return floor($size * pow(1024, stripos(self::UNITS, $unit[0])));
        } else {
            return floor($size);
        }
    }
}