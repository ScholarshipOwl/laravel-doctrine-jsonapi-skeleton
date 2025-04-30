# 🎉 Release Notes: v0.0.1 – Laravel Doctrine JSON:API Skeleton

**Release Date:** 2025-05-02

We are excited to announce the first release of the **Laravel Doctrine JSON:API Skeleton**! This package provides a robust starting point for building modern, API-only Laravel applications using Doctrine ORM and fully compliant JSON:API endpoints.

---

## 🚀 Highlights

- **Doctrine ORM**: Full replacement for Eloquent, leveraging Doctrine’s advanced data-mapping and entity features.
- **Doctrine Migrations**: Manage your database schema using Doctrine’s migration system.
- **Doctrine Extensions**: Out-of-the-box support for features like timestamps, sluggable, and soft deletes.
- **JSON:API Compliance**: All endpoints conform to the JSON:API specification for consistency and scalability.
- **API-Only**: No web routes or Blade views—just a clean, backend-focused API skeleton.
- **Automatic API Docs**: Generate OpenAPI specs, Postman collections, and beautiful docs with Scribe.

---

## 🛠️ Getting Started

1. **Create a new project**
   ```bash
   composer create-project sowl/laravel-doctrine-jsonapi-skeleton my-api
   cd my-api
   ```

2. **Configure Doctrine ORM**
   - Doctrine replaces Eloquent; all data models are Doctrine entities.
   - Configuration is managed in `config/doctrine.php`.
   - Use attributes for entity mapping.

3. **Set Up Doctrine Migrations**
   - All schema changes are managed via Doctrine migrations (`config/migrations.php`).
   - Use `php artisan doctrine:migrations:migrate` to run migrations.

4. **Enable Doctrine Extensions**
   - Activate extensions for advanced entity features in `config/doctrine.php`.

5. **API-Only Structure**
   - No web routes or Blade views are included.
   - All controllers and actions are organized for API-first development.

6. **Automatic Documentation**
   - Generate and view API docs using Scribe.

---

## 📚 Documentation

- **Setup Guide:** See [docs/Setup.md](docs/Setup.md) for detailed installation and configuration steps.
- **API Documentation:** Automatically generated with Scribe—just run the included commands after setup.

---

## 🧪 Testing

- Extensive tests for authentication, entities, and API logic.
- Use:
  ```bash
  ./vendor/bin/phpunit --stop-on-failure --stop-on-error
  ```
  to run tests.

---

## 🔗 Main Packages

- [laravel/laravel](https://github.com/laravel/laravel)
- [laravel-doctrine/orm](https://github.com/laravel-doctrine/orm)
- [laravel-doctrine/migrations](https://github.com/laravel-doctrine/migrations)
- [laravel-doctrine/extensions](https://github.com/laravel-doctrine/extensions)
- [sowl/laravel-doctrine-jsonapi](https://github.com/ScholarshipOwl/laravel-doctrine-jsonapi)
- [scribe](https://github.com/knuckleswtf/scribe)

---

## 📝 Roadmap

- [x] Doctrine ORM integration
- [x] Doctrine Migrations
- [x] Doctrine Extensions
- [x] JSON:API-compliant endpoints
- [x] API-only structure
- [x] Automatic docs with Scribe
- [ ] Extensive test coverage (in progress)

---

## 💬 Feedback

We welcome issues and pull requests! Please see the repository guidelines for contributing and check out the docs for more info.

---

**Ready to build your next API? Get started with Laravel Doctrine JSON:API Skeleton today!**
