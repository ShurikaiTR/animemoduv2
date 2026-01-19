# Eloquent ORM Patterns

> Master Laravel's Eloquent for clean and efficient data access.

## Essential Patterns

| Pattern | Benefit | Example |
|---------|---------|---------|
| **Query Scopes** | Reusable query logic | `$query->published()` |
| **Accessors** | Formatted output | `getFullNameAttribute` |
| **Mutators** | Data transformation | `setPasswordAttribute` |
| **Relationships** | Clean data linking | `$user->posts()` |
| **API Resources** | Clean JSON output | `return new UserResource($user)` |

## Eloquent Rules

1. **Mass Assignment**: Always define `$fillable` or `$guarded`.
2. **Skinny Models**: Business logic goes to **Actions** or **Services**, not Models.
3. **N+1 Prevention**: Always use `with()` for relations in loops.
4. **Soft Deletes**: Use `use SoftDeletes` trait for critical data.
5. **Casting**: Use `$casts` for JSON, Boolean, and Dates.

## Example: Clean Model

```php
class Anime extends Model
{
    protected $fillable = ['title', 'status'];
    protected $casts = ['is_published' => 'boolean'];

    // Scope for reuse
    public function scopePublished($query) {
        return $query->where('status', 'published');
    }

    // Accessor for UI
    public function getShortTitleAttribute() {
        return Str::limit($this->title, 20);
    }
}
```