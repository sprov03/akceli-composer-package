<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;
use Illuminate\Support\Str;

class DefaultMailableGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Mailable' => function (array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the Mailable?', 'ExampleMailable');
            },
            'mailable_type' => function (array $data) {
                return Console::choice('Is [[Mailable]] using view or markdown?', ['markdown', 'view'], 'markdown');
            },
            'mailable_path' => function (array $data) {
                $path = Str::kebab(str_replace('Mailable', '', $data['Mailable']));
                return Console::ask('What is the path for the markdown file? (' . $path . ' will be create in resources/views/email/' . $path . '.blade.php)', $path);
            },
            'mailable_dot_syntax' => function (array $data) {
                $parts = explode('/', $data['mailable_path']);
                $parts = array_map(function (string $part) {
                    return Str::kebab($part);
                }, $parts);
                return implode('.', $parts);
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('mailable', 'app/Mail/[[Mailable]]Mailable.php'),
            Akceli::fileTemplate('mailable_markdown', 'resources/views/emails/[[mailable_path]].blade.php'),
        ];
    }

    public function fileModifiers(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Console::info('Markdown Messages Documentation: https://laravel.com/docs/6.x/mail#writing-markdown-messages');
    }
}
