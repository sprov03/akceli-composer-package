<?php

namespace Akceli\FileModifiers;

use Akceli\AkceliFileModifier;
use Akceli\Console;

class AkceliPhpFileModifier extends AkceliFileModifier
{
    public function getNamespaceOfFile()
    {
        preg_match('/namespace (.[^;]*);/', $this->content, $matches);

        return $matches[1] ?? null;
    }

    protected function addAbstractMethodToFile($method_name, $method_content)
    {
        $method_content = rtrim(explode('{', $method_content)[0]) . ";\n";

        $this->addMethodToFile($method_name, $method_content);
    }

    /**
     * Add Content to a class file
     *
     * @param $method
     * @param string $content
     */
    public function addMethodToFile($method, $content)
    {
        if ($this->classHasMethod($method)) {
            Console::warn("The {$method} method exists on {$this->fileInfo->getRealPath()}");

            return $this;
        }

        $content_parts = explode('}', $this->content);
        array_pop($content_parts);
        $content_parts[count($content_parts) - 1] .= PHP_EOL . $content .PHP_EOL;
        array_push($content_parts, '');
        $this->content = implode('}', $content_parts) . PHP_EOL;

        return $this;
    }

    public function addToTopOfMethod($method, $content)
    {
        if (!$this->classHasMethod($method)) {
            Console::warn("The {$method} method dose not exists in {$this->fileInfo->getRealPath()}");

            return $this;
        }

        $pattern = "/function ({$method})\(([^\)])*\)([^{])*{/";
        return $this->regexAppend($pattern, PHP_EOL . '        ' . $content);
    }

    /**
     * Add use statement to class file
     *
     * Requires a namespace in the file to work.
     *
     * @param string $namespace
     */
    public function addUseStatementToFile(string $namespace)
    {
        if (
            !$this->classHasUseStatement($namespace) &&
            $this->getNamespaceOfFile() !== $namespace
        ) {
            $this->regexAppend("/namespace([^\n])*\s*/", "use {$namespace};" . PHP_EOL);
        }

        /**
         * This is a fallback in case the class does not have a namespace, like a php script file
         */
        if (
            !$this->classHasUseStatement($namespace) &&
            $this->getNamespaceOfFile() !== $namespace
        ) {
            $this->regexAppend("/<?php([^\n])*\s*/", "use {$namespace};" . PHP_EOL);
        }

        return $this;
    }

    public function shouldUseTrait(string $namespace)
    {
        $parts = explode('\\', $namespace);
        $className = array_pop($parts);
        return $this->addUseStatementToFile($namespace)
            ->regexReplace("\n{\n", "\n{\n    use {$className};\n");
    }

    public function addClassPropertyDocToFile($doc_type, $variable)
    {
        if ($this->classHasClassDoc($variable)) {
            Console::warn("The {$variable} variable exists on {$this->fileInfo->getRealPath()}");

            return;
        }

        $file_contents = $this->content;
        $relationships = '/' . preg_quote(' * Relationships', '/') . '/';

        $file_contents = preg_replace(
            $relationships,
            " * Relationships\n * @property {$doc_type} \${$variable}",
            $file_contents
        );

        $this->content = $file_contents;
    }

    public function classHasClassDoc(string $variable): bool
    {
        return (bool) preg_match('/\* @property (.*) \$' . $variable . '\n/', $this->content);
    }

    public function classHasMethod(string $method): bool
    {
        return (bool) preg_match('/function ' . $method . '( |\()/', $this->content);
    }

    public function classHasUseStatement($namespace)
    {
        $escaped_namespace = str_replace('\\', '\\\\', $namespace);
        return (bool) preg_match("/use {$escaped_namespace};/", $this->content);
    }
}