<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

interface AkceliGeneratorInterface
{
    public function requiresTable(): bool;

    public function dataPrompter(): array;

    public function templates(array $data): array;

    public function inlineTemplates(array $data): array;

    public function fileModifiers(array $data): array;

    public function completionMessage(array $data);
}