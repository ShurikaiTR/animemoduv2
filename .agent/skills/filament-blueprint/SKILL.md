---
name: filament-blueprint
description: Filament kaynakları, formları ve tabloları için projenin kurallarına dayalı bir taslak (blueprint) oluşturma sistemi.
---

# Filament Taslak (Blueprint) Yeteneği

Bu yetenek, ajanın Filament kaynakları (resource), formlar, tablolar ve modeller için son derece detaylı teknik şartnameler (Taslaklar) oluşturmasını sağlar. Bu taslaklar, Filament admin paneliyle ilgili herhangi bir kodlama görevi için 1:1 uygulama kılavuzu görevi görür.

## Temel Referanslar

Bu taslakların temel kuralları şu dizinde yer alır:
`vendor/filament/blueprint/resources/markdown/planning/`

Planlama yaparken şu dosyalara MUTLAKA başvurmalısın:
- `overview.md`: Genel yapı ve gereken zorunlu alanlar.
- `models.md`: Özellikler, ilişkiler ve isimlendirme.
- `forms.md`: Form bileşenleri ve doğrulama (validation) kuralları.
- `tables.md`: Sütunlar ve filtreler.
- `schema-layouts.md`: Düzen bileşenleri (Bölümler, Sekmeler vb.).
- `checklist.md`: Teslim öncesi kritik kontrol listesi.

## AnimeModu v2 Projesine Özel Kurallar

Temel Filament kurallarını uygularken, şu projeye özel standartları MUTLAKA dahil etmelisin:

### 1. Veritabanı Mimarisi
- **UUID Kullanımı**: Tüm yeni modeller birincil anahtar (primary key) olarak UUID kullanmalıdır.
  - Migration: `$table->uuid('id')->primary();`
  - Model: `Illuminate\Database\Eloquent\Concerns\HasUuids` trait'ini kullan.
- **Foreign Keys**: İlişkiler için `foreignUuid()` kullan.

### 2. Kod Organizasyonu ("Trait Kuralı")
- **Modüler Formlar**: Büyük formlar `Concerns/` namespace'i altında Trait'lere bölünmelidir.
- **Satır Sınırları**: Hiçbir PHP dosyası 150 satırı geçmemelidir. Büyük formlar ve tablolar mutlaka parçalara ayrılmalıdır.
- **Strict Types**: HER yeni PHP dosyası mutlaka `declare(strict_types=1);` ile başlamalıdır.

### 3. Filament v5 (Kararlı) Sözdizimi
Proje Filament v5 (Kararlı) kullanmaktadır. Güncel namespace'leri kullandığından emin ol:
- `Filament\Forms\Form` yerine `Filament\Schemas\Schema`.
- `->reactive()` yerine `->live()`.
- `blueprint/resources/markdown/planning/schema-layouts.md` dosyasındaki güncel layout namespace'lerini kullan.

## Taslak Oluşturma İş Akışı

1. **Araştırma**: Eğer mevcut bir şeyi güncelliyorsan, ilgili modeli ve kaynağı oku.
2. **Taslak Hazırlama**: `vendor/filament/blueprint/resources/markdown/planning/overview.md` dosyasında belirtilen formatları kullan.
3. **Doğrulama**: Hazırladığın planı `checklist.md` kriterlerine göre kontrol et.
4. **Onay**: Taslağı kullanıcıya incelemesi için sun.

## Örnek Taslak Kesiti

### Modeller: Sipariş (Order)
- `id`: UUID (Primary)
- `customer_id`: UUID (FK to users)
- `status`: string (Enum: pending, completed)
- **İlişkiler**: `belongsTo(User, 'customer_id')`

### Kaynaklar: OrderResource
- **Konum**: `app/Filament/Resources/OrderResource.php`
- **Alanlar (Fields)**:
  - `customer_id`: Bileşen: `Filament\Forms\Components\Select`, Link: `https://filamentphp.com/docs/5.x/forms/fields/select`, Validation: `required`, Konfigürasyon: `->relationship('customer')`
- **Sütunlar (Columns)**:
  - `id`: Bileşen: `Filament\Tables\Columns\TextColumn`, Link: `...`, Konfigürasyon: `->label('ID')->searchable()`
