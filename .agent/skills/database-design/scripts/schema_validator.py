#!/usr/bin/env python3
"""
Laravel Migration Validator - Database schema validation for Laravel
Validates Laravel migration files and checks for common issues.

Usage:
    python schema_validator.py <project_path>

Checks:
    - Missing foreignId constrained()
    - Potential missing indexes
    - Correct timestamp usage
    - Naming conventions
"""

import sys
import json
import re
from pathlib import Path
from datetime import datetime

def find_migration_files(project_path: Path) -> list:
    """Find Laravel migration files."""
    migrations = list(project_path.glob('database/migrations/*.php'))
    return sorted(migrations)

def validate_laravel_migration(file_path: Path) -> list:
    """Validate Laravel migration file."""
    issues = []
    
    try:
        content = file_path.read_text(encoding='utf-8', errors='ignore')
        
        # Check for constrained() if foreignId is used
        foreign_ids = re.findall(r"foreignId\(['\"](.+?)['\"]\)", content)
        for fid in foreign_ids:
            # Check for constrained() call
            if f"foreignId('{fid}')->constrained" not in content and f'foreignId("{fid}")->constrained' not in content:
                issues.append(f"Foreign key '{fid}' should use ->constrained() for integrity")
        
        # Check for missing indexes on common columns (like slug, status, email)
        common_search_cols = ['slug', 'status', 'email', 'type', 'category']
        for col in common_search_cols:
            if f"->string('{col}')" in content or f'->string("{col}")' in content:
                if f"->index()" not in content and f"->unique()" not in content:
                   issues.append(f"Consider adding ->index() to frequently searched column '{col}'")
        
        # Check for timestamps()
        if "timestamps()" not in content and "Schema::create" in content:
            issues.append("Table missing $table->timestamps() (recommended)")

        # Check for raw string IDs instead of id()
        if "increments('id')" in content or "bigIncrements('id')" in content:
            issues.append("Legacy ID increments found. Use $table->id() instead.")

    except Exception as e:
        issues.append(f"Error reading migration: {str(e)[:50]}")
    
    return issues

def main():
    project_path = Path(sys.argv[1] if len(sys.argv) > 1 else ".").resolve()
    
    print(f"\n{'='*60}")
    print(f"[LARAVEL MIGRATION VALIDATOR]")
    print(f"{'='*60}")
    print(f"Project: {project_path}")
    print(f"Time: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    print("-"*60)
    
    # Find migration files
    migrations = find_migration_files(project_path)
    print(f"Found {len(migrations)} migration files")
    
    if not migrations:
        output = {
            "script": "schema_validator",
            "project": str(project_path),
            "migrations_checked": 0,
            "issues_found": 0,
            "passed": True,
            "message": "No migration files found"
        }
        print(json.dumps(output, indent=2))
        sys.exit(0)
    
    # Validate each migration
    all_issues = []
    
    for file_path in migrations:
        issues = validate_laravel_migration(file_path)
        
        if issues:
            all_issues.append({
                "file": str(file_path.name),
                "issues": issues
            })
    
    # Summary
    print("\n" + "="*60)
    print("MIGRATION ISSUES")
    print("="*60)
    
    if all_issues:
        for item in all_issues:
            print(f"\n{item['file']}:")
            for issue in item["issues"][:5]:
                print(f"  - {issue}")
    else:
        print("No migration issues found!")
    
    total_issues = sum(len(item["issues"]) for item in all_issues)
    passed = True # Still warnings
    
    output = {
        "script": "schema_validator",
        "project": str(project_path),
        "migrations_checked": len(migrations),
        "issues_found": total_issues,
        "passed": passed,
        "issues": all_issues
    }
    
    print("\n" + json.dumps(output, indent=2))
    
    sys.exit(0)

if __name__ == "__main__":
    main()