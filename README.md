# Laravel Doctrine JSON:API Skeleton

This skeleton provides a template for building API-only Laravel applications using Doctrine ORM and the [laravel-doctrine-jsonapi](https://github.com/ScholarshipOwl/laravel-doctrine-jsonapi) package for fully compliant [JSON:API](https://jsonapi.org/) implementations.

**Key Features:**
- **Doctrine ORM**: Replaces Eloquent as the database layer, leveraging Doctrine's advanced data-mapping capabilities via [laravel-doctrine/orm](https://github.com/laravel-doctrine/orm).
- **Doctrine Migrations**: Database migrations using [laravel-doctrine/migrations](https://github.com/laravel-doctrine/migrations).
- **Doctrine Extensions**: Extra features (sluggable, timestamps, soft deletes, etc.) via [laravel-doctrine/extensions](https://github.com/laravel-doctrine/extensions).
- **JSON:API**: All APIs conform to the JSON:API specification for standardized, robust, and scalable APIs.
- **API-Only**: No web routes or traditional Blade views are exposed—this skeleton is focused exclusively on backend API development.
- **Automatic API Documentation**: Generates OpenAPI specs, Postman collections, and human-friendly docs using [Scribe](https://scribe.knuckles.wtf/).

---

## About This Skeleton
This project is intended as a starting point for developers who want to:
- Use Laravel as the application framework
- Use Doctrine ORM for data persistence (with full support for migrations and extensions)
- Expose only JSON:API-compliant endpoints (no web UI)

You get a clean Laravel installation, ready for Doctrine and JSON:API integration.

---

## Quick Start

You can start a new project using this skeleton by running:

```bash
composer create-project sowl/laravel-doctrine-jsonapi-skeleton jsonapi
```

---

## Main Packages
- [laravel/laravel](https://github.com/laravel/laravel): Laravel framework
- [laravel-doctrine/orm](https://github.com/laravel-doctrine/orm): Doctrine ORM integration for Laravel ([docs](https://laravel-doctrine-orm-official.readthedocs.io/en/latest/))
- [laravel-doctrine/migrations](https://github.com/laravel-doctrine/migrations): Integration with Doctrine2's migrations package for Laravel
- [laravel-doctrine/extensions](https://github.com/laravel-doctrine/extensions): Doctrine extensions for Laravel (Gedmo, Beberlei, etc.)
- [sowl/laravel-doctrine-jsonapi](https://github.com/ScholarshipOwl/laravel-doctrine-jsonapi): JSON:API implementation for Laravel + Doctrine
- [scribe](https://github.com/knuckleswtf/scribe): API documentation generator

---

## TODO List
This is a high-level roadmap for fully implementing the skeleton:

- [X] Remove default Laravel web routes and Blade views
- [X] Install and configure Doctrine ORM ([laravel-doctrine/orm](https://github.com/laravel-doctrine/orm))
  - [X] Publish and customize Doctrine config
  - [X] Configure environment variables for Doctrine
- [X] Install and configure Doctrine Migrations ([laravel-doctrine/migrations](https://github.com/laravel-doctrine/migrations))
  - [X] Set up migration paths
- [X] Install and configure Doctrine Extensions ([laravel-doctrine/extensions](https://github.com/laravel-doctrine/extensions))
  - [X] Install Gedmo extensions requirement
  - [X] Enable useful extensions (timestamps, sluggable, soft deletes, etc.)
- [X] Set up basic `User` entity
  - [X] Set up entity folder structure
  - [X] Setup authentication and all the relevant user traits
  - [X] Add migration of the `User` entity and rest default entities.
  - [X] Tests for authentication logic
- [X] Install and configure laravel-doctrine-jsonapi ([sowl/laravel-doctrine-jsonapi](https://github.com/ScholarshipOwl/laravel-doctrine-jsonapi))
  - [X] Publish and customize `jsonapi.php` config
  - [X] Register resources in `config/jsonapi.php`
- [X] Create sample Doctrine entity implementing `ResourceInterface`
- [X] Create a transformer extending `AbstractTransformer`
- [X] Set up API routes (remove `web.php`, use `api.php` only)
- [X] Add example resource controller (using default or custom controller)
- [X] Add policy-based authorization for resources
- [X] Add validation for JSON:API requests
- [X] Implement authentication (e.g., Laravel Passport, Sanctum, or JWT)
- [X] Integrate [Scribe](https://scribe.knuckles.wtf/) for automatic generation of OpenAPI specs and API docs
  - [X] Configure Scribe for API-only docs
  - [X] Ensure docs include OpenAPI spec, Postman collection, and sample responses
  - [X] Add custom docs for example resource
- [X] Configure proper exception handling with showing JSON:API errors.
- [ ] Use GUID for the primary key of the entities.
- [ ] Implement extensive testing of this skeleton
  - [ ] Make sure all features work as expected
  - [ ] Add tests for queue-failed jobs
  - [ ] Add tests for cache
  - [ ] Add tests for authentication
  - [ ] Add tests for authorization
  - [ ] Add tests for validation
  - [ ] Add tests for API endpoints
  - [ ] Add tests for API docs
- [X] Implement RBAC (Role-Based Access Control) authorization logic
  - [ ] Define roles and permissions (e.g., Admin, User, Guest)
  - [ ] Assign roles to users and restrict resource actions accordingly
  - [ ] Add policies and middleware for RBAC enforcement
  - [ ] Document RBAC usage and configuration
- [ ] Add detailed onboarding and contribution guide for new developers
  - [ ] Document setup, coding standards, and contribution workflow
  - [ ] Provide example PR and review process
  - [ ] Add FAQ and troubleshooting section
- [ ] Build and document default `.windsurfrules` for this repository
  - [ ] Describe commit, branch, and review policies
  - [ ] Add rules for sensitive files, migrations, and API docs
  - [ ] Ensure `.windsurfrules` is kept up to date with project conventions
- [ ] Add API rate limiting and throttling
  - [ ] Configure Laravel's built-in rate limiting for API endpoints
  - [ ] Add custom logic for user/role-based limits if needed
- [X] Set up CI/CD for automated testing and code quality checks
  - [X] Set up GitHub Actions for CI/CD
  - [X] Set up GitHub Actions for code quality checks

---

## References
- [Laravel Doctrine ORM Documentation](https://laravel-doctrine-orm-official.readthedocs.io/en/latest/)
- [Doctrine ORM Documentation](https://www.doctrine-project.org/projects/orm.html)
- [Doctrine Migrations Documentation](https://www.doctrine-project.org/projects/migrations.html)
- [Doctrine Extensions Documentation](https://laravel-doctrine-extensions.readthedocs.io/)
- [laravel-doctrine-jsonapi Documentation](https://github.com/ScholarshipOwl/laravel-doctrine-jsonapi/blob/main/docs/README.md)
- [Scribe Documentation](https://scribe.knuckles.wtf/laravel)
- [Scribe GitHub](https://github.com/knuckleswtf/scribe)
- [JSON:API Specification](https://jsonapi.org/)

---

## License
This project is open-source and available under the [MIT license](LICENSE).
