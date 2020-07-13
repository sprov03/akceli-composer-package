<?php

namespace Akceli;

use Akceli\Console;
use Akceli\FileModifiers\AkceliPhpFileModifier;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use SplFileInfo;

class AkceliFileModifier
{
    private static string $basePath;

    /**
     * @var SplFileInfo
     */
    protected $fileInfo;

    /**
     * @var string
     */
    protected $content;

    /**
     * @param SplFileInfo|string $file
     * @return static
     */
    public static function file($file)
    {
        if (is_string($file)) {
            if (isset(self::$basePath)) {
                $file = self::getBasePath($file);
            }

            $file_path = $file;
            $file = new SplFileInfo($file);

            if (empty($file->getRealPath())) {
                throw new \Illuminate\Contracts\Filesystem\FileNotFoundException('File Not Found: ' .  $file_path);
            }
        }

        if (!$file instanceof SplFileInfo) {
            throw new \Illuminate\Contracts\Filesystem\FileNotFoundException('File Not Found');
        }

        return new static($file);
    }

    /**
     * @param SplFileInfo|string $file
     * @return AkceliPhpFileModifier
     */
    public static function phpFile($file)
    {
        return AkceliPhpFileModifier::file($file);
    }

    /**
     * static constructor.
     * @param SplFileInfo $fileInfo
     */
    public function __construct(SplFileInfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;
        $this->content = file_get_contents($fileInfo->getRealPath());
    }

    /**
     * @param string $path
     * @return string
     */
    public static function getBasePath(string $path = ''): string
    {
        return rtrim(self::$basePath, '/') . '/' . $path;
    }

    /**
     * @param string $basePath
     */
    public static function setBasePath(string $basePath): void
    {
        self::$basePath = $basePath;
    }

    public function addLineAbove(string $search, string $new_content, $is_raw_pattern = false)
    {
        if (!$is_raw_pattern) {
            $search = preg_quote($search, '/');
        }

        return $this->regexPrepend("/([^\n])*{$search}([^\n])*/", $new_content . PHP_EOL);
    }

    public function addLineBelow(string $search, string $new_content, bool $is_raw_pattern = false, string $delimeter = '/')
    {
        if (!$is_raw_pattern) {
            $search = preg_quote($search, $delimeter);
        }

        return $this->regexAppend($delimeter . "([^\n])*{$search}([^\n])*" . $delimeter, PHP_EOL . $new_content);
    }

    public function saveChanges()
    {
        $pathFromProjectRoot = str_replace(base_path(), '', $this->fileInfo->getRealPath());
        \Akceli\Console::info($pathFromProjectRoot . '    (Modified)');
        file_put_contents($this->fileInfo->getRealPath(), $this->content);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function dump()
    {
        dump($this->content);
    }

    public function dd()
    {
        dd($this->content);
    }

    protected function regexReplace(string $pattern, string $replacement, string $deleminator = '/')
    {
        $pattern = $deleminator . preg_quote($pattern, $deleminator) . $deleminator;
        $this->content = preg_replace($pattern, $replacement, $this->content, 1);

        return $this;
    }

    protected function regexAppend($pattern, string $new_content)
    {
        preg_match($pattern, $this->content, $matches);
        if (isset($matches[0])) {
            $replacement = $matches[0] . $new_content;
            $this->content = preg_replace($pattern, $replacement, $this->content, 1);
        }
        return $this;
    }

    protected function regexPrepend($pattern, string $new_content)
    {
        preg_match($pattern, $this->content, $matches);
        if (isset($matches[0])) {
            $replacement = $new_content . $matches[0];
            $this->content = preg_replace($pattern, $replacement, $this->content, 1);
        }

        return $this;
    }
}
