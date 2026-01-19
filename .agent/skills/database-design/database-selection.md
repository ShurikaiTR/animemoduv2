# Database Selection in Laravel (2025)

> Laravel supports multiple databases. Choose based on environment and scale.

## Decision Tree

```
Where is it running?
│
├── Local Development / CI / Tests
│   └── SQLite (In-memory or file-based)
│
├── Production (Small/Medium app)
│   └── PostgreSQL or MySQL
│
├── Production (High scale / Serverless)
│   └── Neon (Serverless PG) or Turso (via Laravel adapter)
│
└── Analytics / Log Heavy
    └── ClickHouse or separate MongoDB
```

## Comparison

| Database | Best For Laravel | Note |
|----------|----------|------------|
| **SQLite** | Testing, small apps | Zero config, great for local dev |
| **PostgreSQL** | Standard choice | Best for complex queries / pgvector |
| **MySQL** | Legacy / Shared hosting | Very common, reliable |
| **Neon** | Serverless Laravel | Great for auto-scaling deployments |

## Key Laravel Rules

1. Use **SQLite** for parallel testing (`:memory:`) to speed up CI.
2. Ensure `DB_CONNECTION` is correctly set in `.env`.
3. Use `TIMESTAMPTZ` if your app is global.
4. Don't mix database drivers in production if you use raw SQL.