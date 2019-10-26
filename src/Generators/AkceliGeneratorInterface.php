<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

interface AkceliGeneratorInterface
{
    public function requiresTable(): bool;

    public function dataPrompter(): array;

    public function templates(): array;

    public function inlineTemplates(): array;

    public function completionMessage();
}