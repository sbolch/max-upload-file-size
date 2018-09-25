<?php

namespace SubbySnake\MaxUploadFileSize;

class MaxUploadFileSizeGetter {
    private $units = 'bkmgtpezy';

    /**
     * @param null|string $unit
     * @return string
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
            return 'unlimited';
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

        return number_format($maxSize, 0, ',', ' ') . " {$unit}";
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