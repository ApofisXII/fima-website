# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

FIMA (Fondazione Italiana Musica Antica) is a Symfony 8.0 website for the Italian Foundation for Early Music. The application features both public-facing pages and an admin panel for content management.

## Key Technologies

- **PHP 8.4+** with strict typing enabled
- **Symfony 8.0** with attribute-based routing and configuration
- **Doctrine ORM** with PostgreSQL database
- **Asset Mapper** for frontend assets (no Node.js build step)
- **Twig** template engine
- **Pagerfanta** for pagination

## Common Commands

### Development Server
```bash
symfony server:start          # Start development server (https://localhost:8000)
symfony server:stop           # Stop development server
```

### Database
```bash
php bin/console doctrine:migrations:migrate              # Run migrations
php bin/console doctrine:migrations:diff                 # Generate migration from entity changes
php bin/console doctrine:schema:validate                 # Validate schema mapping
php bin/console doctrine:query:sql "SELECT * FROM news"  # Run SQL query
```

### Debug & Information
```bash
php bin/console debug:router                    # List all routes
php bin/console debug:container                 # List all services
php bin/console debug:autowiring                # List autowirable services
php bin/console debug:asset-map                 # Show asset mapper paths
```

### Cache
```bash
php bin/console cache:clear                     # Clear cache
php bin/console cache:warmup                    # Warm up cache
```

### Testing
```bash
vendor/bin/phpunit                              # Run all tests
vendor/bin/phpunit tests/SomeTest.php           # Run specific test
vendor/bin/phpunit --filter testMethodName      # Run specific test method
```

### Asset Management
```bash
php bin/console importmap:require package-name  # Add frontend dependency
php bin/console debug:asset-map                 # View all mapped assets
```

## Architecture

### Multi-language Support

Content entities use a suffix pattern for localization:
- Italian fields: `*_it` (e.g., `title_it`, `body_it`)
- English fields: `*_en` (e.g., `title_en`, `body_en`)
- Routes include locale prefix: `/{_locale}/news`

When creating or modifying entities, always include both language variants.

### Controller Organization

Controllers are organized by access level:
- **Public controllers** (`src/Controller/Public/`): Public-facing pages
- **Admin controllers** (`src/Controller/Admin/`): Protected admin panel

Admin routes are prefixed with `/admin` and protected by Symfony Security.

### Service Layer Pattern

Business logic is encapsulated in services (`src/Service/`):
- Controllers handle HTTP and route to services
- Services contain business logic and coordinate repositories
- Repositories handle database queries

Example: `NewsService` handles news creation/update logic, while `AdminNewsController` handles HTTP concerns.

### DTOs for Request Handling

Use DTOs with Symfony's `#[MapRequestPayload]` and `#[MapQueryString]` attributes for type-safe request handling:
- Located in `src/DTO/Admin/`
- Include validation constraints using Symfony Validator attributes
- Constructor property promotion for clean syntax

### DataTables Integration

Admin lists use server-side DataTables:
- `DataTableRequestDTO` handles DataTables parameters (draw, start, length, search)
- JSON endpoints return paginated results using Pagerfanta
- See `AdminNewsController::adminNewsListJson()` for the pattern

### Authentication

Admin authentication uses Symfony Security:
- `AdminAuthenticator` extends `AbstractLoginFormAuthenticator`
- Users stored in `User` entity with hashed passwords
- Protected routes defined in `config/packages/security.yaml`

### Frontend Asset Management

Uses Symfony Asset Mapper (no Node.js required):
- JavaScript entrypoints defined in `importmap.php`
- Separate entrypoints for public (`app.js`) and admin (`admin.js`)
- CSS files in `assets/styles/`
- Static assets (images, videos) in `assets/`

### Database Naming Convention

Doctrine uses `underscore_number_aware` naming strategy:
- PHP properties in camelCase become snake_case columns
- Example: `$createdAt` → `created_at`

## Entity Patterns

When creating new entities:
1. Use PHP 8.4+ property promotion and typed properties
2. Include created_at/updated_at timestamps
3. Add repository class annotation
4. For public content, add `is_public` boolean flag
5. Generate getters/setters with `php bin/console make:entity`

## Testing

PHPUnit configuration enforces strict standards:
- Fails on deprecations, notices, and warnings
- Uses `tests/` directory for test files
- Test environment uses separate database with `_test` suffix

## Admin Controller Conventions

- **Non fare null check dopo `find()`**: nei controller admin non aggiungere mai `if (!$entity)` dopo una chiamata a `find()`. L'admin è un contesto controllato e si assume che gli ID passati siano validi. Esempio corretto:
  ```php
  $category = $this->categoryRepository->find($categoryId);
  $this->categoryService->delete($category);
  ```

## Maintainer

Project maintained by [@ApofisXII](https://github.com/ApofisXII).
