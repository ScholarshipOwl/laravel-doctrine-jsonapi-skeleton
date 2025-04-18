# Laravel Doctrine JSON:API Skeleton

This repository provides a template for building API-only Laravel applications using Doctrine ORM and the [laravel-doctrine-jsonapi](https://github.com/ScholarshipOwl/laravel-doctrine-jsonapi) package for fully compliant [JSON:API](https://jsonapi.org/) implementations.

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

## Main Packages
- [laravel-doctrine/orm](https://github.com/laravel-doctrine/orm): Doctrine ORM integration for Laravel ([docs](https://laravel-doctrine-orm-official.readthedocs.io/en/latest/))
- [laravel-doctrine/migrations](https://github.com/laravel-doctrine/migrations): Integration with Doctrine2's migrations package for Laravel
- [laravel-doctrine/extensions](https://github.com/laravel-doctrine/extensions): Doctrine extensions for Laravel (Gedmo, Beberlei, etc.)
- [sowl/laravel-doctrine-jsonapi](https://github.com/ScholarshipOwl/laravel-doctrine-jsonapi): JSON:API implementation for Laravel + Doctrine

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
- [ ] Set up basic `User` entity
  - [ ] Set up entity folder structure
  - [ ] Setup authentication and all the relevant user traits
  - [ ] Add migration of the `User` entity
- [ ] Install and configure laravel-doctrine-jsonapi ([sowl/laravel-doctrine-jsonapi](https://github.com/ScholarshipOwl/laravel-doctrine-jsonapi))
  - [ ] Publish and customize `jsonapi.php` config
  - [ ] Register resources in `config/jsonapi.php`
- [ ] Create sample Doctrine entity implementing `ResourceInterface`
- [ ] Create a transformer extending `AbstractTransformer`
- [ ] Set up API routes (remove `web.php`, use `api.php` only)
- [ ] Add example resource controller (using default or custom controller)
- [ ] Implement authentication (e.g., Laravel Passport, Sanctum, or JWT)
- [ ] Add policy-based authorization for resources
- [ ] Add validation for JSON:API requests
- [ ] Add automated tests for API endpoints
- [ ] Add documentation/examples for consumers
- [ ] Integrate [Scribe](https://scribe.knuckles.wtf/) for automatic generation of OpenAPI specs and API docs
  - [ ] Configure Scribe for API-only docs
  - [ ] Ensure docs include OpenAPI spec, Postman collection, and sample responses

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
