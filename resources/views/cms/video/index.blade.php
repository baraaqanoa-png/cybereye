<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>مشغل الفيديوهات | CyberEye</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/sweetalert2@11"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #0a0c10 0%, #0d1117 100%);
            color: #e0e0e0;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(90deg, rgba(155, 89, 182, 0.03) 1px, transparent 1px),
                linear-gradient(0deg, rgba(155, 89, 182, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .player-container {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 1;
            gap: 0;
        }

        .sidebar {
            width: 380px;
            background: linear-gradient(135deg, #0d111a 0%, #0a0e17 100%);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(155, 89, 182, 0.15);
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            box-shadow: -5px 0 40px rgba(0, 0, 0, 0.3);
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(155, 89, 182, 0.15);
            background: linear-gradient(135deg, rgba(26, 20, 40, 0.5) 0%, rgba(20, 15, 35, 0.5) 100%);
        }

        .sidebar-header h2 {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #a855f7, #c084fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .videos-list {
            flex: 1;
            padding: 15px;
        }

        .video-item {
            display: flex;
            gap: 12px;
            padding: 12px;
            margin-bottom: 12px;
            background: rgba(20, 25, 45, 0.6);
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(155, 89, 182, 0.1);
            position: relative;
            backdrop-filter: blur(5px);
        }

        .video-item:hover {
            background: rgba(155, 89, 182, 0.08);
            border-color: rgba(155, 89, 182, 0.4);
            transform: translateX(-5px);
        }

        .video-item.active {
            background: linear-gradient(135deg, rgba(155, 89, 182, 0.15), rgba(168, 85, 247, 0.08));
            border-left: 3px solid #a855f7;
            border-color: #a855f7;
        }

        .video-thumb {
            width: 100px;
            height: 70px;
            background: linear-gradient(135deg, #1a1f2e, #0f1420);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a855f7;
            font-size: 1.8rem;
            border: 1px solid rgba(168, 85, 247, 0.2);
        }

        .video-info {
            flex: 1;
        }

        .video-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: #e0e0e0;
        }

        .video-duration {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .video-item-actions {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 5px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .video-item:hover .video-item-actions {
            opacity: 1;
        }

        .icon-btn {
            background: rgba(30, 35, 50, 0.9);
            border: 1px solid rgba(168, 85, 247, 0.3);
            color: #a855f7;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-btn:hover {
            background: #a855f7;
            color: #0a0c10;
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.5);
        }

        .icon-btn.delete:hover {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
        }

        .main-content {
            flex: 1;
            padding: 30px 40px;
        }

        .hero-header {
            background: linear-gradient(135deg, rgba(20, 25, 45, 0.8), rgba(15, 20, 35, 0.8));
            border: 1px solid rgba(155, 89, 182, 0.15);
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }

        .hero-header h1 {
            font-size: 1.8rem;
            background: linear-gradient(135deg, #a855f7, #c084fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stats-cards {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .stat-card {
            background: rgba(168, 85, 247, 0.08);
            border: 1px solid rgba(168, 85, 247, 0.15);
            padding: 12px 25px;
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            border-color: rgba(168, 85, 247, 0.4);
            transform: translateY(-2px);
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #a855f7, #c084fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* ========== مشغل الفيديو (يدعم فيديو محلي ويوتيوب) ========== */
        .video-wrapper {
            background: #000;
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 25px;
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            height: 0;
            border: 1px solid rgba(168, 85, 247, 0.2);
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
        }

        .video-wrapper video,
        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            border: none;
        }

        .video-details {
            background: rgba(20, 25, 45, 0.8);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 20px;
            border: 1px solid rgba(155, 89, 182, 0.15);
        }

        .video-details h1 {
            font-size: 1.5rem;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #a855f7, #c084fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .video-description {
            color: #aaa;
            line-height: 1.6;
            margin-top: 10px;
        }

        .admin-btn {
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .admin-btn-add {
            background: linear-gradient(135deg, #a855f7, #7e22ce);
            color: white;
            box-shadow: 0 4px 15px rgba(168, 85, 247, 0.3);
        }

        .admin-btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(168, 85, 247, 0.4);
        }

        .details-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(155, 89, 182, 0.15);
            flex-wrap: wrap;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #a855f7;
            text-decoration: none;
            transition: all 0.3s;
        }

        .back-link:hover {
            text-shadow: 0 0 10px rgba(168, 85, 247, 0.5);
            transform: translateX(-5px);
        }

        /* ========== مودال محسن مع دعم يوتيوب ========== */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(20px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: linear-gradient(135deg, #0f1420, #0a0e18);
            border-radius: 32px;
            padding: 0;
            max-width: 550px;
            width: 90%;
            border: 1px solid rgba(168, 85, 247, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 30px;
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(126, 34, 206, 0.05));
            border-bottom: 1px solid rgba(168, 85, 247, 0.1);
        }

        .modal-header h3 {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #a855f7, #c084fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-modal {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #9ca3af;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-modal:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: #ef4444;
            color: #ef4444;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #a855f7;
            font-size: 0.85rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 14px 18px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(168, 85, 247, 0.2);
            border-radius: 16px;
            color: #e0e0e0;
            font-family: 'Cairo', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
        }

        .form-group input[type="file"] {
            padding: 12px;
            background: rgba(0, 0, 0, 0.3);
        }

        .form-group small {
            display: block;
            margin-top: 8px;
            color: #6b7280;
            font-size: 0.7rem;
        }

        /* خيارات نوع الفيديو */
        .video-type-options {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .video-type-btn {
            flex: 1;
            padding: 12px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(168, 85, 247, 0.2);
            border-radius: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            color: #9ca3af;
        }

        .video-type-btn.active {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(126, 34, 206, 0.1));
            border-color: #a855f7;
            color: #a855f7;
        }

        .video-type-btn:hover {
            border-color: #a855f7;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-save {
            flex: 1;
            background: linear-gradient(135deg, #a855f7, #7e22ce);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(168, 85, 247, 0.4);
        }

        .btn-cancel {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            color: #9ca3af;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 14px;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            background: rgba(239, 68, 68, 0.15);
            border-color: #ef4444;
            color: #ef4444;
        }

        @media (max-width: 800px) {
            .player-container { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: relative; }
            .videos-list { display: flex; overflow-x: auto; gap: 12px; padding: 15px; }
            .video-item { min-width: 280px; margin-bottom: 0; }
            .main-content { padding: 20px; }
            .hero-header { padding: 20px; }
            .stats-cards { flex-wrap: wrap; }
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0a0c10; }
        ::-webkit-scrollbar-thumb { background: #a855f7; border-radius: 4px; }
    </style>
</head>

<body>
    <div class="player-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-list"></i> قائمة التشغيل</h2>
                <p style="color: #6b7280; margin-top: 8px;">{{ isset($videos) ? $videos->count() : 0 }} فيديو</p>
                <div class="admin-actions-header" style="display: flex; gap: 10px; margin-top: 15px;">
                    <input type="hidden" name="course_id" value="{{ $courseId }}">
                    <button class="admin-btn admin-btn-add" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> إضافة فيديو
                    </button>
                    <a class="admin-btn admin-btn-add" href="{{ route('materials.index', ['course_id' => $courseId]) }}">
                        <i class="fas fa-book-open"></i> الدروس
                    </a>
                    <a class="admin-btn admin-btn-add" href="{{ route('quizzs.index') }}">
                        <i class="fas fa-question-circle"></i> اختبار
                    </a>
                </div>
            </div>
            <div class="videos-list" id="videosList">
                @forelse ($videos ?? [] as $video)
                    <div class="video-item" data-id="{{ $video->id }}" data-title="{{ $video->title }}"
                        data-description="{{ $video->description }}" data-url="{{ asset($video->url) }}"
                        data-youtube="{{ $video->youtube_url }}" data-duration="{{ $video->duration }}">
                        <div class="video-thumb"><i class="fas fa-play-circle"></i></div>
                        <div class="video-info">
                            <div class="video-title">{{ $video->title }}</div>
                            <div class="video-duration">
                                @if(($video->duration ?? 0) > 0)
                                    {{ floor($video->duration / 60) }}:{{ str_pad($video->duration % 60, 2, '0', STR_PAD_LEFT) }}
                                @else
                                    المدة غير محددة
                                @endif
                            </div>
                        </div>
                        <div class="video-item-actions">
                            <button class="icon-btn edit" onclick="event.stopPropagation(); openEditModal({{ $video->id }}, '{{ addslashes($video->title) }}', '{{ addslashes($video->description) }}', {{ $video->duration ?? 0 }}, '{{ addslashes($video->youtube_url) }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="icon-btn delete" onclick="event.stopPropagation(); deleteVideo({{ $video->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="video-item" style="justify-content: center; opacity: 0.7;">
                        <p style="text-align: center;">لا توجد فيديوهات حالياً</p>
                    </div>
                @endforelse
            </div>
        </aside>

        <main class="main-content">
            <a href="{{ url('/') }}" class="back-link"><i class="fas fa-arrow-right"></i> العودة للرئيسية</a>
            
            <div class="hero-header">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <h1><i class="fas fa-video"></i> مشغل الفيديوهات التعليمية</h1>
                        <p style="color: #6b7280; margin-top: 10px;">استمتع بمشاهدة الفيديوهات وتعلم الأمن السيبراني</p>
                    </div>
                    <div class="stats-cards">
                        <div class="stat-card">
                            <div class="stat-number">{{ isset($videos) ? $videos->count() : 0 }}</div>
                            <div style="font-size: 0.7rem; color: #6b7280;">فيديو</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ isset($videos) ? number_format($videos->sum('duration') / 60, 0) : 0 }}</div>
                            <div style="font-size: 0.7rem; color: #6b7280;">دقيقة</div>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($videos) && $videos->isNotEmpty())
                <div id="videoPlayerArea">
                    <div class="video-wrapper" id="videoWrapper">
                        @php $firstVideo = $videos->first(); @endphp
                        @if($firstVideo->youtube_url)
                            <iframe id="mainVideoFrame" src="{{ $firstVideo->youtube_url }}" frameborder="0" allowfullscreen></iframe>
                        @else
                            <video id="mainVideo" controls>
                                <source src="{{ asset($firstVideo->url) }}" type="video/mp4">
                            </video>
                        @endif
                    </div>
                    <div class="video-details">
                        <h1 id="videoTitle">{{ $firstVideo->title }}</h1>
                        <div id="videoDescription" class="video-description">{{ $firstVideo->description ?? 'لا يوجد وصف' }}</div>
                        <div class="details-actions">
                            <button class="admin-btn admin-btn-add" id="editCurrentBtn" onclick="openEditModal({{ $firstVideo->id }}, '{{ addslashes($firstVideo->title) }}', '{{ addslashes($firstVideo->description) }}', {{ $firstVideo->duration ?? 0 }}, '{{ addslashes($firstVideo->youtube_url) }}')">
                                <i class="fas fa-edit"></i> تعديل
                            </button>
                            <button class="admin-btn admin-btn-add" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة فيديو</button>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-player" style="background: rgba(20, 25, 45, 0.8); border: 1px solid rgba(168, 85, 247, 0.2); border-radius: 24px; padding: 60px; text-align: center;">
                    <i class="fas fa-video-slash" style="font-size: 4rem; color: #a855f7; margin-bottom: 20px; opacity: 0.5;"></i>
                    <h3>لا توجد فيديوهات حالياً</h3>
                    <p style="color: #6b7280; margin: 10px 0;">أضف فيديو جديد لبدء المشاهدة</p>
                    <button class="admin-btn admin-btn-add" style="margin-top: 20px;" onclick="openAddModal()"><i class="fas fa-plus"></i> أضف أول فيديو</button>
                </div>
            @endif
        </main>
    </div>

    <!-- مودال إضافة/تعديل فيديو مع دعم يوتيوب -->
    <div id="videoModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-video"></i> <span id="modalTitle">إضافة فيديو جديد</span></h3>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="videoForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="videoId" name="videoId">
                    <input type="hidden" name="course_id" value="{{ $courseId }}">
                    
                    <div class="form-group">
                        <label><i class="fas fa-heading"></i> عنوان الفيديو *</label>
                        <input type="text" id="title" name="title" placeholder="أدخل عنوان الفيديو" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> وصف الفيديو</label>
                        <textarea id="description" name="description" rows="3" placeholder="أدخل وصف الفيديو..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-clock"></i> المدة (بالثواني)</label>
                        <input type="number" id="duration" name="duration" placeholder="مثال: 3600">
                    </div>

                    <!-- خيارات نوع الفيديو -->
                    <div class="form-group">
                        <label><i class="fas fa-globe"></i> نوع الفيديو</label>
                        <div class="video-type-options">
                            <div class="video-type-btn active" data-type="upload" onclick="selectVideoType('upload')">
                                <i class="fas fa-cloud-upload-alt"></i> رفع ملف
                            </div>
                            <div class="video-type-btn" data-type="youtube" onclick="selectVideoType('youtube')">
                                <i class="fab fa-youtube"></i> رابط يوتيوب
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group" id="uploadGroup">
                        <label><i class="fas fa-cloud-upload-alt"></i> رفع الفيديو</label>
                        <input type="file" id="videoFile" name="videoFile" accept="video/*">
                        <small>MP4, MKV, AVI, MOV (الحد الأقصى 100MB)</small>
                    </div>
                    
                    <div class="form-group" id="youtubeGroup" style="display: none;">
                        <label><i class="fab fa-youtube"></i> رابط يوتيوب</label>
                        <input type="text" id="youtubeUrl" name="youtube_url" placeholder="https://www.youtube.com/watch?v=...">
                        <small>أدخل رابط الفيديو من YouTube</small>
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" onclick="closeModal()">إلغاء</button>
                        <button type="submit" class="btn-save">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentVideoId = {{ isset($videos) && $videos->first() ? $videos->first()->id : 0 }};
        let isEditMode = false;
        let videoCompletionRecorded = false;
        let currentVideoType = 'upload';

        const video = document.getElementById('mainVideo');
        const videoFrame = document.getElementById('mainVideoFrame');

        // دالة لتبديل نوع الفيديو في المودال
        function selectVideoType(type) {
            currentVideoType = type;
            document.querySelectorAll('.video-type-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.video-type-btn[data-type="${type}"]`).classList.add('active');
            
            if (type === 'upload') {
                document.getElementById('uploadGroup').style.display = 'block';
                document.getElementById('youtubeGroup').style.display = 'none';
                document.getElementById('youtubeUrl').value = '';
            } else {
                document.getElementById('uploadGroup').style.display = 'none';
                document.getElementById('youtubeGroup').style.display = 'block';
                document.getElementById('videoFile').value = '';
            }
        }

        // دالة تحميل الفيديو (يدعم فيديو محلي ويوتيوب)
        function loadVideo(videoId, title, description, url, youtubeUrl, duration) {
            const videoEl = document.getElementById('mainVideo');
            const iframeEl = document.getElementById('mainVideoFrame');
            const wrapper = document.getElementById('videoWrapper');
            
            if (!videoEl && !iframeEl) return;
            
            if (currentVideoId) {
                if (videoEl && !videoEl.paused) {
                    localStorage.setItem(`video_time_${currentVideoId}`, videoEl.currentTime);
                }
            }
            
            currentVideoId = videoId;
            videoCompletionRecorded = false;
            document.getElementById('videoTitle').innerText = title;
            document.getElementById('videoDescription').innerHTML = description || 'لا يوجد وصف';
            
            // إعادة بناء المشغل حسب نوع الفيديو
            if (youtubeUrl && youtubeUrl !== 'null' && youtubeUrl !== '') {
                // عرض فيديو يوتيوب عبر iframe
                wrapper.innerHTML = `<iframe id="mainVideoFrame" src="${youtubeUrl}" frameborder="0" allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"></iframe>`;
            } else if (url && url !== 'null' && url !== '') {
                // عرض فيديو محلي
                wrapper.innerHTML = `<video id="mainVideo" controls style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;"><source src="${url}" type="video/mp4"></video>`;
                const newVideo = document.getElementById('mainVideo');
                if (newVideo) {
                    const saved = localStorage.getItem(`video_time_${videoId}`);
                    if (saved) newVideo.currentTime = parseFloat(saved);
                    newVideo.addEventListener('timeupdate', () => {
                        if (currentVideoId) localStorage.setItem(`video_time_${currentVideoId}`, newVideo.currentTime);
                    });
                    newVideo.addEventListener('ended', () => {
                        if (!videoCompletionRecorded && currentVideoId) markVideoCompleted(currentVideoId);
                        videoCompletionRecorded = true;
                    });
                }
            } else {
                wrapper.innerHTML = '<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #6b7280;">لا يمكن تحميل الفيديو</div>';
            }
            
            document.getElementById('editCurrentBtn').onclick = () => openEditModal(videoId, title, description, duration, youtubeUrl);
            
            document.querySelectorAll('.video-item').forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('data-id') == videoId) item.classList.add('active');
            });
        }

        // ربط أحداث النقر على عناصر الفيديو
        document.querySelectorAll('.video-item').forEach(item => {
            item.addEventListener('click', function() { 
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                const description = this.getAttribute('data-description');
                const url = this.getAttribute('data-url');
                const youtubeUrl = this.getAttribute('data-youtube');
                const duration = this.getAttribute('data-duration');
                loadVideo(id, title, description, url, youtubeUrl, duration);
            });
        });

        // دالة إضافة فيديو جديد
        function openAddModal() { 
            isEditMode = false; 
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle"></i> إضافة فيديو جديد'; 
            document.getElementById('videoForm').reset(); 
            document.getElementById('videoId').value = ''; 
            document.getElementById('duration').value = ''; 
            selectVideoType('upload');
            document.getElementById('videoModal').style.display = 'flex'; 
        }
        
        // دالة تعديل فيديو
        function openEditModal(id, title, description, duration, youtubeUrl) { 
            isEditMode = true; 
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> تعديل الفيديو'; 
            document.getElementById('videoId').value = id; 
            document.getElementById('title').value = title; 
            document.getElementById('description').value = description; 
            document.getElementById('duration').value = duration || 0; 
            
            if (youtubeUrl && youtubeUrl !== 'null' && youtubeUrl !== '') {
                selectVideoType('youtube');
                document.getElementById('youtubeUrl').value = youtubeUrl;
            } else {
                selectVideoType('upload');
                document.getElementById('videoFile').value = '';
            }
            document.getElementById('videoModal').style.display = 'flex'; 
        }
        
        function closeModal() { document.getElementById('videoModal').style.display = 'none'; }

        // إرسال النموذج
        document.getElementById('videoForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const videoId = document.getElementById('videoId').value;
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            let duration = document.getElementById('duration').value || 0;
            const videoFile = document.getElementById('videoFile').files[0];
            const youtubeUrl = document.getElementById('youtubeUrl').value;
            
            if (!title) return Swal.fire('خطأ', 'الرجاء إدخال عنوان الفيديو', 'error');
            
            if (currentVideoType === 'upload' && !videoFile && !isEditMode) {
                return Swal.fire('خطأ', 'الرجاء اختيار ملف فيديو', 'error');
            }
            if (currentVideoType === 'youtube' && !youtubeUrl && !isEditMode) {
                return Swal.fire('خطأ', 'الرجاء إدخال رابط يوتيوب', 'error');
            }
            if (currentVideoType === 'youtube' && youtubeUrl && !isValidYoutubeUrl(youtubeUrl)) {
                return Swal.fire('خطأ', 'الرجاء إدخال رابط يوتيوب صحيح', 'error');
            }

            let url = '{{ route('videos.store') }}', method = 'POST', formData = new FormData();
            if (isEditMode && videoId) { url = `/cms/admin/videos/${videoId}`; method = 'POST'; formData.append('_method', 'PUT'); }
            formData.append('title', title);
            formData.append('description', description);
            formData.append('duration', duration);
            formData.append('course_id', document.querySelectorAll('[name="course_id"]')[0]?.value || {{ $courseId ?? 0 }});
            
            if (currentVideoType === 'upload' && videoFile) {
                formData.append('url', videoFile);
            }
            if (currentVideoType === 'youtube' && youtubeUrl) {
                formData.append('youtube_url', youtubeUrl);
            }
            
            try {
                const response = await axios({ method, url, data: formData, headers: { 'Content-Type': 'multipart/form-data' } });
                if (response.status === 200 || response.status === 201) {
                    Swal.fire('نجاح', isEditMode ? 'تم تحديث الفيديو بنجاح' : 'تم إضافة الفيديو بنجاح', 'success')
                        .then(() => window.location.reload());
                }
            } catch (error) { 
                Swal.fire('خطأ', error.response?.data?.message || 'حدث خطأ أثناء الحفظ', 'error'); 
            }
        });

        // التحقق من صحة رابط يوتيوب
        function isValidYoutubeUrl(url) {
            const pattern = /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/;
            return pattern.test(url);
        }

        async function markVideoCompleted(videoId) {
            try {
                const response = await axios.post('/cms/student/video-completed', { video_id: videoId, course_id: {{ $courseId ?? 0 }} });
                if (response.data.course_completed) {
                    Swal.fire({ title: '🎉 مبروك!', text: 'لقد أكملت جميع فيديوهات الكورس! يمكنك الآن الحصول على شهادتك', icon: 'success', confirmButtonText: 'عرض الشهادة' })
                        .then(() => { window.location.href = response.data.certificate_url; });
                } else if (response.data.success && response.data.progress) {
                    const progressFill = document.querySelector('.progress-bar-fill');
                    if (progressFill) progressFill.style.width = response.data.progress.percentage + '%';
                }
            } catch (error) { console.error('Error:', error); }
        }

        async function deleteVideo(id) {
            const result = await Swal.fire({ title: 'هل أنت متأكد؟', text: 'لن تتمكن من استعادة هذا الفيديو!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'نعم، احذف', cancelButtonText: 'إلغاء' });
            if (result.isConfirmed) try { await axios.delete(`/cms/admin/videos/${id}`); Swal.fire('تم الحذف', 'تم حذف الفيديو بنجاح', 'success').then(() => window.location.reload()); } catch (error) { Swal.fire('خطأ', 'حدث خطأ أثناء الحذف', 'error'); }
        }

        window.onclick = function(event) { const modal = document.getElementById('videoModal'); if (event.target === modal) closeModal(); };
        
        // تأثير hover على أزرار الفيديو
        document.querySelectorAll('.video-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                const actions = this.querySelector('.video-item-actions');
                if (actions) actions.style.opacity = '1';
            });
            item.addEventListener('mouseleave', function() {
                const actions = this.querySelector('.video-item-actions');
                if (actions) actions.style.opacity = '0';
            });
        });
    </script>
</body>
</html>