<?php

namespace SubbySnake\MaxUploadFileSize;

class MaxUploadFileSizeGetter {
    private $units = 'bkmgtpezy';
    private
        $format,
        $decimals,
        $decimalPoint,
        $thousandsSeparator,
        $showUnit;

    public function __construct() {
        $this->format = false;
    }

    /**
     * @param null|string $unit
     * @return int|string
     */
    public function get($unit = null) {
        $maxSize = 0;

        $postMaxSize = $this->parseSize(ini_get('post_max_size'));
        $uploadMaxSize = $this->parseSize(ini_get('upload_max_filesize'));

        if($uploadMaxSize > 0 && ($uploadMaxSize <= $postMaxSize || $postMaxSize == 0)) {
            $maxSize = $uploadMaxSize;
        } elseif($postMaxSize > 0 && ($postMaxSize <= $uploadMaxSize || $uploadMaxSize = 0)) {
            $maxSize = $postMaxSize;
        }

        if($maxSize == 0) {
            return $this->format ? 'unlimited' : 0;
        }

        if($unit) {
            $maxSize = floor($maxSize / pow(1024, stripos($this->units, $unit[0])));
        } else {
            $i = 0;
            while($maxSize >= 1024) {
                $maxSize /= 1024;
                $i++;
            }
            $maxSize = floor($maxSize);
            $unit = strtoupper(str_replace('bb', 'b', $this->units[$i] . 'b'));
        }

        if(!$this->format) {
            return $maxSize;
        }

        return number_format($maxSize, $this->decimals, $this->decimalPoint, $this->thousandsSeparator)
            . ($showUnit ? " {$unit}" : '');
    }

    /**
     * @param int $decimals
     * @param string $decimalPoint
     * @param string $thousandsSeparator
     * @param bool $showUnit
     * @return MaxUploadFileSizeGetter
     */
    public function setFormat($decimals = 0, $decimalPoint = '.', $thousandsSeparator = ',', $showUnit = false) {
        $this->format = true;
        $this->decimals = $decimals;
        $this->decimalPoint = $decimalPoint;
        $this->thousandsSeparator = $thousandsSeparator;
        $this->showUnit = $showUnit;
        return $this;
    }

    private function parseSize($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);

        if($unit) {
            return floor($size * pow(1024, stripos($this->units, $unit[0])));
        } else {
            return floor($size);
        }
    }
}