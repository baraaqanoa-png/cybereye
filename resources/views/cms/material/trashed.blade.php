@extends('cms.parent')

@section('title', 'أرشيف المواد التعليمية')

@section('styles')
<style>
    body, .admin-main, .content-wrapper { background-color: #0f0a1a !important; }
    .main-wrapper-fixed { width: 100%; padding: 40px; background-color: #0f0a1a; min-height: 100vh; direction: rtl; box-sizing: border-box; display: block; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
    .page-header h1 { color: #ffffff; font-size: 28px; margin: 0; }
    .btn-back { background-color: #2a1a3a; color: #8a7a9c !important; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; transition: 0.3s; }
    .btn-back:hover { background-color: #3a2a4a; color: #d1c4e0 !important; }
    .table-responsive { width: 100%; overflow-x: auto; background: #0d0818; border-radius: 12px; }
    .trashed-table { width: 100%; border-collapse: collapse; color: #d1c4e0; }
    .trashed-table th, .trashed-table td { padding: 18px; text-align: right; border-bottom: 1px solid #2a1a3a; }
    .trashed-table th { background-color: #2a1a3a; color: #8a7a9c; font-size: 14px; }
    .btn-action { padding: 6px 15px; border-radius: 6px; text-decoration: none; font-size: 13px; margin-left: 5px; display: inline-block; border: none; cursor: pointer; transition: 0.3s; }
    .btn-restore { background-color: #9b59b6; color: white !important; }
    .btn-restore:hover { background-color: #bf77f0; transform: translateY(-2px); }
    .btn-force { background-color: #ef4444; color: white !important; }
    .btn-force:hover { background-color: #dc2626; transform: translateY(-2px); }
    .empty-state { text-align: center; padding: 100px; color: #8a7a9c; }
    .empty-state i { color: #2a1a3a; }
</style>
@endsection

@section('content')
<div class="main-wrapper-fixed">
    <div class="page-header">
        <h1>أرشيف المواد المحذوفة</h1>
        <a href="{{ route('materials.index') }}" class="btn-back">
            <i class="fas fa-arrow-right"></i> العودة للمواد النشطة
        </a>
    </div>

    @if($materials->count() > 0)
        <div class="table-responsive">
            <table class="trashed-table">
                <thead>
                    <tr>
                        <th>عنوان المادة</th>
                        <th>الكورس</th>
                        <th>تاريخ الحذف</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materials as $material)
                    <tr>
                        <td>{{ $material->title }}</td>
                        <td>{{ $material->course->course_name ?? 'غير محدد' }}</td>
                        <td style="color: #8a7a9c;">{{ $material->deleted_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('materials.restore', $material->id) }}" class="btn-action btn-restore">
                                <i class="fas fa-undo"></i> استعادة
                            </a>

                            <a href="{{ route('materials.force', $material->id) }}"
                               onclick="return confirm('تحذير: سيتم حذف الملف نهائياً من السيرفر!')"
                               class="btn-action btn-force">
                                <i class="fas fa-times"></i> حذف نهائي
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-trash-alt fa-4x" style="margin-bottom: 20px;"></i>
            <h3>الأرشيف فارغ حالياً</h3>
        </div>
    @endif
</div>
@endsection