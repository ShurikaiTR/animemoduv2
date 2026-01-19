# Schema Design in Laravel

> Designing robust database structures using Laravel migrations.

## Laravel Migration Types

| Type | Laravel Method | Best For |
|------|----------------|----------|
| **Primary Key** | `$table->id()` | BigInt unsigned AI |
| **UUID** | `$table->uuid('id')->primary()` | Distributed systems |
| **Foreign Key** | `$table->foreignId('user_id')` | Relations |
| **Timestamps** | `$table->timestamps()` | created_at, updated_at |
| **Soft Delete** | `$table->softDeletes()` | deleted_at |

## Primary Key Selection

| Type | Laravel Syntax | Use When |
|------|----------------|----------|
| **BigInt** | `$table->id()` | Default, simple, fast |
| **UUID** | `$table->uuid()` | Public IDs, security |
| **ULID** | `$table->ulid()` | Sortable public IDs |

## Timestamp Strategy

- Always use `$table->timestamps()` for audit trails.
- Use `$table->timestamp('published_at')->nullable()` for custom dates.
- In Laravel, timestamps are usually `TIMESTAMP` (without TZ) unless configured otherwise.

## Foreign Key ON DELETE

```php
$table->foreignId('user_id')
      ->constrained()
      ->cascadeOnDelete(); // or ->nullOnDelete()
```

## Relational Integrity

1. Always use `constrained()` to enforce database-level integrity.
2. Index every Foreign Key (Laravel does this automatically with `foreignId`).
3. Use `unique()` only when absolutely necessary (e.g., slugs).