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
        .modules-list { margin-top: 20px; }
        .module { margin-bottom: 15px; }
        .module-header { background: #14262e; padding: 12px; border-radius: 12px; cursor: pointer; display: flex; justify-content: space-between; font-weight: bold; }
        .module-header:hover { background: #241b35; border-color: #9333ea; }
        .lessons-list { margin-right: 15px; margin-top: 8px; display: block; }
        .lesson-item { display: flex; justify-content: space-between; align-items: center; padding: 12px; margin-bottom: 8px; background: #0a141a; border-radius: 12px; border: 1px solid #14262e; transition: 0.3s; }
        .lesson-item.active { background: #9333ea15; border-right: 3px solid #9333ea; border-color: #9333ea30; }
        .lesson-left { display: flex; align-items: center; gap: 12px; flex: 1; }
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
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); justify-content: center; align-items: center; z-index: 9999; }
        .modal-content { background: #0a141a; padding: 30px; border-radius: 20px; width: 500px; max-width: 90%; border: 1px solid #9333ea; }
        .close-modal { float: left; cursor: pointer; font-size: 24px; color: #fff; transition: 0.3s; }
        .close-modal:hover { color: #9333ea; }
        textarea { background: #071214; color: white; border: 1px solid #14262e; border-radius: 12px; padding: 12px; width: 100%; font-family: inherit; }
        @media (max-width: 800px) { .sidebar { width: 100%; } .lesson-header { flex-direction: column; align-items: flex-start; } }
        .btn-gradient { background: #9333ea; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .btn-outline-secondary { background: #071214; border: 1px solid #14262e; color: #5d757d; padding: 8px 16px; border-radius: 8px; }
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
                <div class="user-menu" style="position: relative;">
                    <a href="{{ route('student.dashboard') }}"><i class="fas fa-user-graduate"></i> كورساتي</a>
                    <a href="{{ route('course.details', $course->id) }}"><i class="fas fa-info-circle"></i> تفاصيل الكورس</a>
                    @if(isset($course->quizzes) && $course->quizzes->count() > 0)
                        <div class="dropdown">
                            <button class="btn btn-gradient" onclick="toggleQuizMenu(event)" style="cursor: pointer;">
                                <i class="fas fa-tasks"></i> اختر الكويز <i class="fas fa-chevron-down"></i>
                            </button>
                            <ul id="quizMenu" style="display:none; position:absolute; top: 100%; left: 0; background:#0a141a; list-style:none; padding:10px; border:1px solid #9333ea; border-radius: 8px; min-width: 200px; z-index: 9999; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
                                @foreach($course->quizzes as $quiz)
                                    <li style="padding: 8px 0; border-bottom: 1px solid #14262e;">
                                        <a href="{{ route('quiz.start', $quiz->id) }}" style="color:white; text-decoration:none; display:block;">
                                            <i class="fas fa-play-circle" style="color:#9333ea; margin-left: 5px;"></i> {{ $quiz->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <button class="btn btn-outline-secondary" disabled>لا توجد كويزات</button>
                    @endif
                </div>
            </div>
        </div>
    </header>
    
    <div class="container course-player-container">
        <aside class="sidebar">
            <div class="course-sidebar-header"><h3>{{ $course->course_name }}</h3></div>
            <div class="modules-list">
                @php
                    $videos = $course->videos->sortBy('order_number');
                    $grouped = $videos->groupBy('lesson_number');
                @endphp
                @foreach($grouped as $moduleNumber => $videosInModule)
                <div class="module">
                    <div class="module-header" onclick="toggleModule({{ $loop->index }})">
                        <span>الوحدة {{ $moduleNumber }}</span>
                        <i class="fas fa-chevron-down" id="moduleIcon{{ $loop->index }}"></i>
                    </div>
                    <div class="lessons-list" id="moduleLessons{{ $loop->index }}">
                        @foreach($videosInModule as $video)
                        <div class="lesson-item" data-video-id="{{ $video->id }}">
                            <div class="lesson-info">
                                <div class="lesson-title">{{ $video->title }}</div>
                            </div>
                            @php
                                $videoUrl = $video->url;
                                if($video->youtube_url) {
                                    $videoUrl = $video->youtube_url;
                                }
                            @endphp
                            <i class="fas fa-play-circle play-icon" onclick="playVideo({{ $video->id }}, '{{ addslashes($video->title) }}', '{{ addslashes($video->description) }}', '{{ $videoUrl }}', this.parentElement)"></i>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </aside>

        <main class="main-content">
            <div class="video-container">
                <div class="video-placeholder" id="videoPlaceholder">
                    <i class="fas fa-play-circle fa-3x"></i>
                    <h3>اختر درساً للبدء</h3>
                </div>
                <video id="lessonVideo" controls style="display: none;">
                    <source src="" type="video/mp4">
                </video>
                <iframe id="youtubeIframe" style="display: none; width: 100%; height: 400px; border-radius: 20px; border: none;"></iframe>
            </div>
            <div class="lesson-info-card">
                <h1 class="lesson-title-main" id="currentLessonTitle">---</h1>
                <p id="lessonDescription">اختر درساً لعرض وصفه.</p>
                <div class="navigation-buttons">
                    <button class="nav-btn" onclick="previousLesson()">السابق</button>
                    <button class="nav-btn" onclick="nextLesson()">التالي</button>
                </div>
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
            
            <div class="modal" id="notesModal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeNotesModal()">&times;</span>
                    <h2>ملاحظاتي</h2>
                    <textarea id="notesText" rows="8" placeholder="اكتب ملاحظاتك هنا..."></textarea>
                    <button class="action-btn" onclick="saveNotes()" style="margin-top: 15px;">حفظ الملاحظات</button>
                </div>
            </div>
        </main>
    </div>

    <script>
        let videos = @json($course->videos);
        let currentVideoId = null;

        function toggleQuizMenu(event) {
            event.stopPropagation();
            const menu = document.getElementById('quizMenu');
            menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
        }

        window.onclick = function(event) {
            const menu = document.getElementById('quizMenu');
            if (menu && menu.style.display === 'block') {
                menu.style.display = 'none';
            }
        }

        function playVideo(id, title, description, url, element) {
            currentVideoId = id;
            document.getElementById('currentLessonTitle').innerText = title;
            document.getElementById('lessonDescription').innerText = description || 'لا يوجد وصف';
            
            const videoPlaceholder = document.getElementById('videoPlaceholder');
            const videoElement = document.getElementById('lessonVideo');
            const iframeElement = document.getElementById('youtubeIframe');
            
            const isYoutube = url.includes('youtube.com') || url.includes('youtu.be');
            
            if (isYoutube) {
                let embedUrl = url;
                if (url.includes('watch?v=')) {
                    embedUrl = url.replace('watch?v=', 'embed/');
                    embedUrl = embedUrl.split('&')[0];
                } else if (url.includes('youtu.be/')) {
                    embedUrl = url.replace('youtu.be/', 'youtube.com/embed/');
                }
                
                videoElement.style.display = 'none';
                iframeElement.src = embedUrl;
                iframeElement.style.display = 'block';
                videoPlaceholder.style.display = 'none';
            } else {
                iframeElement.style.display = 'none';
                videoElement.querySelector('source').src = url;
                videoElement.load();
                videoPlaceholder.style.display = 'none';
                videoElement.style.display = 'block';
                videoElement.play();
            }
            
            document.querySelectorAll('.lesson-item').forEach(el => el.classList.remove('active'));
            if (element) element.classList.add('active');
            localStorage.setItem('current_video_id', id);
        }

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
            let index = videos.findIndex(v => v.id == currentVideoId);
            if (index > 0) {
                let v = videos[index - 1];
                let el = document.querySelector(`[data-video-id="${v.id}"]`);
                let url = v.youtube_url ? v.youtube_url : ('{{ asset("") }}/' + v.url);
                playVideo(v.id, v.title, v.description, url, el);
            }
        }

        function nextLesson() {
            if (!currentVideoId) { alert('الرجاء اختيار درس أولاً'); return; }
            const videoIds = videos.map(v => v.id);
            let index = videoIds.indexOf(currentVideoId);
            if (index < videoIds.length-1 && index !== -1) {
                let nextVideo = videos[index+1];
                let element = document.querySelector(`.lesson-item[data-video-id="${nextVideo.id}"]`);
                if (element) {
                    let url = nextVideo.youtube_url ? nextVideo.youtube_url : ('{{ asset("") }}/' + nextVideo.url);
                    playVideo(nextVideo.id, nextVideo.title, nextVideo.description, url, element);
                }
            } else { alert('هذا هو آخر درس'); }
        }

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

        document.addEventListener('DOMContentLoaded', function() {
            let savedVideoId = localStorage.getItem('current_video_id');
            if (savedVideoId) {
                let video = videos.find(v => v.id == savedVideoId);
                if (video) {
                    let el = document.querySelector(`[data-video-id="${video.id}"]`);
                    let url = video.youtube_url ? video.youtube_url : ('{{ asset("") }}/' + video.url);
                    if (el) playVideo(video.id, video.title, video.description, url, el);
                }
            }
        });
    </script>
</body>
</html>