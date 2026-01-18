---
name: livewire-4-standards
description: Livewire 4 resmi dokümantasyonuna ve projeye özel kurallara dayalı kapsamlı geliştirme rehberi.
---

# Livewire 4 Standartları ve En İyi Uygulamalar

Bu yetenek, projedeki tüm Livewire bileşenlerinin **Livewire 4 resmi standartlarına** ve proje kurallarına %100 uyumlu olmasını sağlar.

## 📚 Resmi Referanslar

- [Components](https://livewire.laravel.com/docs/components): Bileşen oluşturma ve yapısı
- [Properties](https://livewire.laravel.com/docs/properties): Property yönetimi ve binding
- [Actions](https://livewire.laravel.com/docs/actions): Kullanıcı etkileşimleri
- [Lifecycle Hooks](https://livewire.laravel.com/docs/lifecycle-hooks): mount, boot, update, render
- [Events](https://livewire.laravel.com/docs/events): Bileşenler arası iletişim
- [Forms](https://livewire.laravel.com/docs/forms): Form nesneleri
- [Validation](https://livewire.laravel.com/docs/validation): Doğrulama kuralları

---

## 🆕 Livewire 4 Temel Değişiklikler

### 1. Tek Dosya Bileşenler (Single-File Components)
Livewire 4'ün varsayılan formatı tek dosya bileşenleridir. PHP ve Blade aynı dosyada bulunur.

**Konum:** `resources/views/components/⚡component-name.blade.php`

```php
<?php
// resources/views/components/⚡counter.blade.php

declare(strict_types=1);

use Livewire\Component;

new class extends Component
{
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }
};
?>

<div>
    <h1>Count: {{ $count }}</h1>
    <button wire:click="increment">+</button>
</div>
```

### 2. Artisan Komutu
```bash
php artisan make:livewire post.create
# Oluşturur: resources/views/components/post/⚡create.blade.php
```

### 3. Class-Based Bileşenler (Opsiyonel)
Karmaşık bileşenler için ayrı PHP dosyası kullanılabilir:

```bash
php artisan make:livewire PostCreate --class
# Oluşturur: app/Livewire/PostCreate.php + view
```

---

## 🔧 Core Attributes (PHP 8 Attributes)

### #[Validate] - Doğrulama
```php
use Livewire\Attributes\Validate;

new class extends Component
{
    #[Validate('required|min:3|max:100')]
    public string $title = '';

    #[Validate('required|min:10', message: 'İçerik en az 10 karakter olmalı.')]
    public string $content = '';

    public function save(): void
    {
        $this->validate(); // Tüm kuralları çalıştır
        // kaydet...
    }
};
```

### #[Computed] - Hesaplanmış Property
Memoization ile veritabanı sorgularını optimize eder:

```php
use Livewire\Attributes\Computed;

new class extends Component
{
    public string $search = '';

    #[Computed]
    public function posts()
    {
        return Post::where('title', 'like', "%{$this->search}%")->get();
    }
};
```

**Template'de kullanım:** `$this->posts` (dikkat: `$this` zorunlu)

```blade
@foreach ($this->posts as $post)
    <li>{{ $post->title }}</li>
@endforeach
```

### #[On] - Event Dinleyici
```php
use Livewire\Attributes\On;

new class extends Component
{
    #[On('post-created')]
    public function handlePostCreated(string $title): void
    {
        // Event geldiğinde çalışır
    }
};
```

### #[Url] - URL Query String
Property'yi URL query string ile senkronize eder:

```php
use Livewire\Attributes\Url;

new class extends Component
{
    #[Url]
    public string $search = '';

    #[Url(as: 'sayfa', history: true)]
    public int $page = 1;
};
```
URL: `?search=test&sayfa=2`

### #[Reactive] - Reaktif Props
Alt bileşenlerde parent'tan gelen prop'ları reaktif yapar:

```php
use Livewire\Attributes\Reactive;

new class extends Component
{
    #[Reactive]
    public $todos; // Parent değiştiğinde otomatik güncellenir
};
```

### #[Lazy] ve #[Defer]
Bileşen yüklemesini geciktirir:

```php
use Livewire\Attributes\Lazy;

#[Lazy]
new class extends Component
{
    // Viewport'a girince yüklenir
};
```

```blade
<livewire:revenue lazy />  {{-- Scroll ile yüklenir --}}
<livewire:stats defer />   {{-- Sayfa yüklendikten sonra hemen --}}
```

---

## 🔄 Lifecycle Hooks

| Hook | Ne Zaman Çalışır |
|------|------------------|
| `mount()` | İlk render öncesi, 1 kez |
| `boot()` | Her request başında |
| `updating($property, $value)` | Property güncellenmeden önce |
| `updated($property, $value)` | Property güncellendikten sonra |
| `hydrate()` | Her request'te deserialize sonrası |
| `dehydrate()` | Her request'te serialize öncesi |
| `render()` | Her render öncesi |
| `exception($e, $stopPropagation)` | Hata oluştuğunda |

### mount() Kullanımı
```php
public function mount(Post $post): void
{
    // Route model binding otomatik çalışır
    $this->fill($post->only(['title', 'content']));
}
```

---

## 📝 wire:model Modifiers

| Modifier | Davranış |
|----------|----------|
| `wire:model` | Form submit'te günceller (varsayılan) |
| `wire:model.live` | Her tuş vuruşunda günceller (150ms debounce) |
| `wire:model.blur` | Input focus kaybedince günceller |
| `wire:model.change` | Select/checkbox değişince günceller |
| `wire:model.live.debounce.500ms` | Özel debounce süresi |

```blade
{{-- Canlı arama için --}}
<input type="text" wire:model.live.debounce.300ms="search">

{{-- Form alanları için (varsayılan) --}}
<input type="text" wire:model="title">

{{-- Gerçek zamanlı validasyon için --}}
<input type="email" wire:model.blur="email">
```

---

## 📨 Events (Olay Sistemi)

### Event Dispatch Etme
```php
// Tüm bileşenlere
$this->dispatch('post-created', title: $post->title);

// Belirli bileşene
$this->dispatch('refresh')->to(Dashboard::class);

// Kendine
$this->dispatch('saved')->self();
```

### Alpine ile Event
```blade
<button @click="$dispatch('open-modal', { id: 123 })">Aç</button>
```

---

## 📋 Form Objects

Büyük formları ayrı sınıfa taşıma:

```php
// app/Livewire/Forms/PostForm.php
<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PostForm extends Form
{
    #[Validate('required|min:5')]
    public string $title = '';

    #[Validate('required|min:10')]
    public string $content = '';

    public function store(): void
    {
        $this->validate();
        Post::create($this->only(['title', 'content']));
    }
}
```

**Kullanım:**
```php
use App\Livewire\Forms\PostForm;

new class extends Component
{
    public PostForm $form;

    public function save(): void
    {
        $this->form->store();
        $this->redirect('/posts');
    }
};
```

```blade
<input wire:model="form.title">
@error('form.title') <span>{{ $message }}</span> @enderror
```

---

## ⚡ Lazy Loading ve Placeholder

### @placeholder Direktifi
```php
#[Lazy]
new class extends Component
{
    // yavaş yüklenen içerik
};
?>

@placeholder
<div class="animate-pulse bg-gray-200 h-32 rounded"></div>
@endplaceholder

<div>
    <!-- Gerçek içerik -->
</div>
```

### placeholder() Metodu
```php
#[Lazy]
new class extends Component
{
    public function placeholder(): string
    {
        return <<<'HTML'
        <div class="animate-pulse">Yükleniyor...</div>
        HTML;
    }
};
```

---

## 🏗️ Nesting ve İletişim

### Parent-Child İletişim
```blade
{{-- Parent --}}
<livewire:todo-item :$todo :wire:key="$todo->id" />

{{-- Child event'ini dinleme --}}
<livewire:create-post @saved="$refresh" />
```

### $parent ile Erişim
```blade
<button wire:click="$parent.removeItem({{ $item->id }})">Sil</button>
```

---

## 🛡️ Güvenlik Kuralları

### 1. Action Parametrelerini Doğrula
```php
public function delete(Post $post): void
{
    // ✅ Her zaman yetkilendirme yap
    $this->authorize('delete', $post);
    $post->delete();
}
```

### 2. Tehlikeli Metotları Gizle
```php
// ❌ Public metotlar Blade'den çağrılabilir
public function deleteAll() { ... }

// ✅ Protected veya private yap
protected function deleteAll() { ... }
```

### 3. Property Güvenliği
```php
// ❌ Hassas veri property olarak kullanma
public string $role = 'admin';

// ✅ Computed property kullan
#[Computed]
public function role(): string
{
    return auth()->user()->role;
}
```

---

## 📁 AnimeModu v2 Proje Kuralları

### 1. Dosya Konumu
```
app/Livewire/
├── Anime/
│   ├── Show.php           # Full-page component
│   └── Concerns/
│       └── InteractsWithComments.php
├── Auth/
│   └── AuthModal.php
└── Forms/
    └── CommentForm.php
```

### 2. Satır Limitleri
- **Livewire Component:** Maksimum 150 satır
- **Blade View:** Maksimum 200 satır
- Büyük bileşenler → Concerns trait'lerine böl

### 3. Strict Types Zorunlu
```php
<?php

declare(strict_types=1);

namespace App\Livewire\Anime;
```

### 4. Return Type Zorunlu
```php
public function save(): void { ... }
public function getTitle(): string { ... }
```

---

## ✅ Livewire Kontrol Listesi

Yeni bileşen oluştururken:

- [ ] `declare(strict_types=1)` eklendi
- [ ] Return type'lar tanımlı
- [ ] `#[Validate]` kuralları property üzerinde
- [ ] `#[Computed]` ile N+1 sorgu önlendi
- [ ] Action'larda `$this->authorize()` kontrolü yapıldı
- [ ] Loop'larda `:wire:key` kullanıldı
- [ ] `wire:model` modifier doğru seçildi (.live / .blur / varsayılan)
- [ ] Bileşen 150 satırı geçmiyor

---

## 🚀 Çalışma Akışı

1. **Planlama:** Bileşenin sorumluluğunu belirle (tek iş prensibi)
2. **Oluşturma:** `php artisan make:livewire`
3. **Validation:** `#[Validate]` attribute ile kuralları tanımla
4. **Computed:** Veritabanı sorgularını `#[Computed]` ile optimize et
5. **Test:** `Livewire::test()` ile unit test yaz

---

*Bu skill, Livewire 4 resmi dokümantasyonuna ve proje standartlarına dayanmaktadır.*
