---
description: Display agent and project status. Progress tracking and status board.
---

# /status - Show Status

$ARGUMENTS

---

## Task

Gather and show current project and agent status manually.

### Procedure

1. **Scan Project Structure**: Identify main components (Models, Livewire, etc.)
2. **Review `task.md`**: Check current progress and pending items.
3. **Check Environment**: Verify if `artisan serve` or `npm run dev` is likely running.
4. **Report**: Format findings using the template below.

---

## Example Output (AnimeModu Laravel)

```
=== Project Status ===

📁 Project: animemodu-v2
📂 Path: /Users/shurikai/Desktop/projeler/animemoduv2
🏷️ Type: laravel-tall-stack
📊 Status: active (Migration phase)

🔧 Tech Stack:
   Framework: Laravel 12
   Frontend: Livewire 3/4 + Alpine.js
   Styling: Tailwind CSS v4
   Database: PostgreSQL

✅ Completed Features (3):
   • project-foundation
   • modern-agent-setup
   • security-auditor-rules

⏳ Pending (2):
   • anime-catalog-migration
   • video-player-component

📄 Files: 12 agents/skills created, 5 files refactored

=== Agent Status ===

✅ project-planner → Done
🔄 backend-specialist → Setting up Models (20%)
⏳ frontend-specialist → Waiting

=== Preview ===

🌐 Local: http://localhost:8000
💚 Health: OK (Manual check suggested)
```

---

## Technical

This workflow is **Manual-Gathering** based. No external scripts required. The agent must use `task_boundary` and `task.md` as primary data sources.