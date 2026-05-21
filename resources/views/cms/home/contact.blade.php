@extends('cms.home.parent')

@section('title', 'CyberEye - comtact')

@section('styles')
<link rel="stylesheet" href="{{ asset('cms/css/contact.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="icon" type="image/x-icon" href="img/digital.jpg">
@endsection

@section('content')

    <section class="contact-hero">
        <div class="hero-content">
            <h1>Contact us</h1>
            <p>نحن هنا لمساعدتك في رحلتك التعليمية. تواصل معنا لأي استفسار أو دعم.</p>
        </div>
        <div class="wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="currentColor"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="currentColor"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="currentColor"></path>
            </svg>
        </div>
    </section>

    <div class="contact-container">
        <div class="contact-info-section">
            <h2 class="section-title">وسائل الاتصال</h2>
            <p class="section-subtitle">اختر الوسيلة المناسبة للتواصل معنا</p>

            <div class="contact-methods">
                <div class="contact-method">
                    <div class="method-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="method-content">
                        <h3>الهاتف</h3>
                        <p>متاح من الأحد إلى الخميس<br>9:00 ص - 5:00 م</p>
                        <a href="tel:+966123456789" class="method-link">
                            <i class="fas fa-phone"></i> +9705931475923
                        </a>
                    </div>
                </div>

                <div class="contact-method">
                    <div class="method-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="method-content">
                        <h3>البريد الإلكتروني</h3>
                        <p>سنرد عليك خلال 24 ساعة</p>
                        <a href="mailto:info@cybereye.com" class="method-link">
                            <i class="fas fa-envelope"></i> info@cybereye.com
                        </a>
                        <a href="mailto:support@cybereye.com" class="method-link">
                            <i class="fas fa-headset"></i> support@cybereye.com
                        </a>
                    </div>
                </div>

                <div class="contact-method">
                    <div class="method-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="method-content">
                        <h3>ساعات العمل</h3>
                        <p>الأحد - الخميس: 9:00 ص - 5:00 م</p>
                        <p>الجمعة - السبت: مغلق</p>
                        <p>الدعم الفني: 24/7</p>
                    </div>
                </div>
            </div>

            <div class="social-media-section">
                <h3> follow us</h3>
                <div class="social-icons">
                    <a href="https://twitter.com" target="_blank" class="social-icon twitter">
                        <i class="fab fa-twitter"></i>
                        <span>twitter</span>
                    </a>
                    <a href="https://facebook.com" target="_blank" class="social-icon facebook">
                        <i class="fab fa-facebook-f"></i>
                        <span>facebook</span>
                    </a>
                    <a href="https://linkedin.com" target="_blank" class="social-icon linkedin">
                        <i class="fab fa-linkedin-in"></i>
                        <span>linkedin</span>
                    </a>
                    <a href="https://instagram.com" target="_blank" class="social-icon instagram">
                        <i class="fab fa-instagram"></i>
                        <span>instagram</span>
                    </a>
                    <a href="https://youtube.com" target="_blank" class="social-icon youtube">
                        <i class="fab fa-youtube"></i>
                        <span>youtube</span>
                    </a>
                    <a href="https://whatsapp.com" target="_blank" class="social-icon whatsapp">
                        <i class="fab fa-whatsapp"></i>
                        <span>whatsapp</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="contact-form-section">
            <h2 class="section-title">أرسل لنا رسالة</h2>
            <p class="section-subtitle">سنكون سعداء بالرد على استفساراتك</p>

            {{-- إظهار رسالة النجاح إذا وجدت --}}
            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif

            <form class="contact-form" id="contactForm" action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user"></i>  full name
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="أدخل اسمك الكامل">
                        @error('name') <small style="color: red;">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> email
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="أدخل بريدك الإلكتروني">
                        @error('email') <small style="color: red;">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i> phone number
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="أدخل رقم هاتفك">
                    </div>

                    <div class="form-group">
                        <label for="subject">
                            <i class="fas fa-tag"></i> subject*
                        </label>
                        <select id="subject" name="subject" required>
                            <option value="">اختر الموضوع</option>
                            <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>الدعم الفني</option>
                            <option value="courses" {{ old('subject') == 'courses' ? 'selected' : '' }}>استفسار عن الدورات</option>
                            <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>شراكة وتعاون</option>
                            <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>مقترحات وملاحظات</option>
                            <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>موضوع آخر</option>
                        </select>
                        @error('subject') <small style="color: red;">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="message">
                        <i class="fas fa-comment-alt"></i> message *
                    </label>
                    <textarea id="message" name="message" rows="6" required placeholder="اكتب رسالتك هنا...">{{ old('message') }}</textarea>
                    @error('message') <small style="color: red;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" id="newsletter" name="newsletter">
                    <label for="newsletter">أرغب في تلقي النشرة الإخبارية والعروض الخاصة</label>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> send message
                </button>
            </form>
        </div>
    </div>

    <div class="faq-section" id="faq-section">
        <div class="container">
            <h2 class="section-title">الأسئلة الشائعة</h2>
            <p class="section-subtitle">إجابات على أكثر الأسئلة تكرراً</p>

            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>كيف يمكنني التسجيل في الدورات؟</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>يمكنك التسجيل في أي دورة عن طريق زيارة صفحة الدورات، اختيار الدورة المناسبة، والنقر على زر "التسجيل". ستتم عملية الدفع عبر بوابة آمنة، ثم تحصل على وصول فوري للمحتوى.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>هل تقدمون شهادات معتمدة؟</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>نعم، جميع دوراتنا تقدم شهادات إتمام معتمدة من CyberEye. يمكنك إضافتها إلى سيرتك الذاتية ومشاركتها على LinkedIn.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>كيف يمكنني التواصل مع المدرب؟</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>كل دورة تحتوي على منتدى مناقشة حيث يمكنك طرح الأسئلة على المدرب والطلاب الآخرين. كما يمكنك التواصل عبر البريد الإلكتروني الخاص بالدورة.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="{{ asset('cms/js/contact.js') }}"></script>
@endsection
