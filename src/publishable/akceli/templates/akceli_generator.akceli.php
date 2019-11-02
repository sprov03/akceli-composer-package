<?php echo '<?php' . PHP_EOL;
/**
 * @var $GeneratorName
 */
?>

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class <?=$GeneratorName?>Generator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return false;
    }

    public function dataPrompter(): array
    {
        return [
            'GeneratorName' => function() {
                return Console::ask('What is the name of the new Generator?');
            }
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('akceli_generator', 'akceli/generators/[[GeneratorName]]Generator.php'),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            // Akceli::inlineTemplate('template_name', 'destination_path', 'identifier string')
        ];
    }

    public function completionMessage(array $data)
    {
        Console::info('Success');
    }
}
