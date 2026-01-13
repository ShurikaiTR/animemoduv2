# AnimeModu v2 - Proje Kuralları ve Kodlama Standartları

> Bu dosya, projede yapılacak tüm geliştirmeler için referans niteliğindedir.
> Yapay zeka asistanları ve geliştiriciler bu kurallara uymalıdır.

---

## 🛠 Teknoloji Stack

| Kategori | Teknoloji | Versiyon |
|----------|-----------|----------|
| Framework | Laravel | 12.x |
| Runtime | PHP | 8.2+ (Strict Mode) |
| Frontend | Livewire | 3.x |
| Admin Panel | FilamentPHP | 3.x |
| Styling | Tailwind CSS | 4.x |
| Database | PostgreSQL / SQLite (Dev) | Latest |
| Icons | Lucide / Heroicons | Latest |
| Formatting | Laravel Pint | Latest |
| Testing | PHPUnit | 11.x |
| Notifications | Sonner (via Livewire) | Latest |

---

## 📁 Klasör Yapısı

```
app/
├── Actions/               # Single-purpose action classes
├── Http/                  # Minimal controller usage
│   └── Controllers/
├── Livewire/              # Full-page Livewire components
│   ├── Auth/              # Authentication components
│   ├── Layout/            # Layout components
│   └── Pages/             # Page components
├── Models/                # Skinny Models (only relations, scopes, accessors)
├── Providers/             # Service providers
├── Services/              # Multi-method service classes
└── Enums/                 # PHP 8.1+ Enums for statuses

resources/
├── css/                   # Tailwind CSS (app.css)
├── js/                    # Alpine.js / JavaScript
└── views/
    ├── components/        # Reusable Blade components
    │   ├── icons/         # Icon components
    │   ├── layout/        # Navbar, Footer, Sidebar
    │   └── ui/            # Button, Input, Modal
    └── livewire/          # Livewire Blade templates

database/
├── migrations/            # All schema changes via migrations
├── factories/             # Model factories
└── seeders/               # Database seeders
```

---

## 🔐 Livewire Component Pattern

### Full-Page Component

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layout.app')]
#[Title('Sayfa Başlığı')]
class MyPage extends Component
{
    public function render(): \Illuminate\View\View
    {
        return view('livewire.pages.my-page');
    }
}
```

### Validation Pattern

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Validate;

class LoginForm extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:8')]
    public string $password = '';

    public function login(): void
    {
        $this->validate();
        // Login logic...
    }
}
```

### Computed Properties

```php
use Livewire\Attributes\Computed;

#[Computed]
public function episodeCount(): int
{
    return $this->anime->episodes()->count();
}
```

---

## 🧩 Component Pattern'ları

### Action Classes (Single Purpose)

```php
<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Anime;

final class CreateAnimeAction
{
    public function execute(array $data): Anime
    {
        return Anime::create($data);
    }
}
```

### Service Classes (Multi-method)

```php
<?php

declare(strict_types=1);

namespace App\Services;

final class TmdbService
{
    public function searchAnime(string $query): array
    {
        // TMDB API call...
    }

    public function getAnimeDetails(int $id): array
    {
        // TMDB API call...
    }
}
```

### Blade Components

```php
{{-- resources/views/components/ui/button.blade.php --}}
@props([
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $classes = match($variant) {
        'primary' => 'bg-primary hover:bg-primary-hover text-white',
        'secondary' => 'bg-bg-secondary hover:bg-bg-dropdown text-text-main',
        'danger' => 'bg-danger hover:bg-red-600 text-white',
    };
@endphp

<button {{ $attributes->merge(['class' => "rounded-lg font-medium transition-colors $classes"]) }}>
    {{ $slot }}
</button>
```

---

## 📝 Kodlama Standartları

### ✅ YAPILMASI GEREKENLER

1. **Strict Types** - Tüm PHP dosyaları `declare(strict_types=1);` ile başlamalı
2. **Return Types** - Tüm fonksiyonlarda parametre ve dönüş tipleri tanımlı olmalı
3. **Enums** - Durumlar için PHP 8.1+ Enums (magic string yasak)
4. **Laravel Pint** - Commit öncesi `./vendor/bin/pint` çalıştır
5. **Skinny Models** - Model'de sadece relations, scopes, accessors
6. **Action/Service** - İş mantığı Action veya Service sınıflarında
7. **Blade Components** - Tekrar eden UI'lar component'a çıkarılmalı
8. **Loading States** - `wire:loading` ile kullanıcı geri bildirimi

### ❌ YAPILMAMASI GEREKENLER

1. **`mixed` type kullanma** - Her zaman proper type tanımla
2. **Console.log/dump bırakma** - `Log::error()` kullan veya kaldır
3. **Hardcoded string** - Constants veya Enums kullan
4. **Duplicate code** - DRY prensibi, helper/component oluştur
5. **PHP 150+ satır** - Modüler parçalara böl
6. **Blade 200+ satır** - Sub-component'lere böl
7. **Inline styles** - Tailwind utility class'ları kullan
8. **Fat Controllers** - Livewire Full-Page tercih et

---

## 🎨 Styling Kuralları

### Tailwind CSS Conventions

```php
// Doğru: Utility-first, okunabilir sıralama
class="flex items-center justify-between gap-4 p-4 bg-bg-secondary rounded-xl"

// Yanlış: Karmaşık, sırasız
class="rounded-xl bg-bg-secondary justify-between p-4 flex gap-4 items-center"
```

### Tema Renkleri (CSS Variables)

```css
--color-bg-main: #131720;
--color-bg-secondary: #151f30;
--color-bg-dropdown: #1a1f2e;
--color-primary: #2f80ed;
--color-primary-hover: #4a9af5;
--color-danger: #ef4444;
--color-text-main: #e0e0e0;
--color-text-heading: #ffffff;
```

### Glassmorphism Pattern

```php
class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl"
```

---

## 🗄 Veritabanı Kuralları

1. **Migrations** - Elle tablo açmak yasak, her değişiklik migration ile
2. **Foreign Keys** - `constrained()->cascadeOnDelete()` kullan
3. **Indexing** - Sık sorgulanan kolonları indexle (`slug`, `status`, `created_at`)
4. **Soft Deletes** - Önemli tablolarda `SoftDeletes` trait kullan

---

## 🔒 Güvenlik Kuralları

### Authorization

```php
// Policy kullanımı
$this->authorize('update', $anime);

// Gate kullanımı
Gate::authorize('admin');
```

### Rate Limiting

```php
use Livewire\Attributes\Renderless;

#[Renderless]
public function sensitiveAction(): void
{
    // Rate limit check...
}
```

---

## 📦 Import Sıralaması

```php
// 1. PHP/Framework imports
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

// 2. Third-party imports
use Livewire\Component;
use Livewire\Attributes\Layout;

// 3. App imports (Models, Actions, Services)
use App\Models\Anime;
use App\Actions\CreateAnimeAction;
use App\Services\TmdbService;
```

---

## 🧪 Test Yapısı

### Test Dosya Pattern

```php
// tests/Feature/Livewire/Auth/LoginFormTest.php
<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Auth;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Auth\LoginForm;

class LoginFormTest extends TestCase
{
    public function test_can_render_login_form(): void
    {
        Livewire::test(LoginForm::class)
            ->assertStatus(200);
    }

    public function test_email_is_required(): void
    {
        Livewire::test(LoginForm::class)
            ->set('password', 'password123')
            ->call('login')
            ->assertHasErrors(['email' => 'required']);
    }
}
```

---

## 🔄 Git Commit Kuralları

```
feat: Yeni özellik ekle
fix: Bug düzelt
refactor: Kod yeniden yapılandır
style: Formatting, styling değişiklikleri
docs: Dokümantasyon güncelle
test: Test ekle/güncelle
chore: Build, config değişiklikleri
```

---

## 📚 Referanslar

- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [FilamentPHP Documentation](https://filamentphp.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

---

*Son Güncelleme: Ocak 2026*
