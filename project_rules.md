# AnimeModu v2 - Proje Kuralları ve Standartları

> Bu belge, **Laravel 12.x**, **Filament v5** ve **Livewire v4** ile geliştirilen bu projenin "Anayasası" niteliğindedir.
> Tüm geliştirmeler bu standartlara **istisnasız** uymalıdır.

---

## 🛠 Teknoloji Yığını (Tech Stack)

| Bileşen | Teknoloji | Versiyon |
|---------|-----------|----------|
| **Framework** | Laravel | 12.x |
| **Dil** | PHP | 8.2+ (Strict Mode) |
| **Admin Panel** | FilamentPHP | 5.x |
| **Frontend** | Livewire | 4.x |
| **CSS Motoru** | Tailwind CSS | 4.x |
| **Veritabanı** | PostgreSQL / SQLite (Dev) | - |
| **Video İşleme** | FFmpeg + Laravel Horizon | - |
| **API** | Laravel Sanctum | (Opsiyonel) |

---

## 📁 Klasör ve Mimari Yapısı

Proje, **Domain Driven Design (DDD)** prensiplerinden esinlenen ancak Laravel'in doğal yapısını bozmayan modüler bir yaklaşımı benimser.

### 1. Model Yapısı
Model dosyaları `app/Models` altında yer alır ancak **asla** şişirilmez (Skinny Models).
*   ❌ **Yanlış:** Tüm iş mantığını Model içine yazmak.
*   ✅ **Doğru:** Scope'lar, Relationship'ler ve Accessor'lar dışında kod barındırmamak.

### 2. Controller & Livewire Components
*   **Controller:** Mümkün olduğunca az kullanılmalı. Full-Page Livewire Component'leri tercih edilmeli.
*   **Livewire:** `app/Livewire` altında sayfa bazlı klasörleme yapılmalı (örn: `App/Livewire/Anime/Show.php`).

### 3. Business Logic (İş Mantığı)
Karmaşık iş mantığı `Actions` veya `Services` sınıflarına taşınmalıdır.
*   **Action Sınıfları:** Tek bir işi yapan sınıflardır. `app/Actions` altında tutulur.
    *   Örn: `CreateAnimeAction`, `EncodeVideoAction`.
*   **Service Sınıfları:** Ortak bir amaca hizmet eden birden fazla metodun toplandığı sınıflardır.
    *   Örn: `TmdbService`, `CloudflareStreamService`, `AnimeSettingsService`.

### 📉 Kod Satır Sınırı (File Line Limits)
Kodun okunabilir kalması için aşağıdaki satır sınırlarına **kesinlikle** uyulmalıdır:
*   **PHP Dosyaları (Actions, Services, Models, Livewire):** Maksimum **150 satır**.
*   **Blade / Component Dosyaları:** Maksimum **200 satır**.
*   Bu limitlere yaklaşıldığında kod parçalara bölünmeli veya alt bileşenlere (sub-components) ayrılmalıdır.

---

## 📝 İsimlendirme Standartları (Naming Conventions)

| Yapı | Kural | Örnek |
|------|-------|-------|
| **Controller** | PascalCase + Suffix | `AnimeController` |
| **Model** | PascalCase + Singular | `Anime`, `Episode` |
| **Table (DB)** | snake_case + Plural | `animes`, `anime_episodes` |
| **Route** | kebab-case | `/anime/one-piece`, `/izle/bolum-1` |
| **View (Blade)** | kebab-case | `resources/views/pages/anime-detail.blade.php` |
| **Variable** | camelCase | `$animeTitle`, `$episodeCount` |
| **Constant** | UPPER_SNAKE_CASE | `STATUS_PUBLISHED` |

---

## 🔐 Kodlama Standartları (Coding Standards)

### 1. Stil ve Format (Laravel Pint)
Projede **PSR-12** yerine, daha modern ve sıkı kuralları olan **Laravel Preset** kullanılır.
*   Formatlama için `Laravel Pint` aracı kullanılacaktır.
*   Komut: `./vendor/bin/pint` (Otomatik düzeltir).
*   **Kural:** Pull request öncesi mutlaka Pint çalıştırılmalıdır.

### 2. Strict Types
Tüm PHP dosyaları **mutlaka** `declare(strict_types=1);` ile başlamalıdır.

```php
<?php

declare(strict_types=1);

namespace App\Actions;
```

### 2. Return Types & Type Hinting
Fonksiyon parametreleri ve dönüş tipleri **kesinlikle** belirtilmelidir. `mixed` veya `any` kullanımından kaçınılmalıdır.

```php
// ✅ Doğru
public function getEpisodeCount(Anime $anime): int
{
    return $anime->episodes()->count();
}
```

### 3. Enums Kullanımı
"Magic String" veya "Magic Number" yasaktır. Durumlar için PHP 8.1+ Enums kullanılmalıdır.

```php
// ❌ Yanlış
if ($anime->status == 'yayinda') ...

// ✅ Doğru
enum AnimeStatus: string {
    case PUBLISHED = 'published';
    case DRAFT = 'draft';
}

if ($anime->status === AnimeStatus::PUBLISHED) ...
```

### 4. DRY (Don't Repeat Yourself)
Kod tekrarı kesinlikle yasaktır. 
*   **Logic Tekrarı:** Aynı iş mantığı 2 yerde kullanılıyorsa, tek bir `Action` veya `Service` sınıfına taşınmalıdır.
*   **UI Tekrarı:** Aynı HTML yapısı 2 yerde varsa, `Blade Component` yapılmalıdır.
*   **Sınıf İçi Tekrar:** Ortak metodlar `Trait`'lere bölünmelidir.

---

## 🎨 Frontend & Blade Kuralları

### 1. Tailwind CSS v4
*   CSS dosyasına (`app.css`) veya Blade bileşenlerine satır içi (inline) stil veya hex kodu (`#ffffff` gibi) yazmak **kesinlikle yasaktır**.
*   Tüm stiller Tailwind utility sınıflarıyla verilmelidir.
*   Özel değerler (spesifik gölgeler, özel renkler vb.) **mutlaka** `app.css` içindeki `@theme` bloğunda tanımlanmalı ve semantic isimlerle (örn: `shadow-glow`, `color-discord`) kullanılmalıdır.
*   Keyfi değerler (arbitrary values) içeren sınıflar (`shadow-[0_0_20px...]`, `rounded-[2.5rem]` vb.) yerine temada tanımlanmış standart sınıflar kullanılmalıdır.
*   Tekrar eden yapılar için `@apply` yerine **Blade Components** kullanılmalıdır.

### 2. Blade Components
Tekrar eden UI parçaları `resources/views/components` altında toplanmalıdır.
*   `<x-button primary>` gibi parametrik yapılar kurulmalıdır.
*   Layouts klasörü altında `AppLayout`, `GuestLayout` gibi ana şablonlar bulunmalıdır.

---

## 🗄 Veritabanı Kuralları

1.  **Migrations:** Veritabanında elle tablo açmak **yasaktır**. Her değişiklik bir migration dosyası ile yapılmalıdır.
2.  **Foreign Keys:** İlişkisel bütünlük için `constrained()->cascadeOnDelete()` gibi kısıtlamalar mutlaka tanımlanmalıdır.
3.  **Indexing:** Sık sorgulanan kolonlar (örn: `slug`, `status`, `created_at`) indexlenmelidir.

---

## 🚀 Git & Commit Kuralları

Commit mesajları **Conventional Commits** standardına uymalıdır:

*   `feat:` Yeni özellik (örn: `feat: video player component eklendi`)
*   `fix:` Hata düzeltmesi (örn: `fix: login validasyon hatası giderildi`)
*   `refactor:` Kod iyileştirme
*   `style:` Tasarım/CSS değişikliği
*   `chore:` Yapılandırma/Bakım işleri

---

*Bu belge, projenin kalitesini korumak için oluşturulmuştur ve yeni eklenecek kurallarla güncellenebilir.*
