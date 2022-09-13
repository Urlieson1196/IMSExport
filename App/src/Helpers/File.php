<?php

namespace IMSExport\Helpers;

class File
{
    public static function createDirectory($path)
    {
        mkdir(
            $path,
            0777,
            true
        );
    }

    public static function rmDir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                        rmDir($dir . DIRECTORY_SEPARATOR . $object);
                    else
                        unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
            rmdir($dir);
        }
    }

    public static function downloadFile(string $url, string $directory = ''): bool
    {
        $fileName = $directory . '/' . basename($url);;
        if (file_put_contents($fileName, file_get_contents($url))) {
            return true;
        }
        return false;
    }

    public static function getFileNameFromUrl(string $url): string
    {
        $split = explode('/', $url);
        return $split[(count($split) - 1)];
    }
}