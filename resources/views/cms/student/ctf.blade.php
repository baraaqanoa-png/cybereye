@extends('cms.parent')

@section('title', 'CTF - تحديات الأمن السيبراني')

@section('styles')

<style>
    /* ========== تنسيق قسم الـ CTF الاحترافي ========== */
    .ctf-section {
        padding: 60px 20px;
        background: linear-gradient(135deg, #0a0c10 0%, #0f1118 100%);
        min-height: 100vh;
        font-family: 'Cairo', sans-serif;
        direction: rtl;
        position: relative;
    }

    /* خلفية شبكية احترافية */
    .ctf-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            linear-gradient(rgba(139, 92, 246, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(139, 92, 246, 0.03) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }

    .ctf-header {
        text-align: center;
        margin-bottom: 60px;
        position: relative;
    }

    .ctf-header h1 {
        font-size: 3rem;
        background: linear-gradient(135deg, #a855f7, #7c3aed, #c084fc);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-shadow: 0 0 30px rgba(168, 85, 247, 0.2);
        margin-bottom: 15px;
        font-weight: 800;
    }

    .ctf-header p {
        color: #94a3b8;
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .ctf-container {
        max-width: 800px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 30px;
        position: relative;
    }

    /* كارد التحدي */
    .ctf-card {
        background: linear-gradient(135deg, rgba(17, 24, 39, 0.95), rgba(10, 14, 23, 0.98));
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 24px;
        padding: 0;
        display: flex;
        flex-direction: column;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    /* شريط جانبي ملون */
    .ctf-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(135deg, #a855f7, #7c3aed, #c084fc);
        border-radius: 24px 0 0 24px;
    }

    .ctf-card:hover {
        transform: translateX(-8px);
        border-color: rgba(168, 85, 247, 0.5);
        box-shadow: 0 20px 40px rgba(139, 92, 246, 0.2);
    }

    .ctf-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px 0 25px;
    }

    .badge {
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .badge.easy { 
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(168, 85, 247, 0.1));
        color: #c084fc;
        border: 1px solid rgba(168, 85, 247, 0.3);
    }

    .badge.medium { 
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.25), rgba(192, 132, 252, 0.1));
        color: #d8b4fe;
        border: 1px solid rgba(192, 132, 252, 0.4);
    }

    .points {
        font-size: 0.9rem;
        color: #fbbf24;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 5px;
        background: rgba(251, 191, 36, 0.1);
        padding: 4px 12px;
        border-radius: 30px;
    }

    .points i {
        font-size: 0.8rem;
    }

    .ctf-card h3 {
        font-size: 1.3rem;
        padding: 10px 25px 0 25px;
        margin: 0;
        background: linear-gradient(135deg, #e9d5ff, #c084fc);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-weight: 700;
    }

    .ctf-desc {
        color: #9ca3af;
        font-size: 0.9rem;
        line-height: 1.6;
        padding: 10px 25px;
        margin: 0;
        border-bottom: 1px solid rgba(139, 92, 246, 0.1);
    }

    .ctf-hint-box {
        background: rgba(0, 0, 0, 0.4);
        margin: 0 25px 15px 25px;
        padding: 15px;
        border-radius: 12px;
        border-right: 3px solid #a855f7;
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        color: #c084fc;
        direction: ltr;
        text-align: left;
        word-break: break-all;
        backdrop-filter: blur(5px);
    }

    .execution-box pre {
        margin: 0;
        color: #e2e8f0;
        font-family: 'Courier New', monospace;
        font-size: 0.8rem;
        white-space: pre-wrap;
    }

    /* نموذج إدخال العلم */
    .flag-input-group {
        display: flex;
        gap: 12px;
        padding: 0 25px 20px 25px;
        margin-top: auto;
    }

    .flag-input {
        flex: 1;
        background: rgba(0, 0, 0, 0.5);
        border: 1.5px solid rgba(139, 92, 246, 0.3);
        padding: 12px 18px;
        border-radius: 12px;
        color: #ffffff;
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        outline: none;
        transition: all 0.3s;
    }

    .flag-input:focus {
        border-color: #a855f7;
        box-shadow: 0 0 15px rgba(168, 85, 247, 0.3);
        background: rgba(0, 0, 0, 0.7);
    }

    .flag-input::placeholder {
        color: #4a5568;
        font-family: 'Cairo', sans-serif;
    }

    .flag-submit-btn {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        border: none;
        padding: 0 24px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Cairo', sans-serif;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
    }

    .flag-submit-btn:hover {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(124, 58, 237, 0.4);
    }

    .flag-submit-btn:active {
        transform: translateY(0);
    }

    /* رسائل الرد */
    .ctf-response-msg {
        margin: 0 25px 20px 25px;
        font-size: 0.85rem;
        font-weight: 500;
        display: none;
        padding: 12px;
        border-radius: 12px;
        text-align: center;
        backdrop-filter: blur(5px);
    }

    .msg-success { 
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.15), rgba(168, 85, 247, 0.1));
        color: #c084fc;
        border: 1px solid rgba(168, 85, 247, 0.3);
    }

    .msg-error { 
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.05));
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* أنيميشن تحميل */
    @keyframes pulseGlow {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }

    .loading {
        animation: pulseGlow 1s ease-in-out infinite;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .ctf-card-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
        
        .flag-input-group {
            flex-direction: column;
        }
        
        .flag-submit-btn {
            justify-content: center;
            padding: 12px;
        }
        
        .ctf-header h1 {
            font-size: 2rem;
        }
    }

    /* علامة التحدي المحلول */
.solved-badge {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-right: 10px;
}

.solved-icon {
    color: #10b981;
    margin-left: 8px;
    font-size: 1rem;
}

.card-solved {
    border-color: #10b981 !important;
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.2);
}

.card-solved::before {
    background: linear-gradient(135deg, #10b981, #059669) !important;
}
</style>
@endsection

@section('content')

<div class="ctf-section" id="ctf-challenges">
    <div class="ctf-header">
        <h1><i class="fas fa-flag-checkered"></i> مختبر التحديات</h1>
        <p>اختبر مهاراتك في الأمن السيبراني، حل التحديات واستخرج الأعلام المخفية</p>
    </div>

    <div class="ctf-container">
        
        <!-- التحدي 1 -->
        <div class="ctf-card" data-id="1">
            <div class="ctf-card-header">
                <span class="badge easy"><i class="fas fa-seedling"></i> سهل</span>
                <span class="points"><i class="fas fa-coins"></i> 50 نقطة</span>
            </div>
            <h3>🧩 التحدي 1: شفرة القيصر</h3>
            <p class="ctf-desc">تم اعتراض رسالة مشفرة بين مهاجمين تستخدم تشفير روت-13 (ROT13). هل يمكنك معرفة النص الأصلي واستخراج الفلاج؟</p>
            <div class="ctf-hint-box">
                <code>الرسالة المشفرة: PloreslIr{Pel_v1_f3pher}</code>
            </div>
            
            <form class="flag-form" onsubmit="submitFlag(event, 1)">
                @csrf
                <div class="flag-input-group">
                    <input type="text" class="flag-input" placeholder="أدخل الفلاج بصيغة CyberEye{...}" required autocomplete="off">
                    <button type="submit" class="flag-submit-btn"><i class="fas fa-paper-plane"></i> إرسال</button>
                </div>
            </form>
            <div class="ctf-response-msg"></div>
        </div>

        <!-- التحدي 2 -->
        <div class="ctf-card" data-id="2">
            <div class="ctf-card-header">
                <span class="badge easy"><i class="fas fa-seedling"></i> سهل</span>
                <span class="points"><i class="fas fa-coins"></i> 70 نقطة</span>
            </div>
            <h3>🌐 التحدي 2: أسرار المطور</h3>
            <p class="ctf-desc">بعض المطورين المبتدئين يتركون معلومات حساسة في أماكن لا يجب على المستخدم العادي رؤيتها. ابحث في هذه الصفحة نفسها (أو كود المصدر) لتجد الفلاج المخفي!</p>
            <div class="ctf-hint-box">
                <code>💡 تلميح: استخدم أدوات المطور (F12) وابحث في الـ Console أو الـ HTML</code>
            </div>
            
            <form class="flag-form" onsubmit="submitFlag(event, 2)">
                @csrf
                <div class="flag-input-group">
                    <input type="text" class="flag-input" placeholder="أدخل الفلاج بصيغة CyberEye{...}" required autocomplete="off">
                    <button type="submit" class="flag-submit-btn"><i class="fas fa-paper-plane"></i> إرسال</button>
                </div>
            </form>
            <div class="ctf-response-msg"></div>
        </div>

        <!-- التحدي 3 -->
        <div class="ctf-card" data-id="3">
            <div class="ctf-card-header">
                <span class="badge medium"><i class="fas fa-chart-line"></i> متوسط</span>
                <span class="points"><i class="fas fa-coins"></i> 100 نقطة</span>
            </div>
            <h3>🔌 التحدي 3: هجوم النص الواضح</h3>
            <p class="ctf-desc">قام أحد المستخدمين بتسجيل الدخول إلى موقع قديم يستخدم بروتوكول غير مشفر (HTTP). إذا علمت أن الفلاج هو اسم المستخدم وكلمة المرور معاً بصيغة: <code>CyberEye{username_password}</code>، فما هو الفلاج بناءً على السجل التالي؟</p>
            <div class="ctf-hint-box execution-box">
                <pre>POST /login.php HTTP/1.1
Host: insecure-site.com
Content-Type: application/x-www-form-urlencoded

user=admin&pass=SuP3r_S3cur3_P4ss</pre>
            </div>

            <form class="flag-form" onsubmit="submitFlag(event, 3)">
                @csrf
                <div class="flag-input-group">
                    <input type="text" class="flag-input" placeholder="أدخل الفلاج بصيغة CyberEye{...}" required autocomplete="off">
                    <button type="submit" class="flag-submit-btn"><i class="fas fa-paper-plane"></i> إرسال</button>
                </div>
            </form>
            <div class="ctf-response-msg"></div>
        </div>

    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // الأعلام الصحيحة لكل تحدي
    const CORRECT_FLAGS = {
        1: 'CyberEye{Cry_v1_ph3re}',
        2: 'CyberEye{1nsp3ct_3l3m3nt_iS_c00l}',
        3: 'CyberEye{admin_SuP3r_S3cur3_P4ss}'
    };
    
    // مفتاح التخزين في localStorage للتحديات المحلولة
    const SOLVED_KEY = 'ctf_solved_challenges';
    
    // تحميل التحديات المحلولة
    let solvedChallenges = JSON.parse(localStorage.getItem(SOLVED_KEY) || '[]');
    
    // دالة لحفظ التحدي كمحلول
    function saveSolvedChallenge(challengeId) {
        if (!solvedChallenges.includes(challengeId)) {
            solvedChallenges.push(challengeId);
            localStorage.setItem(SOLVED_KEY, JSON.stringify(solvedChallenges));
        }
    }
    
    // دالة لإظهار علامة صح على التحدي
    function markAsSolved(challengeId) {
        const card = document.querySelector(`.ctf-card[data-id="${challengeId}"]`);
        if (!card) return;
        
        card.classList.add('card-solved');
        
        // إضافة علامة صح في header
        const header = card.querySelector('.ctf-card-header');
        if (header && !header.querySelector('.solved-badge')) {
            const solvedBadge = document.createElement('span');
            solvedBadge.className = 'solved-badge';
            solvedBadge.innerHTML = '<i class="fas fa-check-circle"></i> تم الحل ✓';
            header.appendChild(solvedBadge);
        }
        
        // تعطيل زر الإرسال وحقل الإدخال
        const submitBtn = card.querySelector('.flag-submit-btn');
        const inputField = card.querySelector('.flag-input');
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> تم الحل';
            submitBtn.style.opacity = '0.6';
        }
        
        if (inputField) {
            inputField.disabled = true;
            inputField.style.opacity = '0.6';
        }
    }
    
    // دالة لتحميل التحديات المحلولة
    function loadSolvedChallenges() {
        solvedChallenges.forEach(challengeId => {
            markAsSolved(challengeId);
        });
    }
    
    // الدالة الرئيسية لإرسال الفلاج
    window.submitFlag = function(event, challengeId) {
        event.preventDefault();
        
        const form = event.target;
        const inputField = form.querySelector('.flag-input');
        const flagValue = inputField ? inputField.value.trim() : '';
        
        // التحقق من التحدي المحلول مسبقاً
        if (solvedChallenges.includes(challengeId)) {
            Swal.fire({
                icon: 'info',
                title: 'تم الحل مسبقاً',
                text: 'لقد قمت بحل هذا التحدي بالفعل!',
                background: '#111827',
                color: '#fff',
                confirmButtonColor: '#8b5cf6'
            });
            return;
        }
        
        if (!flagValue) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'الرجاء إدخال الفلاج أولاً',
                background: '#111827',
                color: '#fff'
            });
            return;
        }
        
        // إظهار رسالة التحميل
        Swal.fire({
            title: 'جاري التحقق...',
            text: 'الرجاء الانتظار',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // التحقق المحلي أولاً (للتجربة السريعة)
        if (CORRECT_FLAGS[challengeId] === flagValue) {
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: '✅ إجابة صحيحة!',
                    text: '🎉 تهانينا! الفلاج صحيح. لقد حصلت على النقاط!',
                    background: '#111827',
                    color: '#fff',
                    confirmButtonColor: '#8b5cf6'
                });
                saveSolvedChallenge(challengeId);
                markAsSolved(challengeId);
            }, 500);
        } else {
            setTimeout(() => {
                Swal.fire({
                    icon: 'error',
                    title: '❌ إجابة خاطئة',
                    text: 'الفلاج غير صحيح. حاول مرة أخرى!',
                    background: '#111827',
                    color: '#fff',
                    confirmButtonColor: '#8b5cf6'
                });
            }, 500);
        }
    };
    
    // تحميل التحديات المحلولة عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        loadSolvedChallenges();
        console.log('CTF Page Loaded - Solved Challenges:', solvedChallenges);
        console.log('أعلام التحديات الصحيحة:');
        console.log('  التحدي 1: CyberEye{Cry_v1_ph3re}');
        console.log('  التحدي 2: CyberEye{1nsp3ct_3l3m3nt_iS_c00l}');
        console.log('  التحدي 3: CyberEye{admin_SuP3r_S3cur3_P4ss}');
    });
</script>
@endsection
