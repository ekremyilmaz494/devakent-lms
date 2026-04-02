# DevAkent LMS — Proje Rehberi

## Proje Özeti

DevAkent LMS, kurum içi eğitim yönetim sistemidir. Personele kurs atama, video izleme, sınav yapma ve sertifika üretme işlevlerini kapsar. Admin ve Staff olmak üzere iki panel vardır.

## Teknoloji Yığını

| Katman | Teknoloji |
|--------|-----------|
| Backend | Laravel 12, PHP 8.3 |
| Frontend | Livewire 4, Alpine.js 3, Tailwind CSS 3 |
| Bundler | Vite 7 (laravel-vite-plugin) |
| Veritabanı | MySQL 8.0 |
| Cache/Queue/Session | Redis 7 |
| PDF | barryvdh/laravel-dompdf |
| Excel | maatwebsite/excel |
| Yetkilendirme | spatie/laravel-permission |
| Loglama | spatie/laravel-activitylog |
| Auth | Laravel Breeze + Sanctum |
| Container | Docker (PHP-FPM + Nginx + Supervisor) |

## Klasör Yapısı (Önemli Noktalar)

```
app/Livewire/Admin/       → Admin panel Livewire component'leri
app/Livewire/Staff/       → Staff panel Livewire component'leri
resources/views/layouts/   → admin.blade.php, staff.blade.php
resources/views/layouts/partials/ → header, sidebar partial'ları
routes/admin.php          → Admin route'ları
routes/staff.php          → Staff route'ları
routes/web.php            → Genel route'lar
routes/auth.php           → Authentication route'ları
docker-compose.yml        → MySQL, Redis, Queue, Scheduler servisleri
```

## Geliştirme Ortamları

Bu proje hem **macOS** hem **Windows** üzerinde geliştirilmektedir. Aşağıdaki kurallara her zaman uyulmalıdır.

### Projeyi Çalıştırma

**Docker ile (önerilen — her iki OS'te aynı davranır):**
```bash
docker-compose up -d
```

**Lokal geliştirme:**
```bash
composer dev
# Bu komut eş zamanlı çalıştırır: php artisan serve, queue:listen, pail, npm run dev
```

### Port Haritası

| Servis | Port |
|--------|------|
| App (Docker) | 8080 |
| App (lokal) | 8000 |
| MySQL | 3307 (dış) → 3306 (iç) |
| Redis | 6380 (dış) → 6379 (iç) |
| Vite HMR | 5173 |

---

## Cross-Platform Kurallar (macOS ↔ Windows)

> **Bu bölüm kritiktir.** Yeni kod yazarken veya mevcut kodu düzenlerken bu kuralların tamamına uyulmalıdır.

### 1. Satır Sonu (Line Endings)

Proje `.gitattributes` dosyasında `* text=auto eol=lf` tanımlıdır. `.editorconfig` dosyasında da `end_of_line = lf` zorunlu tutulmuştur.

- **Kural:** Tüm dosyalar LF satır sonu kullanmalıdır. CRLF asla commit'lenmemelidir.
- **Windows'ta dikkat:** Git'in `core.autocrlf` ayarını `input` olarak ayarla:
  ```bash
  git config core.autocrlf input
  ```
- **Sorun belirtisi:** Blade dosyalarında veya JS dosyalarında beklenmedik parse hataları, "invisible character" uyarıları.

### 2. Dosya Yolu Birleştirme

- **Asla hardcoded `\` veya `/` kullanma.** PHP tarafında her zaman:
  ```php
  // YANLIŞ
  $path = storage_path() . '\app\exports\file.xlsx';

  // DOĞRU
  $path = storage_path('app/exports/file.xlsx');
  // veya
  $path = storage_path('app' . DIRECTORY_SEPARATOR . 'exports' . DIRECTORY_SEPARATOR . 'file.xlsx');
  ```
- Laravel helper'ları (`storage_path()`, `base_path()`, `resource_path()`) zaten forward slash ile çalışır, bunları tercih et.
- JS/Node tarafında `path.join()` veya `path.resolve()` kullan.

### 3. Case Sensitivity (Büyük/Küçük Harf)

macOS dosya sistemi varsayılan olarak case-insensitive'dir, Windows da öyle. Ancak Linux (Docker, production) case-sensitive'dir.

- **Kural:** Dosya adları ve import/require yolları her zaman gerçek dosya adıyla birebir eşleşmeli.
  ```php
  // Dosya adı: CourseTable.php

  // YANLIŞ (macOS'ta çalışır, Linux'ta çalışmaz)
  use App\Livewire\Admin\coursetable;

  // DOĞRU
  use App\Livewire\Admin\CourseTable;
  ```
- Blade view'larda da aynı kural geçerli:
  ```blade
  {{-- YANLIŞ --}}
  @include('layouts.partials.Header')

  {{-- DOĞRU --}}
  @include('layouts.partials.header')
  ```

### 4. Script ve Komut Uyumluluğu

- `composer.json` içindeki `scripts` bölümünde `npx concurrently` kullanılıyor — bu her iki OS'te çalışır.
- **Asla bash-only syntax (`&&`, pipe, `grep`) kullanma** script tanımlarında. `@php` veya `npx` tercih et.
- Docker içinde çalışırken OS farkı yoktur, ancak lokal geliştirmede dikkat et.

### 5. Ortam Değişkenleri ve .env

- `.env` dosyasında path'ler her zaman forward slash (`/`) kullanmalı.
- Windows'ta `APP_URL=http://localhost:8000` (127.0.0.1 yerine `localhost` tercih et).
- `FILESYSTEM_DISK=local` kullanıldığında `storage/app` altındaki path'ler OS-agnostic olmalı.

### 6. storage ve bootstrap/cache İzinleri

- Windows'ta `chmod` çalışmaz. Bu nedenle `storage/` ve `bootstrap/cache/` izin hataları oluşabilir.
- **Çözüm:** Windows'ta lokal geliştirmede `php artisan storage:link` çalıştır ve izin hatası alırsan PowerShell'i yönetici olarak aç.
- Docker kullanılması bu sorunu tamamen ortadan kaldırır.

### 7. Livewire + Alpine.js + wire:navigate

Bu projede bilinen bir sorun: `wire:navigate` ile sayfa geçişlerinde Alpine.js state'leri bozulabiliyor. Detaylar `menu-bug-prompt.md` dosyasında belgelenmiştir.

- **Kural:** Alpine `x-data` içinde state güncellemelerini her zaman `$nextTick()` ile sar.
- `wire:navigate` kullanılan linklerde, farklı Livewire component'leri yükleyen geçişlerde dikkatli ol.
- Menü active state kontrollerinde `request()->routeIs()` wildcard'ları doğru kullan:
  ```php
  // DOĞRU
  request()->routeIs('admin.courses.*')

  // DİKKAT (nokta eksik olabilir)
  request()->routeIs('admin.courses' . '*')
  ```

---

## Kod Yazım Kuralları

### Genel

- **Indent:** 4 space (`.editorconfig` ile zorunlu).
- **PHP:** PSR-12 standardı. `laravel/pint` ile lint edilmeli.
- **Blade:** Component-based yapı tercih et (`<x-component>` syntax).
- **Livewire:** Her component tek bir sorumluluğa sahip olmalı.
- **Tailwind:** Utility-first yaklaşım. Custom CSS yazmaktan kaçın.

### Veritabanı

- Migration dosya isimleri snake_case ve açıklayıcı olmalı.
- Foreign key constraint'leri her zaman tanımlanmalı.
- `$table->foreignId('user_id')->constrained()->cascadeOnDelete();` kalıbını kullan.

### Route İsimlendirme

- Admin: `admin.{resource}.{action}` (örn: `admin.courses.index`)
- Staff: `staff.{resource}.{action}` (örn: `staff.courses.show`)
- Route isimlerinde panel prefix'i her zaman kullanılmalı.

---

## Bilinen Sorunlar ve Çözümleri

| Sorun | Neden | Çözüm |
|-------|-------|-------|
| Windows'ta butonlar tepki vermiyor | wire:navigate + Alpine state kaybı | `$nextTick()` ile state güncelle, `menu-bug-prompt.md` dosyasına bak |
| Admin bildirim linki staff'a yönlendiriyor | header.blade.php'de hardcoded route | Role-based route kontrolü ekle |
| Mobil sidebar tıklama sorunu | z-index tutarsızlığı (admin z-30, staff z-50) | Admin sidebar'ı z-50'ye çıkar |
| Kategoriler sayfasında menü highlight olmuyor | `$isCourses` kontrolü eksik | `routeIs('admin.categories.*')` ekle |
| storage izin hatası (Windows) | chmod farkı | Docker kullan veya PowerShell admin olarak çalıştır |

---

## Claude İçin Talimatlar

Bu projeyle çalışırken şunlara dikkat et:

1. **Her zaman cross-platform düşün.** Yazdığın her kod macOS, Windows ve Linux (Docker) üçünde de çalışmalı. Hardcoded path ayırıcı (`\` veya `/`) kullanma.
2. **Mevcut mimariyi koru.** Admin ve Staff panelleri ayrı route dosyaları, ayrı Livewire namespace'leri ve ayrı layout'lar kullanır. Bu ayrımı bozma.
3. **wire:navigate davranışını bil.** SPA-benzeri navigasyonda Alpine state kaybı yaşanabilir. Yeni component yazarken `livewire:navigated` event'ini dinle.
4. **Test komutları:** Her değişiklikten sonra şu komutları çalıştır:
   ```bash
   php artisan route:clear && php artisan cache:clear && php artisan view:clear
   ```
5. **Docker-first yaklaşım:** Ortam bağımlılığı olan bir şey yazıyorsan Docker'da çalıştığından emin ol.
6. **Mevcut paketleri kullan:** PDF için dompdf, Excel için maatwebsite/excel, yetkilendirme için spatie/permission zaten kurulu. Yeni paket eklemeden önce mevcut olanları kontrol et.
7. **Base component'leri extend et:** Yeni Livewire component yazarken Admin tarafında `AdminComponent`'i, Staff tarafında `StaffComponent`'i extend et. `Component`'i direkt extend etme — yetkilendirme kontrolü atlanmış olur.
8. **Dosya yükleme işlemlerini try-catch içine al:** `maatwebsite/excel` import'larında ve `Storage` yazma işlemlerinde her zaman `try-catch` kullan; hataları kullanıcıya session flash ile bildir.
9. **Büyük veri tabloları için WithPagination kullan:** Livewire component'lerinde tablo veya liste gösteren her yerde `WithPagination` trait'ini ekle ve `->get()` yerine `->paginate(25)` kullan. Belleğe tüm satırları çekme.
10. **URL alanlarını doğrula:** Kullanıcıdan URL alan tüm form alanlarında `'nullable|url:http,https'` kuralını kullan. Sadece `url` yazmak güvenli değil — `javascript:` gibi scheme'lere izin verir.
11. **Birden fazla tablo etkileyen DB işlemlerinde transaction kullan:** `DB::transaction()` içine al. Transaction dışında bırakan tek istisna e-posta/kuyruk görevleri gibi yan etkilerdir — bunları transaction kapandıktan sonra çalıştır.
12. **Karma soru tipli sınavlarda puan hesaplama:** `ExamService::calculateFinalScore()` kullan. Her soru eşit ağırlıklıdır: MCQ/TF doğruysa 1 puan, açık uçlu `manual_score/10` puan → `(toplam_puan / toplam_soru) * 100`. `finishAttempt()` sonrası `needs_manual_grading = true` ise `evaluateExam()` `is_passed` set etmez; sonuç admin değerlendirmesi tamamlanınca hesaplanır.
