# Migrations in Laravel

> Best practices for managing database schema changes in Laravel.

## Core Commands

| Command | Action | When to Use |
|---------|--------|-------------|
| `php artisan make:migration ...` | Create new migration | Adding tables/columns |
| `php artisan migrate` | Run pending migrations | Deploying changes |
| `php artisan migrate:rollback` | Undo last migration | Fixing mistakes |
| `php artisan migrate:status` | Check migration status | Debugging |

## Safe Migration Strategy

1. **Never edit a migration that has already been pushed.** Create a new one instead.
2. **Nullable First**: When adding a column to a large table, make it `nullable()` first to avoid locking.
3. **Squashing**: If you have too many migrations, use `php artisan schema:dump`.
4. **Data Migrations**: Don't put heavy data migrations in the same file as schema changes. Use a separate script or `Seeds`.

## Example: Safe Rename

```php
public function up()
{
    // ❌ Instead of renaming (downtime risk)
    // $table->renameColumn('old', 'new');

    // ✅ Safe way (Add new -> Migrate data in code -> Drop old later)
    $table->string('new_name')->nullable();
}
```

## Production Rule

Always run `php artisan migrate --force` in your CI/CD pipeline to ensure migrations run automatically during deployment.