Tabii, arkadaşının kod kontrolü (code review) yapabilmesi için Profil Sayfası ile ilgili oluşturduğum ve düzenlediğim tüm dosyaların listesi aşağıdadır:

1. Mantık ve Router (Backend Logic)
Bu dosya sayfanın beynidir. Verileri çeker, tabları yönetir ve takip etme gibi işlemleri kontrol eder.

app/Livewire/Profile/Show.php
2. Ana Görünüm (Main View)
Sayfanın iskeletini ve yerleşimini (layout) oluşturan ana blade dosyası.

resources/views/livewire/profile/show.blade.php
3. Profil Bileşenleri (Components)
Legacy projeden port ettiğimiz, tasarımın parçalarını oluşturan modüler dosyalar.

Header (Üst Kısım): 
resources/views/components/profile/header.blade.php
Aksiyon Butonları (Takip Et/Düzenle): 
resources/views/components/profile/actions.blade.php
Sosyal Medya İkonları: 
resources/views/components/profile/socials.blade.php
İstatistikler (Takipçi Sayıları): 
resources/views/components/profile/stats.blade.php
Bilgi Kutucukları (Yaş, Konum, Katılma): 
resources/views/components/profile/info-pills.blade.php
Sekmeler (Tablar & Mobil Dropdown): 
resources/views/components/profile/tabs.blade.php
Son Aktiviteler Kutusu: 
resources/views/components/profile/activities.blade.php
Tekil Aktivite Satırı: 
resources/views/components/profile/activity-item.blade.php
4. Modeller (Veritabanı İlişkileri)
Kullanıcı ve Profil arasındaki ilişkilerin tanımlandığı dosyalar.

app/Models/Profile.php
 (Cast ve fillable ayarları)
app/Models/User.php
 (Profile, Watchlist, Favorites ilişkileri)
5. Yardımcı İkonlar
Tasarım için oluşturduğum SVG ikonlar.

resources/views/components/icons/ klasörü altındaki:
x.blade.php, instagram.blade.php, discord.blade.php, reddit.blade.php, telegram.blade.php
shield-check.blade.php (Admin rozeti)
chevron-down.blade.php (Dropdown okları)
user-plus.blade.php, user-check.blade.php, edit.blade.php
