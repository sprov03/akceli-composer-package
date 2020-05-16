<?php

namespace Akceli\Bootstrap;

use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

abstract class AkceliBootstrap
{
    protected static string $basePath;

    /**
     * @param string $sub_path
     * @return string
     */
    public static function getBasePath(string $sub_path = ''): string
    {
        return rtrim(self::$basePath, '/') . '/' . $sub_path;
    }

    /**
     * @param string $basePath
     */
    public static function setBasePath(string $basePath): void
    {
        self::$basePath = $basePath;
    }

    /**
     * @return mixed
     */
    public abstract function process();

    /**
     * @param array $excludedDirectories
     * @return RecursiveIteratorIterator
     */
    protected function getModifiableFiles(array $excludedDirectories = [])
    {
        if (!isset(self::$basePath)) {
            throw new \Exception('The base path has not been set yet, use the setBasePath method to set the path for the AkceliBootstrap Class');
        }

        $exclude = array_merge(
            config('akceli.global_bootstrap_excluded_directories', []),
            ['vendor', 'node_modules', '.git', '.idea'],
            $excludedDirectories
        );

        /**
         * @param SplFileInfo $file
         * @param mixed $key
         * @param RecursiveCallbackFilterIterator $iterator
         * @return bool True if you need to recurse or if the item is acceptable
         */
        $filter = function ($file, $key, $iterator) use ($exclude) {
            if ($iterator->hasChildren() && !in_array($file->getFilename(), $exclude)) {
                return true;
            }
            return $file->isFile();
        };

        $innerIterator = new RecursiveDirectoryIterator(
            self::getBasePath(),
            RecursiveDirectoryIterator::SKIP_DOTS
        );

        return new RecursiveIteratorIterator(
            new RecursiveCallbackFilterIterator($innerIterator, $filter)
        );
    }
}