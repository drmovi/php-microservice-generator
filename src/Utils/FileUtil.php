<?php

namespace Drmovi\PackageGenerator\Utils;

class FileUtil
{

    public static function removeDirectory($dir): void
    {

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? static::removeDirectory("$dir/$file") : unlink("$dir/$file");
        }

        rmdir($dir);

    }


    public static function copyDirectory(string $source, string $destination, array $replacements = []): void
    {
        $files = scandir($source);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $sourceFile = $source . DIRECTORY_SEPARATOR . $file;
            $destinationFile = $destination . DIRECTORY_SEPARATOR . str_replace(array_keys($replacements), array_values($replacements), $file);
            if (is_dir($sourceFile)) {
                if (!is_dir($destinationFile)) {
                    mkdir($destinationFile, 0777, true);
                }
                self::copyDirectory($sourceFile, $destinationFile, $replacements);
            } else {
                self::copyFile($sourceFile, $destinationFile, $replacements);
            }
        }

    }

    public static function directoryExist(string $directory): bool
    {
        return is_dir($directory);
    }

    public static function copyFile(string $sourceFile, string $destinationFile, array $replacements): void
    {
        $content = file_get_contents($sourceFile);
        $content = str_replace(array_keys($replacements), array_values($replacements), $content);
        file_put_contents($destinationFile, $content);
    }
}