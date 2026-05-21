@extends('cms.parent')

@section('title', 'عرض تفاصيل الكورس')

@section('styles')
<style>
    /* 1. القاعدة الأساسية: تعتيم شامل وإلغاء أي بياض */
    body, .admin-main, .content-wrapper, .main-panel, .container-fluid {
        background-color: #0f0a1a !important;
        color: #d1c4e0 !important;
        border: none !important;
        font-family: 'Segoe UI', sans-serif;
    }

    /* 2. حاوية التفاصيل (الكارد الأساسي) */
    .form-container {
        background-color: #0d0818 !important;
        border: 1px solid #2a1a3a !important;
        border-radius: 2px !important;
        padding: 30px;
        box-shadow: none !important;
        max-width: 900px;
        margin: 20px auto;
    }

    /* 3. العناوين */
    .form-title {
        color: #f8fafc !important;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-title i {
        color: #9b59b6 !important;
    }

    .form-subtitle {
        color: #9b59b6 !important;
        font-size: 1rem;
        margin: 20px 0 15px 0;
        border-right: 3px solid #9b59b6;
        padding-right: 10px;
    }

    /* 4. توزيع العناصر (Grid) */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 15px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    /* 5. الحقول المعطلة (Disabled) - شكل احترافي */
    label {
        color: #8a7a9c !important;
        font-size: 0.85rem;
        margin-bottom: 8px;
        display: block;
    }

    input[disabled], textarea[disabled] {
        background-color: #06040a !important;
        color: #d1c4e0 !important;
        border: 1px solid #2a1a3a !important;
        border-radius: 2px !important;
        padding: 12px;
        width: 100%;
        cursor: not-allowed;
    }

    /* 6. الصورة (الغلاف) */
    .course-image-display {
        width: 100%;
        max-width: 300px;
        border: 1px solid #2a1a3a !important;
        border-radius: 2px !important;
        padding: 5px;
        background: #0d0818;
        margin: 0 auto 20px auto;
        display: block;
    }

    /* 7. الحالة (Badge) */
    .status-badge {
        background: #9b59b6 !important;
        color: white !important;
        padding: 6px 15px;
        border-radius: 2px !important;
        font-size: 0.8rem;
        display: inline-block;
    }

    /* 8. الأزرار */
    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
        border-top: 1px solid #2a1a3a;
        padding-top: 20px;
    }

    .btn {
        border-radius: 2px !important;
        padding: 10px 20px;
        font-weight: 600;
        border: none !important;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary { background-color: #9b59b6 !important; color: white; }
    .btn-primary:hover { background-color: #bf77f0 !important; transform: translateY(-2px); transition: 0.3s; }
    .btn-secondary { background-color: #2a1a3a !important; color: #8a7a9c; }
    .btn-secondary:hover { background-color: #3a2a4a !important; color: #d1c4e0; }

    /* تنظيف القالب */
    hr, .card-header, .border-top, .border-bottom {
        display: none !important;
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <h2 class="form-title">
        <i class="fas fa-info-circle"></i> تفاصيل الكورس: {{ $course->course_name }}
    </h2>

    <form>
        <h3 class="form-subtitle">غلاف الكورس</h3>
        <img src="{{ asset('storage/' . $course->course_image) }}" class="course-image-display" alt="Course Image">

        <h3 class="form-subtitle">بيانات الكورس الأساسية</h3>

        <div class="form-row">
            <div class="form-group">
                <label>اسم الكورس</label>
                <input type="text" value="{{ $course->course_name }}" disabled>
            </div>
            <div class="form-group">
                <label>التصنيف</label>
                <input type="text" value="{{ $course->category->title ?? 'غير مصنف' }}" disabled>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>المدرب المسؤول</label>
                <input type="text" value="{{ $course->instructor->user1->username ?? 'غير محدد' }}" disabled>
            </div>
            <div class="form-group">
                <label>مستوى الكورس</label>
                <input type="text" value="{{ ucfirst($course->level) }}" disabled>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>مدة الكورس</label>
                <input type="text" value="{{ $course->no_hours }} ساعة تدريبية" disabled>
            </div>
            <div class="form-group">
                <label>حالة الظهور</label>
                <div>
                    <span class="status-badge">
                        {{ $course->status == 'active' ? 'نشط ومتاح' : 'مسودة / مخفي' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>وصف مختصر للمساق</label>
            <textarea rows="4" disabled>{{ $course->short_description }}</textarea>
        </div>

        <div class="form-actions">
            <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i> العودة للقائمة
            </a>
            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> تعديل البيانات
            </a>
        </div>
    </form>
</div>
@endsection