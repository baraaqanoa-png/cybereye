@extends('cms.parent')

@section('title', 'كورساتي | CyberEye')

@section('styles')
<style>
    body {
        background: #f3f0fa !important;
    }
    html.dark body {
        background: #0B0F19 !important;
    }
    .courses-section {
        padding: 20px 0;
    }
    .page-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #2D144A;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    html.dark .page-title {
        color: #ffffff;
        text-shadow: 0 0 10px rgba(139, 92, 246, 0.3);
    }
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
    }
    .course-card {
        background: #ffffff;
        border: 2px solid #ede9fe;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    html.dark .course-card {
        background: #1E1E1E;
        border: 1px solid #2e1065;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    .course-card:hover {
        transform: translateY(-5px);
        border-color: #8b5cf6;
        box-shadow: 0 15px 35px rgba(109, 40, 217, 0.15);
    }
    html.dark .course-card:hover {
        border-color: #a78bfa;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 0 15px rgba(139, 92, 246, 0.2);
    }
    .course-header {
        padding: 25px;
        background: linear-gradient(135deg, rgba(109, 40, 217, 0.06), transparent);
        border-bottom: 1px solid #ede9fe;
    }
    html.dark .course-header {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), transparent);
        border-bottom: 1px solid #2e1065;
    }
    .course-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #2D144A;
        margin-bottom: 8px;
    }
    html.dark .course-title {
        color: #ffffff;
    }
    .instructor-name {
        font-size: 0.85rem;
        color: #4A1D96;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    html.dark .instructor-name {
        color: #ffffff;
    }
    .course-body {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .course-description {
        font-size: 0.9rem;
        color: #2D144A;
        font-weight: 500;
        line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    html.dark .course-description {
        color: #ffffff;
    }
    .course-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
    .btn-view {
        background: linear-gradient(135deg, #4A1D96, #7c3aed);
        color: #ffffff !important;
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        font-size: 0.9rem;
        width: 100%;
        justify-content: center;
    }
    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(109, 40, 217, 0.3);
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #ffffff;
        border-radius: 20px;
        border: 2px dashed #ede9fe;
        max-width: 600px;
        margin: 40px auto;
    }
    html.dark .empty-state {
        background: #1E1E1E;
        border-color: #2e1065;
    }
    .empty-icon {
        font-size: 4rem;
        color: #4A1D96;
        margin-bottom: 20px;
        opacity: 0.7;
    }
    html.dark .empty-icon {
        color: #a78bfa;
    }
    .empty-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #2D144A;
        margin-bottom: 10px;
    }
    html.dark .empty-title {
        color: #ffffff;
    }
    .empty-desc {
        color: #4A1D96;
        font-weight: 500;
        font-size: 0.95rem;
        margin-bottom: 25px;
    }
    html.dark .empty-desc {
        color: #ffffff;
    }
</style>
@endsection

@section('content')
<div class="courses-section">
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="fas fa-graduation-cap"></i>
            <span>كورساتي المسجلة</span>
        </h1>

        @if($courses->count() > 0)
            <div class="courses-grid">
                @foreach($courses as $course)
                    <div class="course-card">
                        <div class="course-header">
                            <h2 class="course-title">{{ $course->course_name }}</h2>
                            <div class="instructor-name">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span>المدرس: {{ $course->instructor->user1->username ?? $course->instructor->name ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                        <div class="course-body">
                            <p class="course-description">{{ $course->description ?? 'لا يوجد وصف متاح لهذا الكورس حالياً.' }}</p>
                            <div class="course-footer">
                                <a href="{{ route('course.player', $course->id) }}" class="btn-view">
                                    <span>دخول الكورس</span>
                                    <i class="fas fa-play"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="empty-title">لم تسجل في أي كورس بعد</h3>
                <p class="empty-desc">يمكنك استعراض الكورسات المتاحة من لوحة التحكم والتسجيل فيها للبدء في التعلم.</p>
                <a href="{{ route('student.dashboard') }}" class="btn-view">
                    <span>تصفح الكورسات المتاحة</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
