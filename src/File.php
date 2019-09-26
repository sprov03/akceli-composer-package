<?php

namespace CrudGenerator;

class File
{
    /** @var  $appFiles \RecursiveIteratorIterator|\SplFileInfo[] */
    private $appFiles;

    private $files_path;

    /**
     * File constructor
     *
     * @param $files_path
     */
    function __construct($files_path)
    {
        $this->files_path = $files_path;
    }

    /**
     * Find file by table name
     *
     * @param string $table_name
     *
     * @param bool $reload
     *
     * @return mixed|null|\SplFileInfo
     */
    public function findByTableName($table_name, $reload = false)
    {
        $files = $this->getAppFiles($reload);

        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }

            if (str_contains(file_get_contents($file), "protected \$table = '{$table_name}';")) {
                return $file;
            }
        }

        return $this->findByClassName(studly_case(str_singular($table_name)));
    }

    /**
     * @param string $className
     *
     * @return mixed|null|\SplFileInfo
     */
    public function findByClassName($className)
    {
        foreach ($this->getAppFiles() as $file) {
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
     * @return mixed|null|\SplFileInfo
     */
    public function findFileByFullyQualifiedClassName($className)
    {
        foreach ($this->getAppFiles() as $file) {
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
     * @return \RecursiveIteratorIterator|\SplFileInfo[]
     */
    public function getAppFiles($reload = false)
    {
        if (isset($this->appFiles) && ! $reload) {
            return $this->appFiles;
        }

        $this->appFiles = self::getDirectoryFiles($this->files_path);

        return $this->appFiles;
    }

    /**
     * @param string $path
     *
     * @return \RecursiveIteratorIterator|\SplFileInfo[]
     */
    private static function getDirectoryFiles($path)
    {
        return new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
    }
}
