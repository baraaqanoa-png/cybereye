 @extends('cms.parent')

@section('title','Start To Quiz Now')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $quizz->title }} | Verif. Quiz</title>
    <style>
        /* نفس الـ CSS الموجود (لا تغيير) */
        * { margin:0; padding:0; box-sizing: border-box; }
        body {
            background: #0a0f1c !important;
            font-family: 'Segoe UI', system-ui, sans-serif;
            display: flex;
            min-height: 100vh;
            padding: 24px 20px;
        }
        .quiz-shifted {
            width: 100%;
            max-width: 1000px;
            margin-left: 22%;
            margin-right: 2%;
        }
        .quiz-card {
            background: #111827;
            border-radius: 28px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.4);
            overflow: hidden;
            border: 1px solid #1f2937;
        }
        .verif-header {
            padding: 20px 28px 8px;
            border-bottom: 1px solid #1f2937;
            background: #0f172a;
        }
        .verif-badge {
            background: #1e293b;
            display: inline-block;
            padding: 4px 14px;
            border-radius: 40px;
            font-weight: 600;
            color: #60a5fa;
        }
        .question-section { padding: 16px 28px; }
        .q-meta {
            display: flex;
            justify-content: space-between;
            border-left: 4px solid #3b82f6;
            padding-left: 16px;
            margin-bottom: 20px;
            color: #cbd5e1;
        }
        .question-text {
            font-size: 1.55rem;
            font-weight: 600;
            margin: 12px 0 28px;
            color: #f1f5f9;
        }
        .options-area { display: flex; flex-direction: column; gap: 14px; margin-bottom: 28px; }
        .option-row {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 18px;
            padding: 12px 20px;
            cursor: pointer;
            transition: 0.2s;
            color:#bcd6f6 ;
        }
        .option-row:hover { background: #2d3a5e; border-color: #60a5fa; }
        .option-radio { width: 20px; height: 20px; accent-color: #3b82f6; }
        .option-letter {
            font-weight: 700;
            background: #8c9dc7;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 40px;
            color: #e2e8f0;
        }
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            padding: 8px 28px 24px;
            border-top: 1px solid #1f2937;
            background: #0f172a;
        }
        .nav-btn {
            background: #1e293b;
            border: 1px solid #334155;
            padding: 10px 28px;
            border-radius: 60px;
            font-weight: 600;
            cursor: pointer;
            color: #cbd5e1;
            transition: 0.2s;
        }
        .nav-btn:hover:not(:disabled) {
            background: #3a5682;
            color: white;
            border-color: #3a557e;
        }
        .nav-btn:disabled { opacity: 0.45; cursor: not-allowed; }
        .btn-submit { background: linear-gradient(95deg, #2563eb, #7c3aed); color: white; border: none; }
        .btn-submit:hover:not(:disabled) { filter: brightness(1.05); transform: translateY(-2px); }
        @media (max-width: 800px) { .quiz-shifted { margin-left: 5%; margin-right: 5%; } }
    </style>
@endsection

@section('content')
<div class="quiz-shifted">
    <div class="quiz-card">
        <div class="verif-header">
            <div class="verif-badge">{{ $quizz->title }}</div>
        </div>

        <form method="POST" action="{{ route('quiz.submit', $quizz->id) }}" id="quizForm">
            @csrf
            <div id="questionsContainer">
                @foreach($questions as $index => $question)
                    <div class="question-block" data-qid="{{ $question->id }}" style="{{ $index === 0 ? 'display:block' : 'display:none' }}">
                        <div class="question-section">
                            <div class="q-meta">
                                <span>سؤال {{ $index+1 }} من {{ count($questions) }}</span>
                                <span>{{ $question->type === 'mc' ? 'اختيار من متعدد' : 'صح/خطأ' }}</span>
                            </div>
                            <div class="question-text">{{ $question->title ?? $question->text }}</div>
                            <div class="options-area">
                                @php
                                    $optionsRaw = $question->options;
                                    if (is_string($optionsRaw)) {
                                        $optionsRaw = json_decode($optionsRaw, true);
                                    }
                                    $options = ($question->type === 'tf') ? ['True', 'False'] : ($optionsRaw ?? []);
                                    $letters = ['A','B','C','D','E','F'];
                                    $saved = $tempAnswers[$question->id] ?? null;
                                @endphp
                                @foreach($options as $optIdx => $opt)
                                    <label class="option-row">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $opt }}"
                                               {{ $saved === $opt ? 'checked' : '' }} class="option-radio" data-qid="{{ $question->id }}">
                                        <span class="option-letter">{{ $letters[$optIdx] ?? chr(65+$optIdx) }}</span>
                                        <span>{{ $opt }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="nav-buttons">
                <button type="button" id="prevBtn" class="nav-btn" disabled>◀ السابق</button>
                <button type="button" id="nextBtn" class="nav-btn">التالي ▶</button>
                <button type="submit" class="nav-btn btn-submit" id="submitExamBtn">✔ إنهاء الامتحان</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const blocks = document.querySelectorAll('.question-block');
    let current = 0;
    const total = blocks.length;
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    function showQuestion(index) {
        blocks.forEach((b, i) => b.style.display = i === index ? 'block' : 'none');
        prevBtn.disabled = (index === 0);

        // إذا وصلنا للسؤال الأخير، أخفِ زر "التالي" وأظهر زر "إنهاء الامتحان"
        if (index === total - 1) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-block';
        } else {
            nextBtn.style.display = 'inline-block';
            submitBtn.style.display = 'none';
        }
    }
    // function showQuestion(index) {
    //     blocks.forEach((b, i) => b.style.display = i === index ? 'block' : 'none');
    //     prevBtn.disabled = (index === 0);
    //     nextBtn.disabled = (index === total-1);
    // }
    prevBtn.onclick = () => { if(current > 0) { current--; showQuestion(current); } };
    nextBtn.onclick = () => { if(current < total-1) { current++; showQuestion(current); } };
    showQuestion(0);

    // حفظ مؤقت
    function collectAnswers() {
        let answers = {};
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            let name = radio.getAttribute('name');
            if (name) {
                let match = name.match(/\[(\d+)\]/);
                if (match) {
                    let qid = match[1];
                    answers[qid] = radio.value;
                }
            }
        });
        return answers;
    }

    function saveTempAnswers() {
        let answers = collectAnswers();
        fetch('{{ route("quiz.saveTemp", $quizz->id) }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ answers: answers })
        }).catch(e => console.warn('Temp save failed', e));
    }

    document.querySelectorAll('input[type="radio"]').forEach(r => r.addEventListener('change', saveTempAnswers));


    document.getElementById('quizForm').addEventListener('submit', function(e) {
        let totalQuestions = {{ count($questions) }};
        let answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;

        if (answeredQuestions < totalQuestions) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'تنبيه',
                text: 'يرجى التأكد من حل جميع الأسئلة قبل التسليم',
                confirmButtonColor: '#2563eb'
            });
        }
    });

    // عرض رسائل الخطأ من الخادم
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: '{{ session('error') }}',
            confirmButtonColor: '#2563eb'
        });
    @endif
</script>


</script>
@endsection

