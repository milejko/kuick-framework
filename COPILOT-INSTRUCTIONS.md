# Kuick Framework - Copilot Instructions

## 1. Project Overview

**Kuick** is an extremely low footprint PHP application framework designed for developers seeking speed, efficiency, and flexibility in web application development. It's optimized for high throughput workloads with minimal resource consumption.

**Key Characteristics:**
- **PSR-15 Middleware-based**: HTTP message handling via middleware stack
- **PSR-14 Event-driven**: Event dispatcher for lifecycle management
- **PSR-11 Container**: PHP-DI dependency injection container
- **PSR-7 HTTP Messages**: Full HTTP message interface implementation
- **PSR-3 Logging**: Monolog integration with PSR-3 logger interface
- **PSR-16 Caching**: Layered caching system with multiple backends
- **Minimal Dependencies**: Only essential Kuick packages and PSR standards
- **Dual-kernel Support**: WebKernel for HTTP applications, ConsoleKernel for CLI commands

**Current Version:** v2.5.1  
**PHP Support:** PHP 8.2, 8.3, 8.4  
**MIT License**

---

## 2. Directory Structure

```
kuick-framework/
├── src/                          # Framework source code (32 PHP files, ~1058 LOC)
│   ├── BaseKernel.php           # Abstract kernel base class with DI & listener setup
│   ├── WebKernel.php            # Web application kernel (routes, guards, middlewares)
│   ├── ConsoleKernel.php        # Console application kernel (Symfony Console)
│   ├── KernelInterface.php       # Kernel contract & constants
│   ├── SystemCache.php          # Layered cache implementation
│   ├── SystemCacheInterface.php # Cache interface extending PSR-16
│   ├── OptionsServingMiddleware.php # Middleware for OPTIONS requests
│   │
│   ├── Config/                  # Configuration management (12 files)
│   │   ├── ConfigIndexer.php           # Config file discovery & validation
│   │   ├── RouteConfig.php            # Route definition DTO
│   │   ├── RouteConfigValidator.php   # Route validation (path regex, methods, controller)
│   │   ├── GuardConfig.php            # Guard/security definition DTO
│   │   ├── GuardConfigValidator.php   # Guard validation
│   │   ├── MiddlewareConfig.php       # Middleware definition DTO
│   │   ├── MiddlewareConfigValidator.php # Middleware validation
│   │   ├── CommandConfig.php          # Console command definition DTO
│   │   ├── CommandConfigValidator.php # Command validation
│   │   ├── ListenerConfig.php         # Event listener definition DTO
│   │   ├── ListenerConfigValidator.php # Listener validation
│   │   └── ConfigException.php        # Config-related exceptions
│   │
│   ├── Events/                  # Framework events (5 files)
│   │   ├── KernelCreatedEvent.php      # Fired when kernel initialized
│   │   ├── RequestReceivedEvent.php    # Fired when HTTP request received
│   │   ├── ResponseCreatedEvent.php    # Fired when response created
│   │   ├── ResponseEmittedEvent.php    # Fired after response sent
│   │   └── ExceptionRaisedEvent.php    # Fired when exception occurs
│   │
│   ├── Listeners/               # Framework listeners (6 files)
│   │   ├── LocalizingListener.php             # Sets locale, timezone, charset
│   │   ├── RegisteringPhpErrorHandlerListener.php # PHP error/exception handling
│   │   ├── RequestHandlingListener.php        # Delegates to request handler
│   │   ├── ExceptionHandlingListener.php      # Exception to response conversion
│   │   ├── ResponseEmittingListener.php       # Sends HTTP response to client
│   │   └── EventLoggingListener.php           # Logs all framework events
│   │
│   └── DependencyInjection/     # Container setup (2 files)
│       ├── ContainerCreator.php  # Builds PSR-11 container with caching
│       └── DefinitionConfigLoader.php # Loads .di.php definition files
│
├── tests/                       # Test suite (59 PHP files)
│   └── Unit/
│       ├── BaseKernelTest.php
│       ├── WebKernelTest.php
│       ├── ConsoleKernelTest.php
│       ├── SystemCacheTest.php
│       ├── Config/              # Config validator & DTO tests (11 test files)
│       ├── Listeners/           # Listener tests (6 test files)
│       ├── Events/              # Event tests
│       ├── DependencyInjection/ # DI tests
│       └── Mocks/               # Test fixtures & mock objects
│           ├── project-dir/     # Mock project structure
│           ├── invalid-project-dir/ # Invalid config test cases
│           └── Mocks*.php       # Mock classes
│
├── config/                      # Framework default configuration
│   ├── kuick.listeners.php      # Core framework listeners
│   ├── kuick.middlewares.php    # Core middleware stack
│   └── di/
│       ├── kuick.di.php         # Core DI definitions
│       ├── kuick.logger.di.php  # Logger configuration
│       ├── kuick.services.di.php # Service definitions
│       └── kuick.optimization.php # Production optimizations
│
├── config-example/              # Example configuration templates
│   ├── app.routes.php           # Route configuration template
│   ├── app.guards.php           # Guard/security template
│   ├── app.middlewares.php      # Middleware template
│   ├── app.listeners.php        # Listener template
│   ├── app.commands.php         # Console command template
│   └── di/
│       └── app.di@dev.php       # Dev-specific DI template
│
├── public/
│   └── index.php                # Web application entry point
│
├── bin/
│   ├── kuick-installer          # Installation script (copies config templates)
│   └── console                  # Console entry point (installed by installer)
│
├── composer.json                # PHP dependencies and scripts
├── phpunit.xml                  # PHPUnit configuration with coverage
├── Makefile                     # Docker-based test/build automation
├── version.txt                  # Current version tag
├── LICENSE                      # MIT License
├── README.md                    # Project documentation
└── Dockerfile                   # Multi-stage Docker image
```

---

## 3. Build, Test & Lint Commands

### Composer Scripts (defined in composer.json)

```bash
# Code Quality
composer test:phpcs      # PHP CodeSniffer (PSR12 standard)
composer test:phpstan    # Static analysis (level 5)
composer test:phpmd      # PHP Mess Detector
composer fix:phpcbf      # Auto-fix PSR12 violations

# Testing
composer test:phpunit    # PHPUnit test suite

# All checks
composer test:all        # Runs: phpcs, phpstan, phpmd, phpunit
```

### Makefile Commands

```bash
make test               # Docker: Run full test suite in container
make build              # Docker: Build multi-platform production images
make console            # Docker: Interactive bash for development
```

### Direct Commands

```bash
# Run all tests
./vendor/bin/phpunit

# Run single test
./vendor/bin/phpunit tests/Unit/WebKernelTest.php::WebKernelTest::testIfDevKernelIsWellDefined

# Run tests with filter
./vendor/bin/phpunit --filter testIfDevKernelIsWellDefined

# Check code style
./vendor/bin/phpcs -n --standard=PSR12 src tests/Unit

# Static analysis
./vendor/bin/phpstan --level=5 --no-progress analyse src tests/Unit
```

### PHPUnit Configuration

- **Bootstrap:** `vendor/autoload.php`
- **Test Directory:** `tests/`
- **Coverage:** Enabled with strict metadata checking
- **Cache Directory:** `./.phpunit/cache`
- **Execution Order:** Depends & defects-based
- **Failure Conditions:** Strict about deprecations, output, risky tests

---

## 4. Architecture & Patterns

### Core Architecture Layers

```
┌─────────────────────────────────────────────────┐
│           Application Entry Point                │
│  (public/index.php or bin/console)              │
└──────────────┬──────────────────────────────────┘
               │
┌──────────────┴──────────────────────────────────┐
│         Kernel (Web or Console)                  │
│  ├─ Initializes DI Container                   │
│  ├─ Loads all Configuration                     │
│  ├─ Registers Listeners (Event-driven)         │
│  └─ Dispatches KernelCreatedEvent              │
└──────────────┬──────────────────────────────────┘
               │
┌──────────────┴──────────────────────────────────┐
│     PSR-14 Event Dispatcher Lifecycle           │
│  1. KernelCreatedEvent (HIGHEST priority)       │
│  2. LocalizingListener - locale/timezone/charset│
│  3. RegisteringPhpErrorHandlerListener          │
│  4. RequestReceivedEvent (NORMAL priority)      │
│  5. RequestHandlingListener → Middleware Stack  │
│  6. ExceptionRaisedEvent (if error)             │
│  7. ResponseCreatedEvent → Response Emitting    │
│  8. ResponseEmittedEvent (after headers sent)   │
└──────────────┬──────────────────────────────────┘
               │
┌──────────────┴──────────────────────────────────┐
│        Middleware Stack (PSR-15)                │
│  1. SecurityMiddleware (guards)                 │
│  2. OptionsServingMiddleware                    │
│  3. RoutingMiddleware                           │
│  + Custom app middlewares                       │
└──────────────┬──────────────────────────────────┘
               │
┌──────────────┴──────────────────────────────────┐
│        Request Handler Chain                    │
│  ├─ Guards (SecurityMiddleware)                 │
│  ├─ Router (finds matching route)              │
│  ├─ Controller (invokable class)               │
│  └─ Fallback: 404 or Error Handler             │
└─────────────────────────────────────────────────┘
```

### Key Abstractions & Interfaces

**Kernels:**
- `KernelInterface` - Contract for all kernels
- `BaseKernel` - Shared initialization (DI, listeners)
- `WebKernel extends BaseKernel` - HTTP request handling
- `ConsoleKernel extends BaseKernel` - CLI command handling

**Configuration Management:**
- `ConfigIndexer` - Discovers & validates config files
- Pattern: Files must match `*.SUFFIX.php` or `*.SUFFIX@ENV.php`
- Each config file returns array of DTOs (RouteConfig, GuardConfig, etc.)
- Caching for production performance (infinite cache in dev mode)

**Dependency Injection:**
- `ContainerCreator` - Builds PSR-11 container using PHP-DI
- Definition files: `/config/di/*.di.php` and environment-specific `/config/di/*.di@prod.php`
- Container compilation for production (disabled in dev)
- APCu caching support when available
- Attributes injection with `#[Inject]` from PHP-DI

**Event System:**
- `ListenerProvider` - Registers listeners for event patterns
- `EventDispatcher` - Fires events with listeners
- Listener priorities: `HIGHEST`, `HIGH`, `NORMAL`, `LOW`, `LOWEST`
- Pattern-based listener registration: `'*'` for all events

**Caching:**
- `SystemCache` - Layered cache (in-memory → APCu → filesystem)
- Dev mode: NullCache only (no caching)
- Prod mode: InMemoryCache + APCu (if available) + FilesystemCache
- Location: `/var/cache/ENV/` directory

### DI Container Setup Flow

1. **ContainerCreator::create()** creates builder with attributes enabled
2. Loads environment-specific definition files from `/config/di/*.di@ENV.php`
3. Adds core DI definitions (framework services)
4. App can override via its own definition files
5. Container compiled to `/var/cache/ENV/CompiledContainer.php` in production
6. APCu cache used for definition cache if available

---

## 5. PHP Namespaces & Class Organization

### Primary Namespace
- `Kuick\Framework\` - All framework code

### Sub-namespaces
```
Kuick\Framework\                          # Core classes
Kuick\Framework\Config\                   # Configuration DTOs & validators
Kuick\Framework\Events\                   # Framework events
Kuick\Framework\Listeners\                # Framework listeners
Kuick\Framework\DependencyInjection\      # Container setup
```

### Test Namespaces
```
Tests\Unit\Kuick\Framework\               # Unit tests
Tests\Unit\Kuick\Framework\Mocks\         # Test fixtures
```

### Class Organization Principles

1. **One class per file** (PSR-4 autoloading)
2. **Naming**: PascalCase for classes
3. **File naming**: Matches class name exactly
4. **Visibility**: Mostly `final` classes (few extensions)
5. **Constructor injection**: DI attributes with `#[Inject]` for optional overrides
6. **Invokable patterns**: Listeners & controllers use `__invoke()` method

---

## 6. Key Conventions

### Configuration Files Convention

```
Pattern: config/[app-name].[SUFFIX].php
Pattern: config/[app-name].[SUFFIX]@[ENV].php

Suffixes:
- routes      → RouteConfig objects
- guards      → GuardConfig objects
- middlewares → MiddlewareConfig objects
- listeners   → ListenerConfig objects
- commands    → CommandConfig objects

Valid Envs: dev, prod, test (set via APP_ENV)
```

### Route Configuration Example
```php
use Kuick\Framework\Config\RouteConfig;
use Kuick\Http\Message\RequestInterface;

return [
    new RouteConfig(
        path: '/users/(\d+)',           // Regex pattern
        controllerClassName: UserController::class,
        methods: [RequestInterface::METHOD_GET]
    ),
];
```

### Guard Configuration Example
```php
use Kuick\Framework\Config\GuardConfig;
use Kuick\Http\Message\RequestInterface;

return [
    new GuardConfig(
        path: '/admin/(.*)',             // Path pattern
        guardClassName: AdminGuard::class,
        methods: [
            RequestInterface::METHOD_GET,
            RequestInterface::METHOD_POST,
        ]
    ),
];
```

### Middleware Configuration Example
```php
use Kuick\Framework\Config\MiddlewareConfig;

return [
    new MiddlewareConfig(
        middlewareClassName: AuthMiddleware::class,
        beforeMiddlewareClassName: RoutingMiddleware::class  // Optional: insert before
    ),
];
```

### Listener Configuration Example
```php
use Kuick\Framework\Config\ListenerConfig;
use Kuick\EventDispatcher\ListenerPriority;
use Kuick\Framework\Events\RequestReceivedEvent;

return [
    new ListenerConfig(
        pattern: RequestReceivedEvent::class,  // Class name or '*' for all
        listenerClassName: CustomListener::class,
        priority: ListenerPriority::HIGH       // Default: NORMAL
    ),
];
```

### Command Configuration Example
```php
use Kuick\Framework\Config\CommandConfig;

return [
    new CommandConfig(
        name: 'app:migrate',
        commandClassName: MigrateCommand::class,
        description: 'Run database migrations'
    ),
];
```

### DI Definition File Example
```php
// config/di/app.di.php
use function DI\autowire;
use function DI\create;
use function DI\env;

return [
    'app.name' => env('APP_NAME', 'My App'),
    'app.timezone' => env('APP_TIMEZONE', 'UTC'),
    
    SomeService::class => autowire(SomeService::class),
    DatabaseConnection::class => create(DatabaseConnection::class)
        ->constructor(env('DATABASE_URL')),
];
```

### Middleware Pattern
```php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

final class CustomMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Pre-processing
        $response = $handler->handle($request);
        // Post-processing
        return $response;
    }
}
```

### Controller Pattern (Invokable)
```php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Kuick\Http\Message\JsonResponse;

final class UserController
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['user' => 'data']);
    }
}
```

### Guard Pattern
```php
namespace App\Security;

use Psr\Http\Message\ServerRequestInterface;

final class AdminGuard
{
    public function __invoke(ServerRequestInterface $request): void
    {
        // Throw HttpException if unauthorized
        // Otherwise, return void for success
    }
}
```

### Listener Pattern
```php
namespace App\Listener;

use Kuick\Framework\Events\RequestReceivedEvent;
use Psr\Log\LoggerInterface;

final class CustomListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function __invoke(RequestReceivedEvent $event): void
    {
        $this->logger->info('Request received');
    }
}
```

### Environment-specific Configuration
```
config/app.routes.php              → Loaded always
config/app.routes@dev.php          → Loaded only in dev
config/app.routes@prod.php         → Loaded only in prod
config/di/app.di.php               → Loaded always
config/di/app.di@dev.php           → Loaded only in dev
```

---

## 7. AI Assistant Config Files

**Status:** None found in the repository

No existing `CLAUDE.md`, `.cursorrules`, `.cursor/`, `AGENTS.md`, `.windsurfrules`, `CONVENTIONS.md`, or `.clinerules` files exist.

---

## 8. README.md Summary

**Kuick Framework** is an extremely low footprint PHP application framework optimized for speed and high throughput workloads. It implements PSR standards (PSR-3, PSR-7, PSR-11, PSR-14, PSR-15, PSR-16) using modern PHP 8.2+ features.

**Key Features:**
- Logging with PSR-3 interface (Monolog)
- HTTP messages with PSR-7
- Dependency injection (PSR-11, PHP-DI)
- Event dispatcher (PSR-14)
- Request handling (PSR-15)
- Caching (PSR-16)

**Quick Start:**
```bash
composer require kuick/framework
composer require kuick/api-tools
./vendor/bin/kuick-installer
```

**Docker:** Ready-to-deploy images at `kuickphp/kuick:alpine`

**Environment Variables:**
- `APP_ENV`: dev, prod (default: prod)
- `APP_NAME`: Application name
- `APP_CHARSET`: UTF-8 (default)
- `APP_LOCALE`: en_US.utf-8 (default)
- `APP_TIMEZONE`: UTC (default)
- `APP_LOG_USEMICROSECONDS`: Log microseconds
- `APP_LOG_LEVEL`: DEBUG, INFO, NOTICE, WARNING, ERROR, CRITICAL
- `API_SECURITY_OPS_GUARD_TOKEN`: Token for /api/ops endpoint

---

## 9. Testing Patterns & Organization

### Test Organization Structure

```
tests/Unit/
├── Config/              # Configuration validation tests
│   ├── RouteConfigTest.php
│   ├── RouteConfigValidatorTest.php
│   ├── GuardConfigTest.php
│   ├── GuardConfigValidatorTest.php
│   ├── MiddlewareConfigTest.php
│   ├── MiddlewareConfigValidatorTest.php
│   ├── CommandConfigTest.php
│   ├── CommandConfigValidatorTest.php
│   ├── ListenerConfigTest.php
│   ├── ListenerConfigValidatorTest.php
│   └── ConfigIndexerTest.php
├── Listeners/           # Framework listener tests
│   ├── EventLoggingListenerTest.php
│   ├── ExceptionHandlingListenerTest.php
│   ├── LocalizingListenerTest.php
│   ├── RegisteringPhpErrorHandlerListenerTest.php
│   ├── RequestHandlingListenerTest.php
│   └── ResponseEmittingListenerTest.php
├── Events/              # Framework event tests
├── DependencyInjection/ # DI container tests
├── Mocks/               # Test fixtures
│   ├── MockController.php
│   ├── MockGuard.php
│   ├── MockListener.php
│   ├── MockKernel.php
│   ├── MockRequestHandler.php
│   ├── project-dir/              # Valid mock project
│   ├── invalid-project-dir/      # Invalid config tests
│   ├── invalid-project-dir-2/
│   └── invalid-project-dir-3/
├── BaseKernelTest.php
├── WebKernelTest.php
├── ConsoleKernelTest.php
└── SystemCacheTest.php
```

### Test Patterns Used

**Coverage Attributes (PHPUnit 10+):**
```php
#[CoversClass(SomeClass::class)]
class SomeClassTest extends TestCase
{
    public function testSomething(): void
    {
        // Test code
    }
}
```

**Separate Process Annotation:**
```php
/**
 * @runInSeparateProcess
 */
public function testHeadersAreSent(): void
{
    // Test that sends headers
}
```

**Setup Methods:**
```php
public static function setUpBeforeClass(): void
{
    self::$projectDir = realpath(__DIR__ . '/Mocks/project-dir');
}

public function setUp(): void
{
    // Per-test setup
}
```

**No Assertions Pattern:**
```php
public function testListenerIsInvoked(): void
{
    $listener = new EventLoggingListener(new NullLogger());
    $listener(new stdClass());
    $this->expectNotToPerformAssertions();  // Test passes if no exception
}
```

### Running Tests

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test class
./vendor/bin/phpunit tests/Unit/WebKernelTest.php

# Run single test method
./vendor/bin/phpunit tests/Unit/WebKernelTest.php::WebKernelTest::testIfDevKernelIsWellDefined

# Run with coverage
./vendor/bin/phpunit --coverage-clover=clover.xml

# Run with verbose output
./vendor/bin/phpunit -v
```

---

## 10. Key Interfaces, Abstract Classes & Base Classes

### Core Interfaces

```php
// src/KernelInterface.php
interface KernelInterface {
    const APP_ENV = 'APP_ENV';
    const ENV_DEV = 'dev';
    const ENV_PROD = 'prod';
    const DI_APP_ENV_KEY = 'app.env';
    const DI_APP_NAME_KEY = 'app.name';
    const DI_PROJECT_DIR_KEY = 'app.projectDir';
    
    public function getContainer(): ContainerInterface;
}

// src/SystemCacheInterface.php
interface SystemCacheInterface extends CacheInterface {
    const CACHE_PATH = '/var/cache';
}
```

### Abstract/Base Classes

```php
// src/BaseKernel.php
abstract class BaseKernel implements KernelInterface {
    private ContainerInterface $container;
    
    public function __construct(string $projectDir) {
        // DI container creation
        // Listener registration
    }
    
    public function getContainer(): ContainerInterface;
}

// src/WebKernel.php (extends BaseKernel)
final class WebKernel extends BaseKernel {
    public function __construct(string $projectDir) {
        parent::__construct($projectDir);
        // Guard registration
        // Route registration
        // Middleware registration
        // KernelCreatedEvent dispatch
    }
    
    public function run(ServerRequestInterface $request): void;
}

// src/ConsoleKernel.php (extends BaseKernel)
final class ConsoleKernel extends BaseKernel {
    public function __construct(string $projectDir) {
        parent::__construct($projectDir);
        // Command registration
        // KernelCreatedEvent dispatch
    }
    
    public function run(): int;  // Returns exit code
}
```

### Event Classes (all `final`)

```php
final class RequestReceivedEvent { /* ServerRequestInterface $request */ }
final class ResponseCreatedEvent { /* ResponseInterface $response */ }
final class ResponseEmittedEvent { /* ResponseInterface $response */ }
final class ExceptionRaisedEvent { /* Throwable $exception */ }
final class KernelCreatedEvent { /* KernelInterface $kernel */ }
```

### Configuration DTOs (all `final` with `readonly` properties)

```php
final class RouteConfig {
    public readonly string $path;
    public readonly string $controllerClassName;
    public readonly array $methods;
}

final class GuardConfig {
    public readonly string $path;
    public readonly string $guardClassName;
    public readonly array $methods;
}

final class MiddlewareConfig {
    public readonly string $middlewareClassName;
    public readonly ?string $beforeMiddlewareClassName;
}

final class ListenerConfig {
    public readonly string $pattern;
    public readonly string $listenerClassName;
    public readonly int $priority;
}

final class CommandConfig {
    public readonly string $name;
    public readonly string $commandClassName;
    public readonly string $description;
}
```

### Listener Classes (all `final`)

```php
final class LocalizingListener { /* Locale/timezone/charset setup */ }
final class RegisteringPhpErrorHandlerListener { /* PHP error handling */ }
final class RequestHandlingListener { /* Request → response conversion */ }
final class ExceptionHandlingListener { /* Exception → response */ }
final class ResponseEmittingListener { /* Send response to client */ }
final class EventLoggingListener { /* Logs all events */ }
```

### Validator Classes (all `final`)

All validators follow the pattern:
```php
final class SomeConfigValidator {
    public function validate(SomeConfig $configObject): void;
    // Throws ConfigException on validation failure
}
```

---

## 11. Configuration Examples & Patterns

### config-example/ Directory Contents

**Example Files:**
- `app.routes.php` - HTTP route definitions
- `app.guards.php` - Security/authorization guards
- `app.middlewares.php` - HTTP middleware stack
- `app.listeners.php` - Event listeners
- `app.commands.php` - Console commands
- `di/app.di@dev.php` - Dev-only DI definitions

**Configuration Loading Rules:**
1. Framework loads `/vendor/kuick/*/config/*.SUFFIX.php` (dependencies)
2. Then loads `/config/*.SUFFIX.php` (app config)
3. Finally loads `/config/*.SUFFIX@ENV.php` (env-specific overrides)
4. Results cached infinitely (dev) or via APCu+filesystem (prod)

**Location Templates:**
```
Vendor: /vendor/kuick/*/config/di/*.di.php
App:    /config/di/*.di.php
App:    /config/di/*.di@{ENV}.php
```

### Core Framework Configuration

**kuick.listeners.php** (Core listeners loaded first):
1. EventLoggingListener (HIGHEST priority)
2. LocalizingListener (HIGHEST priority)
3. RegisteringPhpErrorHandlerListener (HIGHEST priority)
4. ExceptionHandlingListener (LOWEST priority)
5. RequestHandlingListener (LOWEST priority)
6. ResponseEmittingListener (LOWEST priority)

**kuick.middlewares.php** (Core middleware stack):
1. SecurityMiddleware (guards)
2. OptionsServingMiddleware
3. RoutingMiddleware

**kuick.di.php** (Core services):
```php
'app.name' => env('APP_NAME', 'Kuick App')
'app.charset' => env('APP_CHARSET', 'UTF-8')
'app.locale' => env('APP_LOCALE', 'en_US.utf-8')
'app.timezone' => env('APP_TIMEZONE', 'UTC')
'app.log.level' => env('APP_LOG_LEVEL', 'NOTICE')
'app.log.usemicroseconds' => env('APP_LOG_USEMICROSECONDS', false)
'app.log.handlers' => [['type' => 'stream', 'path' => 'php://stdout']]
```

**kuick.services.di.php**:
```php
Application::class => create(Application::class)
EventDispatcherInterface::class => autowire(EventDispatcher::class)
FallbackRequestHandlerInterface::class => create(JsonNotFoundRequestHandler::class)
ListenerProviderInterface::class => create(ListenerProvider::class)
RequestHandlerInterface::class => autowire(StackRequestHandler::class)
SystemCacheInterface::class => autowire(SystemCache::class)
```

---

## 12. Detailed src/ Directory Analysis

### Root Level Files (5 files)

**BaseKernel.php** (52 lines)
- Abstract kernel implementing KernelInterface
- Creates DI container via ContainerCreator
- Registers event listeners from config files
- Base for WebKernel and ConsoleKernel

**WebKernel.php** (84 lines)
- Extends BaseKernel
- Loads and registers routes
- Loads and registers guards
- Loads and registers middlewares
- Dispatches KernelCreatedEvent
- Implements run(ServerRequestInterface): void

**ConsoleKernel.php** (48 lines)
- Extends BaseKernel
- Loads console commands from config
- Registers with Symfony Console Application
- Dispatches KernelCreatedEvent
- Implements run(): int

**KernelInterface.php** (29 lines)
- Contract for all kernels
- Defines constants for ENV_DEV, ENV_PROD
- Defines DI container keys

**SystemCache.php** (43 lines)
- Extends LayeredCache (from kuick/cache)
- Constructor injection of projectDir and env
- Dev mode: NullCache only
- Prod mode: InMemoryCache → APCu (if available) → FilesystemCache

**SystemCacheInterface.php** (18 lines)
- Extends PSR-16 CacheInterface
- Defines CACHE_PATH constant = '/var/cache'

**OptionsServingMiddleware.php** (28 lines)
- Implements MiddlewareInterface
- Returns 204 No Content for OPTIONS requests
- Delegates to handler for other methods

### Config/ Directory (12 files, ~6000 LOC)

**ConfigIndexer.php** (114 lines)
- Discovers config files via glob patterns
- Validates config file contents
- Caches results for performance
- Supports environment-specific files (@env suffix)
- File suffixes: commands, listeners, guards, routes, middlewares

**RouteConfig.php** (26 lines)
- DTO with path (regex), controllerClassName, methods array
- All properties readonly

**RouteConfigValidator.php** (82 lines)
- Validates route path is non-empty regex
- Validates methods are standard HTTP methods
- Validates controller class exists and is invokable
- Throws ConfigException on validation failure

**GuardConfig.php** (33 lines)
- DTO with path (pattern), guardClassName, methods array

**GuardConfigValidator.php** (82 lines)
- Validates guard path is non-empty pattern
- Validates methods are standard HTTP methods
- Validates guard class exists and is invokable

**MiddlewareConfig.php** (23 lines)
- DTO with middlewareClassName, optional beforeMiddlewareClassName

**MiddlewareConfigValidator.php** (55 lines)
- Validates middleware class exists and is a MiddlewareInterface
- Optional beforeMiddleware must also be valid

**ListenerConfig.php** (23 lines)
- DTO with pattern (event class or '*'), listenerClassName, priority

**ListenerConfigValidator.php** (47 lines)
- Validates listener class exists and is callable/invokable

**CommandConfig.php** (24 lines)
- DTO with name, commandClassName, optional description

**CommandConfigValidator.php** (61 lines)
- Validates command name is non-empty
- Validates command class exists and extends Command

**ConfigException.php** (17 lines)
- Custom exception for configuration errors

### Events/ Directory (5 files)

All are final classes with single readonly property and getter:

**RequestReceivedEvent.php** (25 lines)
- Property: ServerRequestInterface $request
- Fired when HTTP request received

**ResponseCreatedEvent.php** (25 lines)
- Property: ResponseInterface $response
- Fired when response created

**ResponseEmittedEvent.php** (25 lines)
- Property: ResponseInterface $response
- Fired after response sent to client

**ExceptionRaisedEvent.php** (25 lines)
- Property: Throwable $exception
- Fired when exception occurs

**KernelCreatedEvent.php** (25 lines)
- Property: KernelInterface $kernel
- Fired when kernel fully initialized

### Listeners/ Directory (6 files)

**LocalizingListener.php** (43 lines)
- Sets locale, timezone, charset via ini_set and setlocale
- Fired on KernelCreatedEvent
- Injects 'app.locale', 'app.timezone', 'app.charset'

**RegisteringPhpErrorHandlerListener.php** (40 lines)
- Registers PHP error and exception handlers
- Converts PHP errors/exceptions to framework exceptions
- Fired on KernelCreatedEvent

**RequestHandlingListener.php** (38 lines)
- Handles RequestReceivedEvent
- Delegates to RequestHandlerInterface
- Dispatches ResponseCreatedEvent

**ExceptionHandlingListener.php** (42 lines)
- Handles ExceptionRaisedEvent
- Converts exception to HTTP response via FallbackRequestHandlerInterface
- Dispatches ResponseCreatedEvent
- Logs exception appropriately

**ResponseEmittingListener.php** (39 lines)
- Handles ResponseCreatedEvent
- Emits response to client via ResponseEmitter
- Dispatches ResponseEmittedEvent

**EventLoggingListener.php** (31 lines)
- Listens to '*' (all events)
- Logs every event fired
- HIGHEST priority to run first

### DependencyInjection/ Directory (2 files)

**ContainerCreator.php** (97 lines)
- Creates PSR-11 container using PHP-DI
- Builds or loads from cache
- Enables compilation for non-dev environments
- Supports APCu caching
- Loads definition files via DefinitionConfigLoader

**DefinitionConfigLoader.php** (44 lines)
- Loads .di.php files from config locations
- Supports environment-specific definitions (@env suffix)
- Returns array of loaded definition file paths

---

## Summary Statistics

- **Total Files:** 32 PHP files in src/
- **Lines of Code (src/):** ~1,058 LOC
- **Test Files:** 59 PHP files
- **Classes:** All use `final` keyword (except abstract BaseKernel)
- **Namespaces:** Single main namespace `Kuick\Framework\` with 4 sub-namespaces
- **PSR Standards:** PSR-3, PSR-4, PSR-7, PSR-11, PSR-14, PSR-15, PSR-16
- **PHP Version:** 8.2+
- **Dependencies:** Minimal (only Kuick packages, PSR interfaces, Monolog, Symfony Console, PHP-DI)
- **Code Style:** PSR-12
- **Testing:** PHPUnit 10+ with coverage requirements
