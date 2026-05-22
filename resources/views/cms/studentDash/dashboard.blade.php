<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> my course  | CyberEye</title>
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
            background: #0a0c10;
            color: #e0e0e0;
        }

        /* Dashboard Container */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(10, 14, 23, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(168, 85, 247, 0.2);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(168, 85, 247, 0.2);
        }

        .sidebar-header h2 {
            color: #bd5fff;
            font-size: 1.5rem;
        }

        .sidebar-header p {
            color: #888;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .nav-menu {
            padding: 20px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 12px;
            color: #e0e0e0;
            text-decoration: none;
            transition: all 0.3s;
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(189, 95, 255, 0.1);
            color: #bd5fff;
        }

        .nav-item i {
            width: 24px;
            font-size: 1.2rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-right: 280px;
            padding: 30px;
        }

        /* Header */
        .top-header {
            background: linear-gradient(135deg, #0a0c10, #0d1117);
            border: 1px solid rgba(189, 95, 255, 0.3);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .welcome-text h1 {
            font-size: 1.8rem;
            color: #bd5fff;
        }

        .welcome-text p {
            color: #888;
            margin-top: 5px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(20, 25, 40, 0.8);
            border: 1px solid rgba(189, 95, 255, 0.2);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #bd5fff;
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            background: rgba(189, 95, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #bd5fff;
        }

        .stat-info h3 {
            font-size: 1.8rem;
            color: #bd5fff;
        }

        .stat-info p {
            color: #888;
            font-size: 0.85rem;
        }

        /* Section Title */
        .section-title {
            font-size: 1.3rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #bd5fff;
        }

        /* Courses Grid */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .course-card {
            background: rgba(20, 25, 40, 0.8);
            border: 1px solid rgba(189, 95, 255, 0.2);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s;
            position: relative;
        }

        .course-card:hover {
            transform: translateY(-5px);
            border-color: #bd5fff;
        }

        .course-header {
            padding: 20px;
            background: linear-gradient(135deg, rgba(189, 95, 255, 0.1), transparent);
        }

        .course-header h3 {
            color: #bd5fff;
            margin-bottom: 5px;
        }

        .course-instructor {
            color: #888;
            font-size: 0.8rem;
        }

        /* الشارة المخصصة لكورسات التدريب الميداني */
        .internship-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(234, 179, 8, 0.15);
            color: #eab308;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 700;
            border: 1px solid rgba(234, 179, 8, 0.3);
        }

        .course-body {
            padding: 20px;
        }

        .course-description {
            color: #aaa;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .course-progress {
            margin-bottom: 15px;
        }

        .progress-bar-wrapper {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
        }

        .progress-bar-fill {
            background: linear-gradient(90deg, #bd5fff, #e11d48);
            height: 100%;
            width: 0%;
            border-radius: 10px;
            transition: width 0.3s;
        }

        .course-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #bd5fff, #8b5cf6);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 15px rgba(189, 95, 255, 0.5);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #bd5fff;
            color: #bd5fff;
        }

        .btn-outline:hover {
            background: rgba(189, 95, 255, 0.1);
        }

        .btn-warning {
            background: linear-gradient(135deg, #d4af37, #b8960c);
            color: white;
        }

        /* Certificates List */
        .certificates-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 15px;
        }

        .certificate-item {
            background: rgba(20, 25, 40, 0.8);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 12px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .certificate-info h4 {
            color: #d4af37;
            margin-bottom: 5px;
        }

        .certificate-info p {
            color: #888;
            font-size: 0.75rem;
        }

        /* Empty State */
        .empty-state {
            background: rgba(20, 25, 40, 0.8);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            margin-bottom: 40px;
        }

        .empty-state i {
            font-size: 3rem;
            color: #888;
            margin-bottom: 15px;
        }

        .empty-state p {
            color: #888;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: rgba(10, 14, 23, 0.95);
            border-radius: 20px;
            padding: 30px;
            max-width: 420px;
            width: 90%;
            border: 1px solid rgba(189, 95, 255, 0.3);
            text-align: center;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        /* حقل إدخال الكود السري التابع للثيم السيبراني */
        .access-code-input {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(189, 95, 255, 0.4);
            border-radius: 10px;
            color: white;
            text-align: center;
            font-size: 1rem;
            font-weight: bold;
            letter-spacing: 1px;
            outline: none;
            font-family: 'Cairo', sans-serif;
        }
        .access-code-input:focus {
            border-color: #bd5fff;
            box-shadow: 0 0 10px rgba(189, 95, 255, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            .sidebar-header h2, .sidebar-header p, .nav-item span {
                display: none;
            }
            .main-content {
                margin-right: 80px;
            }
            .nav-item {
                justify-content: center;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="dashboard-container">

        <main class="main-content">

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $enrolledCourses->count() }}</h3>
                        <p>كورسات مسجل فيها</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $certificatesCount }}</h3>
                        <p>شهادات حصلت عليها</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $allCourses->count() }}</h3>
                        <p>كورسات متاحة</p>
                    </div>
                </div>
            </div>

            <div class="section-title">
                <i class="fas fa-book"></i>
                <h2>كورساتي المسجل فيها</h2>
            </div>

            @if($enrolledCourses->count() > 0)
                <div class="courses-grid">
                    @foreach($enrolledCourses as $course)
                        @php
                            $progress = $coursesProgress[$course->id] ?? ['total' => 0, 'completed' => 0, 'percentage' => 0];
                        @endphp
                        <div class="course-card">
                            <div class="course-header">
                                <h3>{{ $course->course_name }}</h3>
                                <p class="course-instructor">
                                    <i class="fas fa-chalkboard-user"></i> {{ $course->instructor->user1->username ?? 'name' }}
                                </p>
                            </div>
                            <div class="course-body">
                                <p class="course-description">{{ Str::limit($course->short_description ?? 'لا يوجد وصف', 100) }}</p>
                                <div class="course-progress">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 0.75rem;">
                                        <span>تقدمك</span>
                                        <span>{{ $progress['percentage'] }}%</span>
                                    </div>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar-fill" style="width: {{ $progress['percentage'] }}%;"></div>
                                    </div>
                                </div>

                               <a href="{{ route('course.player', $course->id) }}" class="btn btn-primary">
                                    <i class="fas fa-play-circle"></i> متابعة الكورس
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <p>لم تسجل في أي كورس بعد</p>
                </div>
            @endif

            <div class="section-title">
                <i class="fas fa-globe"></i>
                <h2>جميع الكورسات المتاحة</h2>
            </div>

            <div class="courses-grid">
                @foreach($allCourses as $course)
                    @php
                        $isEnrolled = $enrolledCourses->contains($course->id);
                        
                        // هنا نضع شرطاً ذكياً: إذا كان معرف الكورس أو اسمه يدل على التدريب الميداني
                        // يمكنك استبدال الشرط بـ حقل حقيقي لاحقاً إن أردت مثل $course->category == 'internship'
                        $isInternship = Str::contains(strtolower($course->course_name), ['تدريب', 'internship', 'ميداني']);
                    @endphp
                    <div class="course-card">
                        
                        @if($isInternship)
                            <div class="internship-badge">
                                <i class="fas fa-briefcase"></i> تدريب ميداني معتمد
                            </div>
                        @endif

                        <div class="course-header">
                            <h3>{{ $course->course_name }}</h3>
                            <p class="course-instructor">
                                <i class="fas fa-chalkboard-user"></i> {{ $course->instructor->user1->username ?? 'name' }}
                            </p>
                        </div>
                        <div class="course-body">
                            <p class="course-description">{{ Str::limit($course->short_description ?? 'لا يوجد وصف', 100) }}</p>
                            <div class="course-actions">
                                @if($isEnrolled)
                                    <a href="{{ route('course.player', $course->id) }}" class="btn btn-primary">
                                        <i class="fas fa-play"></i> متابعة
                                    </a>
                                @else
                                    <button onclick="showEnrollModal({{ $course->id }}, '{{ addslashes($course->course_name) }}', {{ $isInternship ? 'true' : 'false' }})" class="btn btn-outline">
                                        <i class="fas fa-plus"></i> تسجيل
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($certificates->count() > 0)
                <div class="section-title">
                    <i class="fas fa-certificate"></i>
                    <h2>أحدث الشهادات</h2>
                </div>
                <div class="certificates-list">
                    @foreach($certificates->take(3) as $cert)
                        <div class="certificate-item">
                            <div class="certificate-info">
                                <h4>{{ $cert->course->name }}</h4>
                            </div>
                            <a href="{{ route('certificate.show', $cert->id) }}" class="btn btn-warning" style="padding: 8px 15px;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>

    <div id="enrollModal" class="modal">
        <div class="modal-content">
            <div id="modalIcon" style="font-size: 2.5rem; color: #bd5fff; margin-bottom: 10px;">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3 id="modalTitle" style="color: #bd5fff; margin-bottom: 15px;">تسجيل في الكورس</h3>
            <p id="modalDescription">هل أنت متأكد من رغبتك في التسجيل في كورس <strong id="courseName"></strong>؟</p>
            
            <form id="enrollForm" method="POST" action="">
                @csrf
                <input type="hidden" name="course_id" id="courseId">
                
                <div id="accessCodeWrapper" style="display: none;">
                    <input type="text" name="access_code" id="accessCodeInput" class="access-code-input" placeholder="أدخل كود التفعيل السري هنا...">
                    <p style="color: #888; font-size: 0.8rem; margin-top: 8px;"><i class="fas fa-info-circle"></i> هذا المسار مقفل، يرجى الحصول على الكود من المهندس المشرف.</p>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" onclick="closeModal()">إلغاء</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary">تأكيد التسجيل</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showEnrollModal(courseId, courseName, isInternship) {
    const form = document.getElementById('enrollForm');
    const codeWrapper = document.getElementById('accessCodeWrapper');
    const codeInput = document.getElementById('accessCodeInput');
    const modalTitle = document.getElementById('modalTitle');
    const modalIcon = document.getElementById('modalIcon');
    const submitBtn = document.getElementById('submitBtn');

    document.getElementById('courseId').value = courseId;
    document.getElementById('courseName').innerText = courseName;

    if (isInternship) {
        // بدلاً من السطر القديم، اجعله هكذا:
form.action = "/cms/course/verify-internship/" + courseId;
        codeWrapper.style.display = 'block';
        codeInput.required = true;
        modalTitle.innerText = "تفعيل مسار التدريب الميداني";
        modalIcon.innerHTML = '<i class="fas fa-lock" style="color: #eab308;"></i>';
        submitBtn.innerText = "تحقق وتفعيل الكورس";
    } else {
        form.action = "{{ route('student.enroll') }}";
        codeWrapper.style.display = 'none';
        codeInput.required = false;
        modalTitle.innerText = "تسجيل في الكورس";
        modalIcon.innerHTML = '<i class="fas fa-graduation-cap"></i>';
        submitBtn.innerText = "تأكيد التسجيل";
    }

    document.getElementById('enrollModal').style.display = 'flex';
}
        // تحديث الدالة لتستقبل معيار "هل الكورس تدريب ميداني؟"
        // function showEnrollModal(courseId, courseName, isInternship) {
        //     const form = document.getElementById('enrollForm');
        //     const codeWrapper = document.getElementById('accessCodeWrapper');
        //     const codeInput = document.getElementById('accessCodeInput');
        //     const modalTitle = document.getElementById('modalTitle');
        //     const modalDescription = document.getElementById('modalDescription');
        //     const modalIcon = document.getElementById('modalIcon');
        //     const submitBtn = document.getElementById('submitBtn');

        //     document.getElementById('courseId').value = courseId;
        //     document.getElementById('courseName').innerText = courseName;

        //     if (isInternship) {
        //   window.href()='{{ route('course.player', $course->id) }}'
        //   form.action = "/cms/course/course/" + courseId + "/enroll";
        //         codeWrapper.style.display = 'block';
        //         codeInput.required = true;
        //         modalTitle.innerText = "تفعيل مسار التدريب الميداني";
        //         modalIcon.innerHTML = '<i class="fas fa-lock" style="color: #eab308;"></i>';
        //         submitBtn.innerText = "تحقق وتفعيل الكورس";
        //     } else {
        //         // 2. إبقاء المودال العادي للكورسات الأخرى
        //         form.action = "{{ route('student.enroll') }}"; // مسار التسجيل الافتراضي القديم لديك
        //         codeWrapper.style.display = 'none';
        //         codeInput.required = false;
        //         modalTitle.innerText = "تسجيل في الكورس";
        //         modalIcon.innerHTML = '<i class="fas fa-graduation-cap"></i>';
        //         submitBtn.innerText = "تأكيد التسجيل";
        //     }

        //     document.getElementById('enrollModal').style.display = 'flex';
        // }

        function closeModal() {
            document.getElementById('enrollModal').style.display = 'none';
            document.getElementById('accessCodeInput').value = ''; // تنظيف الحقل عند الإغلاق
        }

        window.onclick = function(event) {
            const modal = document.getElementById('enrollModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // إشعارات النجاح أو الخطأ المريحة
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'نجاح', text: '{{ session('success') }}', confirmButtonColor: '#bd5fff' });
        @endif

        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'خطأ', text: '{{ session('error') }}', confirmButtonColor: '#bd5fff' });
        @endif
    </script>

</body>
</html>