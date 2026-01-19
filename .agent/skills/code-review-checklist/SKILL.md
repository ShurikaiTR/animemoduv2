---
name: code-review-checklist
description: Code review guidelines covering code quality, security, and best practices.
allowed-tools: Read, Glob, Grep
---

# Code Review Checklist

## Quick Review Checklist

### Correctness
- [ ] Code does what it's supposed to do
- [ ] Edge cases handled
- [ ] Error handling in place
- [ ] No obvious bugs

### Security
- [ ] Input validated and sanitized
- [ ] No SQL/NoSQL injection vulnerabilities
- [ ] No XSS or CSRF vulnerabilities
- [ ] No hardcoded secrets or sensitive credentials
- [ ] **AI-Specific:** Protection against Prompt Injection (if applicable)
- [ ] **AI-Specific:** Outputs are sanitized before being used in critical sinks

### Performance
- [ ] No N+1 queries
- [ ] No unnecessary loops
- [ ] Appropriate caching
- [ ] Bundle size impact considered

### Code Quality
- [ ] Clear naming
- [ ] DRY - no duplicate code
- [ ] SOLID principles followed
- [ ] Appropriate abstraction level

### Testing
- [ ] Unit tests for new code
- [ ] Edge cases tested
- [ ] Tests readable and maintainable

### Documentation
- [ ] Complex logic commented
- [ ] Public APIs documented
- [ ] README updated if needed

## AI & LLM Review Patterns (2025)

### Logic & Hallucinations
- [ ] **Chain of Thought:** Does the logic follow a verifiable path?
- [ ] **Edge Cases:** Did the AI account for empty states, timeouts, and partial failures?
- [ ] **External State:** Is the code making safe assumptions about file systems or networks?

### Prompt Engineering Review
```markdown
// ❌ Vague prompt in code
const response = await ai.generate(userInput);

// ✅ Structured & Safe prompt
const response = await ai.generate({
  system: "You are a specialized parser...",
  input: sanitize(userInput),
  schema: ResponseSchema
});
```

## Anti-Patterns to Flag

```php
// ❌ Magic numbers/strings
if ($status === 3) { ... }

// ✅ PHP 8.1+ Enums
if ($status === ActionStatus::ACTIVE) { ... }

// ❌ Deep nesting
if ($a) { if ($b) { if ($c) { ... } } }

// ✅ Early returns / Guard clauses
if (!$a) return;
if (!$b) return;
// do work

// ❌ Missing type hinting
public function save($data) { ... }

// ✅ Strict typing (PHP 8.2+)
public function save(UserDTO $data): bool { ... }

// ❌ Logic in Blade (PHP blocks)
@php $count = $user->posts()->count(); @php

// ✅ Logic in Component/Action
public int $postCount; // computed in mount() or via accessor
```

## Review Comments Guide

```
// Blocking issues use 🔴
🔴 BLOCKING: Missing CSRF protection or SQL injection risk

// Important suggestions use 🟡
🟡 SUGGESTION: Consider Move this logic to an Action class

// Minor nits use 🟢
🟢 NIT: PascalCase for this class name please

// Questions use ❓
❓ QUESTION: Why are we using whereRaw() instead of where()?
```

## Laravel Specific Gate (MANDATORY)

- [ ] **N+1 Check:** Are there any loops with DB queries inside?
- [ ] **Mass Assignment:** Is `$fillable` or `$guarded` set correctly in the Model?
- [ ] **XSS Check:** Is `{!! !!}` used safely and sanitized?
- [ ] **Validation:** Is `Request->validate()` or Livewire `#[Validate]` used?
- [ ] **Naming:** Does it follow project rules (Models: PascalCase Singular)?