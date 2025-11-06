# Building Custom Generators with Akceli

## Table of Contents
1. [Introduction](#introduction)
2. [Understanding the Generator Anatomy](#understanding-the-generator-anatomy)
3. [Creating Your First Generator](#creating-your-first-generator)
4. [Template Syntax Guide](#template-syntax-guide)
5. [Working with Database Schema](#working-with-database-schema)
6. [Advanced Features](#advanced-features)
7. [Best Practices](#best-practices)
8. [Real-World Examples](#real-world-examples)

## Introduction

Akceli is a meta-framework for code generation. Instead of providing generic generators that try to fit every project, Akceli gives you the tools to build generators that match **your team's specific patterns and conventions**.

### When to Build a Custom Generator

Build a custom generator when you:
- Repeatedly create similar files with predictable patterns
- Have team conventions that differ from Laravel defaults
- Want to enforce architectural decisions through code generation
- Need to generate multiple related files at once (controller + test + request + resource)

### Philosophy

> **Akceli generators are meant to be project-specific, not universal.**

The example generators in the package are there for **reference and inspiration**, not as one-size-fits-all solutions.

## Understanding the Generator Anatomy

Every Akceli generator extends `AkceliGenerator` and implements five key methods:

```php
<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class MyCustomGenerator extends AkceliGenerator
{
    // 1. Does this generator need a database table?
    public function requiresTable(): bool
    {
        return true; // or false
    }

    // 2. What data do we need to collect?
    public function dataPrompter(): array
    {
        return [
            'key' => function(array $data) {
                return 'value';
            }
        ];
    }

    // 3. What files should we create?
    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate('template_name', 'destination/path.php')
        ];
    }

    // 4. What existing files should we modify?
    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::insertInline('file.php', '/** marker */', 'code to insert')
        ];
    }

    // 5. What message to show when complete?
    public function completionMessage(array $data): void
    {
        Console::info('Success!');
    }
}
```

## Creating Your First Generator

Let's build a complete generator for creating service classes with tests and mocks.

### Step 1: Generate the Generator Boilerplate

```bash
php artisan akceli new-generator MyService
```

This creates: `akceli/generators/MyServiceGenerator.php`

### Step 2: Configure the Generator

```php
<?php

namespace Akceli\Generators;

use Akceli\Akceli;
use Akceli\Console;

class MyServiceGenerator extends AkceliGenerator
{
    /**
     * This generator doesn't need database table information
     */
    public function requiresTable(): bool
    {
        return false;
    }

    /**
     * Collect the data we need
     */
    public function dataPrompter(): array
    {
        return [
            // Service name - can be passed as argument or prompted
            'Service' => function (array $data) {
                return $data['arg1'] ?? Console::ask(
                    'What is the service name?', 
                    'PaymentService'
                );
            },
            
            // Automatically derive the interface name
            'ServiceInterface' => function (array $data) {
                return $data['Service'] . 'Interface';
            },
            
            // Ask if they want caching
            'with_cache' => function (array $data) {
                return Console::choice(
                    'Enable caching support?',
                    ['yes', 'no'],
                    'no'
                ) === 'yes';
            }
        ];
    }

    /**
     * Define which files to create
     */
    public function templates(array $data): array
    {
        $templates = [
            Akceli::fileTemplate(
                'service',
                'app/Services/[[Service]]/[[Service]].php'
            ),
            Akceli::fileTemplate(
                'service_interface',
                'app/Services/[[Service]]/[[ServiceInterface]].php'
            ),
            Akceli::fileTemplate(
                'service_test',
                'tests/Services/[[Service]]/[[Service]]Test.php'
            ),
        ];

        // Conditionally add cache decorator if requested
        if ($data['with_cache']) {
            $templates[] = Akceli::fileTemplate(
                'service_cache_decorator',
                'app/Services/[[Service]]/Cached[[Service]].php'
            );
        }

        return $templates;
    }

    /**
     * Update existing files (like registering in service provider)
     */
    public function inlineTemplates(array $data): array
    {
        return [
            // Add import statement
            Akceli::insertInline(
                'app/Providers/AppServiceProvider.php',
                '/** Auto Import Services */',
                'use App\Services\[[Service]]\[[Service]];'
            ),
            
            // Register binding
            Akceli::insertInline(
                'app/Providers/AppServiceProvider.php',
                '/** Register Services */',
                '$this->app->singleton([[Service]]::class);'
            ),
        ];
    }

    /**
     * Show helpful information when complete
     */
    public function completionMessage(array $data): void
    {
        Console::info('Service created successfully!');
        Console::info('');
        Console::warn('Next steps:');
        Console::info('1. Implement your service methods');
        Console::info('2. Write tests in: tests/Services/' . $data['Service']);
        
        if ($data['with_cache']) {
            Console::info('3. Configure cache TTL in your .env');
        }
    }
}
```

### Step 3: Create the Templates

Create `akceli/templates/service.akceli.php`:

```php
<?php echo '<?php' . PHP_EOL;
/**
 * Available variables:
 * @var string $Service - The service name (e.g., "PaymentService")
 * @var string $ServiceInterface - The interface name
 * @var bool $with_cache - Whether caching is enabled
 */
?>

namespace App\Services\[[Service]];

use Illuminate\Support\Facades\Log;

/**
 * [[Service]]
 * 
 * Handles business logic for [[Service]]
 */
class [[Service]] implements [[ServiceInterface]]
{
    /**
     * Create a new service instance
     */
    public function __construct()
    {
        //
    }

    /**
     * Example method - replace with your actual implementation
     */
    public function process(array $data): array
    {
        Log::info('[[Service]] processing', ['data' => $data]);
        
        // Your business logic here
        
        return [
            'success' => true,
            'message' => 'Processed successfully'
        ];
    }
}
```

Create `akceli/templates/service_interface.akceli.php`:

```php
<?php echo '<?php' . PHP_EOL; ?>

namespace App\Services\[[Service]];

/**
 * [[ServiceInterface]]
 * 
 * Contract for [[Service]] implementations
 */
interface [[ServiceInterface]]
{
    /**
     * Process the given data
     *
     * @param array $data
     * @return array
     */
    public function process(array $data): array;
}
```

Create `akceli/templates/service_test.akceli.php`:

```php
<?php echo '<?php' . PHP_EOL; ?>

namespace Tests\Services\[[Service]];

use App\Services\[[Service]]\[[Service]];
use Tests\TestCase;

class [[Service]]Test extends TestCase
{
    protected [[Service]] $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app([[Service]]::class);
    }

    /** @test */
    public function it_processes_data_successfully(): void
    {
        // Arrange
        $data = ['key' => 'value'];

        // Act
        $result = $this->service->process($data);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('Processed successfully', $result['message']);
    }
}
```

### Step 4: Register the Generator

Edit `config/akceli.php`:

```php
'generators' => [
    'service' => MyServiceGenerator::class,
    // ... other generators
],
```

### Step 5: Use Your Generator

```bash
# With argument
php artisan gen service PaymentService

# Or let it prompt you
php artisan gen service
```

## Template Syntax Guide

Templates use PHP syntax to generate code. Here's the complete reference:

### Variable Interpolation

```php
// Simple variable
[[VariableName]]

// In PHP string context
$className = '[[ClassName]]';

// In text context
namespace App\Models\[[ModelName]];
```

### PHP Execution Blocks

```php
<?php 
// Any PHP code here
echo '<?php' . PHP_EOL;
?>
```

### Outputting PHP Tags

Since templates ARE PHP, you need special handling for PHP tags:

```php
// To output: <?php
<?php echo '<?php' . PHP_EOL; ?>

// To output: ?>
<?php echo '?>' . PHP_EOL; ?>
```

### Conditional Logic

```php
<?php if ($condition): ?>
    This appears if true
<?php else: ?>
    This appears if false
<?php endif; ?>

// Inline conditional
<?php if ($data['with_cache']): ?>with cache<?php endif; ?>
```

### Loops

```php
<?php foreach ($table->columns as $column): ?>
    public $<?=$column->getField()?>;
<?php endforeach; ?>

// With index
<?php foreach ($items as $index => $item): ?>
    // Item #<?=$index?>: <?=$item?>
<?php endforeach; ?>
```

### Checking for Last Item

```php
<?php foreach ($items as $item): ?>
<?php if ($items->last() === $item): ?>
    '<?=$item?>'  // No comma on last item
<?php else: ?>
    '<?=$item?>',
<?php endif; ?>
<?php endforeach; ?>
```

### Multi-line PHP Logic

```php
<?php
$hasRequiredColumns = $table->columns->filter(function ($column) {
    return !$column->isNullable();
})->count() > 0;

$computedValue = calculateSomething($data);
?>

// Now use the computed values
<?php if ($hasRequiredColumns): ?>
    // Generated code here
<?php endif; ?>
```

### Comments

```php
<?php 
/**
 * This is a comment in the template - won't appear in generated code
 */
?>

// This comment WILL appear in generated code
```

### Generating Blade Templates

When generating Blade files, be careful with delimiters:

```php
// Template file: form.akceli.php
<div>
    @if($condition)
        <p>User: {{ $user->name }}</p>
    @endif
    
    <?php foreach ($fields as $field): ?>
    <input name="<?=$field?>" />
    <?php endforeach; ?>
</div>
```

The `@if` and `{{ }}` will be in the generated file, while `<?php ?>` is processed during generation.

## Working with Database Schema

When `requiresTable()` returns `true`, your generator gets a `$table` object with rich database information.

### Available Table Data

```php
// In your template, you have access to:
$table->table_name;        // 'users'
$table->ModelName;         // 'User'
$table->modelName;         // 'user'
$table->modelNames;        // 'users'
$table->ModelNames;        // 'Users'
$table->columns;           // Collection of columns
$table->primaryKey;        // 'id'
```

### Working with Columns

```php
<?php foreach ($table->columns as $column): ?>
    // Column methods:
    $column->getField();              // 'email_address'
    $column->Field;                   // 'email_address' (alias)
    $column->isNullable();            // true/false
    $column->isString();              // true/false
    $column->isInteger();             // true/false
    $column->isBoolean();             // true/false
    $column->isTimeStamp();           // true/false
    $column->isEnum();                // true/false
    $column->hasValidationRules();    // true/false
    $column->getValidationRulesAsString();  // 'required|email'
    $column->getValidationRulesAsArray();   // ['required', 'email']
    
    // Custom trait methods (from AkceliColumnTrait):
    $column->isRelation();            // true if ends with '_id'
    $column->toRelation();            // 'user_id' -> 'user'
    $column->getClientLabel();        // 'email_address' -> 'Email Address'
    $column->startsWith('prefix_');   // true/false
    $column->endsWith('_suffix');     // true/false
    
    // Column settings (from config):
    $column->getColumnSetting('php_class_doc_type', 'string');
    $column->getColumnSetting('casts', 'string');
<?php endforeach; ?>
```

### Filtering Columns

```php
// Filter out timestamp columns
<?php 
$nonTimestampColumns = $table->columns->filter(function($column) {
    return !$column->isTimeStamp();
});
?>

// Get only relationship columns
<?php
$relationships = $table->columns->filter(function($column) {
    return $column->endsWith('_id');
});
?>

// Get required columns
<?php
$required = $table->columns->filter(function($column) {
    return !$column->isNullable();
});
?>
```

### Example: Generating Validation Rules

```php
public function rules(): array
{
    return [
<?php foreach ($table->columns as $column): ?>
<?php if ($column->hasValidationRules()): ?>
        '<?=$column->getField()?>' => <?=$column->getValidationRulesAsArray()?>,
<?php endif; ?>
<?php endforeach; ?>
    ];
}
```

### Example: Generating Relationships

```php
<?php foreach ($table->columns as $column): ?>
<?php if ($column->isRelation()): ?>
    /**
     * Relationship: <?=$column->getField() . PHP_EOL?>
     */
    public function <?=$column->toRelation()?>()
    {
        return $this->belongsTo(<?=Str::studly($column->toRelation())?>::class);
    }

<?php endif; ?>
<?php endforeach; ?>
```

## Advanced Features

### Data Prompting Strategies

The `dataPrompter()` method is very flexible:

```php
public function dataPrompter(): array
{
    return [
        // 1. Command line argument
        'Name' => function(array $data) {
            return $data['arg1'] ?? Console::ask('Enter name:');
        },
        
        // 2. Multiple choice
        'Type' => function(array $data) {
            return Console::choice(
                'Select type:',
                ['API', 'Web', 'Console'],
                'API'
            );
        },
        
        // 3. Computed from other data
        'Namespace' => function(array $data) {
            return 'App\\Services\\' . $data['Name'];
        },
        
        // 4. Conditional prompting
        'CacheKey' => function(array $data) {
            if ($data['Type'] === 'API') {
                return Console::ask('Cache key:', 'api_cache');
            }
            return null;
        },
        
        // 5. Access table data (when requiresTable = true)
        'Validator' => function(array $data) {
            return $data['ModelName'] . 'Validator';
        },
        
        // 6. Boolean from choice
        'with_tests' => function(array $data) {
            $options = ['yes' => true, 'no' => false];
            return $options[Console::choice('Include tests?', ['yes', 'no'], 'yes')];
        },
    ];
}
```

### Template Composition

You can include one template from another:

```php
// In parent template:
public function rules(): array
{
    return [
<?=$this->fetch('validation_rules')?>
    ];
}

// Create: akceli/templates/validation_rules.akceli.php
<?php foreach ($table->columns as $column): ?>
<?php if ($column->hasValidationRules()): ?>
        '<?=$column->getField()?>' => <?=$column->getValidationRulesAsArray()?>,
<?php endif; ?>
<?php endforeach; ?>
```

### Inline Template Strategies

Inline templates modify existing files. Use them for:

#### 1. Adding Imports

```php
Akceli::insertInline(
    'app/Providers/AppServiceProvider.php',
    '/** Auto Import */',
    'use App\Services\[[Service]]\[[Service]];'
)
```

The marker `/** Auto Import */` should exist in your file:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
/** Auto Import */

class AppServiceProvider extends ServiceProvider
{
    // ...
}
```

#### 2. Adding Configuration

```php
Akceli::insertInline(
    'config/services.php',
    "'facebook' => [",
    <<<'PHP'
    
        'api_key' => env('FACEBOOK_API_KEY'),
        'api_secret' => env('FACEBOOK_API_SECRET'),
    PHP
)
```

#### 3. Registering Routes

```php
Akceli::insertInline(
    'routes/api.php',
    '/** Register API Routes */',
    '[[ModelName]]Controller::apiRoutes();'
)
```

#### 4. Inline Template with Template Syntax

```php
Akceli::inlineTemplate(
    'request_docs',                           // Template name
    'app/Http/Requests/Create[[ModelName]]Request.php',  // File to modify
    'class Create[[ModelName]]Request extends FormRequest'  // Identifier
)
```

This inserts the rendered template BEFORE the identifier line.

### Conditional File Generation

```php
public function templates(array $data): array
{
    $templates = [
        Akceli::fileTemplate('service', 'app/Services/[[Service]].php'),
    ];

    // Only generate if requested
    if ($data['with_tests']) {
        $templates[] = Akceli::fileTemplate(
            'service_test',
            'tests/Services/[[Service]]Test.php'
        );
    }

    // Only generate for API services
    if ($data['type'] === 'API') {
        $templates[] = Akceli::fileTemplate(
            'api_docs',
            'docs/[[Service]].md'
        );
    }

    return $templates;
}
```

### Multi-File Generators

Some generators should create multiple related files:

```php
public function templates(array $data): array
{
    return [
        // Controller
        Akceli::fileTemplate(
            'controller',
            'app/Http/Controllers/[[ModelName]]Controller.php'
        ),
        
        // Requests
        Akceli::fileTemplate(
            'create_request',
            'app/Http/Requests/Create[[ModelName]]Request.php'
        ),
        Akceli::fileTemplate(
            'update_request',
            'app/Http/Requests/Update[[ModelName]]Request.php'
        ),
        
        // Resource
        Akceli::fileTemplate(
            'resource',
            'app/Http/Resources/[[ModelName]]Resource.php'
        ),
        
        // Tests
        Akceli::fileTemplate(
            'controller_test',
            'tests/Http/Controllers/[[ModelName]]ControllerTest.php'
        ),
    ];
}
```

## Best Practices

### 1. Use Clear Naming Conventions

```php
// Good - Clear what it generates
MyApiControllerGenerator
MyRepositoryPatternGenerator
MyEventSourcingGenerator

// Bad - Too generic
MyGenerator
CodeGenerator
FileGenerator
```

### 2. Organize Templates by Purpose

```
akceli/templates/
├── api/
│   ├── controller.akceli.php
│   ├── request.akceli.php
│   └── resource.akceli.php
├── repositories/
│   ├── repository.akceli.php
│   └── repository_interface.akceli.php
└── tests/
    ├── feature_test.akceli.php
    └── unit_test.akceli.php
```

Reference them:
```php
Akceli::fileTemplate('api/controller', 'app/Http/Controllers/...')
```

### 3. Provide Good Defaults

```php
'Service' => function(array $data) {
    return $data['arg1'] ?? Console::ask(
        'Service name?',
        'PaymentService'  // ← Good default
    );
}
```

### 4. Add Helpful Completion Messages

```php
public function completionMessage(array $data): void
{
    Console::info('✓ Service created successfully!');
    Console::info('');
    Console::warn('Next steps:');
    Console::info('  1. Implement methods in: app/Services/' . $data['Service']);
    Console::info('  2. Run tests: php artisan test --filter=' . $data['Service']);
    Console::info('');
    Console::info('Documentation: https://your-docs.com/services');
}
```

### 5. Use Column Settings for Consistency

Define in `config/akceli.php`:

```php
'column-settings' => [
    'typescript_type' => Akceli::columnSetting(
        'string',   // string columns
        'number',   // integer columns
        'string',   // enum columns
        'string',   // enum columns
        'Date',     // timestamp columns
        'boolean'   // boolean columns
    ),
],
```

Use in templates:

```php
interface I[[ModelName]] {
<?php foreach ($table->columns as $column): ?>
    <?=$column->getField()?>: <?=$column->getColumnSetting('typescript_type', 'any')?>;
<?php endforeach; ?>
}
```

### 6. Handle Edge Cases

```php
// Check if column exists before using it
<?php if ($table->hasField('deleted_at')): ?>
use Illuminate\Database\Eloquent\SoftDeletes;
<?php endif; ?>

// Handle empty collections
<?php if ($relationships->count() > 0): ?>
    // Generate relationships
<?php endif; ?>

// Provide fallbacks
<?=$column->getColumnSetting('php_type', 'mixed')?>
```

### 7. Document Your Templates

```php
<?php
/**
 * Template: Service Class Generator
 * 
 * Available Variables:
 * @var string $Service - The service class name
 * @var string $ServiceInterface - The interface name
 * @var bool $with_cache - Whether to include caching
 * @var bool $with_logging - Whether to include logging
 * 
 * Usage:
 *   gen service PaymentService
 */
?>
```

### 8. Version Control Markers

Use clear, unique markers for inline templates:

```php
// Good - Specific and searchable
/** Auto Import Services */
/** Register API Routes */
/** Add Middleware Here */

// Bad - Generic and might conflict
/** Insert Here */
/** TODO */
// Add code
```

### 9. Test Your Generators

Create a test project or branch:

```bash
# Test your generator
git checkout -b test-generator
php artisan gen myfeature TestCase
# Review generated files
git diff
# Clean up
git checkout main
git branch -D test-generator
```

### 10. Keep Templates Focused

```php
// Good - Single responsibility
'api_controller' => ApiControllerGenerator
'repository' => RepositoryGenerator
'service' => ServiceGenerator

// Bad - Doing too much
'everything' => EverythingGenerator  // Creates 20 different files
```

## Real-World Examples

### Example 1: Repository Pattern Generator

```php
class RepositoryGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return true;
    }

    public function dataPrompter(): array
    {
        return [
            'Repository' => fn($data) => $data['ModelName'] . 'Repository',
            'RepositoryInterface' => fn($data) => $data['Repository'] . 'Interface',
            'with_cache' => fn($data) => Console::choice(
                'Enable caching?',
                ['yes', 'no'],
                'no'
            ) === 'yes',
        ];
    }

    public function templates(array $data): array
    {
        $templates = [
            Akceli::fileTemplate(
                'repository_interface',
                'app/Repositories/Contracts/[[RepositoryInterface]].php'
            ),
            Akceli::fileTemplate(
                'repository',
                'app/Repositories/[[Repository]].php'
            ),
            Akceli::fileTemplate(
                'repository_test',
                'tests/Repositories/[[Repository]]Test.php'
            ),
        ];

        if ($data['with_cache']) {
            $templates[] = Akceli::fileTemplate(
                'cached_repository',
                'app/Repositories/Cached[[Repository]].php'
            );
        }

        return $templates;
    }

    public function inlineTemplates(array $data): array
    {
        $bindings = [
            Akceli::insertInline(
                'app/Providers/RepositoryServiceProvider.php',
                '/** Auto Import */',
                'use App\Repositories\Contracts\[[RepositoryInterface]];'
            ),
            Akceli::insertInline(
                'app/Providers/RepositoryServiceProvider.php',
                '/** Auto Import */',
                'use App\Repositories\[[Repository]];'
            ),
            Akceli::insertInline(
                'app/Providers/RepositoryServiceProvider.php',
                '/** Bind Repositories */',
                '$this->app->bind([[RepositoryInterface]]::class, [[Repository]]::class);'
            ),
        ];

        if ($data['with_cache']) {
            $bindings[] = Akceli::insertInline(
                'app/Providers/RepositoryServiceProvider.php',
                '/** Auto Import */',
                'use App\Repositories\Cached[[Repository]];'
            );
        }

        return $bindings;
    }

    public function completionMessage(array $data): void
    {
        Console::info('✓ Repository pattern created!');
        Console::info('');
        Console::info('Files created:');
        Console::info('  • app/Repositories/Contracts/' . $data['RepositoryInterface'] . '.php');
        Console::info('  • app/Repositories/' . $data['Repository'] . '.php');
        Console::info('  • tests/Repositories/' . $data['Repository'] . 'Test.php');
        
        if ($data['with_cache']) {
            Console::info('  • app/Repositories/Cached' . $data['Repository'] . '.php');
            Console::warn('');
            Console::warn('⚠ Don\'t forget to configure cache TTL in config/cache.php');
        }
    }
}
```

### Example 2: GraphQL Type Generator

```php
class GraphQLTypeGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return true;
    }

    public function dataPrompter(): array
    {
        return [
            'TypeName' => fn($data) => $data['ModelName'] . 'Type',
            'with_mutations' => fn($data) => Console::choice(
                'Include mutations?',
                ['yes', 'no'],
                'yes'
            ) === 'yes',
            'with_subscriptions' => fn($data) => Console::choice(
                'Include subscriptions?',
                ['yes', 'no'],
                'no'
            ) === 'yes',
        ];
    }

    public function templates(array $data): array
    {
        $templates = [
            Akceli::fileTemplate(
                'graphql_type',
                'app/GraphQL/Types/[[TypeName]].php'
            ),
            Akceli::fileTemplate(
                'graphql_query',
                'app/GraphQL/Queries/[[ModelName]]Query.php'
            ),
        ];

        if ($data['with_mutations']) {
            $templates[] = Akceli::fileTemplate(
                'graphql_mutations',
                'app/GraphQL/Mutations/[[ModelName]]Mutation.php'
            );
        }

        if ($data['with_subscriptions']) {
            $templates[] = Akceli::fileTemplate(
                'graphql_subscription',
                'app/GraphQL/Subscriptions/[[ModelName]]Subscription.php'
            );
        }

        return $templates;
    }

    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::insertInline(
                'config/graphql.php',
                "'types' => [",
                "        '[[TypeName]]' => [[TypeName]]::class,"
            ),
        ];
    }

    public function completionMessage(array $data): void
    {
        Console::info('✓ GraphQL type generated!');
        Console::info('');
        Console::info('Query example:');
        Console::info('  {');
        Console::info('    ' . Str::camel($data['ModelName']) . '(id: 1) {');
        foreach ($data['table']->columns->take(3) as $column) {
            Console::info('      ' . $column->getField());
        }
        Console::info('    }');
        Console::info('  }');
    }
}
```

### Example 3: Event-Driven Architecture Generator

```php
class EventDrivenGenerator extends AkceliGenerator
{
    public function requiresTable(): bool
    {
        return true;
    }

    public function dataPrompter(): array
    {
        return [
            'Event' => fn($data) => $data['ModelName'] . 'Created',
            'Listener' => fn($data) => 'Handle' . $data['Event'],
            'async' => fn($data) => Console::choice(
                'Should listener be queued?',
                ['yes', 'no'],
                'yes'
            ) === 'yes',
        ];
    }

    public function templates(array $data): array
    {
        return [
            Akceli::fileTemplate(
                'event',
                'app/Events/[[Event]].php'
            ),
            Akceli::fileTemplate(
                'listener',
                'app/Listeners/[[Listener]].php'
            ),
            Akceli::fileTemplate(
                'event_test',
                'tests/Events/[[Event]]Test.php'
            ),
        ];
    }

    public function inlineTemplates(array $data): array
    {
        return [
            Akceli::insertInline(
                'app/Providers/EventServiceProvider.php',
                '/** Auto Import Events */',
                'use App\Events\[[Event]];'
            ),
            Akceli::insertInline(
                'app/Providers/EventServiceProvider.php',
                '/** Auto Import Listeners */',
                'use App\Listeners\[[Listener]];'
            ),
            Akceli::insertInline(
                'app/Providers/EventServiceProvider.php',
                "protected \$listen = [",
                "        [[Event]]::class => [\n            [[Listener]]::class,\n        ],"
            ),
        ];
    }

    public function completionMessage(array $data): void
    {
        Console::info('✓ Event and listener created!');
        Console::info('');
        Console::info('Dispatch with:');
        Console::info('  event(new ' . $data['Event'] . '($' . $data['modelName'] . '));');
        
        if ($data['async']) {
            Console::warn('');
            Console::warn('⚠ Listener is queued - make sure queue worker is running');
        }
    }
}
```

## Debugging Tips

### 1. Enable Debug Mode

In `config/akceli.php`:

```php
'debugging' => true,
```

### 2. Inspect Generated Data

```php
public function completionMessage(array $data): void
{
    // Dump all available data
    dump($data);
    
    // Or specific values
    Console::info('Model: ' . $data['ModelName']);
    Console::info('Table: ' . $data['table_name']);
}
```

### 3. Test Template Syntax

Create a minimal test template:

```php
<?php echo '<?php' . PHP_EOL; ?>

namespace Test;

// Data dump:
<?php dump($data); ?>

class [[ModelName]]
{
    // Table: [[table_name]]
}
```

### 4. Validate Before Generation

```php
public function templates(array $data): array
{
    // Validate required files exist
    if (!file_exists(app_path('Providers/AppServiceProvider.php'))) {
        throw new \Exception('AppServiceProvider not found!');
    }

    return [
        // ... templates
    ];
}
```

## Conclusion

Akceli empowers you to codify your team's conventions and patterns. The key principles are:

1. **Start small** - Create generators for your most repeated patterns first
2. **Iterate** - Refine templates based on actual usage
3. **Document** - Help your team understand what each generator does
4. **Share** - Keep example generators for reference, even if unused
5. **Customize** - Don't force-fit generic patterns; make generators that match YOUR needs

Remember: **The best generator is the one that saves YOUR team time.** Don't worry about making it perfect or generic - make it solve your specific problem well.

## Additional Resources

- [Template Syntax Reference](TEMPLATE_SYNTAX.md)
- [Column Settings Guide](COLUMN_SETTINGS.md)
- [Best Practices](BEST_PRACTICES.md)

---

**Questions or improvements?** This is a living document. Add your own examples and patterns as you discover them!
