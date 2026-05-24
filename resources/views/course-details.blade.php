@extends('cms.parent')

@section('title', $course->course_name . ' | CYBEReye')

@section('styles')
    <link rel="stylesheet" href="{{ asset('cms/css/course-details.css') }}">
    <style>
        main { padding-top: 80px; }
        .btn-primary { background: #4361ee; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    </style>
@endsection

@section('content')
    <main dir="rtl" style="text-align: right;">
        <section class="course-hero" style="background: #1a1a2e; color: white; padding: 4rem 0;">
            <div class="container" style="max-width: 1200px; margin: auto; padding: 0 20px;">
                <h1 class="course-title">{{ $course->course_name }}</h1>
                <div class="course-meta" style="display: flex; gap: 20px; margin-top: 1rem;">
                    <div class="meta-item"><i class="fas fa-chart-line"></i> المستوى: {{ $course->level }}</div>
                    <div class="meta-item"><i class="fas fa-clock"></i> مدة الكورس: {{ $course->no_hours }} ساعة</div>
                    <div class="meta-item"><i class="fas fa-star" style="color: #ffc107;"></i> {{ number_format($course->reviews->avg('rating') ?: 5, 1) }} / 5</div>
                </div>

                <p style="font-size: 1.2rem; margin-top: 1.5rem; opacity: 0.9;">{{ $course->short_description }}</p>

                <div class="course-actions" style="margin-top: 2rem;">
                  <a href="{{ route('course.player', $course->id) }}" class="btn-primary">
                    <i class="fas fa-play-circle"></i> ابدأ الكورس الآن
                  </a>
                </div>
            </div>
        </section>

        <div class="container" style="max-width: 1200px; margin: auto; padding: 0 20px;">
            <div class="course-content" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 3rem;">

                <div class="course-main">
                    <div class="content-card" style="background: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                        <h2><i class="fas fa-info-circle"></i> وصف الكورس</h2>
                        <p style="color: #4a5568; line-height: 1.8;">{{ $course->short_description }}</p>
                    </div>

                    <div class="content-card" style="background: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-right: 5px solid #10b981;">
                        <h2 style="color: #1a1a2e; margin-bottom: 1.5rem;"><i class="fas fa-file-download" style="color: #10b981;"></i> المصادر والملفات المرفقة</h2>
                        @if($course->materials->count() > 0)
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px;">
                                @foreach($course->materials as $material)
                                <div style="border: 1px solid #edf2f7; padding: 15px; border-radius: 12px; display: flex; align-items: center; gap: 12px; background: #f8fafc;">
                                    <div style="font-size: 1.8rem;">
                                        <i class="fas {{ $material->file_type == 'pdf' ? 'fa-file-pdf' : 'fa-file-alt' }}" style="color: {{ $material->file_type == 'pdf' ? '#ef4444' : '#6b7280' }};"></i>
                                    </div>
                                    <div style="flex-grow: 1;">
                                        <h4 style="font-size: 0.95rem; margin: 0; color: #2d3748;">{{ Str::limit($material->title, 18) }}</h4>
                                        <small style="color: #718096;">{{ strtoupper($material->file_type) }}</small>
                                    </div>
                                    <a href="{{ route('materials.show', $material->id) }}" style="color: #10b981; font-size: 1.3rem;"><i class="fas fa-cloud-download-alt"></i></a>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p style="color: #9ca3af;">لا توجد ملفات مرفقة حالياً.</p>
                        @endif
                    </div>

                    {{-- نظام التقييم --}}
                    <div class="rating-section" style="background: white; padding: 2rem; border-radius: 12px; margin-top: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                        <h2 style="margin-bottom: 1.5rem;"><i class="fas fa-star" style="color: #ffc107;"></i> تقييم الكورس</h2>
                        <form action="{{ route('course.review.store', $course->id) }}" method="POST">
                            @csrf
                            <div class="stars-input" style="direction: ltr; text-align: right; font-size: 2rem; margin-bottom: 1rem;">
                                <input type="hidden" name="rating" id="ratingValue" value="5">
                                <span class="star-btn" data-v="5" style="cursor: pointer; color: #ffc107;">★</span>
                                <span class="star-btn" data-v="4" style="cursor: pointer; color: #ffc107;">★</span>
                                <span class="star-btn" data-v="3" style="cursor: pointer; color: #ffc107;">★</span>
                                <span class="star-btn" data-v="2" style="cursor: pointer; color: #ffc107;">★</span>
                                <span class="star-btn" data-v="1" style="cursor: pointer; color: #ffc107;">★</span>
                            </div>
                            <textarea name="comment" placeholder="شاركنا برأيك..." style="width: 100%; height: 100px; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0;"></textarea>
                            @auth('student')
                                <button type="submit" class="btn-primary" style="margin-top: 1rem;">إرسال التقييم</button>
                            @else
                                <button type="button" onclick="showLoginAlert()" class="btn-primary" style="margin-top: 1rem;">إرسال التقييم</button>
                            @endauth
                        </form>
                        {{-- بداية قسم عرض التعليقات --}}
                        <div class="reviews-list" style="margin-top: 3rem; border-top: 1px solid #edf2f7; padding-top: 2rem;">
                            <h3 style="margin-bottom: 1.5rem; font-size: 1.3rem;">
                                <i class="fas fa-comments" style="color: #4361ee;"></i> آراء الطلاب ({{ $course->reviews->count() }})
                            </h3>

                            @forelse($course->reviews as $review)
                                <div class="review-item" style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; margin-bottom: 1.2rem; border: 1px solid #e2e8f0;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="width: 40px; height: 40px; background: #4361ee; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                {{ Str::upper(Str::substr($review->student->user1->username ?? 'S', 0, 1)) }}
                                            </div>
                                            <strong>{{ $review->user->username ?? 'طالب مجهول' }}</strong>
                                        </div>
                                        <div style="color: #ffc107; font-size: 0.9rem;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p style="color: #4a5568; line-height: 1.6; margin: 0;">{{ $review->comment }}</p>
                                    <div style="text-align: left; margin-top: 0.5rem;">
                                        <small style="color: #94a3b8; font-size: 0.8rem;">
                                            <i class="far fa-clock"></i> {{ $review->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            @empty
                                <div style="text-align: center; padding: 2rem; color: #94a3b8;">
                                    <i class="fas fa-comment-slash" style="font-size: 2rem; display: block; margin-bottom: 1rem;"></i>
                                    لا توجد تقييمات لهذا الكورس بعد.
                                </div>
                            @endforelse
                        </div>
                        {{-- نهاية قسم عرض التعليقات --}}
                    </div>
                </div>

                {{-- ======================= الكولكشن الجانبي (السايدبار) ======================= --}}
                <div class="course-sidebar">
                    <div class="instructor-card" style="background: white; padding: 1.5rem; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 1.5rem;">
                        {{-- <img src="{{ asset('storage/' . ($course->instructor->user1->profile_image ?? 'default.jpg')) }}" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #4361ee;"> --}}

    <div style="margin: 15px auto;">
    <div style="background: #1e1e23; padding: 10px; border-radius: 16px; display: inline-block; box-shadow: 0 0 10px rgba(187,134,252,0.3);">
        <img src="{{ $qrCodeUrl }}" alt="QR Code" style="width: 120px; height: 120px; border-radius: 12px;">
    </div>
    <p style="font-size: 11px; color: #bb86fc; margin-top: 8px; margin-bottom: 0;">
        <i class="fas fa-qrcode"></i> اسحب للتواصل مع المدرب
    </p>
</div>

                        <h4 style="margin-top: 1rem;">{{ $course->instructor->user1->username }}</h4>
                        <p style="color: #64748b;">{{ $course->instructor->specialization ?? 'خبير أمن سيبراني' }}</p>
                    </div>

                    <div class="stats-card" style="background: white; padding: 1.8rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                        <h3 style="font-size: 1.1rem; margin-bottom: 1.5rem; color: #1e293b;">مميزات الكورس:</h3>
                        <div style="margin-bottom: 15px;"><i class="fas fa-video" style="color: #4361ee;"></i> {{ $course->lessons->count() }}+ درس تعليمي</div>
                        <div style="margin-bottom: 15px;"><i class="fas fa-file-code" style="color: #4361ee;"></i> {{ $course->materials->count() }} مصادر</div>
                        <div style="margin-bottom: 15px;"><i class="fas fa-clock" style="color: #4361ee;"></i> {{ $course->no_hours }} ساعة</div>
                        <div><i class="fas fa-certificate" style="color: #ffc107;"></i> شهادة إتمام معتمدة</div>
                    </div>
                </div>
                {{-- ================================================================= --}}

            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.star-btn').forEach(star => {
            star.onclick = function() {
                let val = this.getAttribute('data-v');
                document.getElementById('ratingValue').value = val;
                document.querySelectorAll('.star-btn').forEach(s => {
                    s.style.color = s.getAttribute('data-v') <= val ? '#ffc107' : '#e2e8f0';
                });
            }
        });

        function showLoginAlert() {
            if(confirm("يجب عليك تسجيل الدخول أولاً لتتمكن من إضافة تقييم.")) {
                window.location.href = "{{ route('view.login', ['guard' => 'student']) }}";
            }
        }
    </script>
@endsection
