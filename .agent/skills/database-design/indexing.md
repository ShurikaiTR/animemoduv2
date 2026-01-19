# Indexing in Laravel

> Performance tuning using Laravel migrations.

## Indexing Methods

| Method | Laravel Syntax | Use For |
|------|---------|------|
| **Index** | `$table->index('column')` | General purpose |
| **Unique** | `$table->unique('column')` | Constraints |
| **Fulltext** | `$table->fullText('column')` | Text search |
| **Composite** | `$table->index(['a', 'b'])` | Multiple columns |

## When to Create Indexes

```
Index these:
├── Foreign key columns (Laravel's foreignId does this)
├── Columns in where() clauses
├── Columns in orderBy()
└── Columns used in join()
```

## Composite Index Order

In Laravel migrations, the order in the array matters:
```php
$table->index(['status', 'created_at']);
```
This is efficient for:
1. `where('status', '...')->get()`
2. `where('status', '...')->orderBy('created_at')->get()`

## Performance Tip

Before adding an index, run:
```bash
php artisan db:show --counts
```
This gives an overview of table sizes and helps prioritize indexing.