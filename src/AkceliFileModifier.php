<?php

namespace Akceli;

use Akceli\Console;
use SplFileInfo;

class AkceliFileModifier
{
    /**
     * @var SplFileInfo
     */
    private $fileInfo;

    /**
     * @var string
     */
    private $content;

    /**
     * @param \SplFileInfo|string $file
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function file($file)
    {
        if (is_string($file)) {
            $file = new \SplFileInfo($file);
        }

        if (!$file instanceof \SplFileInfo) {
            throw new \Illuminate\Contracts\Filesystem\FileNotFoundException('File Not Found');
        }

        return new AkceliFileModifier($file);
    }

    /**
     * AkceliFileModifier constructor.
     * @param SplFileInfo $fileInfo
     */
    public function __construct(SplFileInfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;
        $this->content = file_get_contents($fileInfo->getRealPath());
    }

    public function getNamespaceOfFile()
    {
        preg_match('/namespace (.[^;]*);/', $this->content, $matches);

        return $matches[1];
    }

    protected function addAbstractMethodToFile($method_name, $method_content)
    {
        $method_content = rtrim(explode('{', $method_content)[0]) . ";\n";

        $this->addMethodToFile($method_name, $method_content);
    }

    /**
     * Add Content to a class file
     *
     * @param SplFileInfo $fileInfo
     * @param $method
     * @param string $content
     */
    public function addMethodToFile($method, $content)
    {
        if ($this->classHasMethod($method)) {
            Console::warn("The {$method} method exists on {$this->fileInfo->getRealPath()}");

            return;
        }

        $content_parts = explode('}', $this->content);
        array_pop($content_parts);
        $content_parts[count($content_parts) - 1] .= $content;
        array_push($content_parts, '');
        $this->content = implode('}', $content_parts);
    }

    /**
     * Add use statement to class file
     *
     * @param string $namespace
     */
    public function addUseStatementToFile(string $namespace)
    {
        if (
            !$this->classHasUseStatement($namespace) &&
            $this->getNamespaceOfFile() !== $namespace
        ) {
            $pattern = '/' . preg_quote('use', '/') . '/';
            $this->content = preg_replace($pattern, "use {$namespace};\nuse", $this->content, 1);
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

    public function addLineAbove(string $search, string $new_content)
    {
        return $this->regexPrepend("/([^\n])*{$search}([^\n])*/", $new_content . PHP_EOL);
    }

    public function addLineBelow(string $search, string $new_content)
    {
        return $this->regexAppend("/([^\n])*{$search}([^\n])*/", PHP_EOL . $new_content);
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

    public function saveChanges()
    {
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

    private function regexReplace(string $pattern, string $replacement)
    {
        $pattern = '/' . preg_quote($pattern, '/') . '/';
        $this->content = preg_replace($pattern, $replacement, $this->content, 1);

        return $this;
    }

    private function regexAppend($pattern, string $new_content)
    {
        preg_match($pattern, $this->content, $matches);
        if (isset($matches[0])) {
            $replacement = $matches[0] . $new_content;
            $this->content = preg_replace($pattern, $replacement, $this->content, 1);
        }

        return $this;
    }

    private function regexPrepend($pattern, string $new_content)
    {
        preg_match($pattern, $this->content, $matches);
        if (isset($matches[0])) {
            $replacement = $new_content . $matches[0];
            $this->content = preg_replace($pattern, $replacement, $this->content, 1);
        }

        return $this;
    }
}
