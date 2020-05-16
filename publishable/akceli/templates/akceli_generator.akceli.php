<?php echo '<?php' . PHP_EOL;
/**
 * @var $Generator
 */
?>

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class [[Generator]] extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'Generator' => function(array $data) {
                return $data['arg1'] ?? Console::ask('What is the name of the new Generator?', 'ExampleGenerator');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('akceli_generator', 'akceli/generators/[[Generator]].php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
