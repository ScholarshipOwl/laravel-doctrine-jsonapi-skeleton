# Testing

## Database preparation


### Migrations

Run the following command to run migrations:

```shell
sail artisan doctrine:migrations:migrate --env=testing
```

### Seeding

Run the following command to seed the database:

```shell
sail artisan db:seed --env=testing
```
