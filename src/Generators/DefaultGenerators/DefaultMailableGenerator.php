<?php

namespace Akceli\Generators\DefaultGenerators;

use Akceli\Generators\AkceliGenerator;

use Akceli\Akceli;
use Akceli\Console;

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
                Console::info('Markdown Messages Documentation: https://laravel.com/docs/6.x/mail#writing-markdown-messages');
                return $data['arg1'] ?? Console::ask('What is the name of the Mailable?');
            },
            'mailable_type' => function (array $data) {
                return Console::choice('Is [[Mailable]]Mailable using view or markdown?', ['markdown', 'view'], 'markdown');
            },
            'markdown_path' => function (array $data) {
                return Console::ask('What is the path for the markdown file? example (example will be placed in resources/views/email/example)');
            },
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('mailable', 'app/Mail/[[Mailable]]Mailable.php'),
            Akceli::fileTemplate('mailable_markdown', 'resources/views/emails/[[markdown_path]].blade.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
