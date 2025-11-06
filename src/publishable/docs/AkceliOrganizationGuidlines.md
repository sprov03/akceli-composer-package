# Best Practices for Organizing Project-Specific Generators

## Table of Contents
1. [Introduction](#introduction)
2. [Directory Structure](#directory-structure)
3. [Naming Conventions](#naming-conventions)
4. [Generator Categories](#generator-categories)
5. [Template Organization](#template-organization)
6. [Configuration Management](#configuration-management)
7. [Documentation Standards](#documentation-standards)
8. [Version Control](#version-control)
9. [Team Workflow](#team-workflow)
10. [Migration and Maintenance](#migration-and-maintenance)

## Introduction

As your project grows, you'll accumulate many generators. Without proper organization, they become difficult to maintain and use. This guide provides proven patterns for organizing generators in real-world Laravel projects.

### The Problem

```
akceli/
├── generators/
│   ├── ServiceGenerator.php
│   ├── MyControllerGenerator.php
│   ├── APIStuffGenerator.php
│   ├── NewFeatureGenerator.php
│   ├── TestGenerator.php
│   └── ... 50 more files
└── templates/
    ├── service.akceli.php
    ├── controller.akceli.php
    ├── test.akceli.php
    └── ... 100 more files
```

This becomes unmaintainable quickly. Let's fix it.

## Directory Structure

### Recommended Structure for Medium Projects

```
akceli/
├── generators/
│   ├── Active/                    # Currently used generators
│   │   ├── API/
│   │   │   ├── ApiControllerGenerator.php
│   │   │   ├── ApiResourceGenerator.php
│   │   │   └── ApiRequestGenerator.php
│   │   ├── Domain/
│   │   │   ├── ServiceGenerator.php
│   │   │   ├── RepositoryGenerator.php
│   │   │   └── ActionGenerator.php
│   │   ├── Testing/
│   │   │   ├── FeatureTestGenerator.php
│   │   │   └── UnitTestGenerator.php
│   │   └── Database/
│   │       ├── MigrationGenerator.php
│   │       └── SeederGenerator.php
│   │
│   ├── Experimental/              # Testing new patterns
│   │   └── GraphQLGenerator.php
│   │
│   ├── Deprecated/                # Keep for reference, don't use
│   │   └── OldPatternGenerator.php
│   │
│   └── Examples/                  # Reference implementations
│       └── ReferenceGenerator.php
│
├── templates/
│   ├── api/
│   │   ├── controller.akceli.php
│   │   ├── resource.akceli.php
│   │   └── request.akceli.php
│   ├── domain/
│   │   ├── service.akceli.php
│   │   └── repository.akceli.php
│   ├── testing/
│   │   └── feature_test.akceli.php
│   └── partials/                  # Reusable template fragments
│       ├── validation_rules.akceli.php
│       ├── api_docs.akceli.php
│       └── relationships.akceli.php
│
├── traits/
│   ├── AkceliTableDataTrait.php
│   └── AkceliColumnTrait.php
│
└── docs/
    ├── GENERATORS.md              # List of available generators
    ├── TEMPLATES.md               # Template documentation
    └── EXAMPLES.md                # Usage examples
```

### Recommended Structure for Large Projects

```
akceli/
├── generators/
│   ├── API/
│   │   ├── V1/                    # API versioning
│   │   │   ├── ControllerGenerator.php
│   │   │   └── ResourceGenerator.php
│   │   ├── V2/
│   │   │   └── ControllerGenerator.php
│   │   └── Shared/
│   │       └── RequestGenerator.php
│   │
│   ├── Architecture/              # Architectural patterns
│   │   ├── DDD/
│   │   │   ├── AggregateGenerator.php
│   │   │   ├── EntityGenerator.php
│   │   │   └── ValueObjectGenerator.php
│   │   ├── CQRS/
│   │   │   ├── CommandGenerator.php
│   │   │   ├── QueryGenerator.php
│   │   │   └── HandlerGenerator.php
│   │   └── EventSourcing/
│   │       ├── EventGenerator.php
│   │       └── ProjectionGenerator.php
│   │
│   ├── Infrastructure/
│   │   ├── Queue/
│   │   │   ├── JobGenerator.php
│   │   │   └── ListenerGenerator.php
│   │   ├── Cache/
│   │   │   └── CacheDecoratorGenerator.php
│   │   └── Integration/
│   │       └── ServiceClientGenerator.php
│   │
│   ├── Testing/
│   │   ├── Unit/
│   │   ├── Integration/
│   │   └── E2E/
│   │
│   └── Admin/                     # Admin panel specific
│       ├── Nova/
│       └── Filament/
│
├── templates/
│   ├── api/
│   │   ├── v1/
│   │   └── v2/
│   ├── architecture/
│   │   ├── ddd/
│   │   ├── cqrs/
│   │   └── event-sourcing/
│   └── shared/                    # Cross-cutting templates
│       ├── base/
│       ├── traits/
│       └── interfaces/
│
└── config/
    ├── generators.php             # Generator-specific config
    └── templates.php              # Template-specific config
```

## Naming Conventions

### Generator Class Names

Follow these patterns consistently:

```php
// ✅ GOOD - Clear purpose and scope
class ApiControllerGenerator extends AkceliGenerator { }
class UserRepositoryGenerator extends AkceliGenerator { }
class FeatureTestGenerator extends AkceliGenerator { }
class DddAggregateGenerator extends AkceliGenerator { }

// ❌ BAD - Too vague or confusing
class ControllerGen extends AkceliGenerator { }
class MyGenerator extends AkceliGenerator { }
class Generator1 extends AkceliGenerator { }
class TheNewOneGenerator extends AkceliGenerator { }
```

### Pattern Rules

1. **Be specific about what it generates**
   ```php
   PaymentServiceGenerator  // ✅ Clear
   ServiceGenerator         // ⚠️  Okay if only one type
   Generator               // ❌ Too vague
   ```

2. **Include architectural context if needed**
   ```php
   CqrsCommandGenerator     // ✅ Clear context
   EventSourcingEventGenerator  // ✅ Clear context
   CommandGenerator         // ⚠️  Ambiguous - shell command or CQRS?
   ```

3. **Use full words, not abbreviations**
   ```php
   RepositoryGenerator      // ✅
   RepoGenerator           // ❌
   ApiControllerGenerator  // ✅
   ApiCtrlGen             // ❌
   ```

### Template File Names

```
// ✅ GOOD - Matches generator purpose
api/controller.akceli.php
api/resource.akceli.php
domain/service.akceli.php
testing/feature_test.akceli.php

// ❌ BAD - Unclear or inconsistent
api/ctrl.akceli.php
domain/serv.akceli.php
template1.akceli.php
new_thing.akceli.php
```

### Command Aliases

Register generators with clear, memorable aliases:

```php
// config/akceli.php
'generators' => [
    // ✅ GOOD - Clear and consistent
    'api:controller' => ApiControllerGenerator::class,
    'api:resource' => ApiResourceGenerator::class,
    'repo' => RepositoryGenerator::class,
    'service' => ServiceGenerator::class,
    
    // ❌ BAD - Inconsistent or unclear
    'c' => ControllerGenerator::class,
    'mk-repo' => RepositoryGenerator::class,
    'new-service' => ServiceGenerator::class,
    'generate-api' => ApiGenerator::class,
],
```

## Generator Categories

### 1. Core Generators (Always Active)

Generators used daily by the entire team:

```php
'generators' => [
    // API Layer
    'api:controller' => API\ControllerGenerator::class,
    'api:request' => API\RequestGenerator::class,
    'api:resource' => API\ResourceGenerator::class,
    
    // Domain Layer
    'service' => Domain\ServiceGenerator::class,
    'action' => Domain\ActionGenerator::class,
    'model' => Domain\ModelGenerator::class,
    
    // Testing
    'test:feature' => Testing\FeatureTestGenerator::class,
    'test:unit' => Testing\UnitTestGenerator::class,
],
```

### 2. Specialized Generators (Domain Specific)

Generators for specific features or modules:

```php
'generators' => [
    // Payment Module
    'payment:gateway' => Payments\GatewayGenerator::class,
    'payment:webhook' => Payments\WebhookGenerator::class,
    
    // Notification System
    'notification' => Notifications\NotificationGenerator::class,
    'notification:channel' => Notifications\ChannelGenerator::class,
    
    // Reporting
    'report' => Reporting\ReportGenerator::class,
    'report:export' => Reporting\ExportGenerator::class,
],
```

### 3. Experimental Generators (Opt-In)

New patterns being tested:

```php
'generators' => [
    // Prefix with 'x-' or 'experimental-'
    'x:graphql' => Experimental\GraphQLGenerator::class,
    'x:grpc' => Experimental\GrpcGenerator::class,
    
    // Or keep in separate config file
    // config/experimental-generators.php
],
```

### 4. Deprecated Generators (Reference Only)

Keep for backwards compatibility, but discourage use:

```php
'generators' => [
    // Clearly mark as deprecated
    'old:controller' => Deprecated\OldControllerGenerator::class,  // Use api:controller instead
    'legacy:service' => Deprecated\LegacyServiceGenerator::class,  // Use service instead
],
```

## Template Organization

### Strategy 1: Mirror Generator Structure

```
generators/
├── API/
│   └── ControllerGenerator.php
└── Domain/
    └── ServiceGenerator.php

templates/
├── api/
│   └── controller.akceli.php
└── domain/
    └── service.akceli.php
```

### Strategy 2: By Layer

```
templates/
├── presentation/        # Controllers, Resources, Requests
│   ├── controller.akceli.php
│   ├── resource.akceli.php
│   └── request.akceli.php
├── application/        # Services, Actions, Use Cases
│   ├── service.akceli.php
│   └── action.akceli.php
├── domain/            # Entities, Value Objects
│   ├── entity.akceli.php
│   └── value_object.akceli.php
└── infrastructure/    # Repositories, External Services
    ├── repository.akceli.php
    └── external_service.akceli.php
```

### Strategy 3: By Feature Module

```
templates/
├── user-management/
│   ├── user_controller.akceli.php
│   ├── user_service.akceli.php
│   └── user_repository.akceli.php
├── billing/
│   ├── subscription_controller.akceli.php
│   └── payment_service.akceli.php
└── shared/
    ├── base_controller.akceli.php
    └── base_service.akceli.php
```

### Reusable Template Partials

Create DRY templates with partials:

```
templates/
├── partials/
│   ├── validation_rules.akceli.php
│   ├── relationships.akceli.php
│   ├── api_docs.akceli.php
│   ├── constructor.akceli.php
│   └── timestamps.akceli.php
│
└── api/
    └── controller.akceli.php  # Uses partials
```

Usage in templates:

```php
// templates/api/controller.akceli.php
class [[ModelName]]Controller
{
<?=$this->fetch('partials/constructor')?>

    public function rules(): array
    {
        return [
<?=$this->fetch('partials/validation_rules')?>
        ];
    }
}
```

## Configuration Management

### Split Configuration by Purpose

Instead of one massive `config/akceli.php`:

```php
// config/akceli.php (main config)
return [
    'mysql_connection' => env('AKCELI_MYSQL_CONNECTION', 'mysql'),
    'debugging' => env('AKCELI_DEBUG', false),
    'model_directory' => 'app/Models',
    
    // Import generator configs
    'generators' => array_merge(
        require __DIR__ . '/generators/api.php',
        require __DIR__ . '/generators/domain.php',
        require __DIR__ . '/generators/testing.php',
        require __DIR__ . '/generators/admin.php',
    ),
    
    // Import column settings
    'column-settings' => require __DIR__ . '/generators/column-settings.php',
];

// config/generators/api.php
return [
    'api:controller' => \Akceli\Generators\API\ControllerGenerator::class,
    'api:request' => \Akceli\Generators\API\RequestGenerator::class,
    'api:resource' => \Akceli\Generators\API\ResourceGenerator::class,
];

// config/generators/domain.php
return [
    'service' => \Akceli\Generators\Domain\ServiceGenerator::class,
    'repository' => \Akceli\Generators\Domain\RepositoryGenerator::class,
    'action' => \Akceli\Generators\Domain\ActionGenerator::class,
];
```

### Environment-Specific Generators

```php
// config/akceli.php
return [
    'generators' => array_merge(
        require __DIR__ . '/generators/core.php',
        
        // Only load experimental in local
        app()->environment('local') 
            ? require __DIR__ . '/generators/experimental.php'
            : [],
            
        // Team-specific generators
        env('LOAD_TEAM_GENERATORS')
            ? require __DIR__ . '/generators/team-specific.php'
            : [],
    ),
];
```

### Generator Metadata

Add metadata to help with discovery:

```php
// config/generators/api.php
return [
    'api:controller' => [
        'class' => ApiControllerGenerator::class,
        'description' => 'Generate RESTful API controller with CRUD operations',
        'usage' => 'gen api:controller users',
        'requires_table' => true,
        'tags' => ['api', 'crud', 'rest'],
        'team' => 'backend',
        'added_in' => '2024-01-15',
    ],
];
```

## Documentation Standards

### 1. Generator Documentation

Each generator should have inline documentation:

```php
<?php

namespace Akceli\Generators\API;

use Akceli\Generators\AkceliGenerator;

/**
 * API Controller Generator
 * 
 * Generates a RESTful API controller with:
 * - CRUD operations (index, show, store, update, destroy)
 * - OpenAPI/Swagger documentation
 * - Form request validation
 * - Resource transformation
 * - Feature tests
 * 
 * @usage gen api:controller users
 * @example gen api:controller products --with-cache
 * 
 * @requires Database table
 * @generates 4-5 files (controller, requests, resource, test)
 * 
 * @author Backend Team
 * @since 2024-01-15
 */
class ApiControllerGenerator extends AkceliGenerator
{
    // ...
}
```

### 2. Template Documentation

Document available variables at the top of each template:

```php
<?php
/**
 * API Controller Template
 * 
 * Generates a RESTful controller following team conventions.
 * 
 * Available Variables:
 * @var string $ModelName - Singular, StudlyCase model name (e.g., "User")
 * @var string $modelName - Singular, camelCase (e.g., "user")
 * @var string $modelNames - Plural, camelCase (e.g., "users")
 * @var string $table_name - Database table name (e.g., "users")
 * @var Collection $columns - Database columns
 * @var bool $with_cache - Whether caching is enabled
 * 
 * Example Usage:
 *   gen api:controller users
 *   gen api:controller products --with-cache
 * 
 * @see docs/generators/api-controller.md for detailed documentation
 */

echo '<?php' . PHP_EOL;
?>
```

### 3. GENERATORS.md File

Create a catalog of available generators:

```markdown
# Available Generators

## API Generators

### `api:controller`
Generate RESTful API controller with CRUD operations.

**Usage:** `gen api:controller {table}`

**Options:**
- `--with-cache` - Add caching layer
- `--with-docs` - Include OpenAPI docs

**Generates:**
- Controller
- Form Requests (Create, Update)
- Resource
- Feature Test

**Example:**
```bash
gen api:controller users
gen api:controller products --with-cache --with-docs
```

### `api:resource`
Generate API resource for transforming models to JSON.

**Usage:** `gen api:resource {table}`

---

## Domain Generators

### `service`
Generate service class following team patterns.

**Usage:** `gen service {name}`
```

### 4. CHANGELOG.md for Generators

Track changes to your generator suite:

```markdown
# Generator Changelog

## [2024-02] - February 2024

### Added
- `api:v2:controller` - New API v2 controller pattern
- `notification:push` - Push notification generator

### Changed
- `api:controller` - Now includes rate limiting by default
- `service` - Added dependency injection support

### Deprecated
- `old:controller` - Use `api:controller` instead

### Removed
- `legacy:model` - Use standard `model` generator

## [2024-01] - January 2024

### Added
- `repository` - Repository pattern generator
- `test:integration` - Integration test generator
```

## Version Control

### What to Commit

```
# ✅ COMMIT THESE
akceli/
├── generators/          # All generator classes
├── templates/          # All templates
├── traits/             # Custom traits
├── docs/              # Documentation
└── .gitignore         # Akceli-specific ignores

# ❌ DON'T COMMIT THESE
akceli/
└── .cache/            # Generator cache (if any)
```

### .gitignore for Akceli

```gitignore
# Akceli
/akceli/.cache/
/akceli/temp/
```

### Branching Strategy

```bash
# Feature branches for new generators
git checkout -b generator/api-v2-controller
git checkout -b generator/graphql-support

# Test generators before merging
git checkout -b test/new-generator-pattern

# Tag major generator releases
git tag -a generators-v2.0 -m "Major API generator overhaul"
```

## Team Workflow

### 1. Generator Request Process

Create an issue template for generator requests:

```markdown
# Generator Request

## What should this generator create?
Describe the files and patterns this generator should produce.

## Why is this needed?
Explain why existing generators don't cover this use case.

## Example output
Provide an example of the desired output files.

## Frequency
How often would this generator be used?
- [ ] Daily
- [ ] Weekly
- [ ] Monthly
- [ ] Once per feature

## Team consensus
Has the team agreed on this pattern?
- [ ] Yes, we've used this pattern in 3+ features
- [ ] No, this is a new pattern proposal
```

### 2. Generator Review Checklist

Before merging a new generator:

```markdown
- [ ] Follows naming conventions
- [ ] Includes inline documentation
- [ ] Generates working code (tested)
- [ ] Includes test files
- [ ] Follows team architectural patterns
- [ ] Templates are properly organized
- [ ] Added to GENERATORS.md
- [ ] Config updated
- [ ] Example usage provided
- [ ] No hardcoded values (uses config)
```

### 3. Onboarding Documentation

Create a quick start guide for new team members:

```markdown
# Generator Quick Start

## Most Common Generators

### Creating a new API endpoint
```bash
gen api:controller posts
```

This creates:
- Controller with CRUD operations
- Form requests
- Resource
- Tests

### Creating a service class
```bash
gen service PaymentProcessor
```

### Running tests
```bash
php artisan test --filter=ApiController
```

## Need help?
Run `gen --list` to see all available generators.
```

### 4. Generator Maintenance Schedule

Assign ownership and review schedule:

```php
// config/generators/metadata.php
return [
    'api:controller' => [
        'owner' => 'backend-team',
        'last_updated' => '2024-01-15',
        'review_frequency' => 'quarterly',
        'next_review' => '2024-04-15',
    ],
];
```

## Migration and Maintenance

### Deprecating Old Generators

Use a grace period strategy:

```php
// Phase 1: Mark as deprecated (Month 1)
'old:controller' => [
    'class' => OldControllerGenerator::class,
    'deprecated' => true,
    'use_instead' => 'api:controller',
    'removal_date' => '2024-06-01',
],

// Phase 2: Show warning (Month 2-3)
class OldControllerGenerator extends AkceliGenerator
{
    public function __construct()
    {
        Console::warn('⚠️  WARNING: old:controller is deprecated!');
        Console::warn('   Use: gen api:controller instead');
        Console::warn('   Will be removed: 2024-06-01');
        Console::info('');
        
        $continue = Console::choice('Continue anyway?', ['yes', 'no'], 'no');
        if ($continue !== 'yes') {
            exit(0);
        }
    }
}

// Phase 3: Remove (Month 4)
// Delete the generator entirely
```

### Updating Generators with Breaking Changes

```php
// Version the generator
'api:v1:controller' => ApiV1ControllerGenerator::class,
'api:v2:controller' => ApiV2ControllerGenerator::class,
'api:controller' => ApiV2ControllerGenerator::class,  // Latest

// Or use feature flags
'api:controller' => [
    'class' => env('USE_NEW_API_PATTERN') 
        ? NewApiControllerGenerator::class
        : ApiControllerGenerator::class,
],
```

### Refactoring Template Organization

When reorganizing templates, use symlinks temporarily:

```bash
# Old structure
templates/controller.akceli.php

# New structure
templates/api/controller.akceli.php

# Symlink for backwards compatibility
ln -s api/controller.akceli.php templates/controller.akceli.php
```

### Measuring Generator Usage

Add analytics to understand which generators are used:

```php
abstract class AkceliGenerator
{
    protected function logUsage(): void
    {
        if (!config('akceli.track_usage')) {
            return;
        }
        
        Storage::append('akceli-usage.log', json_encode([
            'generator' => static::class,
            'user' => exec('whoami'),
            'timestamp' => now()->toIso8601String(),
            'table' => $this->requiresTable() ? $this->table->table_name : null,
        ]));
    }
}
```

Analyze periodically:

```bash
# Most used generators
cat storage/logs/akceli-usage.log | jq -r '.generator' | sort | uniq -c | sort -nr

# Unused generators (candidates for removal)
# Compare registered generators vs logged usage
```

## Complete Example: Well-Organized Project

Here's a complete example of a well-organized Akceli setup:

```
project/
├── akceli/
│   ├── generators/
│   │   ├── API/
│   │   │   ├── V1/
│   │   │   │   ├── ControllerGenerator.php
│   │   │   │   └── ResourceGenerator.php
│   │   │   ├── V2/
│   │   │   │   └── ControllerGenerator.php
│   │   │   └── RequestGenerator.php
│   │   ├── Domain/
│   │   │   ├── ServiceGenerator.php
│   │   │   ├── ActionGenerator.php
│   │   │   └── RepositoryGenerator.php
│   │   ├── Testing/
│   │   │   ├── FeatureTestGenerator.php
│   │   │   └── UnitTestGenerator.php
│   │   └── Experimental/
│   │       └── GraphQLGenerator.php
│   │
│   ├── templates/
│   │   ├── api/
│   │   │   ├── v1/
│   │   │   │   ├── controller.akceli.php
│   │   │   │   └── resource.akceli.php
│   │   │   ├── v2/
│   │   │   │   └── controller.akceli.php
│   │   │   └── request.akceli.php
│   │   ├── domain/
│   │   │   ├── service.akceli.php
│   │   │   ├── action.akceli.php
│   │   │   └── repository.akceli.php
│   │   ├── testing/
│   │   │   ├── feature_test.akceli.php
│   │   │   └── unit_test.akceli.php
│   │   └── partials/
│   │       ├── validation_rules.akceli.php
│   │       ├── relationships.akceli.php
│   │       └── api_docs.akceli.php
│   │
│   ├── traits/
│   │   ├── AkceliTableDataTrait.php
│   │   └── AkceliColumnTrait.php
│   │
│   └── docs/
│       ├── GENERATORS.md
│       ├── TEMPLATES.md
│       ├── EXAMPLES.md
│       ├── CHANGELOG.md
│       └── MIGRATION_GUIDE.md
│
└── config/
    ├── akceli.php                          # Main config
    └── generators/
        ├── api.php                         # API generators
        ├── domain.php                      # Domain generators
        ├── testing.php                     # Testing generators
        ├── experimental.php                # Experimental generators
        └── column-settings.php             # Shared column settings
```

**config/akceli.php:**
```php
<?php

return [
    'mysql_connection' => env('AKCELI_MYSQL_CONNECTION', 'mysql'),
    'debugging' => env('AKCELI_DEBUG', false),
    'model_directory' => 'app/Models',
    'select-template-behavior' => 'auto-complete',
    
    'generators' => array_merge(
        require __DIR__ . '/generators/api.php',
        require __DIR__ . '/generators/domain.php',
        require __DIR__ . '/generators/testing.php',
        
        // Load experimental only in local environment
        app()->environment('local')
            ? require __DIR__ . '/generators/experimental.php'
            : [],
    ),
    
    'column-settings' => require __DIR__ . '/generators/column-settings.php',
];
```

**config/generators/api.php:**
```php
<?php

use Akceli\Generators\API\V1\ControllerGenerator as V1ControllerGenerator;
use Akceli\Generators\API\V2\ControllerGenerator as V2ControllerGenerator;
use Akceli\Generators\API\RequestGenerator;
use Akceli\Generators\API\V1\ResourceGenerator;

return [
    // Current version (v2)
    'api:controller' => [
        'class' => V2ControllerGenerator::class,
        'description' => 'Generate RESTful API controller (v2 pattern)',
        'requires_table' => true,
    ],
    
    // Version-specific
    'api:v1:controller' => [
        'class' => V1ControllerGenerator::class,
        'description' => 'Generate RESTful API controller (v1 pattern)',
        'deprecated' => true,
        'use_instead' => 'api:controller',
    ],
    
    'api:v2:controller' => [
        'class' => V2ControllerGenerator::class,
        'description' => 'Generate RESTful API controller (v2 pattern)',
        'requires_table' => true,
    ],
    
    // Shared generators
    'api:request' => [
        'class' => RequestGenerator::class,
        'description' => 'Generate form request with validation',
        'requires_table' => true,
    ],
    
    'api:resource' => [
        'class' => ResourceGenerator::class,
        'description' => 'Generate API resource for JSON transformation',
        'requires_table' => true,
    ],
];
```

## Checklist for Well-Organized Generators

Use this checklist to audit your generator organization:

### Structure
- [ ] Generators are organized by category/layer
- [ ] Templates mirror generator organization
- [ ] Deprecated generators are clearly separated
- [ ] Experimental generators are opt-in

### Naming
- [ ] All generators follow consistent naming convention
- [ ] Template names match generator purpose
- [ ] Command aliases are intuitive and consistent

### Documentation
- [ ] Each generator has inline documentation
- [ ] Each template documents available variables
- [ ] GENERATORS.md catalog exists
- [ ] Usage examples are provided
- [ ] CHANGELOG.md tracks changes

### Configuration
- [ ] Config is split by category
- [ ] Column settings are centralized
- [ ] Metadata includes ownership and review dates
- [ ] Environment-specific generators are handled

### Maintenance
- [ ] Generator usage is tracked
- [ ] Deprecation process is defined
- [ ] Review schedule exists
- [ ] Team ownership is assigned

### Team
- [ ] Onboarding documentation exists
- [ ] Review checklist is defined
- [ ] Request process is documented
- [ ] Common generators are easily discoverable

## Conclusion

Well-organized generators are:

1. **Easy to find** - Clear naming and structure
2. **Easy to use** - Good documentation and examples
3. **Easy to maintain** - Clear ownership and review process
4. **Easy to evolve** - Versioning and deprecation strategies

Remember: **Organization is not a one-time task**. As your project grows, regularly review and refactor your generator organization to keep it maintainable.

Start with the basic structure, and evolve it based on your team's needs. The best organization is the one that your team actually uses and maintains.

---

**Next Steps:**
1. Audit your current generator organization
2. Create a GENERATORS.md catalog
3. Split your config by category
4. Document your top 5 most-used generators
5. Schedule quarterly reviews

**Need help?** Review the complete example above and adapt it to your project's needs.
