<?php

namespace Akceli;

use Akceli\Modifiers\ClassModifier;
use RecursiveIteratorIterator;
use SplFileInfo;

class FileService
{
    /**
     * @var string
     */
    private static $root_path;

    /** @var  $appFiles RecursiveIteratorIterator|SplFileInfo[] */
    private static $appFiles;

    public static function setRootDirectory(string $root_path)
    {
        self::$root_path = $root_path;
    }

    /**
     * Find file by table name
     *
     * @param string $table_name
     *
     * @param bool $reload
     *
     * @return mixed|null|SplFileInfo
     */
    public static function findByTableName($table_name, $reload = false)
    {
        $files = self::getAppFiles($reload);

        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }

            if (str_contains(file_get_contents($file), "protected \$table = '{$table_name}';")) {
                return $file;
            }
        }

        return self::findByClassName(studly_case(str_singular($table_name)));
    }

    /**
     * @param string $className
     *
     * @param bool $reload
     * @return mixed|null|SplFileInfo
     */
    public static function findByClassName($className, $reload = false)
    {
        foreach (self::getAppFiles($reload) as $file) {
            if ($file->isDir()) {
                continue;
            }

            if ($className . '.php' == $file->getFilename()) {
                return $file;
            }
        }

        return null;
    }

    /**
     * @param string $className
     *
     * @param bool $reload
     * @return mixed|null|SplFileInfo
     */
    public static function findFileByFullyQualifiedClassName($className, $reload = false)
    {
        foreach (self::getAppFiles($reload) as $file) {
            if ($file->isDir()) {
                continue;
            }

            if ($className == $file->getFilename()) {
                $namespaceParts = explode('\\', $className);
                array_pop($namespaceParts);
                $namespace = implode('\\', $namespaceParts);

                if (ClassModifier::getNamespaceOfFile($file) === $namespace) {
                    return $file;
                }
            }
        }

        return null;
    }

    /**
     *
     * @param bool $reload
     *
     * @return RecursiveIteratorIterator|SplFileInfo[]
     */
    public static function getAppFiles($reload = false)
    {
        if (isset(self::$appFiles) && ! $reload) {
            return self::$appFiles;
        }

        self::$appFiles = self::getDirectoryFiles(self::$root_path);

        return self::$appFiles;
    }

    /**
     * @param string $path
     *
     * @return RecursiveIteratorIterator|SplFileInfo[]
     */
    private static function getDirectoryFiles($path)
    {
        return new RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
    }
}
