<?php
declare(strict_types=1);

namespace Arrayly\Test;

class TestUtils
{

    public static function printTestResult(string $message, $result)
    {
        echo PHP_EOL."===".$message."===".PHP_EOL;
        echo json_encode($result).PHP_EOL;
    }

    public static function iterableAsArray(iterable $it): array
    {
        $sink = [];
        foreach ($it as $k => $v) {
            $sink[$k] = $v;
        }

        return $sink;
    }

    /**
     * @param string $name
     * @return string
     */
    public static function loadResource(string $name): string
    {
        $baseDir = __DIR__.'/resources';
        $fileInfo = new \SplFileInfo($baseDir.'/'.$name);

        return self::fileGetContents($fileInfo);
    }

    /**
     * @param \SplFileInfo $fileInfo
     * @return string
     */
    private static function fileGetContents(\SplFileInfo $fileInfo): string
    {
        $location = $fileInfo->getPathname();
        $isValid = $fileInfo->isFile() && (!$fileInfo->isDir()) && $fileInfo->isReadable();
        if (!$isValid) {
            throw new \RuntimeException(
                'IOERROR: Failed get content from file: '.$fileInfo->getPathname().' !'
                .' details: This is not a readable file!'
            );
        }

        $level = error_reporting(0);
        $content = file_get_contents($location);
        error_reporting($level);
        if (false === $content) {
            $error = error_get_last();

            throw new \RuntimeException(
                'IOERROR: Failed get content from file: '.$fileInfo->getPathname().' !'
                .' details: '.$error['message']
            );
        }

        return (string)$content;
    }

    /**
     * @param string $name
     * @param bool $assoc
     * @return mixed
     */
    public static function loadResourceJson(string $name, bool $assoc = true)
    {
        $baseDir = __DIR__.'/resources';
        $fileInfo = new \SplFileInfo($baseDir.'/'.$name);
        $text = self::fileGetContents($fileInfo);

        return json_decode($text, $assoc);
    }

}