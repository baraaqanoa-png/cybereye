@extends('cms.parent')

@section('title', 'المكتبة التعليمية - CyberEye')

@section('styles')
<style>
    .admin-main { background-color: #0f0a1a !important; }
    .main-wrapper-custom { width: 100%; padding: 40px; background-color: #0f0a1a; min-height: 100vh; direction: rtl; box-sizing: border-box; }

    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
    .page-header-text h1 { color: #ffffff; font-size: 28px; font-weight: 800; margin: 0; }
    .page-header-text p { color: #8a7a9c; margin-top: 8px; font-size: 15px; }

    .btn-add-main {
        background-color: #9b59b6; color: white !important; padding: 12px 24px; border-radius: 12px;
        font-weight: 700; display: flex; align-items: center; gap: 10px; transition: 0.3s;
        box-shadow: 0 4px 15px rgba(155, 89, 182, 0.2); border: none; text-decoration: none;
    }
    .btn-add-main:hover { background-color: #bf77f0; transform: translateY(-2px); }

    .materials-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }

    .material-card {
        background: #0d0818; border: 1px solid #2a1a3a; border-radius: 15px; overflow: hidden;
        display: flex; flex-direction: column; transition: all 0.4s ease;
    }
    .material-card:hover { border-color: #9b59b6; transform: translateY(-8px); box-shadow: 0 10px 30px rgba(155, 89, 182, 0.1); }

    .file-icon-wrapper {
        height: 120px; background: #0d0818; display: flex; align-items: center;
        justify-content: center; position: relative; border-bottom: 1px solid #2a1a3a;
    }
    .file-type-badge {
        position: absolute; top: 10px; right: 10px; background: #9b59b6;
        color: white; padding: 2px 10px; border-radius: 5px; font-size: 10px; font-weight: 800;
    }

    .card-content { padding: 20px; flex-grow: 1; }

    .course-badge {
        display: inline-block; background: rgba(155, 89, 182, 0.1); color: #9b59b6;
        padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700;
        margin-bottom: 12px; border: 1px solid rgba(155, 89, 182, 0.2);
    }

    .card-content h3 { color: #ffffff; font-size: 18px; font-weight: 800; margin-bottom: 10px; }
    .card-content p { color: #8a7a9c; font-size: 13px; line-height: 1.5; }

    .card-footer {
        padding: 15px 20px; background: #0f0a1a; display: flex; justify-content: space-between;
        align-items: center; border-top: 1px solid #2a1a3a;
    }
    .download-stat { color: #8a7a9c; font-size: 12px; display: flex; align-items: center; gap: 5px; }

    .action-btns { display: flex; gap: 8px; }
    .btn-icon {
        width: 35px; height: 35px; border-radius: 8px; display: flex; align-items: center;
        justify-content: center; text-decoration: none; transition: 0.3s; font-size: 14px;
        border: none; cursor: pointer;
    }
    .btn-download { background: rgba(155, 89, 182, 0.1); color: #9b59b6; }
    .btn-download:hover { background: #9b59b6; color: white; }
    .btn-edit { background: rgba(155, 89, 182, 0.1); color: #9b59b6; }
    .btn-edit:hover { background: #9b59b6; color: white; }
    .btn-delete { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .btn-delete:hover { background: #ef4444; color: white; }
</style>
@endsection

@section('content')
<div class="main-wrapper-custom">
    <div class="page-header">
        <div class="page-header-text">
            <h1>إدارة المواد التعليمية</h1>
            <p>إدارة الملفات والمصادر التعليمية الخاصة بالكورسات</p>
        </div>

        <div class="actions" style="display: flex; gap: 10px;">
            <a href="{{ route('materials.trashed') }}" class="btn-add-main" style="background-color: #2a1a3a; box-shadow: none;">
                <i class="fas fa-archive"></i> الأرشيف
            </a>

            <a href="{{ route('materials.create', ['course_id' => $courseId]) }}" class="btn-add-main">
                <i class="fas fa-plus"></i> رفع مادة جديدة
            </a>
        </div>
    </div>

    <div class="materials-grid">
        @forelse($materials as $material)
        <div class="material-card" id="card-{{ $material->id }}">
            <input type="hidden" name="course_id" value="{{ $courseId }}">
            <div class="file-icon-wrapper">
                <span class="file-type-badge">{{ strtoupper($material->file_type) }}</span>
                @if($material->file_type == 'pdf')
                    <i class="fas fa-file-pdf fa-4x" style="color: #ef4444;"></i>
                @elseif(in_array($material->file_type, ['doc', 'docx']))
                    <i class="fas fa-file-word fa-4x" style="color: #3b82f6;"></i>
                @elseif(in_array($material->file_type, ['xls', 'xlsx']))
                    <i class="fas fa-file-excel fa-4x" style="color: #10b981;"></i>
                @else
                    <i class="fas fa-file-alt fa-4x" style="color: #9b59b6;"></i>
                @endif
            </div>

            <div class="card-content">
                <span class="course-badge">
                    <i class="fas fa-graduation-cap ml-1"></i>
                    {{ $material->course->course_name ?? ($material->course->title ?? 'كورس غير معروف') }}
                </span>
                <h3>{{ $material->title }}</h3>
                <p>{{ Str::limit($material->description, 80) }}</p>
            </div>

            <div class="card-footer">
                <div class="download-stat">
                    <i class="fas fa-calendar-alt"></i> {{ $material->created_at->format('Y-m-d') }}
                </div>
                <div class="action-btns">
                    <button type="button" onclick="confirmDelete('{{ $material->id }}', this)" class="btn-icon btn-delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>

                    <a href="{{ route('materials.edit', $material->id) }}" class="btn-icon btn-edit">
                        <i class="fas fa-edit"></i>
                    </a>

                    <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="btn-icon btn-download">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                <i class="fas fa-folder-open fa-4x" style="color: #2a1a3a; margin-bottom: 20px;"></i>
                <p style="color: #8a7a9c;">لا توجد مواد تعليمية نشطة حالياً.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete(id, reference) {
        Swal.fire({
            title: 'هل تريد نقل المادة للأرشيف؟',
            text: "يمكنك العثور عليها واستعادتها من قسم الأرشيف لاحقاً",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#9b59b6',
            cancelButtonColor: '#374151',
            confirmButtonText: 'نعم، أرشفها',
            cancelButtonText: 'إلغاء',
            background: '#0d0818',
            color: '#d1c4e0'
        }).then((result) => {
            if (result.isConfirmed) {
                performDelete(id, reference);
            }
        });
    }

    function performDelete(id, reference) {
        axios.delete('/cms/instructor/materials/' + id)
            .then(function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم النقل للأرشيف',
                    text: response.data.title || 'تمت العملية بنجاح',
                    background: '#0d0818',
                    color: '#d1c4e0',
                    showConfirmButton: false,
                    timer: 1500
                });

                document.getElementById('card-' + id).remove();

                setTimeout(() => {
                    if (document.querySelectorAll('.material-card').length === 0) {
                        location.reload();
                    }
                }, 1600);
            })
            .catch(function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ في العملية',
                    text: 'حدث خطأ غير متوقع، يرجى المحاولة لاحقاً',
                    background: '#0d0818',
                    color: '#d1c4e0',
                    confirmButtonColor: '#9b59b6'
                });
            });
    }
</script>
@endsection