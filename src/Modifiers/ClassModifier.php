<?php

namespace Akceli\Modifiers;

use Akceli\FileService;
use Akceli\Console;
use Akceli\Parser;
use Akceli\Schema\SchemaInterface;
use SplFileInfo;

class ClassModifier
{
    /** @var Parser  */
    protected $parser;

    /** @var mixed|null|SplFileInfo  */
    protected $fileInfo;

    /** @var bool */
    protected $force;

    /** @var SchemaInterface  */
    protected $schema;

    /**
     * ClassModifier constructor
     *
     * @param Parser $parser
     * @param SchemaInterface $schema
     * @param bool $force
     */
    public function __construct(Parser $parser, SchemaInterface $schema, $force = false)
    {
        $this->fileInfo = FileService::findByTableName($schema->getTable());
        $this->parser = $parser;
        $this->schema = $schema;
        $this->force = $force;
    }

    public function setPolymorphicRelationships($interface)
    {
        Console::error("{$interface} was not set because ' .
            'setBelongsToManyRelationships not yet implemented");
    }

    public function setBelongsToManyRelationship($relationship)
    {
        Console::error("{$relationship->foreign_key} was not set because ' .
            'setBelongsToManyRelationships not yet implemented");
    }

    public static function getNamespaceOfFile(SplFileInfo $fileInfo)
    {
        preg_match('/namespace (.[^;]*);/', file_get_contents($fileInfo->getRealPath()), $matches);

        return $matches[1];
    }

    protected function addAbstractMethodToFile(SplFileInfo $fileInfo, $method_name, $method_content)
    {
        $method_content = rtrim(explode('{', $method_content)[0]) . ";\n";

        $this->addMethodToFile($fileInfo, $method_name, $method_content);
    }

    protected function putFile($content, $path)
    {
        file_put_contents($path, $content);

        return new SplFileInfo($path);
    }

    /**
     * Add Content to a class file
     *
     * @param SplFileInfo $fileInfo
     * @param $method
     * @param string $content
     */
    public function addMethodToFile(SplFileInfo $fileInfo, $method, $content)
    {
        if ($this->classHasMethod($fileInfo, $method)) {
            Console::warn("The {$method} method exists on {$fileInfo->getRealPath()}");

            return;
        }

        $file_contents = file_get_contents($fileInfo->getRealPath());
        $content_parts = explode('}', $file_contents);
        array_pop($content_parts);
        $content_parts[count($content_parts) -1] .= $content;
        array_push($content_parts, '');

        file_put_contents($fileInfo->getRealPath(), implode('}', $content_parts));
    }

    /**
     * Add use statement to class file
     *
     * @param SplFileInfo $from
     * @param SplFileInfo $to
     */
    public function addUseStatementToFile(SplFileInfo $to, SplFileInfo $from)
    {
        $namespace = self::getNamespaceOfFile($from);
        $fullNamespace = $namespace . '\\' . str_replace('.php', '', $from->getFilename());
        if (
            ! $this->classHasUseStatement($to, $fullNamespace) &&
            self::getNamespaceOfFile($to) !== self::getNamespaceOfFile($from)
        ) {
            $file_contents = file_get_contents($to->getRealPath());
            $use = '/' . preg_quote('use', '/') . '/';

            $file_contents = preg_replace($use, "use {$fullNamespace};\nuse", $file_contents, 1);
            file_put_contents($to->getRealPath(), $file_contents);
        }
    }

    public function addClassPropertyDocToFile(SplFileInfo $fileInfo, $doc_type, $variable)
    {
        if ($this->classHasClassDoc($fileInfo, $variable)) {
            Console::warn("The {$variable} variable exists on {$fileInfo->getRealPath()}");

            return;
        }

        $file_contents = file_get_contents($fileInfo->getRealPath());
        $relationships = '/' . preg_quote(' * Relationships', '/') . '/';

        $file_contents = preg_replace(
            $relationships,
            " * Relationships\n * @property {$doc_type} \${$variable}",
            $file_contents
        );

        file_put_contents($fileInfo->getRealPath(), $file_contents);
    }

    public function classHasClassDoc(SplFileInfo $fileInfo, $variable)
    {
        $file_contents = file_get_contents($fileInfo->getRealPath());

        return self::stringHasClassDoc($file_contents, $variable);
    }

    public static function stringHasClassDoc($class_string, $variable)
    {
        return (bool) preg_match('/\* @property (.*) \$' . $variable . '\n/', $class_string);
    }

    public function classHasMethod(SplFileInfo $fileInfo, $method)
    {
        $file_contents = file_get_contents($fileInfo->getRealPath());

        return self::stringHasMethod($file_contents, $method);
    }

    public static function stringHasMethod($class_string, $method)
    {
        return (bool) preg_match('/function ' . $method . '( |\()/', $class_string);
    }

    public function classHasUseStatement(SplFileInfo $fileInfo, $namespace)
    {
        $file_contents = file_get_contents($fileInfo->getRealPath());

        return self::stringHasUseStatement($file_contents, $namespace);
    }

    public static function stringHasUseStatement($class_string, $namespace)
    {
        $escaped_namespace = str_replace('\\', '\\\\', $namespace);
        return (bool) preg_match("/use {$escaped_namespace};/", $class_string);
    }
}
