# Query Optimization in Laravel

> Solving N+1 problems and optimizing Eloquent queries.

## N+1 Problem in Eloquent

```php
// ❌ WRONG: Executes 1 + N queries
$animes = Anime::all();
foreach ($animes as $anime) {
    echo $anime->category->name;
}

// ✅ CORRECT: Executes 2 queries (Eager Loading)
$animes = Anime::with('category')->get();
```

## Advanced Optimization

| Feature | Use Case | Implementation |
|---------|----------|----------------|
| **Lazy Eager Loading** | Load only if needed | `$model->load('relation')` |
| **Constraining** | Load specific columns | `with('user:id,name')` |
| **Subquery Selects** | Fetch count/avg efficiently | `withCount('comments')` |
| **Chunking** | Large datasets | `chunk(100, function()...)` |
| **Indexing** | Fast lookups | See `indexing.md` |

## Query Analysis in Laravel

1. Use **Laravel Debugbar** or **Clockwork** to monitor queries.
2. Log slow queries in `AppServiceProvider`:
```php
DB::listen(function ($query) {
    if ($query->time > 500) {
        Log::warning('Slow query: ' . $query->sql);
    }
});
```

## Optimization Priorities

1. **Eager Load** all relations used in loops.
2. **Select only needed columns** (`select('id', 'title')`).
3. **Use withCount()** instead of `count($model->relation)`.
4. **Cache** expensive results using `Cache::remember()`.