<?php

namespace Akceli;

use Akceli\Modifiers\ClassModifier;
use Illuminate\Http\File;
use phpDocumentor\Reflection\Types\Self_;
use RecursiveIteratorIterator;
use Illuminate\Support\Str;
use SplFileInfo;

class FileService
{
    /**
     * @var string
     */
    private static $root_path;

    /** @var  $appFiles RecursiveIteratorIterator|SplFileInfo[]|null */
    private static $appFiles;
    private static array $definedNamespaces;

    public static function setRootDirectory(string $root_path)
    {
        self::$root_path = $root_path;
        self::$appFiles = null;
    }

    /**
     * @param SplFileInfo $file
     * @return string
     */
    public static function getClassNameOfFile(SplFileInfo $file)
    {
        return $file->getBasename('.' . $file->getExtension());
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

            if (Str::contains(file_get_contents($file), "protected \$table = '{$table_name}';")) {
                return $file;
            }
        }

        $file = self::findByClassName(Str::studly(Str::singular($table_name)));

        if ($reload === false && is_null($file)) {
            return self::findByTableName($table_name, true);
        } else {
            return $file;
        }
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
     * @param bool $reload
     *
     * @return RecursiveIteratorIterator|SplFileInfo[]
     */
    public static function getAppFiles($reload = false)
    {
        if (isset(self::$appFiles) && self::$appFiles !== null && ! $reload) {
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

    public static function putFile(string $path, string $content)
    {
        $nodes = explode('/', $path);

        $path = base_path();
        $file_name = array_pop($nodes);

        foreach ($nodes as $node) {
            $path .= "/{$node}";

            if (! file_exists($path)) {
                mkdir($path);
            }
        }

        $path .= "/{$file_name}";

        file_put_contents($path, $content);
    }

    private static function getDefinedNamespaces()
    {
        if (isset(self::$definedNamespaces)) {
            return self::$definedNamespaces;
        }

        $composerJsonPath = base_path('composer.json');
        $composerConfig = json_decode(file_get_contents($composerJsonPath));

        //Apparently PHP doesn't like hyphens, so we use variable variables instead.
        $psr4 = "psr-4";
        $autoloadDev = "autoload-dev";
        $namespaces = $composerConfig->autoload->$psr4;
        $devNamespaces = $composerConfig->$autoloadDev->$psr4;
        self::$definedNamespaces = array_merge((array) $namespaces, (array) $devNamespaces);

        return self::$definedNamespaces;
    }

    public static function getExpectedNamespaceOfFile(SplFileInfo $fileInfo): string
    {
        $path_in_project = str_replace(base_path() . '/', '', $fileInfo->getRealPath());
        self::getDefinedNamespaces();
        foreach (self::getDefinedNamespaces() as $namespace => $namespace_path) {
            if (Str::startsWith($path_in_project, $namespace_path)) {
                $path_in_project = str_replace($namespace_path, $namespace, $path_in_project);
                $path_in_project = str_replace('/', '\\', $path_in_project);
                $path_in_project = str_replace('.php', '', $path_in_project);
                return $path_in_project;
            }
        }

        return $path_in_project;
    }

    /**
     * @param string $trait
     * @param bool $include_abstract_classes
     * @return \Illuminate\Support\Collection|SplFileInfo[]
     */
    public static function getFilesThatExtend(string $full_class_name, bool $include_abstract_classes = false)
    {
        $acceptedFiles = collect();
        foreach (self::getAppFiles() as $fileInfo) {
            try {
                /** ignore directories */
                if ($fileInfo->isDir()) {
                    continue;
                }

                $classReflector = new \ReflectionClass(self::getExpectedNamespaceOfFile($fileInfo));
                continue;
            } catch (\Throwable $throwable) {
                continue;
            }

            /**
             * Dont include abstract classes if they not explicitly asked to be included
             */
            if (!$include_abstract_classes) {
                if ($classReflector->isAbstract()) {
                    continue;
                }
            }

            if ($classReflector->isSubclassOf($full_class_name)) {
                $acceptedFiles->push($fileInfo);
            }
        }

        return  $acceptedFiles;
    }

    /**
     * @param string $trait
     * @param bool $include_abstract_classes
     * @return \Illuminate\Support\Collection|SplFileInfo[]
     */
    public static function getFilesThatUseTrait(string $trait, bool $include_abstract_classes = false)
    {
        $acceptedFiles = collect();
        foreach (self::getAppFiles() as $fileInfo) {
            try {
                /** ignore directories */
                if ($fileInfo->isDir()) {
                    continue;
                }

                $classReflector = new \ReflectionClass(self::getExpectedNamespaceOfFile($fileInfo));
            } catch (\Throwable $throwable) {
                continue;
            }

            /**
             * Dont include abstract classes if they not explicitly asked to be included
             */
            if (!$include_abstract_classes) {
                if ($classReflector->isAbstract()) {
                    continue;
                }
            }

            /** ignore traits and interfaces */
            if ($classReflector->isTrait() || $classReflector->isInterface()) {
                continue;
            }

            foreach ($classReflector->getTraits() as $modelTrait) {
                if ($modelTrait->getName() === $trait) {
                    $acceptedFiles->push($fileInfo);
                }
            }
        }

        return  $acceptedFiles;
    }
}
