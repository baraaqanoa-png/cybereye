<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $course->course_name }} | CYBEReye</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Cairo', 'Tahoma', sans-serif; background: #05080a; color: #e0e0e0; line-height: 1.6; }
        .container { max-width: 1400px; margin: 0 auto; padding: 0 20px; }
        header { background: #0a141a; border-bottom: 1px solid #14262e; padding: 15px 0; }
        .header-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .logo { font-size: 1.5rem; font-weight: bold; color: #9333ea; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .course-title-header { font-size: 1rem; color: #7da0a5; margin-top: 5px; }
        .user-menu { display: flex; align-items: center; gap: 20px; }
        .user-menu a { color: #fff; text-decoration: none; transition: 0.3s; }
        .user-menu a:hover { color: #9333ea; }
        .course-player-container { display: flex; gap: 25px; margin: 30px auto; flex-wrap: wrap; }
        .sidebar { width: 380px; background: #0a141a; border-radius: 20px; padding: 20px; border: 1px solid #14262e; }
        .course-sidebar-header h3 { color: #9333ea; margin-bottom: 15px; }
        .progress-bar { background: #14262e; border-radius: 10px; height: 10px; overflow: hidden; }
        .progress-fill { width: {{ $progress }}%; background: #9333ea; height: 100%; border-radius: 10px; }
        .progress-text { margin-top: 8px; text-align: center; color: #9333ea; font-size: 0.85rem; }
        .modules-list { margin-top: 20px; }
        .module { margin-bottom: 15px; }
        .module-header { background: #14262e; padding: 12px; border-radius: 12px; cursor: pointer; display: flex; justify-content: space-between; font-weight: bold; }
        .module-header:hover { background: #241b35; border-color: #9333ea; }
        .lessons-list { margin-right: 15px; margin-top: 8px; display: block; }
        .lesson-item { display: flex; justify-content: space-between; align-items: center; padding: 12px; margin-bottom: 8px; background: #0a141a; border-radius: 12px; border: 1px solid #14262e; transition: 0.3s; }
        .lesson-item.active { background: #9333ea15; border-right: 3px solid #9333ea; border-color: #9333ea30; }
        .lesson-left { display: flex; align-items: center; gap: 12px; flex: 1; }
        .lesson-checkbox { width: 20px; height: 20px; cursor: pointer; accent-color: #9333ea; }
        .lesson-info { flex: 1; }
        .lesson-title { font-weight: bold; }
        .lesson-duration { font-size: 0.75rem; color: #7da0a5; }
        .play-icon { cursor: pointer; color: #9333ea; font-size: 1.2rem; margin-right: 10px; }
        .main-content { flex: 1; min-width: 300px; }
        .video-container { margin-bottom: 25px; }
        .video-placeholder { background: #0a141a; border-radius: 20px; padding: 60px; text-align: center; border: 1px solid #14262e; }
        .video-placeholder i { color: #9333ea; }
        video { width: 100%; border-radius: 20px; background: #000; display: none; }
        .lesson-info-card { background: #0a141a; border-radius: 20px; padding: 25px; border: 1px solid #14262e; margin-top: 20px; }
        .lesson-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #14262e; }
        .lesson-title-main { color: #9333ea; font-size: 1.5rem; }
        .lesson-actions { display: flex; gap: 10px; }
        .action-btn { background: #9333ea; border: none; padding: 8px 16px; border-radius: 8px; color: #fff; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; }
        .action-btn:hover { background: #a855f7; }
        .lesson-description { margin: 20px 0; }
        .lesson-description h3 { margin-bottom: 10px; color: #9333ea; }
        .lesson-materials { margin: 30px 0; }
        .lesson-materials h3 { margin-bottom: 15px; color: #9333ea; }
        .materials-list { display: flex; flex-direction: column; gap: 10px; }
        .material-item { display: flex; gap: 15px; background: #071214; border-radius: 12px; padding: 15px; cursor: pointer; border: 1px solid #14262e; transition: 0.3s; }
        .material-item:hover { background: #1b1429; transform: translateX(-5px); border-color: #9333ea50; }
        .navigation-buttons { display: flex; justify-content: space-between; margin-top: 30px; gap: 15px; }
        .nav-btn { background: #9333ea; border: none; padding: 10px 20px; border-radius: 8px; color: #fff; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; }
        .nav-btn:hover { background: #a855f7; }
        .footer { text-align: center; padding: 25px; background: #0a141a; margin-top: 50px; border-top: 1px solid #14262e; color: #7da0a5; }
        .footer-links a { color: #9333ea; margin: 0 10px; text-decoration: none; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); justify-content: center; align-items: center; z-index: 9999; }
        .modal-content { background: #0a141a; padding: 30px; border-radius: 20px; width: 500px; max-width: 90%; border: 1px solid #9333ea; }
        .close-modal { float: left; cursor: pointer; font-size: 24px; color: #fff; transition: 0.3s; }
        .close-modal:hover { color: #9333ea; }
        textarea { background: #071214; color: white; border: 1px solid #14262e; border-radius: 12px; padding: 12px; width: 100%; font-family: inherit; }
        @media (max-width: 800px) { .sidebar { width: 100%; } .lesson-header { flex-direction: column; align-items: flex-start; } }

        /* ========== زر الكويز الرئيسي ========== */
        .btn-gradient {
            background: #9333ea;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(147, 51, 234, 0.3);
        }

        .btn-gradient:hover {
            background: #a855f7;
            transform: translateY(-1px);
            color: white;
        }

        .btn-gradient:active {
            transform: translateY(0);
        }

        .btn-gradient i {
            margin-left: 6px;
            font-size: 0.8rem;
        }

        /* ========== القائمة المنسدلة ========== */
        .dropdown-menu {
            background: #0a141a;
            border-radius: 10px;
            border: 1px solid #14262e;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            padding: 6px 0;
            min-width: 200px;
            margin-top: 6px;
        }

        .dropdown-item {
            padding: 8px 16px;
            font-size: 0.85rem;
            color: #e0e0e0;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .dropdown-item i {
            font-size: 0.85rem;
            width: 18px;
            color: #9333ea;
        }

        .dropdown-item:hover {
            background: #1b1429;
            color: #fff;
        }

        /* ========== البادج ========== */
        .badge.bg-secondary {
            background: #14262e !important;
            color: #7da0a5 !important;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            margin-right: 6px;
            border: 1px solid #9333ea40;
        }

        /* ========== زر القائمة المنسدلة نفسه ========== */
        .dropdown-toggle::after {
            margin-right: 6px;
            vertical-align: middle;
            font-size: 0.7rem;
        }

        /* ========== زر "لا توجد كويزات" ========== */
        .btn-outline-secondary {
            background: #071214;
            border: 1px solid #14262e;
            color: #5d757d;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: not-allowed;
        }

        .btn-outline-secondary i {
            margin-left: 6px;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div>
                    <a href="{{ url('/') }}" class="logo"><i class="fas fa-shield-alt"></i> CYBEReye</a>
                    <div class="course-title-header">{{ $course->course_name }}</div>
                </div>
                <div class="user-menu">
                    <a href="{{ route('student.dashboard') }}"><i class="fas fa-user-graduate"></i> كورساتي</a>
                    <a href="{{ route('course.details', $course->id) }}"><i class="fas fa-info-circle"></i> تفاصيل الكورس</a>
                    @if($course->quizzes->count() > 0)
                    <div class="dropdown">
                        <button class="btn btn-gradient dropdown-toggle" type="button" id="quizDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-tasks me-2"></i> اختر الكويز
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="quizDropdown">
                            @foreach($course->quizzes as $quiz)
                                <li>
                                    <a class="dropdown-item" href="{{ route('quiz.start', $quiz->id) }}">
                                        <i class="fas fa-play-circle me-2"></i> {{ $quiz->title }}
                                        @if($quiz->duration)
                                            <span class="badge bg-secondary ms-2">{{ $quiz->duration }} دقيقة</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <button class="btn btn-outline-secondary" disabled>
                        <i class="fas fa-times-circle me-2"></i> لا توجد كويزات متاحة
                    </button>
                @endif
                </div>
            </div>
        </div>
    </header>

    <div class="container course-player-container">
        <aside class="sidebar">
            <div class="course-sidebar-header">
                <h3>{{ $course->course_name }}</h3>
            </div>
            <div class="modules-list" id="modulesList">
                @php
                    $videos = $course->videos->sortBy('order_number');
                    $grouped = $videos->groupBy('lesson_number');
                @endphp
                @foreach($grouped as $moduleNumber => $videosInModule)
                <div class="module">
                    <div class="module-header" onclick="toggleModule({{ $loop->index }})">
                        <span>الوحدة {{ $moduleNumber }}: {{ $videosInModule->first()->module_name ?? 'محتوى الوحدة' }}</span>
                        <i class="fas fa-chevron-down" id="moduleIcon{{ $loop->index }}"></i>
                    </div>
                    <div class="lessons-list" id="moduleLessons{{ $loop->index }}">
                        @foreach($videosInModule as $video)
                        <div class="lesson-item" data-video-id="{{ $video->id }}" id="lesson-{{ $video->id }}">
                            <div class="lesson-left">
                                <input type="checkbox" class="lesson-checkbox" data-id="{{ $video->id }}"
                                    {{ $video->completed_for_user ? 'checked' : '' }}>
                                <div class="lesson-info">
                                    <div class="lesson-title">{{ $video->title }}</div>
                                    <div class="lesson-duration">
                                        @php
                                            $duration = $video->duration ?? 0;
                                            $minutes = floor($duration / 60);
                                            $seconds = $duration % 60;
                                            echo $minutes . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
                                        @endphp
                                    </div>
                                </div>
                            </div>
                            <i class="fas fa-play-circle play-icon" onclick="playVideo({{ $video->id }}, '{{ addslashes($video->title) }}', '{{ addslashes($video->description) }}', '{{ asset($video->url) }}', this.parentElement)"></i>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </aside>

        <main class="main-content">
            <div class="lesson-content">
                <div class="video-container">
                    <div class="video-placeholder" id="videoPlaceholder">
                        <i class="fas fa-play-circle fa-3x"></i>
                        <h3 style="margin: 1rem 0;">اختر درساً للبدء</h3>
                        <p>انقر على أيقونة التشغيل بجانب الدرس لمشاهدته</p>
                    </div>
                    <video id="lessonVideo" controls>
                        <source src="" type="video/mp4">
                    </video>
                </div>

                <div class="lesson-info-card">
                    <div class="lesson-header">
                        <h1 class="lesson-title-main" id="currentLessonTitle">---</h1>
                        <div class="lesson-actions">
                            <button class="action-btn" onclick="takeNotes()"><i class="fas fa-edit"></i> كتابة ملاحظات</button>
                        </div>
                    </div>
                    <div class="lesson-description">
                        <h3>وصف الدرس</h3>
                        <p id="lessonDescription">اختر درساً لعرض وصفه.</p>
                    </div>

                    <div class="lesson-materials">
                        <h3>المواد التعليمية</h3>
                        <div class="materials-list">
                            @foreach($course->materials as $material)
                            <div class="material-item" onclick="downloadMaterial('{{ asset('storage/' . $material->file_path) }}')">
                                <div class="material-icon"><i class="fas fa-file-pdf fa-2x" style="color: #9333ea;"></i></div>
                                <div>
                                    <strong>{{ $material->title }}</strong>
                                    <p style="color: #aaa;">{{ $material->description ?? 'ملف تعليمي' }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button class="nav-btn prev" onclick="previousLesson()"><i class="fas fa-arrow-right"></i> الدرس السابق</button>
                        <button class="nav-btn next" onclick="nextLesson()">الدرس التالي <i class="fas fa-arrow-left"></i></button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="modal" id="notesModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeNotesModal()">&times;</span>
            <h2>ملاحظاتي</h2>
            <textarea id="notesText" rows="8" placeholder="اكتب ملاحظاتك هنا..."></textarea>
            <button class="action-btn" onclick="saveNotes()" style="margin-top: 15px;">حفظ الملاحظات</button>
        </div>
    </div>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        let videos = @json($course->videos);
        let currentVideoId = null;
        let currentVideoElement = null;

        function updateProgress() {
            let checkboxes = document.querySelectorAll('.lesson-checkbox');
            let total = checkboxes.length;
            let completed = Array.from(checkboxes).filter(cb => cb.checked).length;
            let percent = total > 0 ? Math.round((completed / total) * 100) : 0;
            let progressFill = document.getElementById('progressFill');
            if(progressFill) progressFill.style.width = percent + '%';
            let progressPercentage = document.getElementById('progressPercentage');
            if(progressPercentage) progressPercentage.innerText = percent;
        }

        function playVideo(id, title, description, url, element) {
            currentVideoId = id;
            document.getElementById('currentLessonTitle').innerText = title;
            document.getElementById('lessonDescription').innerText = description || 'لا يوجد وصف';
            const video = document.getElementById('lessonVideo');
            const source = video.querySelector('source');
            source.src = url;
            video.load();
            document.getElementById('videoPlaceholder').style.display = 'none';
            video.style.display = 'block';
            video.play();

            document.querySelectorAll('.lesson-item').forEach(el => el.classList.remove('active'));
            if (element) element.classList.add('active');
            currentVideoElement = element;

            localStorage.setItem('current_video_id', id);
            localStorage.setItem('current_course_id', {{ $course->id }});
        }

        document.querySelectorAll('.lesson-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function(e) {
                e.stopPropagation();
                let videoId = this.dataset.id;
                let completed = this.checked;
                axios.post('{{ route("student.video.completed") }}', {
                    video_id: videoId,
                    course_id: {{ $course->id }},
                    completed: completed
                }).then(response => {
                    if (response.data.success) {
                        updateProgress();
                    } else {
                        this.checked = !completed;
                        alert('حدث خطأ: ' + (response.data.message || ''));
                    }
                }).catch(error => {
                    this.checked = !completed;
                    alert('فشل الاتصال بالخادم');
                });
            });
        });

        function takeNotes() {
            let savedNotes = localStorage.getItem('course_notes_{{ $course->id }}') || '';
            document.getElementById('notesText').value = savedNotes;
            document.getElementById('notesModal').style.display = 'flex';
        }
        function closeNotesModal() { document.getElementById('notesModal').style.display = 'none'; }
        function saveNotes() {
            let notes = document.getElementById('notesText').value;
            localStorage.setItem('course_notes_{{ $course->id }}', notes);
            alert('تم حفظ الملاحظات');
            closeNotesModal();
        }
        function downloadMaterial(url) { window.open(url, '_blank'); }

        function toggleModule(index) {
            let lessons = document.getElementById('moduleLessons' + index);
            let icon = document.getElementById('moduleIcon' + index);
            if (lessons.style.display === 'none') {
                lessons.style.display = 'block';
                icon.classList.remove('fa-chevron-left');
                icon.classList.add('fa-chevron-down');
            } else {
                lessons.style.display = 'none';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-left');
            }
        }

        function previousLesson() {
            if (!currentVideoId) { alert('الرجاء اختيار درس أولاً'); return; }
            const videoIds = videos.map(v => v.id);
            let index = videoIds.indexOf(currentVideoId);
            if (index > 0) {
                let prevVideo = videos[index-1];
                let element = document.querySelector(`.lesson-item[data-video-id="${prevVideo.id}"]`);
                if (element) {
                    playVideo(prevVideo.id, prevVideo.title, prevVideo.description, '{{ asset("") }}' + '/' + prevVideo.url, element);
                }
            } else { alert('هذا هو أول درس'); }
        }

        function nextLesson() {
            if (!currentVideoId) { alert('الرجاء اختيار درس أولاً'); return; }
            const videoIds = videos.map(v => v.id);
            let index = videoIds.indexOf(currentVideoId);
            if (index < videoIds.length-1 && index !== -1) {
                let nextVideo = videos[index+1];
                let element = document.querySelector(`.lesson-item[data-video-id="${nextVideo.id}"]`);
                if (element) {
                    playVideo(nextVideo.id, nextVideo.title, nextVideo.description, '{{ asset("") }}' + '/' + nextVideo.url, element);
                }
            } else { alert('هذا هو آخر درس'); }
        }

        window.onclick = function(event) {
            let modal = document.getElementById('notesModal');
            if (event.target === modal) closeNotesModal();
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateProgress();
            let savedVideoId = localStorage.getItem('current_video_id');
            if (savedVideoId && {{ $enrolled ? 'true' : 'false' }}) {
                let video = videos.find(v => v.id == savedVideoId);
                if (video) {
                    let element = document.querySelector(`.lesson-item[data-video-id="${video.id}"]`);
                    if (element) {
                        playVideo(video.id, video.title, video.description, '{{ asset("") }}' + '/' + video.url, element);
                    }
                }
            }
        });
    </script>
</body>
</html>