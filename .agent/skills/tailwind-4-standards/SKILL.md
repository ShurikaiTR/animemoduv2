---
name: tailwind-4-standards
description: Tailwind CSS v4 resmi dokümantasyonuna ve projeye özel semantik tokenlara dayalı kapsamlı tasarım sistemi rehberi.
---

# Tailwind CSS v4 Standartları ve En İyi Uygulamalar

Bu yetenek, projedeki tüm stillerin **Tailwind CSS v4 resmi standartlarına** ve proje tasarım sistemine %100 uyumlu olmasını sağlar.

## 📚 Resmi Referanslar

- [Theme Variables](https://tailwindcss.com/docs/theme): @theme ve CSS değişkenleri
- [Adding Custom Styles](https://tailwindcss.com/docs/adding-custom-styles): @utility, @layer, @variant
- [Responsive Design](https://tailwindcss.com/docs/responsive-design): Breakpoints ve container queries
- [Dark Mode](https://tailwindcss.com/docs/dark-mode): dark: variant kullanımı

---

## 🆕 Tailwind v4 Temel Değişiklikler

### CSS-First Konfigürasyon
Tailwind v4'te `tailwind.config.js` yerine **CSS içinde @theme** bloğu kullanılır:

```css
@import 'tailwindcss';

@theme {
    --color-primary: #2f80ed;
    --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;
}
```

Bu değişkenler hem Tailwind utility'leri (`bg-primary`) hem de CSS (`var(--color-primary)`) tarafından kullanılır.

---

## 🎨 @theme - Tema Değişkenleri

### Yeni Değişken Ekleme
```css
@theme {
    --color-discord: #5865f2;
    --shadow-glow: 0 0 1.25rem rgba(var(--color-primary-rgb), 0.3);
}
```

**Kullanım:** `bg-discord`, `shadow-glow`

### Namespace'leri Sıfırlama
Varsayılan değerleri tamamen kaldırmak için:

```css
@theme {
    --color-*: initial;  /* Tüm varsayılan renkleri sil */
    --color-primary: #2f80ed;
    --color-bg-main: #131720;
}
```

### Varsayılan Değeri Değiştirme
```css
@theme {
    --breakpoint-sm: 30rem;  /* 40rem yerine 30rem */
}
```

### Tema Değişken Namespace'leri

| Namespace | Utility Örneği | CSS Değişkeni |
|-----------|----------------|---------------|
| `--color-*` | `bg-primary`, `text-danger` | `var(--color-primary)` |
| `--font-*` | `font-sans`, `font-inter` | `var(--font-inter)` |
| `--shadow-*` | `shadow-glow`, `shadow-lg` | `var(--shadow-glow)` |
| `--spacing-*` | `p-4`, `mt-hero` | `var(--spacing-hero)` |
| `--radius-*` | `rounded-4xl` | `var(--radius-4xl)` |
| `--breakpoint-*` | `sm:`, `md:`, `lg:` | Responsive breakpoints |
| `--container-*` | `@sm:`, `@md:` | Container query sizes |

---

## 🔧 @utility - Özel Utility Sınıfları

### Basit Utility
```css
@utility content-auto {
    content-visibility: auto;
}
```

**Kullanım:** `content-auto`, `hover:content-auto`, `lg:content-auto`

### Karmaşık Utility (Nesting ile)
```css
@utility no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
    
    &::-webkit-scrollbar {
        display: none;
    }
}
```

### Fonksiyonel Utility (Değer Alan)
```css
@utility tab-* {
    tab-size: --value(--tab-size-*, integer);
}
```

**Kullanım:** `tab-4`, `tab-8`

---

## 📦 @layer - Katmanlı Stiller

### base Katmanı (Varsayılan Stiller)
```css
@layer base {
    h1 {
        font-size: var(--text-2xl);
        font-weight: bold;
    }
    
    a {
        color: var(--color-primary);
    }
}
```

### components Katmanı (Bileşen Sınıfları)
```css
@layer components {
    .card {
        background-color: var(--color-bg-secondary);
        border-radius: var(--radius-lg);
        padding: var(--spacing-6);
    }
    
    .btn-primary {
        background-color: var(--color-primary);
        color: white;
        padding: var(--spacing-2) var(--spacing-4);
    }
}
```

**Not:** `@layer components` içindeki sınıflar utility'ler tarafından override edilebilir.

---

## 🔀 @variant - Özel CSS'de Variant Kullanımı

```css
.my-element {
    background: white;
    
    @variant dark {
        background: black;
    }
    
    @variant hover {
        background: gray;
    }
}
```

### İç İçe Variant'lar
```css
.my-button {
    background: blue;
    
    @variant dark {
        @variant hover {
            background: lightblue;
        }
    }
}
```

---

## 📱 Responsive Breakpoints

### Varsayılan Breakpoint'ler

| Prefix | Min Width | CSS |
|--------|-----------|-----|
| `sm:` | 40rem (640px) | `@media (width >= 40rem)` |
| `md:` | 48rem (768px) | `@media (width >= 48rem)` |
| `lg:` | 64rem (1024px) | `@media (width >= 64rem)` |
| `xl:` | 80rem (1280px) | `@media (width >= 80rem)` |
| `2xl:` | 96rem (1536px) | `@media (width >= 96rem)` |

### Mobile-First Yaklaşım
```html
<!-- Mobilde: w-full, md ve üstü: w-1/2, lg ve üstü: w-1/3 -->
<div class="w-full md:w-1/2 lg:w-1/3">...</div>
```

### Max-Width Variant'ları
```html
<!-- Sadece md'nin altında görünür -->
<div class="block max-md:hidden">...</div>
```

### Breakpoint Aralıkları
```html
<!-- Sadece sm ile md arasında -->
<div class="hidden sm:max-md:block">...</div>
```

---

## 📦 Container Queries

### Temel Kullanım
```html
<div class="@container">
    <div class="flex flex-col @md:flex-row">
        <!-- Container 48rem üstünde flex-row olur -->
    </div>
</div>
```

### Container Query Boyutları

| Prefix | Container Width |
|--------|-----------------|
| `@xs:` | 20rem (320px) |
| `@sm:` | 24rem (384px) |
| `@md:` | 28rem (448px) |
| `@lg:` | 32rem (512px) |
| `@xl:` | 36rem (576px) |
| `@2xl:` | 42rem (672px) |

### İsimli Container'lar
```html
<div class="@container/sidebar">
    <div class="@md/sidebar:hidden">
        <!-- sidebar container'ı 28rem'den büyükse gizle -->
    </div>
</div>
```

### Max-Width Container Queries
```html
<div class="@container">
    <div class="flex-row @max-md:flex-col">
        <!-- Container 28rem'den küçükse flex-col -->
    </div>
</div>
```

---

## 🌙 Dark Mode

### Varsayılan Kullanım (prefers-color-scheme)
```html
<div class="bg-white dark:bg-gray-800">
    <p class="text-gray-900 dark:text-white">Merhaba</p>
</div>
```

### Manuel Toggle (Sınıf Tabanlı)
```css
@import "tailwindcss";

@custom-variant dark (&:where(.dark, .dark *));
```

```html
<html class="dark">
    <body>
        <div class="bg-white dark:bg-black">...</div>
    </body>
</html>
```

---

## � AnimeModu v2 Semantik Tokenları

Projenin `resources/css/app.css` dosyasında tanımlı özel tokenlar:

### Renkler
| Token | Değer | Kullanım |
|-------|-------|----------|
| `bg-bg-main` | #131720 | Ana arka plan |
| `bg-bg-secondary` | #151f30 | İkincil arka plan |
| `bg-bg-input` | #1e2330 | Form inputları |
| `bg-bg-dropdown` | #1a1f2e | Dropdown menüler |
| `bg-primary` | #2f80ed | Ana vurgu rengi |
| `bg-danger` | #ef4444 | Hata durumları |
| `bg-success` | #22c55e | Başarı durumları |
| `bg-discord` | #5865f2 | Discord butonu |
| `text-text-main` | #e0e0e0 | Ana metin |
| `text-text-heading` | #ffffff | Başlıklar |

### Gölgeler
| Token | Kullanım |
|-------|----------|
| `shadow-glow` | Primary renkli hafif parıltı |
| `shadow-glow-lg` | Primary renkli güçlü parıltı |
| `shadow-success-glow` | Yeşil parıltı |
| `shadow-danger-glow` | Kırmızı parıltı |
| `shadow-glow-white` | Beyaz parıltı |

### Özel Utility'ler
| Utility | Açıklama |
|---------|----------|
| `no-scrollbar` | Scrollbar'ı gizle |
| `z-modal` | Modal z-index (100) |
| `z-toast` | Toast z-index (200) |
| `z-dropdown` | Dropdown z-index (60) |
| `text-2xs` | 0.625rem font |
| `aspect-poster` | 2:3 aspect ratio |

### Özel Spacing
| Token | Değer | Kullanım |
|-------|-------|----------|
| `h-hero` | 65vh | Hero section |
| `h-hero-lg` | 80vh | Büyük hero |
| `h-auth-modal` | 70vh | Auth modal |

---

## ⛔ Proje Kuralları

### 1. Keyfi (Arbitrary) Değerler YASAK
```html
<!-- ❌ YANLIŞ -->
<div class="bg-[#2f80ed] text-[10px] shadow-[0_0_20px...]">

<!-- ✅ DOĞRU -->
<div class="bg-primary text-2xs shadow-glow">
```

### 2. Hex Kod YASAK
```html
<!-- ❌ YANLIŞ -->
<div style="color: #2f80ed">

<!-- ✅ DOĞRU -->
<div class="text-primary">
```

### 3. Yeni Değer Gerektiğinde
1. `resources/css/app.css` içindeki `@theme` bloğuna ekle
2. Semantik bir isim ver (örn: `--color-accent`, `--shadow-card`)
3. HTML'de bu token'ı kullan

### 4. @apply Yerine Blade Component
```html
<!-- ❌ app.css'de @apply ile -->
.btn-primary {
    @apply bg-primary text-white px-4 py-2 rounded-lg;
}

<!-- ✅ Blade Component olarak -->
<!-- resources/views/components/button.blade.php -->
<button {{ $attributes->merge(['class' => 'bg-primary text-white px-4 py-2 rounded-lg']) }}>
    {{ $slot }}
</button>
```

### 5. Inline Style YASAK
```html
<!-- ❌ YANLIŞ -->
<div style="background: rgba(0,0,0,0.5)">

<!-- ✅ DOĞRU - @theme'e ekle -->
@theme {
    --color-overlay: rgba(0, 0, 0, 0.5);
}
<div class="bg-overlay">
```

---

## ✅ Tailwind Kontrol Listesi

Stil yazarken:

- [ ] Arbitrary value (`[...]`) kullanmadım
- [ ] Hex kod yazmadım
- [ ] Yeni değer için `@theme`'e ekledim
- [ ] `@utility` ile özel utility tanımladım
- [ ] Responsive için mobile-first yaklaşım kullandım
- [ ] Dark mode variant'ını düşündüm
- [ ] Tekrarlayan yapı için Blade Component oluşturdum

---

## 🚀 Çalışma Akışı

1. **Token Kontrolü:** İhtiyacın olan değer `app.css` @theme'de var mı?
2. **Yoksa Ekle:** Semantik bir isimle @theme'e ekle
3. **Utility Kontrolü:** Özel bir utility gerekiyor mu? `@utility` kullan
4. **Responsive:** Mobile-first mu yazdın?
5. **Component:** Tekrar ediyor mu? Blade Component yap

---

*Bu skill, Tailwind CSS v4 resmi dokümantasyonuna ve proje standartlarına dayanmaktadır.*
