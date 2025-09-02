<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Analytics Pro - Yorum Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --accent-color: #06b6d4;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --border-radius: 16px;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
        }

        .main-container {
            background: var(--light-color);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            -webkit-backdrop-filter: blur(20px);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-sm);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--dark-color) !important;
        }

        .card-modern {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
        }

        .card-modern:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .fetch-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
        }

        .fetch-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--success-color) 100%);
            color: white;
            border-radius: var(--border-radius);
        }

        .comment-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-color);
        }

        .comment-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-left-color: var(--accent-color);
        }

        .author-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: var(--shadow-md);
        }

        .btn-modern {
            border-radius: 12px;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-modern:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-outline-modern {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline-modern:hover {
            background: var(--primary-color);
            color: white;
        }

        .form-control-modern {
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control-modern:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .loading {
            display: none;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .comment-text {
            line-height: 1.7;
            word-wrap: break-word;
            color: #374151;
        }

        .badge-modern {
            background: linear-gradient(135deg, var(--accent-color), var(--success-color));
            color: white;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.02);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .page-header {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            text-align: center;
        }

        .section-title {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-modern {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .video-title {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .author-name {
            font-weight: 600;
            color: var(--dark-color);
        }

        .comment-meta {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
            100% {
                opacity: 1;
            }
        }

        .new-comment {
            animation: slideInRight 0.5s ease-out;
            border-left-color: var(--success-color) !important;
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.1) 0%, rgba(255, 255, 255, 1) 10%);
        }

        .updated-comment {
            animation: pulseUpdate 2s ease-out;
            border-left-color: var(--warning-color) !important;
            background: linear-gradient(90deg, rgba(245, 158, 11, 0.1) 0%, rgba(255, 255, 255, 1) 10%);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutLeft {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(-100%);
                opacity: 0;
            }
        }

        @keyframes pulseUpdate {
            0%, 100% {
                background: rgba(245, 158, 11, 0.1);
            }
            50% {
                background: rgba(245, 158, 11, 0.3);
            }
        }

        .notification-toast {
            position: fixed;
            top: 6rem;
            right: 2rem;
            z-index: 1050;
            max-width: 350px;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem 0;
            }
            
            .page-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom fixed-top">
        <div class="container">
            <span class="navbar-brand mb-0 h1">
                <i class="fab fa-youtube text-danger me-2"></i>
                YouTube Analytics Pro
            </span>
            <div class="d-flex align-items-center">
                <span class="badge bg-success me-2">
                    <i class="fas fa-circle me-1"></i>
                    Aktif
                </span>
                <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="main-container" style="padding-top: 5rem;">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="mb-3">
                    <i class="fas fa-chart-line text-primary me-3"></i>
                    YouTube Yorum Analiz Paneli
                </h1>
                <p class="text-muted mb-0">İşletmeniz için YouTube videolarındaki yorumları analiz edin ve yönetin</p>
            </div>
            <!-- Video Ekleme Bölümü -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card-modern fetch-section">
                        <div class="card-body p-5 position-relative">
                            <h3 class="card-title mb-4 fw-bold">
                                <i class="fas fa-rocket me-3"></i>
                                Yeni Video Yorumları Çek
                            </h3>
                            <p class="mb-4 opacity-90">YouTube video URL'sini yapıştırın ve yorumları anında analiz edin</p>
                            <form id="fetchForm">
                                <div class="row g-3">
                                    <div class="col-lg-7">
                                        <input type="url" class="form-control form-control-modern form-control-lg" 
                                               id="videoUrl" placeholder="https://www.youtube.com/watch?v=..." required>
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-control form-control-modern form-control-lg" id="maxResults">
                                            <option value="50">50 Yorum</option>
                                            <option value="100" selected>100 Yorum</option>
                                            <option value="200">200 Yorum</option>
                                            <option value="500">500 Yorum</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="submit" class="btn btn-light btn-modern btn-lg w-100 fw-semibold">
                                            <i class="fas fa-download me-2"></i>
                                            Analiz Et
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div id="fetchResult" class="mt-4"></div>
                            <div id="loading" class="loading text-center mt-4">
                                <div class="loading-spinner mx-auto mb-3"></div>
                                <h5>Yorumlar analiz ediliyor...</h5>
                                <p class="mb-0 opacity-75">Bu işlem birkaç saniye sürebilir</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- İstatistikler -->
            <div class="row mb-5" id="statsSection">
                <div class="col-12">
                    <div class="card-modern stats-card">
                        <div class="card-body p-4">
                            <h4 class="section-title text-white mb-4">
                                <i class="fas fa-chart-line"></i>
                                Analiz İstatistikleri
                            </h4>
                            <div id="videoStats" class="row g-3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yorumlar Listesi -->
            <div class="row">
                <div class="col-12">
                    <div class="card-modern">
                        <div class="card-header bg-white border-0 p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="section-title mb-0">
                                    <i class="fas fa-comments text-primary"></i>
                                    Yorum Analizi
                                </h4>
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input" type="checkbox" id="autoSyncToggle" onchange="toggleAutoSync()">
                                        <label class="form-check-label fw-semibold" for="autoSyncToggle">
                                            <i class="fas fa-bolt text-warning me-1"></i>
                                            Anlık Takip
                                        </label>
                                    </div>
                                    <div class="me-3" id="intervalSelector" style="display: none;">
                                        <select class="form-select form-select-sm" id="syncInterval" onchange="updateSyncInterval()" style="width: 120px;">
                                            <option value="5">5 saniye</option>
                                            <option value="10" selected>10 saniye</option>
                                            <option value="15">15 saniye</option>
                                            <option value="30">30 saniye</option>
                                            <option value="60">1 dakika</option>
                                        </select>
                                    </div>
                                    <div id="syncStatus" class="me-3" style="display: none;">
                                        <span class="badge bg-success">
                                            <i class="fas fa-circle pulse me-1"></i>
                                            <span id="syncStatusText">Canlı</span>
                                        </span>
                                    </div>
                                    <button class="btn btn-outline-modern btn-modern" onclick="loadComments(true)" title="Yorumları yenile">
                                        <i class="fas fa-sync-alt me-1" aria-hidden="true"></i>
                                        Yenile
                                    </button>
                                    <button class="btn btn-primary-modern btn-modern" onclick="exportComments()" title="Yorumları CSV olarak indir">
                                        <i class="fas fa-download me-1" aria-hidden="true"></i>
                                        Dışa Aktar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div id="commentsList"></div>
                            <div class="text-center mt-4">
                                <button id="loadMoreBtn" class="btn btn-outline-modern btn-modern" onclick="loadMoreComments()">
                                    <i class="fas fa-chevron-down me-2"></i>
                                    Daha Fazla Yorum Yükle
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentOffset = 0;
        const limit = 20;
        let autoSyncInterval = null;
        let lastSyncTime = null;
        let isAutoSyncEnabled = false;
        let syncIntervalSeconds = 10; // Default 10 seconds
        let currentVideoId = null; // Track current video for sync

        // Sayfa yüklendiğinde
        document.addEventListener('DOMContentLoaded', function() {
            loadComments();
            loadStats();
            lastSyncTime = new Date().toISOString();
        });

        // Form submit
        document.getElementById('fetchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetchComments();
        });

        async function fetchComments() {
            const videoUrl = document.getElementById('videoUrl').value;
            const maxResults = document.getElementById('maxResults').value;
            const loading = document.getElementById('loading');
            const result = document.getElementById('fetchResult');
            
            // Store video URL for later use
            const tempVideoUrl = videoUrl;
            
            loading.style.display = 'block';
            result.innerHTML = '';
            
            try {
                const response = await fetch('api/fetch-comments.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        video_url: videoUrl,
                        max_results: parseInt(maxResults)
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    result.innerHTML = `
                        <div class="alert alert-success alert-modern">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-3 fs-4"></i>
                                <div>
                                    <h6 class="mb-1 fw-semibold">${data.message}</h6>
                                    <div class="small">
                                        <i class="fas fa-video me-1"></i>
                                        <strong>${data.video_info.title}</strong><br>
                                        <i class="fas fa-comments me-1"></i>
                                        Toplam: <span class="fw-semibold">${data.total_comments}</span> yorum, 
                                        Kaydedilen: <span class="fw-semibold">${data.saved_comments}</span> yorum
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    document.getElementById('videoUrl').value = '';
                    
                    // Extract video ID from URL for sync tracking  
                    currentVideoId = extractVideoId(tempVideoUrl);
                    console.log('Current video ID set to:', currentVideoId);
                    
                    loadComments(true);
                    loadStats();
                } else {
                    result.innerHTML = `
                        <div class="alert alert-danger alert-modern">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                                <div>
                                    <h6 class="mb-1 fw-semibold">Hata Oluştu</h6>
                                    <div class="small">${data.error}</div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                result.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Bir hata oluştu: ${error.message}
                    </div>
                `;
            } finally {
                loading.style.display = 'none';
            }
        }

        async function loadComments(reset = false) {
            if (reset) currentOffset = 0;
            
            try {
                const response = await fetch(`api/get-comments.php?limit=${limit}&offset=${currentOffset}`);
                const data = await response.json();
                
                if (data.success) {
                    const commentsList = document.getElementById('commentsList');
                    
                    if (reset) {
                        commentsList.innerHTML = '';
                    }
                    
                    if (data.comments.length === 0 && reset) {
                        commentsList.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-comments"></i>
                                <h5>Henüz yorum bulunmuyor</h5>
                                <p>Yukarıdan bir YouTube video URL'si ekleyerek yorumları analiz etmeye başlayın.</p>
                            </div>
                        `;
                        return;
                    }

                    data.comments.forEach(comment => {
                        const commentHtml = createCommentHtml(comment, false);
                        commentsList.innerHTML += commentHtml;
                    });
                    
                    if (data.comments.length < limit) {
                        document.getElementById('loadMoreBtn').style.display = 'none';
                    } else {
                        document.getElementById('loadMoreBtn').style.display = 'block';
                    }
                }
            } catch (error) {
                console.error('Yorumlar yüklenirken hata:', error);
            }
        }

        function loadMoreComments() {
            currentOffset += limit;
            loadComments();
        }

        async function loadStats() {
            try {
                const response = await fetch('api/get-stats.php');
                const data = await response.json();
                
                if (data.success) {
                    const statsContainer = document.getElementById('videoStats');
                    statsContainer.innerHTML = '';
                    
                    data.videos.forEach(video => {
                        const statHtml = `
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="stat-item">
                                    <div class="stat-number">${video.comment_count}</div>
                                    <div class="fw-semibold mb-2">Toplam Yorum</div>
                                    <p class="mb-0 small opacity-75 text-truncate" title="${video.video_title}">
                                        <i class="fas fa-play-circle me-1"></i>
                                        ${video.video_title}
                                    </p>
                                    <div class="small mt-2 opacity-75">
                                        <i class="fas fa-clock me-1"></i>
                                        ${new Date(video.latest_comment).toLocaleDateString('tr-TR')}
                                    </div>
                                </div>
                            </div>
                        `;
                        statsContainer.innerHTML += statHtml;
                    });
                }
            } catch (error) {
                console.error('İstatistikler yüklenirken hata:', error);
            }
        }

        // Export comments functionality
        function exportComments() {
            const comments = document.querySelectorAll('.comment-card');
            if (comments.length === 0) {
                alert('Dışa aktarılacak yorum bulunmuyor!');
                return;
            }

            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Yazar,Video,Yorum,Beğeni,Tarih\n";
            
            comments.forEach(card => {
                const author = card.querySelector('.author-name').textContent;
                const video = card.querySelector('.video-title').textContent.replace('', '').trim();
                const comment = card.querySelector('.comment-text').textContent.replace(/"/g, '""');
                const likes = card.querySelector('.badge-modern').textContent.replace('', '').trim();
                const date = card.querySelector('.comment-meta').textContent.replace('', '').trim();
                
                csvContent += `"${author}","${video}","${comment}","${likes}","${date}"\n`;
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `youtube-yorumlar-${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Smooth scroll to top
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Add scroll to top button
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                if (!document.getElementById('scrollTopBtn')) {
                    const scrollBtn = document.createElement('button');
                    scrollBtn.id = 'scrollTopBtn';
                    scrollBtn.className = 'btn btn-primary-modern btn-modern position-fixed';
                    scrollBtn.style.cssText = 'bottom: 2rem; right: 2rem; z-index: 1000; border-radius: 50%; width: 50px; height: 50px;';
                    scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
                    scrollBtn.onclick = scrollToTop;
                    document.body.appendChild(scrollBtn);
                }
            } else {
                const scrollBtn = document.getElementById('scrollTopBtn');
                if (scrollBtn) {
                    scrollBtn.remove();
                }
            }
        });

        // Auto-sync toggle function
        function toggleAutoSync() {
            const toggle = document.getElementById('autoSyncToggle');
            const status = document.getElementById('syncStatus');
            const intervalSelector = document.getElementById('intervalSelector');
            
            isAutoSyncEnabled = toggle.checked;
            
            if (isAutoSyncEnabled) {
                status.style.display = 'block';
                intervalSelector.style.display = 'block';
                updateSyncStatusText();
                startAutoSync();
                showNotification(`Anlık takip başlatıldı! (${syncIntervalSeconds} saniye aralık)`, 'success');
            } else {
                status.style.display = 'none';
                intervalSelector.style.display = 'none';
                stopAutoSync();
                showNotification('Anlık takip durduruldu!', 'info');
            }
        }

        // Update sync interval
        function updateSyncInterval() {
            const select = document.getElementById('syncInterval');
            syncIntervalSeconds = parseInt(select.value);
            
            if (isAutoSyncEnabled) {
                // Restart with new interval
                stopAutoSync();
                updateSyncStatusText();
                startAutoSync();
                showNotification(`Takip aralığı ${syncIntervalSeconds} saniye olarak güncellendi!`, 'info');
            }
        }

        // Start auto-sync
        function startAutoSync() {
            if (autoSyncInterval) {
                clearInterval(autoSyncInterval);
            }
            
            // Seçilen aralıkta kontrol et
            autoSyncInterval = setInterval(async () => {
                await checkForNewComments();
            }, syncIntervalSeconds * 1000); // Convert to milliseconds
            
            // İlk kontrolü hemen yap
            checkForNewComments();
            
            console.log(`Auto-sync started with ${syncIntervalSeconds} seconds interval`);
        }

        // Stop auto-sync
        function stopAutoSync() {
            if (autoSyncInterval) {
                clearInterval(autoSyncInterval);
                autoSyncInterval = null;
            }
        }

        // Check for new comments with full sync
        async function checkForNewComments() {
            if (!isAutoSyncEnabled) return;
            
            try {
                // Build URL with video ID if available
                let url = `api/get-latest-comments.php?last_check=${encodeURIComponent(lastSyncTime)}&limit=50`;
                if (currentVideoId) {
                    url += `&video_id=${currentVideoId}`;
                }
                
                console.log('Sync URL:', url);
                console.log('Last sync time:', lastSyncTime);
                console.log('Current video ID:', currentVideoId);
                
                const response = await fetch(url);
                const data = await response.json();
                
                console.log('Sync response:', data);
                
                if (data.success) {
                    console.log('Processing sync data...');
                    const commentsList = document.getElementById('commentsList');
                    let changesMade = false;
                    
                    // Eğer herhangi bir değişiklik varsa tam yenileme yap
                    if (data.total_changes > 0) {
                        console.log('Changes detected, refreshing comments...');
                        
                        // Tüm listeyi temizle
                        commentsList.innerHTML = '';
                        
                        // Güncel yorumları yükle
                        if (currentVideoId) {
                            await refreshCurrentComments();
                        } else {
                            // Video ID yoksa genel yorumları yükle
                            await loadComments(true);
                        }
                        
                        changesMade = true;
                        
                        // Show notification
                        let message = '';
                        if (data.new_comments && data.new_comments.length > 0) {
                            message += `${data.new_comments.length} yeni yorum`;
                        }
                        if (data.updated_comments && data.updated_comments.length > 0) {
                            if (message) message += ', ';
                            message += `${data.updated_comments.length} güncelleme`;
                        }
                        if (data.deleted_comment_ids && data.deleted_comment_ids.length > 0) {
                            if (message) message += ', ';
                            message += `${data.deleted_comment_ids.length} silinen yorum`;
                        }
                        
                        if (message) {
                            showNotification(`Senkronize edildi: ${message}`, 'success');
                        }
                        
                        // Update stats
                        loadStats();
                    } else {
                        console.log('No changes detected');
                    }
                    
                    // Update last sync time
                    lastSyncTime = data.current_time;
                }
                
            } catch (error) {
                console.error('Auto-sync error:', error);
                showNotification('Senkronizasyon hatası!', 'error');
            }
        }

        // Create comment HTML
        function createCommentHtml(comment, isNew = false) {
            const newClass = isNew ? 'new-comment' : '';
            const newBadge = isNew ? '<div class="badge bg-success mt-1"><i class="fas fa-star me-1"></i>YENİ</div>' : '';
            
            return `
                <div class="comment-card ${newClass}" data-comment-id="${comment.comment_id}">
                    <div class="card-body p-4">
                        <div class="d-flex">
                            <div class="author-avatar me-3">
                                ${comment.author_name.charAt(0).toUpperCase()}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="author-name mb-1">${comment.author_name}</h6>
                                        <div class="video-title small mb-1">
                                            <i class="fas fa-video me-1 text-primary"></i>
                                            ${comment.video_title}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge-modern">
                                            <i class="fas fa-thumbs-up me-1"></i>
                                            ${comment.like_count}
                                        </span>
                                        <div class="comment-meta mt-1">
                                            <i class="fas fa-clock me-1"></i>
                                            ${new Date(comment.published_at).toLocaleDateString('tr-TR', {
                                                year: 'numeric',
                                                month: 'short',
                                                day: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            })}
                                        </div>
                                        ${newBadge}
                                    </div>
                                </div>
                                <div class="comment-text">${comment.comment_text}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Eski bildirimi kaldır
            const existingToast = document.querySelector('.notification-toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            const toast = document.createElement('div');
            toast.className = `notification-toast alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                    <div>${message}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // 5 saniye sonra otomatik kaldır
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 5000);
        }

        // Background sync for active videos
        async function backgroundSync() {
            if (!isAutoSyncEnabled) return;
            
            try {
                const response = await fetch('api/auto-sync.php');
                const data = await response.json();
                
                if (data.success && data.total_new_comments > 0) {
                    console.log(`Background sync: ${data.total_new_comments} new comments found`);
                }
            } catch (error) {
                console.error('Background sync error:', error);
            }
        }

        // Update sync status text
        function updateSyncStatusText() {
            const statusText = document.getElementById('syncStatusText');
            if (statusText) {
                statusText.textContent = `Canlı (${syncIntervalSeconds}s)`;
            }
        }

        // Refresh current comments from database
        async function refreshCurrentComments() {
            try {
                const response = await fetch(`api/get-comments.php?video_id=${currentVideoId}&limit=100`);
                const data = await response.json();
                
                if (data.success && data.comments.length > 0) {
                    const commentsList = document.getElementById('commentsList');
                    
                    data.comments.forEach(comment => {
                        const commentHtml = createCommentHtml(comment, false);
                        commentsList.innerHTML += commentHtml;
                    });
                }
            } catch (error) {
                console.error('Error refreshing comments:', error);
            }
        }

        // Extract video ID from YouTube URL
        function extractVideoId(url) {
            const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
            const match = url.match(regex);
            return match ? match[1] : null;
        }

        // Start background sync every 5 minutes
        setInterval(backgroundSync, 300000); // 5 minutes
    </script>
</body>
</html>
