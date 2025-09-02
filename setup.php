<?php
// YouTube Comments Panel - Kurulum Scripti

echo "<h1>YouTube Yorumları Paneli - Kurulum</h1>";

// 1. Composer bağımlılıklarını kontrol et
echo "<h2>1. Composer Bağımlılıkları</h2>";
if (file_exists('vendor/autoload.php')) {
    echo "✅ Composer bağımlılıkları yüklü<br>";
} else {
    echo "❌ Composer bağımlılıkları yüklenmemiş. Terminal'de şu komutu çalıştırın:<br>";
    echo "<code>composer install</code><br>";
}

// 2. Konfigürasyon dosyasını kontrol et
echo "<h2>2. Konfigürasyon</h2>";
if (file_exists('config/config.php')) {
    require_once 'config/config.php';
    if (YOUTUBE_API_KEY === 'YOUR_YOUTUBE_API_KEY_HERE') {
        echo "⚠️ YouTube API Key henüz ayarlanmamış<br>";
        echo "config/config.php dosyasında YOUTUBE_API_KEY değerini güncelleyin<br>";
    } else {
        echo "✅ YouTube API Key ayarlanmış<br>";
    }
} else {
    echo "❌ Konfigürasyon dosyası bulunamadı<br>";
}

// 3. Veritabanı bağlantısını test et
echo "<h2>3. Veritabanı Bağlantısı</h2>";
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
    echo "✅ MySQL bağlantısı başarılı<br>";
    
    // Veritabanını oluştur
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    echo "✅ Veritabanı oluşturuldu/kontrol edildi<br>";
    
    // Tabloları oluştur
    $pdo->exec("USE " . DB_NAME);
    $schema = file_get_contents('database/schema.sql');
    $statements = explode(';', $schema);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    echo "✅ Tablolar oluşturuldu<br>";
    
} catch (PDOException $e) {
    echo "❌ Veritabanı hatası: " . $e->getMessage() . "<br>";
}

// 4. API endpoint'lerini test et
echo "<h2>4. API Endpoints</h2>";
$endpoints = [
    'api/get-comments.php',
    'api/get-stats.php',
    'api/fetch-comments.php'
];

foreach ($endpoints as $endpoint) {
    if (file_exists($endpoint)) {
        echo "✅ $endpoint mevcut<br>";
    } else {
        echo "❌ $endpoint bulunamadı<br>";
    }
}

echo "<h2>Kurulum Tamamlandı!</h2>";
echo "<p>Eğer tüm kontroller başarılıysa, <a href='index.php'>Ana Sayfaya</a> gidebilirsiniz.</p>";

echo "<h3>YouTube API Key Nasıl Alınır?</h3>";
echo "<ol>";
echo "<li><a href='https://console.developers.google.com/' target='_blank'>Google Developers Console</a>'a gidin</li>";
echo "<li>Yeni bir proje oluşturun veya mevcut projeyi seçin</li>";
echo "<li>'APIs & Services' > 'Library' bölümüne gidin</li>";
echo "<li>'YouTube Data API v3'ü arayın ve etkinleştirin</li>";
echo "<li>'APIs & Services' > 'Credentials' bölümüne gidin</li>";
echo "<li>'Create Credentials' > 'API Key' seçin</li>";
echo "<li>Oluşturulan API Key'i config/config.php dosyasına yapıştırın</li>";
echo "</ol>";
?>
