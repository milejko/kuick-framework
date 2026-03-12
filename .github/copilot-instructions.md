# Kuick Framework – Copilot Instructions

Kuick is a minimal, high-throughput PHP framework built on PSR standards (PSR-3, 7, 11, 14, 15, 16). PHP 8.2+ required.

## Commands

```bash
# Run full test suite (CS + static analysis + PHPMD + PHPUnit)
composer test:all

# Individual checks
composer test:phpcs       # PSR-12 code style
composer test:phpstan     # Static analysis (level 5)
composer test:phpmd       # Mess detection
composer test:phpunit     # Unit tests with coverage

# Auto-fix code style violations
composer fix:phpcbf

# Run a single test file
./vendor/bin/phpunit tests/Unit/WebKernelTest.php

# Run a single test method
./vendor/bin/phpunit --filter testIfDevKernelIsWellDefined

# Docker-based (runs full suite in container)
make test
```

## Architecture

### Kernel hierarchy
- `BaseKernel` – bootstraps the DI container and registers event listeners from config files
- `WebKernel extends BaseKernel` – additionally registers guards, routes, and middlewares; entry point is `run(ServerRequestInterface $request)`
- `ConsoleKernel extends BaseKernel` – registers Symfony Console commands

### Event-driven request lifecycle
```
KernelCreatedEvent       → localization, PHP error handler registration
RequestReceivedEvent     → middleware stack → security guards → routing → controller
ExceptionRaisedEvent     → exception handling listener
ResponseCreatedEvent     → response emitting listener
ResponseEmittedEvent     → cleanup
```

### DI container
Built with PHP-DI. `ContainerCreator` uses `DefinitionConfigLoader` to discover `*.di.php` files, then compiles to a cached container in non-dev environments. In `dev`, compilation is disabled entirely.

### Caching (`SystemCache`)
Layered: **in-memory → APCu → filesystem** in `prod`; **NullCache** in `dev`.

## Config file discovery

`ConfigIndexer` scans for PHP files matching these patterns (in order, each layer overrides the previous):
1. `vendor/kuick/*/config/*.SUFFIX.php` – framework/package defaults
2. `config/*.SUFFIX.php` – app-level config
3. `config/*.SUFFIX@ENV.php` – environment-specific overrides (`APP_ENV` value)

Config is cached after first load. **Suffixes and their return types:**

| Suffix | Returns |
|--------|---------|
| `routes` | `RouteConfig[]` |
| `guards` | `GuardConfig[]` |
| `middlewares` | `MiddlewareConfig[]` |
| `listeners` | `ListenerConfig[]` |
| `commands` | `CommandConfig[]` |

**DI definitions** are discovered separately by `DefinitionConfigLoader` (not `ConfigIndexer`) using the patterns:
- `vendor/kuick/*/config/di/*.di.php`
- `config/di/*.di.php`
- `config/di/*.di@ENV.php` (env-specific)

## Key conventions

### All classes are `final`
Every concrete class in `src/` is `final`. Use composition, not extension (except extending `BaseKernel` for custom kernels).

### Invokable pattern
Controllers, guards, middleware, listeners, and commands all implement `__invoke()` and are registered by class name. The container resolves and instantiates them.

### Config DTOs are `final readonly`
`RouteConfig`, `GuardConfig`, `MiddlewareConfig`, `ListenerConfig`, `CommandConfig` are immutable value objects. Each has a corresponding `*Validator` class.

### DI injection via `#[Inject]`
Use PHP-DI's attribute injection for named parameters:
```php
public function __construct(
    #[Inject('app.projectDir')] string $projectDir,
    #[Inject('app.env')] string $env,
) {}
```

### Environment variables map to DI keys
| Env var | DI key |
|---------|--------|
| `APP_NAME` | `app.name` |
| `APP_ENV` | `app.env` |
| `APP_TIMEZONE` | `app.timezone` |
| `APP_LOCALE` | `app.locale` |
| `APP_LOG_LEVEL` | `app.log.level` |

### Namespace structure
```
Kuick\Framework\                     # Kernels, cache, interfaces
Kuick\Framework\Config\              # DTOs + validators + ConfigIndexer
Kuick\Framework\Events\              # Lifecycle event classes
Kuick\Framework\Listeners\           # Framework event listeners
Kuick\Framework\DependencyInjection\ # ContainerCreator
```

Test namespace mirrors: `Tests\Unit\Kuick\Framework\`

## Testing patterns

- PHPUnit 10+ attribute syntax: `#[CoversClass(Foo::class)]` on every test class
- Tests that emit HTTP headers require `@runInSeparateProcess`
- Mock project structures live in `tests/Unit/Mocks/project-dir/` and provide realistic config/DI fixtures
- `expectNotToPerformAssertions()` used when testing that no exception is thrown
