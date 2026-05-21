<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title') | CYBEReye</title>

    <link rel="stylesheet" href="{{ asset('cms/css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.css" rel="stylesheet">
    <style>
        body {
            background: #0a0e27;
            background-image: 
                linear-gradient(rgba(0, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
        }
        
        .admin-menu {
            padding: 20px;
            background: linear-gradient(135deg, #0a0f1e 0%, #0d1428 100%);
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 255, 255, 0.1), 0 0 20px rgba(0, 255, 255, 0.05);
            border: 1px solid rgba(0, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
            max-height: calc(100vh - 40px);
            scroll-behavior: smooth;
        }
        
        .admin-menu::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #00ffff, #00ff88, #00ffff, transparent);
            animation: scan 3s linear infinite;
        }
        
        @keyframes scan {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .menu-title {
            font-size: 13px;
            font-weight: 700;
            color: #00ffff;
            padding: 15px 0 8px 0;
            margin-top: 10px;
            border-bottom: 1px solid rgba(0, 255, 255, 0.3);
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
            position: relative;
        }
        
        .menu-title::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 50px;
            height: 2px;
            background: #00ffff;
            box-shadow: 0 0 10px #00ffff;
        }
        
        .admin-menu-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            margin: 5px 0;
            color: #a8b3cf;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
        }
        
        .admin-menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .admin-menu-item:hover::before {
            left: 100%;
        }
        
        .admin-menu-item i {
            margin-left: 12px;
            width: 22px;
            font-size: 16px;
            transition: all 0.3s ease;
            color: #00ffff;
            text-shadow: 0 0 3px #00ffff;
        }
        
        .admin-menu-item span {
            font-size: 14px;
            font-weight: 500;
        }
        
        .admin-menu-item:hover {
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.1) 0%, rgba(0, 255, 136, 0.05) 100%);
            color: #00ffff;
            transform: translateX(-5px);
            border-color: rgba(0, 255, 255, 0.3);
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.2);
        }
        
        .admin-menu-item:hover i {
            color: #00ff88;
            text-shadow: 0 0 8px #00ff88;
        }
        
        .admin-menu-item.active {
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.15) 0%, rgba(0, 255, 136, 0.1) 100%);
            color: #00ffff;
            border-color: rgba(0, 255, 255, 0.5);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
            text-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
        }
        
        .dropdown-menu-item {
            position: relative;
            margin: 5px 0;
        }
        
        .dropdown-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 10px 15px;
            background: linear-gradient(135deg, rgba(10, 20, 40, 0.8) 0%, rgba(5, 15, 30, 0.9) 100%);
            border: 1px solid rgba(0, 255, 255, 0.2);
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #a8b3cf;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .dropdown-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .dropdown-btn:hover::before {
            left: 100%;
        }
        
        .dropdown-btn:hover {
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.1) 0%, rgba(0, 255, 136, 0.05) 100%);
            color: #00ffff;
            border-color: rgba(0, 255, 255, 0.5);
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.2);
        }
        
        .dropdown-btn span i {
            margin-left: 12px;
            width: 22px;
            font-size: 16px;
            color: #00ffff;
            text-shadow: 0 0 3px #00ffff;
        }
        
        .dropdown-btn .arrow {
            transition: transform 0.3s ease;
            font-size: 12px;
            color: #00ffff;
        }
        
        .dropdown-btn.active {
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.15) 0%, rgba(0, 255, 136, 0.1) 100%);
            border-color: rgba(0, 255, 255, 0.6);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
        }
        
        .dropdown-btn.active .arrow {
            transform: rotate(180deg);
            color: #00ff88;
        }
        
        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, rgba(5, 15, 30, 0.95) 0%, rgba(10, 20, 40, 0.95) 100%);
            border-radius: 10px;
            margin-top: 5px;
            margin-bottom: 5px;
            border: 1px solid rgba(0, 255, 255, 0.1);
        }
        
        .dropdown-content.show {
            max-height: 500px;
        }
        
        .dropdown-content a {
            display: flex;
            align-items: center;
            padding: 8px 15px 8px 40px;
            color: #8892b0;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
            position: relative;
        }
        
        .dropdown-content a::before {
            content: '>';
            position: absolute;
            left: 20px;
            opacity: 0;
            transition: all 0.3s ease;
            color: #00ffff;
        }
        
        .dropdown-content a i {
            margin-left: 12px;
            width: 20px;
            font-size: 13px;
            color: #00ffff;
        }
        
        .dropdown-content a:hover {
            background: linear-gradient(90deg, rgba(0, 255, 255, 0.1) 0%, transparent 100%);
            color: #00ffff;
            border-right-color: #00ffff;
            padding-right: 40px;
        }
        
        .dropdown-content a:hover::before {
            opacity: 1;
            left: 15px;
        }
        
        .admin-menu::-webkit-scrollbar {
            width: 6px;
        }
        
        .admin-menu::-webkit-scrollbar-track {
            background: rgba(0, 255, 255, 0.05);
            border-radius: 10px;
        }
        
        .admin-menu::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #00ffff, #00ff88);
            border-radius: 10px;
            box-shadow: 0 0 5px #00ffff;
        }
        
        @keyframes neonPulse {
            0% { box-shadow: 0 0 5px rgba(0, 255, 255, 0.5); }
            50% { box-shadow: 0 0 20px rgba(0, 255, 255, 0.8); }
            100% { box-shadow: 0 0 5px rgba(0, 255, 255, 0.5); }
        }
        
        .admin-menu-item:hover,
        .dropdown-btn:hover {
            animation: neonPulse 1s infinite;
        }
        
        @media (max-width: 768px) {
            .admin-menu {
                padding: 15px;
            }
            .admin-menu-item,
            .dropdown-btn {
                padding: 8px 12px;
                font-size: 13px;
            }
            .dropdown-content a {
                padding: 6px 15px 6px 35px;
                font-size: 12px;
            }
        }
        
        .admin-menu {
            scrollbar-width: thin;
            scrollbar-color: #00ffff rgba(0, 255, 255, 0.05);
        }

        
    </style>
    @yield('styles')
</head>

<body>

 
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <aside class="admin-sidebar">
        <div class="admin-header">
            <div class="admin-logo">
                <i class="fas fa-shield-alt"></i>
                CYBEReye
            </div>
        </div>

        <nav class="admin-menu">


            <!-- ========== الأدمن فقط ========== -->
            @if(auth('admin')->check())
                @php $user = auth('admin')->user(); @endphp
                
                <div class="admin-menu-section">
                    <div class="menu-title">الرئيسية</div>
                    <a href="{{ route('main') }}" class="admin-menu-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>لوحة التحكم</span>
                    </a>
                </div>
                
        
                <div class="menu-title">نظام الصلاحيات</div>
                <div class="dropdown-menu-item">
                    <button class="dropdown-btn" onclick="toggleDropdown(this)">
                        <span><i class="fas fa-shield-alt"></i><span>الأدوار والصلاحيات</span></span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('roles.index') }}"><i class="fas fa-shield-alt"></i><span>الأدوار</span></a>
                        <a href="{{ route('roles.create') }}"><i class="fas fa-plus-circle"></i><span>إضافة دور</span></a>
                        <a href="{{ route('permissions.index') }}"><i class="fas fa-key"></i><span>الصلاحيات</span></a>
                        <a href="{{ route('permissions.create') }}"><i class="fas fa-plus-square"></i><span>إضافة صلاحية</span></a>
                    </div>
                </div>
        
                <div class="menu-title">إدارة المستخدمين</div>
                <div class="dropdown-menu-item">
                    <button class="dropdown-btn" onclick="toggleDropdown(this)">
                        <span><i class="fas fa-users"></i><span>المستخدمين</span></span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <div class="dropdown-content">
                        {{-- @can('index-student') --}}
                        <a href="{{ route('students.index') }}"><i class="fas fa-user-graduate"></i><span>الطلاب</span></a>
                        {{-- @endcan --}}


                        {{-- @can('create-student') --}}
                       
                        <a href="{{ route('students.create') }}"><i class="fas fa-user-graduate"></i><span>اضافة طالب </span></a>
                        {{-- @endcan --}}

                        {{-- @can('index-instructor') --}}
                        <a href="{{ route('instructors.index') }}"><i class="fas fa-chalkboard-teacher"></i><span>المدربين</span></a>
                        {{-- @endcan --}}
                        {{-- @can('create-instructor') --}}
                        <a href="{{ route('instructors.create') }}"><i class="fas fa-chalkboard-teacher"></i><span> ضافةالمدربين</span></a>
                        {{-- @endcan --}}
                        @if(auth('admin')->user()->hasRole('admin') || auth('admin')->user()->can('create-admin'))
                        <a href="{{ route('admins.index') }}"><i class="fas fa-user-cog"></i><span>المشرفين</span></a>
                        <a href="{{ route('admins.create') }}"><i class="fas fa-plus-circle"></i><span>إضافة مشرف</span></a>
                    @endif
                        {{-- @can('create-admin')
                        <a href="{{ route('admins.index') }}"><i class="fas fa-user-cog"></i><span>المشرفين</span></a>
                        <a href="{{ route('admins.create') }}"><i class="fas fa-user-cog"></i><span> اضافةالمشرفين</span></a>
                        @endcan --}}
                    </div>
                </div>
        
                <div class="menu-title">إدارة المحتوى</div>
                <div class="dropdown-menu-item">
                    <button class="dropdown-btn" onclick="toggleDropdown(this)">
                        <span><i class="fas fa-book-open"></i><span>الكورسات</span></span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('courses.index') }}"><i class="fas fa-list"></i><span>جميع الكورسات</span></a>
                    </div>
                </div>
        
                <div class="dropdown-menu-item">
                    <button class="dropdown-btn" onclick="toggleDropdown(this)">
                        <span><i class="fas fa-tags"></i><span>التصنيفات</span></span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('categories.index') }}"><i class="fas fa-list"></i><span>جميع التصنيفات</span></a>
                    </div>
                </div>
        
                <div class="dropdown-menu-item">
                    <button class="dropdown-btn" onclick="toggleDropdown(this)">
                        <span><i class="fas fa-file-download"></i><span>المواد التعليمية</span></span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('materials.index') }}"><i class="fas fa-list"></i><span>جميع المواد</span></a>
                    </div>
                </div>
        
                <div class="dropdown-menu-item">
                    <button class="dropdown-btn" onclick="toggleDropdown(this)">
                        <span><i class="fas fa-video"></i><span>الفيديوهات</span></span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('videos.index') }}"><i class="fas fa-list"></i><span>جميع الفيديوهات</span></a>
                    </div>
                </div>
        
                <div class="dropdown-menu-item">
                    <button class="dropdown-btn" onclick="toggleDropdown(this)">
                        <span><i class="fas fa-edit"></i><span>الكويزات</span></span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('quizzs.index') }}"><i class="fas fa-list"></i><span>جميع الكويزات</span></a>
                    </div>
                </div>


                <a href="{{ route('dictionary.index') }}" class="admin-menu-item">
                    <i class="fas fa-book"></i>
                    <span>القاموس الذكي </span>
                </a>
            @endif
        
            <!-- ========== الطالب فقط ========== -->
            @if(auth('student')->check())
                @php $user = auth('student')->user(); @endphp
                
                <div class="admin-menu-section">
                    <div class="menu-title">الرئيسية</div>
                    <a href="{{ route('main') }}" class="admin-menu-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>لوحة التحكم</span>
                    </a>
                </div>
        
                <div class="menu-title">لوحة الطالب</div>
                <a href="{{ route('student.dashboard') }}" class="admin-menu-item">
                    <i class="fas fa-book-open"></i>
                    <span>كورساتي المسجلة</span>
                </a>
                <a href="{{ route('student.ctf') }}" class="admin-menu-item">
                    <i class="fas fa-flag-checkered"></i>
                    <span>CTF</span>
                </a>
                <a href="{{ route('certificate.my-certificates') }}" class="admin-menu-item">
                    <i class="fas fa-certificate"></i>
                    <span>شهاداتي</span>
                </a>
            @endif
        
            <!-- ========== المدرب فقط ========== -->
            @if(auth('instructor')->check())
            <div class="admin-menu-section">
                <div class="menu-title">الرئيسية</div>
                <a href="{{ route('main') }}" class="admin-menu-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>لوحة التحكم</span>
                </a>
            </div>
            {{-- 1. قسم الكورسات --}}
            {{-- @if(auth('instructor')->user()->can('index-course', 'instructor')) --}}
            <div class="dropdown-menu-item">
                <button class="dropdown-btn" onclick="toggleDropdown(this)">
                    <span><i class="fas fa-graduation-cap"></i><span>إدارة الكورسات</span></span>
                    <i class="fas fa-chevron-down arrow"></i>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('courses.index') }}"><i class="fas fa-list"></i><span>عرض الكورسات</span></a>
                    {{-- @if(auth('instructor')->user()->can('create-course', 'instructor')) --}}
                        <a href="{{ route('courses.create') }}"><i class="fas fa-plus-circle"></i><span>إضافة كورس</span></a>
                    {{-- @endif --}}
                
                </div>
            </div>
            {{-- @endif --}}
        
            {{-- 2. قسم الكويزات والأسئلة
            @if(auth('instructor')->user()->can('index-quiz', 'instructor'))
            <div class="dropdown-menu-item">
                <button class="dropdown-btn" onclick="toggleDropdown(this)">
                    <span><i class="fas fa-question-circle"></i><span>الاختبارات (Quizzes)</span></span>
                    <i class="fas fa-chevron-down arrow"></i>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('quizzs.index') }}"><i class="fas fa-tasks"></i><span>عرض الكويزات</span></a>
                    @if(auth('instructor')->user()->can('create-quiz', 'instructor'))
                        <a href="{{ route('quizzs.create') }}"><i class="fas fa-plus"></i><span>إضافة كويز</span></a>
                    @endif
                    @if(auth('instructor')->user()->can('index-question', 'instructor'))
                        <hr>
                        <a href="{{ route('questions.index') }}"><i class="fas fa-list-ol"></i><span>بنك الأسئلة</span></a>
                    @endif
                    @if(auth('instructor')->user()->can('create-question', 'instructor'))
                        <a href="{{ route('questions.create') }}"><i class="fas fa-plus-square"></i><span>إضافة سؤال</span></a>
                    @endif
                </div>
            </div>
            @endif --}}
        
        
        @endif
        <div style="position: relative; z-index: 2;">
            <a href="{{ route('view.logout') }}" style="background: rgba(255,255,255,0.1); color: white; padding: 10px 20px; border-radius: 12px; text-decoration: none; font-size: 0.9rem; font-weight: 600; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                تسجيل الخروج <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i>
            </a>
        </div>
        </nav>
        <div class="admin-footer">
            @if(auth('admin')->check())
                @php $user = auth('admin')->user(); @endphp
                <div class="admin-profile">
                    <div class="admin-avatar">{{ substr($user->username, 0, 1) }}</div>
                    <div class="admin-info">
                        <h4>{{ $user->username }}</h4>
                        <p>{{ $user->email }}</p>
                        <small><i class="fas fa-crown"></i> {{ $user->getRoleNames()->first() ?? 'أدمن' }}</small>
                    </div>
                </div>
            @elseif(auth('student')->check())
                @php $user = auth('student')->user(); @endphp
                <div class="admin-profile">
                    <div class="admin-avatar">{{ substr($user->username, 0, 1) }}</div>
                    <div class="admin-info">
                        <h4>{{ $user->username }}</h4>
                        <p>{{ $user->email }}</p>
                        <small><i class="fas fa-user-graduate"></i> {{ $user->getRoleNames()->first() ?? 'طالب' }}</small>
                    </div>
                </div>
            @elseif(auth('instructor')->check())
                @php $user = auth('instructor')->user(); @endphp
                <div class="admin-profile">
                    <div class="admin-avatar">{{ substr($user->username, 0, 1) }}</div>
                    <div class="admin-info">
                        <h4>{{ $user->username }}</h4>
                        <p>{{ $user->email }}</p>
                        <small><i class="fas fa-chalkboard-teacher"></i> {{ $user->getRoleNames()->first() ?? 'مدرب' }}</small>
                    </div>
                </div>
            @endif
        </div>

    </aside>

    <main class="admin-main">
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('cms/js/admin.js') }}"></script>
    <script src="{{ asset('cms/js/crud.js') }}"></script>
    <script>
        function toggleDropdown(btn) {
            btn.classList.toggle('active');
            let content = btn.nextElementSibling;
            content.classList.toggle('show');
        }
        
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.dropdown-menu-item')) {
                document.querySelectorAll('.dropdown-content').forEach(function(content) {
                    content.classList.remove('show');
                });
                document.querySelectorAll('.dropdown-btn').forEach(function(btn) {
                    btn.classList.remove('active');
                });
            }
        });
    </script>
    @yield('scripts')
</body>

</html>