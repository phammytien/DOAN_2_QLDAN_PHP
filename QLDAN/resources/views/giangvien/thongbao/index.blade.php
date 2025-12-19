{{-- @extends('layouts.giangvien')

@section('title', 'Thông báo')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">📢 Thông báo mới nhất</h3>

    @forelse($thongbao as $tb)
        <div class="alert alert-info mb-3">
            <strong>{{ $tb->NoiDung }}</strong><br>

            <small>
                Đăng lúc: {{ \Carbon\Carbon::parse($tb->TGDang)->format('d/m/Y H:i') }}
            </small> --}}
{{-- 
            Hiển thị file đính kèm nếu có
            @if($tb->TenFile)
                <br>
                <a href="{{ asset('storage/uploads/thongbao/' . $tb->TenFile) }}" 
                   target="_blank">
                   📎 Tải tệp đính kèm
                </a>
            @endif
        </div>
    @empty
        <div class="alert alert-secondary">
            Không có thông báo nào.
        </div>
    @endforelse
</div>
@endsection --}}


@extends('layouts.giangvien')

@section('title', 'Thông báo')

@section('content')

<style>
    /* ===== VARIABLES ===== */
    :root {
        --primary-blue: #0d6efd;
        --light-blue: #e7f1ff;
        --dark-blue: #0a58ca;
        --accent-blue: #57c4ff;
        --info-blue: #0dcaf0;
        --border-color: #d9e2ff;
    }

    /* ===== GENERAL ===== */
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e8f0fe 100%);
        min-height: 100vh;
    }

    .container {
        max-width: 900px;
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
        color: white;
        padding: 30px 35px;
        border-radius: 20px;
        margin-bottom: 35px;
        box-shadow: 0 8px 25px rgba(13, 110, 253, 0.25);
        animation: slideDown 0.6s ease;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .page-header h3 {
        margin: 0;
        font-weight: 800;
        font-size: 28px;
        display: flex;
        align-items: center;
        gap: 15px;
        position: relative;
        z-index: 1;
    }

    .notification-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        font-size: 28px;
        animation: bellRing 2s ease-in-out infinite;
    }

    @keyframes bellRing {
        0%, 100% { transform: rotate(0deg); }
        10%, 30% { transform: rotate(-10deg); }
        20%, 40% { transform: rotate(10deg); }
        50% { transform: rotate(0deg); }
    }

    /* ===== NOTIFICATION CARDS ===== */
    .notification-card {
        background: white;
        border: none;
        border-left: 5px solid var(--info-blue);
        border-radius: 15px;
        padding: 25px 30px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        animation: fadeInUp 0.5s ease;
        position: relative;
        overflow: hidden;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .notification-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, var(--info-blue) 0%, var(--primary-blue) 100%);
        transition: width 0.4s ease;
    }

    .notification-card:hover {
        transform: translateX(8px) translateY(-4px);
        box-shadow: 0 12px 35px rgba(13, 110, 253, 0.2);
        border-left-color: var(--primary-blue);
    }

    .notification-card:hover::before {
        width: 8px;
    }

    /* ===== NOTIFICATION CONTENT ===== */
    .notification-content {
        display: flex;
        align-items: flex-start;
        gap: 20px;
    }

    .notification-icon-circle {
        flex-shrink: 0;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--info-blue) 0%, var(--primary-blue) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(13, 202, 240, 0.3);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 4px 12px rgba(13, 202, 240, 0.3);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(13, 202, 240, 0.5);
        }
    }

    .notification-body {
        flex: 1;
    }

    .notification-text {
        color: #2c3e50;
        font-size: 16px;
        font-weight: 600;
        line-height: 1.6;
        margin-bottom: 12px;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .time-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--light-blue);
        color: var(--dark-blue);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .time-badge i {
        font-size: 14px;
    }

    /* ===== ATTACHMENT BUTTON ===== */
    .attachment-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
        color: white;
        padding: 10px 18px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    .attachment-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
        color: white;
        background: linear-gradient(135deg, var(--dark-blue) 0%, #084298 100%);
    }

    .attachment-link i {
        font-size: 16px;
        animation: paperclip 1s ease-in-out infinite;
    }

    @keyframes paperclip {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-10deg); }
        75% { transform: rotate(10deg); }
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        background: white;
        border: 2px dashed var(--border-color);
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
        animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .empty-icon {
        font-size: 80px;
        color: var(--info-blue);
        margin-bottom: 20px;
        animation: sway 3s ease-in-out infinite;
    }

    @keyframes sway {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(10px); }
    }

    .empty-text {
        color: #6c757d;
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    /* ===== BADGES ===== */
    .new-badge {
        display: inline-block;
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        animation: blink 2s ease-in-out infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    /* ===== SCROLLBAR ===== */
    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--dark-blue);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .page-header {
            padding: 25px 20px;
        }

        .page-header h3 {
            font-size: 22px;
        }

        .notification-card {
            padding: 20px;
        }

        .notification-content {
            flex-direction: column;
            gap: 15px;
        }

        .notification-icon-circle {
            width: 45px;
            height: 45px;
            font-size: 20px;
        }

        .empty-state {
            padding: 40px 20px;
        }

        .empty-icon {
            font-size: 60px;
        }
    }

    /* ===== STAGGER ANIMATION ===== */
    .notification-card:nth-child(1) { animation-delay: 0.1s; }
    .notification-card:nth-child(2) { animation-delay: 0.2s; }
    .notification-card:nth-child(3) { animation-delay: 0.3s; }
    .notification-card:nth-child(4) { animation-delay: 0.4s; }
    .notification-card:nth-child(5) { animation-delay: 0.5s; }
</style>

<div class="container mt-4">
    <!-- Page Header -->
    <div class="page-header">
        <h3>
            <span class="notification-icon">🔔</span>
            Thông báo mới nhất
        </h3>
    </div>

    @forelse($thongbao as $tb)
        <div class="notification-card">
            <div class="notification-content">
                <div class="notification-icon-circle">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                
                <div class="notification-body">
                    <div class="notification-text">
                        {{ $tb->NoiDung }}
                    </div>

                    <div class="notification-meta">
                        <span class="time-badge">
                            <i class="bi bi-clock-fill"></i>
                            {{ \Carbon\Carbon::parse($tb->TGDang)->format('d/m/Y H:i') }}
                        </span>

                        {{-- Hiển thị file đính kèm nếu có --}}
                        @if($tb->TenFile)
                            <a href="{{ asset('storage/uploads/thongbao/' . $tb->TenFile) }}" 
                               target="_blank"
                               class="attachment-link">
                                <i class="bi bi-paperclip"></i>
                                Tải tệp đính kèm
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <p class="empty-text">Không có thông báo nào.</p>
        </div>
    @endforelse
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add entrance animation to cards
        const cards = document.querySelectorAll('.notification-card');
        
        // Intersection Observer for scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        cards.forEach((card, index) => {
            observer.observe(card);
        });

        // Add ripple effect to attachment links
        document.querySelectorAll('.attachment-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                ripple.style.width = ripple.style.height = '100px';
                ripple.style.left = e.clientX - this.offsetLeft - 50 + 'px';
                ripple.style.top = e.clientY - this.offsetTop - 50 + 'px';
                ripple.style.animation = 'ripple-effect 0.6s ease-out';
                
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Auto mark as read animation
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.borderLeftColor = 'var(--primary-blue)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.borderLeftColor = 'var(--info-blue)';
            });
        });

        // Smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });

    // Add ripple animation style
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple-effect {
            from {
                transform: scale(0);
                opacity: 1;
            }
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
</script>

@endsection