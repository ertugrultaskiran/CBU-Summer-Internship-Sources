# YouTube Yorumları Analiz Paneli

İşletmeler için YouTube video yorumlarını gerçek zamanlı olarak analiz eden profesyonel panel sistemi.

## 🚀 Özellikler

### ⚡ Gerçek Zamanlı Takip
- **10 saniye aralıklarla** anlık yorum senkronizasyonu
- **Yeni yorumlar** otomatik panele düşer
- **Silinen yorumlar** otomatik panelden kalkar
- **Güncellenen yorumlar** (beğeni, metin) otomatik yenilenir

### 🎨 Modern Arayüz
- **Profesyonel tasarım** - Inter font ve modern gradientler
- **Responsive design** - Mobil, tablet ve desktop uyumlu
- **Smooth animasyonlar** - Yeni/güncellenen/silinen yorumlar için
- **Dark mode ready** - Modern renk paleti

### 📊 Analiz Özellikleri
- **CSV dışa aktarma** - Excel ile analiz için
- **Video istatistikleri** - Yorum sayıları ve trendler
- **Filtreleme** - Video bazında yorum görüntüleme
- **Zaman damgası** - Yorumların tarih/saat bilgisi

## 📋 Gereksinimler

- **PHP 7.4+**
- **MySQL/MariaDB**
- **Composer**
- **XAMPP** (önerilen)
- **YouTube Data API v3 Key**

## 🔧 Kurulum

### 1. Projeyi İndirin
```bash
git clone https://github.com/ertugrultaskiran/youtube-yorumlar.git
cd youtube-yorumlar
```

### 2. Composer Bağımlılıklarını Yükleyin
```bash
composer install
```

### 3. YouTube API Key Alın
1. [Google Developers Console](https://console.developers.google.com/)'a gidin
2. Yeni proje oluşturun
3. **YouTube Data API v3**'ü etkinleştirin
4. **Credentials** → **API Key** oluşturun

### 4. Konfigürasyonu Yapın
```bash
cp config/config.example.php config/config.php
```
`config/config.php` dosyasında API key'inizi güncelleyin:
```php
define('YOUTUBE_API_KEY', 'YOUR_ACTUAL_API_KEY_HERE');
```

### 5. Veritabanını Kurun
- XAMPP'ta MySQL'i başlatın
- Tarayıcıda `http://localhost/youtube-yorumlar/setup.php` açın
- Kurulum adımlarını takip edin

### 6. Senkronizasyon Tablosunu Ekleyin
```bash
php setup-sync.php
```

## 🎯 Kullanım

### Temel Kullanım
1. `http://localhost/youtube-yorumlar/` adresine gidin
2. YouTube video URL'sini yapıştırın
3. **"Analiz Et"** butonuna tıklayın
4. Yorumlar yüklenecek

### Anlık Takip
1. Video analiz ettikten sonra
2. **"Anlık Takip"** switch'ini açın
3. **Aralık seçin** (5s, 10s, 15s, 30s, 1dk)
4. YouTube'da o videoya yorum yapın/silin
5. **10 saniye içinde** değişiklikler panelde görünür!

## 📊 API Limitleri

### YouTube Data API v3
- **Günlük limit:** 10,000 birim (ücretsiz)
- **Yorum çekme:** 1 birim/istek
- **Video bilgisi:** 1 birim/istek

### Önerilen Kullanım
- **10 saniye aralık:** Günde ~8,640 birim (güvenli)
- **1-2 video takip:** Optimal
- **3+ video:** 15-30 saniye aralık önerili

## 🔗 API Endpoints

### Yorum İşlemleri
- `POST /api/fetch-comments.php` - Yeni video yorumları çek
- `GET /api/get-comments.php` - Kayıtlı yorumları listele
- `GET /api/get-latest-comments.php` - Anlık senkronizasyon

### İstatistikler
- `GET /api/get-stats.php` - Video istatistikleri

## 🛠️ Geliştirme

### Proje Yapısı
```
youtube-yorumlar/
├── api/                    # API endpoints
│   ├── fetch-comments.php  # Yorum çekme
│   ├── get-comments.php    # Yorum listesi
│   ├── get-stats.php       # İstatistikler
│   └── get-latest-comments.php # Anlık sync
├── config/                 # Konfigürasyon
│   ├── config.example.php  # Örnek config
│   └── config.php         # Gerçek config (git'te yok)
├── database/              # Veritabanı şemaları
│   ├── schema.sql         # Ana tablolar
│   └── sync_table.sql     # Sync tablosu
├── src/                   # PHP sınıfları
│   ├── Database.php       # Veritabanı işlemleri
│   └── YouTubeAPI.php     # YouTube API wrapper
├── index.php              # Ana panel
├── setup.php              # Kurulum scripti
├── setup-sync.php         # Sync kurulumu
└── composer.json          # Bağımlılıklar
```

### Teknolojiler
- **Backend:** PHP 7.4+, MySQL
- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **UI Framework:** Bootstrap 5.3
- **Icons:** Font Awesome 6.4
- **Fonts:** Inter (Google Fonts)
- **API:** YouTube Data API v3

## 🎨 Tasarım Sistemi

### Renk Paleti
- **Primary:** #6366f1 (İndigo)
- **Secondary:** #8b5cf6 (Purple)
- **Accent:** #06b6d4 (Cyan)
- **Success:** #10b981 (Emerald)
- **Warning:** #f59e0b (Amber)
- **Danger:** #ef4444 (Red)

### Animasyonlar
- **Yeni yorumlar:** Slide-in right + yeşil vurgu
- **Güncellenen yorumlar:** Pulse + sarı vurgu
- **Silinen yorumlar:** Slide-out left
- **Hover efektleri:** Transform + shadow

## 🔒 Güvenlik

- **API key** config dosyasında (git'te yok)
- **SQL injection** koruması (prepared statements)
- **XSS** koruması (output escaping)
- **CORS** ayarları

## 📝 Lisans

MIT License - Detaylar için `LICENSE` dosyasına bakın.

## 🤝 Katkıda Bulunma

1. Fork edin
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Commit edin (`git commit -m 'Add amazing feature'`)
4. Push edin (`git push origin feature/amazing-feature`)
5. Pull Request açın

## 📞 Destek

Sorularınız için:
- **GitHub Issues** açın
- **Email:** [your-email@domain.com]

---

**Made with ❤️ by [Your Name]**