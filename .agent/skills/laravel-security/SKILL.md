---
name: laravel-security
description: Laravel 12 ve Filament 5 projelerinde güvenlik standartları, yetkilendirme politikaları ve en iyi uygulamalar rehberi.
---

# Laravel Güvenlik Standartları ve En İyi Uygulamalar

Bu yetenek, projedeki tüm güvenlik uygulamalarının **Laravel** ve **OWASP** standartlarına %100 uyumlu olmasını sağlar.

## 📚 Resmi Referanslar

Güvenlik konularında şu kaynaklara başvurulmalıdır:
- [Laravel Security](https://laravel.com/docs/12.x/security): Resmi dokümantasyon.
- [Laravel Authorization](https://laravel.com/docs/12.x/authorization): Policies ve Gates.
- [Filament Authorization](https://filamentphp.com/docs/5.x/panels/resources/getting-started#authorization): Panel erişim kontrolü.
- [OWASP Top 10](https://owasp.org/www-project-top-ten/): Web güvenlik açıkları.

---

## 🛡️ 1. Kimlik Doğrulama (Authentication)

### Laravel Sanctum Kullanımı
Proje API kimlik doğrulaması için **Laravel Sanctum** kullanabilir.

```php
// config/sanctum.php
'expiration' => 60 * 24, // Token 24 saat sonra geçersiz olur
```

### Oturum Güvenliği
Session hijacking'e karşı önlemler:

```php
// config/session.php
'secure' => env('SESSION_SECURE_COOKIE', true),    // HTTPS zorunluluğu
'http_only' => true,                                // JS erişimini engelle
'same_site' => 'lax',                               // CSRF koruması
```

### Şifre Gereksinimleri
```php
// Validation kuralı örneği
'password' => ['required', 'min:8', 'confirmed', Password::defaults()],
```

---

## 🔐 2. Yetkilendirme (Authorization)

### Policy Sınıfları
Her model için ayrı bir Policy sınıfı oluşturulmalıdır.

**Konum:** `app/Policies/`

```php
<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Anime;
use App\Models\User;

class AnimePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Herkes listeleyebilir
    }

    public function create(User $user): bool
    {
        return $user->profile->role === UserRole::ADMIN->value;
    }

    public function update(User $user, Anime $anime): bool
    {
        return $user->profile->role === UserRole::ADMIN->value;
    }

    public function delete(User $user, Anime $anime): bool
    {
        return $user->profile->role === UserRole::ADMIN->value;
    }
}
```

**Kayıt:** `AuthServiceProvider`

```php
protected $policies = [
    Anime::class => AnimePolicy::class,
];
```

### Filament Resource Yetkilendirmesi
Filament kaynakları otomatik olarak Policy'leri kullanır:

```php
// EpisodeResource.php
public static function canCreate(): bool
{
    return auth()->user()?->profile?->role === UserRole::ADMIN->value;
}
```

### Gate Kullanımı (Basit Kontroller)
Tek seferlik kontroller için Gate kullanılabilir:

```php
// AuthServiceProvider boot()
Gate::define('manage-settings', function (User $user): bool {
    return $user->profile->role === UserRole::ADMIN->value;
});

// Kullanım
if (Gate::allows('manage-settings')) { ... }
```

---

## 🧹 3. Girdi Doğrulama (Input Validation)

### Form Request Sınıfları
Karmaşık doğrulamalar için `FormRequest` kullanılmalıdır.

**Konum:** `app/Http/Requests/`

```php
<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:3', 'max:1000'],
            'parent_id' => ['nullable', 'uuid', 'exists:comments,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Yorum boş bırakılamaz.',
            'body.max' => 'Yorum en fazla 1000 karakter olabilir.',
        ];
    }
}
```

### Livewire Doğrulaması
Livewire bileşenlerinde inline validation:

```php
// ✅ Doğru: Kuralları property olarak tanımla
protected array $rules = [
    'body' => 'required|string|min:3|max:1000',
];

// Kullanım
$this->validate();
```

### XSS Koruması (Cross-Site Scripting)
- **Blade:** Varsayılan olarak `{{ }}` ile escape eder.
- **Tehlikeli:** `{!! !!}` ham HTML çıktısı verir, dikkatli kullan.
- **Kural:** Kullanıcı girdisi asla `{!! !!}` ile gösterilmez.

```blade
{{-- ✅ Güvenli --}}
<p>{{ $comment->body }}</p>

{{-- ❌ Tehlikeli - Sadece güvenilir HTML için --}}
{!! $page->content !!}
```

---

## 🚫 4. Mass Assignment Koruması

### $fillable Kuralları
`$fillable` dizisine **asla** hassas alanlar eklenmemelidir:

```php
// ❌ YANLIŞ - Güvenlik açığı
protected $fillable = ['name', 'email', 'role', 'is_admin'];

// ✅ DOĞRU
protected $fillable = ['name', 'email', 'avatar'];

// role ve is_admin gibi alanlar sadece manuel atanır
$user->role = UserRole::ADMIN->value;
$user->save();
```

### Güvenilir Alanlar Listesi

| Alan Tipi | Fillable? | Açıklama |
|-----------|-----------|----------|
| `name`, `email`, `bio` | ✅ Evet | Kullanıcı güncelleyebilir |
| `role`, `is_admin` | ❌ Hayır | Sadece admin kodla atayabilir |
| `password` | ⚠️ Dikkatli | Mutlaka hash ile kaydet |
| `email_verified_at` | ❌ Hayır | Sistem tarafından atanır |

---

## 🔒 5. SQL Injection Koruması

### Eloquent ORM Kullanımı
Eloquent varsayılan olarak parametrik sorgular kullanır:

```php
// ✅ Güvenli - Parametrik
Anime::where('slug', $slug)->first();

// ✅ Güvenli - whereIn
Anime::whereIn('id', $ids)->get();
```

### Ham Sorgu Dikkatli Kullanımı
```php
// ❌ TEHLİKELİ - SQL Injection açığı
DB::select("SELECT * FROM users WHERE email = '$email'");

// ✅ GÜVENLİ - Binding kullan
DB::select('SELECT * FROM users WHERE email = ?', [$email]);

// ✅ GÜVENLİ - Named binding
DB::select('SELECT * FROM users WHERE email = :email', ['email' => $email]);
```

---

## 🛑 6. CSRF Koruması

Laravel otomatik CSRF koruması sağlar.

### Blade Formları
```blade
<form method="POST" action="/comment">
    @csrf
    <!-- form içeriği -->
</form>
```

### Livewire
Livewire otomatik CSRF token ekler, ekstra işlem gerekmez.

### API Endpoint'leri
Sanctum token kullanılan API'lerde CSRF gerekmez.

---

## 🔗 7. Rate Limiting

Brute-force saldırılarına karşı rate limiting:

```php
// routes/web.php veya RouteServiceProvider
Route::middleware(['throttle:login'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// RouteServiceProvider boot()
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});
```

### Livewire Rate Limiting
```php
use Livewire\Attributes\Validate;

#[Validate(['email' => 'required|email'])]
public string $email = '';

public function login(): void
{
    $this->ensureIsNotRateLimited();
    // login logic
}

protected function ensureIsNotRateLimited(): void
{
    if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => RateLimiter::availableIn($this->throttleKey()),
            ]),
        ]);
    }
}

protected function throttleKey(): string
{
    return Str::lower($this->email) . '|' . request()->ip();
}
```

---

## 📝 8. Logging ve Monitoring

### Güvenlik Olaylarını Logla
```php
use Illuminate\Support\Facades\Log;

// Başarısız giriş denemeleri
Log::warning('Failed login attempt', [
    'email' => $email,
    'ip' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);

// Admin işlemleri
Log::info('Admin action', [
    'user_id' => auth()->id(),
    'action' => 'deleted_anime',
    'target_id' => $anime->id,
]);
```

---

## 🔧 9. HTTP Güvenlik Başlıkları

### Middleware ile Header Ekleme

```php
// app/Http/Middleware/SecurityHeaders.php
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        return $response;
    }
}
```

---

## ✅ 10. Güvenlik Kontrol Listesi

Yeni özellik veya endpoint eklerken bu listeyi kontrol et:

- [ ] **Authentication:** Kullanıcı giriş yapmış mı kontrol edildi?
- [ ] **Authorization:** Policy veya Gate ile yetki kontrolü yapıldı?
- [ ] **Validation:** Tüm girdiler doğrulandı (`required`, `max`, `exists` vb.)?
- [ ] **Mass Assignment:** `$fillable`'da hassas alan yok?
- [ ] **XSS:** Kullanıcı girdisi `{{ }}` ile gösteriliyor?
- [ ] **SQL Injection:** Ham sorgu yerine Eloquent/Query Builder kullanıldı?
- [ ] **CSRF:** Form'larda `@csrf` var?
- [ ] **Rate Limiting:** Login/API endpoint'lerinde throttle var?
- [ ] **Logging:** Kritik işlemler loglanıyor?

---

## 🚀 Çalışma Akışı

1. **Planlama:** Yeni özellik için tehdit modellemesi yap.
2. **Policy Oluştur:** Model için Policy sınıfı yaz.
3. **Validation:** FormRequest veya inline kurallar tanımla.
4. **Test:** Manuel olarak yetkisiz erişim dene.
5. **Log:** Kritik işlemleri logla.

---

*Bu skill, OWASP Top 10 ve Laravel güvenlik en iyi uygulamalarına dayanmaktadır.*
