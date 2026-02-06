# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

FIMA (Fondazione Italiana Musica Antica) website - Official website for the Italian Foundation for Early Music. Built with Symfony 8.0, PHP 8.4+, Doctrine ORM, and Twig templating.

## Development Environment

### Prerequisites
- PHP 8.4+
- Composer
- Symfony CLI (recommended)
- MySQL database

### Initial Setup
```bash
composer install
php bin/console doctrine:migrations:migrate
symfony server:start
```

## Essential Commands

### Development Server
```bash
symfony server:start          # Start local server
symfony server:stop           # Stop local server
```

### Database
```bash
php bin/console doctrine:migrations:migrate                    # Run all migrations
php bin/console doctrine:migrations:migrate --no-interaction   # Non-interactive
php bin/console doctrine:schema:update --dump-sql              # Preview schema changes
php bin/console doctrine:schema:validate                       # Validate mapping
```

### Database Migrations
```bash
php bin/console make:migration                                 # Generate migration from entities
php bin/console doctrine:migrations:status                     # Check migration status
php bin/console doctrine:migrations:diff                       # Generate diff migration
```

### Code Generation (Maker Bundle)
```bash
php bin/console make:entity              # Create/update entity
php bin/console make:controller          # Create controller
php bin/console make:form                # Create form type
php bin/console make:migration           # Generate migration
php bin/console make:repository          # Create repository
```

### Testing
```bash
vendor/bin/phpunit                       # Run all tests
vendor/bin/phpunit --testdox            # Run with detailed output
vendor/bin/phpunit tests/SomeTest.php   # Run specific test file
```

### Cache & Assets
```bash
php bin/console cache:clear                    # Clear cache
php bin/console cache:warmup                   # Warm up cache
php bin/console assets:install                 # Install assets to public/
php bin/console importmap:install              # Install importmap dependencies
php bin/console debug:asset-map                # List asset mapper entries
```

### Debugging
```bash
php bin/console debug:router                   # List all routes
php bin/console debug:router <route_name>     # Show specific route
php bin/console debug:container               # List services
php bin/console debug:autowiring              # List autowirable services
```

## Architecture Overview

### Directory Structure

**src/Controller/** - Controllers organized by access level:
- `Admin/` - Admin panel controllers (requires ROLE_ADMIN)
  - `AdminAuthController.php` - Authentication (login/logout)
  - `AdminNewsController.php` - News CRUD with DataTable pagination
  - `AdminMainController.php` - Admin dashboard
- `Public/` - Public-facing controllers
  - `GeneralController.php` - Homepage with locale detection
  - `NewsController.php` - Public news display
  - `UrbinoMainController.php` - Urbino section

**src/Entity/** - Doctrine entities (database tables):
- `News.php` - News/events with bilingual content (IT/EN)
- `User.php` - Admin users with Symfony security integration
- `UrbinoCourse.php`, `UrbinoEvent.php`, `UrbinoEdition.php` - Urbino section entities

**src/Repository/** - Doctrine repositories with custom queries
- Each entity has a corresponding repository
- Custom query methods for filtering, sorting, pagination

**src/Service/** - Business logic layer:
- `NewsService.php` - News creation/update logic
- `UrbinoEditionService.php` - Urbino edition management
- Services handle complex operations beyond simple CRUD

**src/DTO/** - Data Transfer Objects:
- `Admin/DataTableRequestDTO.php` - Server-side DataTable pagination parameters
- `Admin/NewsRequestDTO.php` - News form validation/mapping
- DTOs use Symfony's `#[MapRequestPayload]` and `#[MapQueryString]` attributes

**src/Security/** - Authentication & authorization:
- `AdminAuthenticator.php` - Custom form login authenticator for admin panel

**templates/** - Twig templates organized by access level:
- `admin/` - Admin panel templates with DataTable integration
- `public/` - Public website templates with bilingual support

**assets/** - Frontend assets:
- `app.js` - Public site entrypoint
- `admin.js` - Admin panel entrypoint
- `styles/` - CSS files
- `images/`, `videos/`, `fonts/` - Static assets
- Uses Symfony AssetMapper (no build step required)

### Key Technical Patterns

**Bilingual Content**: Most entities have `_it` and `_en` field suffixes for Italian/English content. The `GeneralController` detects browser language and redirects to appropriate locale (`/{_locale}` routes).

**Admin Panel**: Protected by `ROLE_ADMIN` access control. Uses server-side DataTables for list views with search and pagination. Forms submit via AJAX to JSON endpoints.

**Routing**: Controllers use PHP attributes (`#[Route]`) for routing. Admin routes prefixed with `/admin`, public routes use `/{_locale}` parameter.

**Security**: Custom authenticator (`AdminAuthenticator`) with remember-me functionality. Admin routes require `ROLE_ADMIN`, except `/admin/login` which is public.

**Pagination**: Admin lists use Pagerfanta with Doctrine ORM adapter for efficient server-side pagination. DataTable requests map to `DataTableRequestDTO`.

**Asset Management**: Uses Symfony AssetMapper with importmap.php for JavaScript dependencies (DataTables, jQuery, Splide). No npm/webpack build step required.

**Database**: PostgreSQL with Doctrine ORM. Migrations in `migrations/` directory track schema changes. All entities use underscore naming strategy.

## Code Conventions

- Use PHP 8.4+ features (typed properties, constructor property promotion, enums)
- All routes defined via `#[Route]` attributes in controllers
- Repository methods should return typed results (arrays, single entities, or null)
- Services handle business logic; keep controllers thin
- Use DTOs with Symfony's mapper attributes for request validation
- Template paths follow pattern: `namespace/action.html.twig` (e.g., `admin/news-list.html.twig`)

## Database Schema Notes

- All entities use auto-generated integer IDs
- Timestamps: `created_at`, `updated_at` (DateTime)
- Boolean flags use `is_` or `has_` prefixes
- Slugs used for public-facing URLs
- News entity supports both standard news and events (`is_event` flag with optional `event_datetime`)

## Project Maintainer

Code owner: [@ApofisXII](https://github.com/ApofisXII)
