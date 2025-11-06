# Akceli Template Syntax Guide

## Overview

Akceli uses PHP as its templating language. This choice is intentional - since you're often generating Blade files, using PHP avoids nested templating engine confusion and gives you full programmatic control.

## Why PHP Templates?

1. **No Delimiter Conflicts**: When generating Blade files, using Blade for templates would create `{{ }}` conflicts
2. **Full Language Power**: Access to all PHP features, functions, and control structures
3. **Familiar Syntax**: Laravel developers already know PHP
4. **Type Safety**: IDE support with proper PHPDoc comments

---

## Basic Syntax

### Variable Output

```php
<?=$variable?>
```

Output a variable directly. Equivalent to `<?php echo $variable; ?>`

**Examples:**
```php
<?=$table->ModelName?>           // Outputs: User
<?=$column->getField()?>         // Outputs: email_address
<?=$data['controller_name']?>    // Outputs: UserController
```

### Multi-line PHP

```php
<?php echo '<?php' . PHP_EOL; ?>
```

Start generated PHP files properly. This outputs the opening PHP tag in the generated file.

**Example:**
```php
<?php echo '<?php' . PHP_EOL;
/**
 * @var TemplateData $table
 */
?>

namespace App\Models;

class <?=$table->ModelName?> extends Model
{
    // ...
}
```

**Generates:**
```php
<?php

namespace App\Models;

class User extends Model
{
    // ...
}
```

---

## Control Structures

### Conditionals

```php
<?php if ($condition): ?>
    content when true
<?php endif; ?>
```

**Examples:**

**Simple condition:**
```php
<?php if ($column->isNullable()): ?>
    ->nullable()
<?php endif; ?>
```

**If-else:**
```php
<?php if ($column->isString()): ?>
    Text::make('<?=$column->getField()?>'),
<?php elseif ($column->isInteger()): ?>
    Number::make('<?=$column->getField()?>'),
<?php else: ?>
    Field::make('<?=$column->getField()?>'),
<?php endif; ?>
```

**Ternary operator:**
```php
<?=$column->isNullable() ? 'nullable' : 'required'?>
```

### Loops

**foreach:**
```php
<?php foreach ($table->columns as $column): ?>
    '<?=$column->getField()?>' => $this->resource-><?=$column->getField()?>,
<?php endforeach; ?>
```

**foreach with index:**
```php
<?php foreach ($table->columns as $index => $column): ?>
<?php if ($index === 0): ?>
    // First item special handling
<?php endif; ?>
    '<?=$column->getField()?>',
<?php endforeach; ?>
```

**Handling last item differently:**
```php
<?php foreach ($table->columns as $column): ?>
<?php if ($table->columns->last() === $column): ?>
    $<?=$column->Field?>    // No comma on last item
<?php else: ?>
    $<?=$column->Field?>,   // Comma on all others
<?php endif; ?>
<?php endforeach; ?>
```

---

## Available Variables

### Table/Model Data (`$table`)

When `requiresTable()` returns `true`, you get a `TemplateData` object:

```php
$table->table_name          // users
$table->ModelName           // User
$table->ModelNames          // Users
$table->modelName           // user
$table->modelNames          // users
$table->model_name          // user
$table->model_names         // users
$table->columns             // Collection of columns
$table->primaryKey          // id
```

### Column Data (`$column`)

Each column in `$table->columns` is a `ColumnInterface`:

```php
$column->Field              // Raw field name from database
$column->getField()         // Processed field name
$column->Type               // varchar(255)
$column->Null               // YES or NO
$column->Key                // PRI, MUL, etc.
$column->Default            // Default value
$column->Extra              // auto_increment, etc.

// Helper methods
$column->isNullable()       // true/false
$column->isString()         // true/false
$column->isInteger()        // true/false
$column->isBoolean()        // true/false
$column->isTimeStamp()      // true/false
$column->isEnum()           // true/false
$column->hasValidationRules()           // true/false
$column->getValidationRulesAsString()   // 'required|string|max:255'
$column->getValidationRulesAsArray()    // ['required', 'string', 'max:255']

// Custom trait methods (from AkceliColumnTrait)
$column->isRelation()       // Checks if ends with '_id'
$column->toRelation()       // user_id -> user
$column->getClientLabel()   // user_id -> User Id
$column->getSpacedField()   // user_id -> user id
$column->isIn(['id', 'name'])           // Check if field in array
$column->notIn(['created_at', 'updated_at'])  // Inverse check
```

### Custom Data (`$data`)

Data from your generator's `dataPrompter()`:

```php
public function dataPrompter(): array
{
    return [
        'Controller' => function (array $data) {
            return Console::ask('Controller name?', 'UserController');
        },
        'custom_value' => 'hardcoded',
    ];
}
```

Access in templates:
```php
<?=$data['Controller']?>      // UserController
<?=$data['custom_value']?>    // hardcoded
```

---

## Advanced Techniques

### Column Settings

Access configured column type mappings:

```php
// In config/akceli.php
'column-settings' => [
    'php_class_doc_type' => Akceli::columnSetting('string', 'integer', ...),
    'nova_field_type' => Akceli::columnSetting('Text', 'Number', ...),
]

// In template
<?=$column->getColumnSetting('nova_field_type', 'Text')?>
// Outputs: Number (for integer), Boolean (for bool), etc.
```

**Common use case:**
```php
<?php foreach ($table->columns as $column): ?>
    /**
     * @property <?=$column->getColumnSetting('php_class_doc_type', 'string')?> $<?=$column->getField()?>
     */
<?php endforeach; ?>
```

### Filtering Columns

```php
// Filter out timestamps
<?php foreach ($table->filterDates($table->columns) as $column): ?>
    '<?=$column->getField()?>',
<?php endforeach; ?>

// Custom filtering
<?php $nonNullable = $table->columns->filter(fn($col) => !$col->isNullable()); ?>
<?php foreach ($nonNullable as $column): ?>
    '<?=$column->getField()?>' => 'required',
<?php endforeach; ?>
```

### Template Composition

Call other templates from within a template:

```php
public function templates(array $data): array
{
    return [
        Akceli::fileTemplate('model', 'app/Models/[[ModelName]].php'),
    ];
}
```

In `model.akceli.php`:
```php
class <?=$table->ModelName?> extends Model
{
<?=$this->fetch('method_properties')?>
}
```

In `method_properties.akceli.php`:
```php
<?php foreach ($table->columns as $column): ?>
    $<?=$column->Field?>,
<?php endforeach; ?>
```

### Conditional Blocks

```php
<?php if ($table->hasField('deleted_at')): ?>
use Illuminate\Database\Eloquent\SoftDeletes;
<?php endif; ?>

class <?=$table->ModelName?> extends Model
{
<?php if ($table->hasField('deleted_at')): ?>
    use SoftDeletes;
<?php endif; ?>
}
```

### String Manipulation

```php
use Illuminate\Support\Str;

<?=Str::studly($column->getField())?>      // user_name -> UserName
<?=Str::camel($column->getField())?>       // user_name -> userName
<?=Str::snake($data['ModelName'])?>        // UserProfile -> user_profile
<?=Str::kebab($data['ModelName'])?>        // UserProfile -> user-profile
<?=Str::plural($table->model_name)?>       // user -> users
<?=Str::singular($table->model_names)?>    // users -> user
```

### Heredoc for Complex Strings

```php
<?php
$complexString = <<<'PHP'
    Route::get('users', function () {
        return User::all();
    });
PHP;
?>
<?=$complexString?>
```

---

## Common Patterns

### Generating Method Parameters

```php
public static function create(
<?php foreach ($table->columns as $column): ?>
<?php if ($column->endsWith('_id')): ?>
<?php if ($table->columns->last() === $column): ?>
    <?=Str::studly(str_replace('_id', '', $column->getField()))?> $<?=str_replace('_id', '', $column->getField())?><?=($column->isNullable()) ? " = null\n" : "\n"?>
<?php else: ?>
    <?=Str::studly(str_replace('_id', '', $column->getField()))?> $<?=str_replace('_id', '', $column->getField())?><?=($column->isNullable()) ? " = null,\n" : ",\n"?>
<?php endif; ?>
<?php else: ?>
<?php if ($table->columns->last() === $column): ?>
    $<?=$column->Field?>
<?php else: ?>
    $<?=$column->Field?>,
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
) {
    // ...
}
```

**Generates:**
```php
public static function create(
    string $name,
    string $email,
    ?string $phone = null
) {
    // ...
}
```

### Generating Array Elements

```php
return [
<?php foreach ($table->columns as $column): ?>
<?php if ($column->hasValidationRules()): ?>
    '<?=$column->getField()?>' => <?=$column->getValidationRulesAsArray()?>,
<?php endif; ?>
<?php endforeach; ?>
];
```

**Generates:**
```php
return [
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'unique:users'],
    'phone' => ['nullable', 'string'],
];
```

### Generating PHPDoc

```php
/**
 * Class <?=$table->ModelName?>
 *
 * Database Fields
<?php foreach ($table->columns as $column): ?>
 * @property <?=$column->getColumnSetting('php_class_doc_type', 'string')?> $<?=$column->getField()?>
<?php endforeach; ?>
 *
 * @package App\Models
 */
class <?=$table->ModelName?> extends Model
{
}
```

### Generating Relationships

```php
<?php foreach ($table->columns as $column): ?>
<?php if ($column->isRelation()): ?>
    public function <?=$column->toRelation()?>()
    {
        return $this->belongsTo(<?=Str::studly($column->toRelation())>::class);
    }

<?php endif; ?>
<?php endforeach; ?>
```

---

## Debugging Templates

### Enable Debugging

In `config/akceli.php`:
```php
'debugging' => true,
```

### Output Debug Info

```php
<?php
// View all available data
var_dump($data);
var_dump($table);

// Check column properties
foreach ($table->columns as $column) {
    echo "Field: {$column->getField()}, Type: {$column->Type}\n";
}
?>
```

### Common Issues

**1. Missing Variable**
```php
// BAD: Undefined variable
<?=$undefined_var?>

// GOOD: Check first or provide default
<?=isset($custom_var) ? $custom_var : 'default'?>
```

**2. Wrong Property Access**
```php
// BAD: Methods require parentheses
<?=$column->getField?>

// GOOD:
<?=$column->getField()?>
```

**3. Spacing Issues**
```php
// BAD: Creates extra newlines
<?php foreach ($items as $item): ?>

    <?=$item?>

<?php endforeach; ?>

// GOOD: Keep it tight
<?php foreach ($items as $item): ?>
    <?=$item?>
<?php endforeach; ?>
```

---

## Best Practices

### 1. Use Column Helper Methods

```php
// GOOD: Use helper methods
<?php if ($column->isNullable()): ?>

// AVOID: Direct property checks
<?php if ($column->Null === 'YES'): ?>
```

### 2. Handle Edge Cases

```php
<?php foreach ($table->columns as $column): ?>
<?php if (in_array($column->getField(), ['created_at', 'updated_at', 'deleted_at'])): ?>
    // Skip timestamp columns
    <?php continue; ?>
<?php endif; ?>
    // Process column
<?php endforeach; ?>
```

### 3. Keep Templates Focused

```php
// GOOD: One template per concern
Akceli::fileTemplate('model', 'app/Models/[[ModelName]].php'),
Akceli::fileTemplate('model_test', 'tests/Models/[[ModelName]]Test.php'),

// AVOID: Kitchen sink templates
```

### 4. Use Template Composition

```php
// In main template
<?=$this->fetch('validation_rules')?>

// In validation_rules.akceli.php
<?php foreach ($table->columns as $column): ?>
    '<?=$column->getField()?>' => <?=$column->getValidationRulesAsArray()?>,
<?php endforeach; ?>
```

### 5. Comment Complex Logic

```php
<?php
/**
 * This generates method parameters with proper type hints.
 * Foreign keys (ending in _id) get Model type hints.
 * Nullable columns get default null values.
 */
?>
<?php foreach ($table->columns as $column): ?>
<?php if ($column->endsWith('_id')): ?>
    <?=Str::studly(str_replace('_id', '', $column->getField()))?> $<?=str_replace('_id', '', $column->getField())?><?=($column->isNullable()) ? " = null" : ""?>
<?php endif; ?>
<?php endforeach; ?>
```

---

## Template Variables Quick Reference

```php
// Table/Model
$table->table_name          // users
$table->ModelName           // User  
$table->modelName           // user
$table->ModelNames          // Users
$table->modelNames          // users
$table->columns             // Collection

// Column
$column->Field              // Raw field
$column->getField()         // Processed field
$column->isNullable()       // bool
$column->isRelation()       // bool (ends with _id)
$column->toRelation()       // user_id -> user
$column->getClientLabel()   // user_id -> User Id
$column->hasValidationRules()  // bool
$column->getValidationRulesAsString()  // string
$column->getValidationRulesAsArray()   // array

// Column Settings
$column->getColumnSetting('php_class_doc_type', 'string')
$column->getColumnSetting('nova_field_type', 'Text')
$column->getColumnSetting('casts', null)

// Custom Data (from dataPrompter)
$data['key']                // Your custom values

// Helpers
$this->fetch('template_name')  // Include sub-template
```

---

## Real-World Examples

### Example 1: Model Class

```php
<?php echo '<?php' . PHP_EOL;
/** @var TemplateData $table */
use Akceli\TemplateData;
?>

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<?php if ($table->hasField('deleted_at')): ?>
use Illuminate\Database\Eloquent\SoftDeletes;
<?php endif; ?>

/**
 * Class <?=$table->ModelName?>
 *
<?php foreach ($table->columns as $column): ?>
 * @property <?=$column->getColumnSetting('php_class_doc_type', 'string')?> $<?=$column->getField()?>
<?php endforeach; ?>
 */
class <?=$table->ModelName?> extends Model
{
<?php if ($table->hasField('deleted_at')): ?>
    use SoftDeletes;

<?php endif; ?>
    protected $table = '<?=$table->table_name?>';

    protected $fillable = [
<?php foreach ($table->columns as $column): ?>
<?php if (!in_array($column->getField(), ['id', 'created_at', 'updated_at', 'deleted_at'])): ?>
        '<?=$column->getField()?>',
<?php endif; ?>
<?php endforeach; ?>
    ];

    protected $casts = [
<?php foreach ($table->columns as $column): ?>
<?php if ($column->getColumnSetting('casts')): ?>
        '<?=$column->getField()?>' => '<?=$column->getColumnSetting('casts')?>',
<?php endif; ?>
<?php endforeach; ?>
    ];
}
```

### Example 2: Form Request

```php
<?php echo '<?php' . PHP_EOL; ?>

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Create<?=$table->ModelName?>Request extends FormRequest
{
    public function rules()
    {
        return [
<?php foreach ($table->columns as $column): ?>
<?php if ($column->hasValidationRules()): ?>
            '<?=$column->getField()?>' => <?=$column->getValidationRulesAsArray()?>,
<?php endif; ?>
<?php endforeach; ?>
        ];
    }
}
```

### Example 3: API Resource

```php
<?php echo '<?php' . PHP_EOL; ?>

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class <?=$table->ModelName?>Resource extends JsonResource
{
    public function toArray($request)
    {
        return [
<?php foreach ($table->columns as $column): ?>
<?php if (!in_array($column->getField(), ['deleted_at', 'deleted_by'])): ?>
            '<?=$column->getField()?>' => $this->resource-><?=$column->getField()?>,
<?php endif; ?>
<?php endforeach; ?>
        ];
    }
}
```

---

## Next Steps

- Read [BUILDING_GENERATORS.md](BUILDING_GENERATORS.md) to create your own generators
- See [BEST_PRACTICES.md](BEST_PRACTICES.md) for organizing your project's generators
- Check `akceli/templates/examples/` for more real-world template examples
