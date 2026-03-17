# DevAkent LMS — Menü Navigasyon Hataları: Analiz ve Çözüm Prompt'u

Aşağıda, projedeki sidebar menü navigasyon sorunlarının kök nedenlerini ve kesin çözümlerini bulacaksın. Her sorunu belirtilen dosyada, belirtilen satırlarda düzelt.

---

## SORUN #1: header.blade.php — Admin panelinde bildirim linki staff'a yönlendiriyor

**Dosya:** `resources/views/layouts/partials/header.blade.php` (satır 69)

**Problem:** Header dosyası hem admin hem staff layout'unda `@include` ediliyor. Ama bildirim dropdown'undaki "Tümü" linki hardcoded olarak `staff.notifications.index` route'una gidiyor. Admin panelindeyken bu linke tıklayan admin, staff bildirim sayfasına yönlendiriliyor (veya 403 hatası alıyor).

**Mevcut Kod (satır 69):**
```blade
<a href="{{ route('staff.notifications.index') }}" class="text-[11px] font-medium text-primary-600 dark:text-primary-400 hover:underline">Tümü</a>
```

**Düzeltilmiş Kod:**
```blade
<a href="{{ auth()->user()->hasRole('admin') ? route('admin.notifications.index') : route('staff.notifications.index') }}" class="text-[11px] font-medium text-primary-600 dark:text-primary-400 hover:underline">Tümü</a>
```

---

## SORUN #2: wire:navigate + Alpine.js x-data state kaybı — Sayfa geçişlerinde sidebar state bozuluyor

**Dosya:** `resources/views/layouts/partials/admin-sidebar.blade.php` (satır 22-32)

**Problem:** Livewire'ın `wire:navigate` özelliği SPA-benzeri sayfa geçişi yapar ve DOM'u morphlar. Bu sırada Alpine.js `x-data` state'leri yeniden initialize olmuyor veya yanlış elementlere bind oluyor. Sonuç olarak:
- Expandable menüler (Eğitimler, Personel) tıklandığında yanlış menü açılıyor
- `openMenu` state'i sayfa geçişinden sonra bozuluyor
- Tıklama event'leri yanlış elemanlara düşüyor

**Mevcut Kod (satır 22-32):**
```blade
<nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto"
     x-data="{
         openMenu: '',
         updateMenu() {
             const p = window.location.pathname;
             if (p.includes('/courses') || p.includes('/categories')) this.openMenu = 'courses';
             else if (p.includes('/staff') || p.includes('/departments')) this.openMenu = 'staff';
             else this.openMenu = '';
         }
     }"
     x-init="updateMenu()"
     x-on:livewire:navigated.window="updateMenu()">
```

**Düzeltilmiş Kod:**
```blade
<nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto"
     x-data="{
         openMenu: '',
         updateMenu() {
             this.$nextTick(() => {
                 const p = window.location.pathname;
                 if (p.includes('/courses') || p.includes('/categories')) this.openMenu = 'courses';
                 else if (p.includes('/staff') || p.includes('/departments')) this.openMenu = 'staff';
                 else this.openMenu = '';
             });
         }
     }"
     x-init="updateMenu()"
     @popstate.window="updateMenu()"
     x-on:livewire:navigated.window="updateMenu()">
```

**Ek olarak**, `wire:navigate` kullanılan tüm `<a>` linklerine `wire:navigate.hover` ekleyerek prefetch davranışını kontrol et. Veya eğer sorun devam ediyorsa, expandable menü parent butonlarında `wire:navigate` KULLANILMADIĞINDAN emin ol (satır 47 ve 66'daki `<button>` elementlerinde wire:navigate olmamalı — sadece child `<a>` linklerinde olmalı). Bu zaten doğru, ama kontrol edilmeli.

---

## SORUN #3: Admin sidebar — Kategoriler sayfasında "Eğitimler" menüsü active görünmüyor

**Dosya:** `resources/views/layouts/partials/admin-sidebar.blade.php` (satır 46)

**Problem:** `$isCourses` değişkeni sadece `admin.courses.*` route'larını kontrol ediyor. Kategoriler sayfasına (`admin.categories.*`) gidildiğinde "Eğitimler" expandable menüsü active görünmüyor, kullanıcı nerede olduğunu kaybediyor.

**Mevcut Kod (satır 46):**
```blade
@php $isCourses = request()->routeIs('admin.courses.*'); @endphp
```

**Düzeltilmiş Kod:**
```blade
@php $isCourses = request()->routeIs('admin.courses.*') || request()->routeIs('admin.categories.*'); @endphp
```

---

## SORUN #4: Staff sidebar — Active state wildcard sorunu

**Dosya:** `resources/views/layouts/partials/staff-sidebar.blade.php` (satır 42)

**Problem:** `request()->routeIs($item['route'] . '*')` ifadesinde route adı ile wildcard arasında nokta (`.`) yok. Örneğin `staff.dashboard` + `*` = `staff.dashboard*`. Bu, `staff.dashboard` ile başlayan herhangi bir route ile eşleşir. Şu an sorun çıkarmıyor çünkü öyle bir route yok, ama `staff.courses.index*` ifadesi `staff.courses.index` ve `staff.courses.indexXYZ` gibi olası gelecek route'larla da eşleşir. Daha güvenli hale getir:

**Mevcut Kod (satır 42):**
```blade
@php $isActive = request()->routeIs($item['route'] . '*'); @endphp
```

**Düzeltilmiş Kod:**
```blade
@php $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*'); @endphp
```

---

## SORUN #5: Mobil sidebar z-index çakışması — Tıklama yanlış elemente düşüyor

**Dosyalar:**
- `resources/views/layouts/admin.blade.php` (sidebar z-30, backdrop z-20)
- `resources/views/layouts/staff.blade.php` (sidebar z-50, backdrop z-40)
- `resources/views/layouts/partials/header.blade.php` (header z-30)

**Problem:** Admin layout'unda sidebar `z-30`, header da `z-30` ile aynı seviyede. Mobil görünümde sidebar açıkken header'daki bildirim dropdown'u sidebar'ın üstüne çıkabiliyor veya tıklama event'leri yanlış katmana düşüyor. Staff layout'unda sidebar `z-50` ile doğru çalışıyor ama admin'de tutarsız.

**admin.blade.php'deki sidebar (satır 3-4 arası, admin-sidebar.blade.php satır 1):**

Admin sidebar'daki `z-30`'u `z-50` yap ve backdrop'u `z-40` yap (staff layout ile tutarlı hale getir):

**admin-sidebar.blade.php satır 1 — Mevcut:**
```blade
<aside class="fixed inset-y-0 left-0 z-30 flex flex-col w-64 ...
```

**Düzeltilmiş:**
```blade
<aside class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 ...
```

**admin.blade.php satır 32 — Mevcut:**
```blade
class="fixed inset-0 z-20 bg-black/50 lg:hidden">
```

**Düzeltilmiş:**
```blade
class="fixed inset-0 z-40 bg-black/50 lg:hidden">
```

---

## SORUN #6: wire:navigate ile Livewire component state'i korunmuyor

**Genel Problem:** `wire:navigate` kullanıldığında Livewire, tam sayfa yenilemesi yapmak yerine sadece body içeriğini morphlar. Bu sırada:
1. Alpine.js component'leri yanlış state ile kalabiliyor
2. Livewire component'lerinin `mount()` metodu tekrar çağrılmayabiliyor
3. Eski sayfanın DOM event listener'ları temizlenmeyebiliyor

**Çözüm — Tüm layout dosyalarında `@livewireScriptConfig` ekle:**

`admin.blade.php` ve `staff.blade.php`'de `@livewireScripts` satırından ÖNCE şunu ekle:
```blade
@livewireScriptConfig
@livewireScripts
```

**Ek olarak**, `wire:navigate` yerine bazı kritik linklerde normal navigasyon kullanmayı düşün. Özellikle farklı Livewire component'leri yükleyen sayfa geçişlerinde (örn: Dashboard → Courses → Staff) `wire:navigate` sorun çıkarabilir. Test et: `wire:navigate` kaldırıldığında sorun devam ediyor mu?

---

## HIZLI TEST ADIMLARI

Bu düzeltmeleri uyguladıktan sonra şu adımlarla test et:

1. `php artisan route:clear && php artisan cache:clear && php artisan view:clear`
2. Tarayıcıda hard refresh (Ctrl+Shift+R)
3. Admin panelinde her menü öğesine tek tek tıkla, adres çubuğundaki URL'i kontrol et
4. Staff panelinde her menü öğesine tek tek tıkla, adres çubuğundaki URL'i kontrol et
5. Mobil görünümde (F12 → responsive) sidebar menüsünü test et
6. Eğer sorun devam ediyorsa: tüm `wire:navigate` attribute'larını geçici olarak kaldır ve tekrar test et — sorun wire:navigate kaynaklıysa bu adımda düzelecektir

---

## ÖNCELİK SIRASI

| # | Düzeltme | Etki | Zorluk |
|---|----------|------|--------|
| 1 | header.blade.php bildirim linki | Admin bildirim navigasyonu düzelir | Kolay |
| 2 | Alpine.js $nextTick + popstate | Sayfa geçişlerinde menü state'i düzelir | Kolay |
| 3 | $isCourses active state | Kategoriler sayfasında menü doğru highlight olur | Kolay |
| 4 | Staff sidebar wildcard | Gelecekte olası active state hataları önlenir | Kolay |
| 5 | z-index tutarlılığı | Mobil menü tıklama sorunları düzelir | Kolay |
| 6 | wire:navigate genel kontrol | SPA navigasyon hataları ortadan kalkar | Orta |
