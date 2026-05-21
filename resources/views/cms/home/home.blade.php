@extends('cms.home.parent')

@section('title', 'CyberEye - Home')

@section('styles')
<link rel="stylesheet" href="{{ asset('cms/css/style.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    /* تنسيق القاموس الذكي */
    .dictionary-section {
        padding: 80px 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
        direction: rtl;
    }

    .dictionary-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        text-align: center;
    }

    .dictionary-title {
        font-size: 2rem;
        color: #1a237e;
        margin-bottom: 10px;
    }

    .dictionary-subtitle {
        color: #666;
        margin-bottom: 30px;
    }

    .search-box {
        position: relative;
        margin-bottom: 20px;
    }

    .search-input {
        width: 100%;
        padding: 15px 20px;
        font-size: 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 50px;
        outline: none;
        transition: all 0.3s;
        font-family: 'Cairo', sans-serif;
    }

    .search-input:focus {
        border-color: #1a237e;
        box-shadow: 0 0 10px rgba(26, 35, 126, 0.2);
    }

    .search-input::placeholder {
        color: #aaa;
    }

    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #1a237e;
        font-size: 1.2rem;
    }

    .result-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-top: 20px;
        text-align: right;
        display: none;
        border-right: 4px solid #4caf50;
    }

    .result-term {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a237e;
        margin-bottom: 10px;
    }

    .result-definition {
        color: #444;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .result-category {
        display: inline-block;
        background: #e8eaf6;
        color: #1a237e;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        margin-top: 10px;
    }

    .result-example {
        background: #fff3e0;
        padding: 12px;
        border-radius: 10px;
        margin-top: 15px;
        color: #e65100;
        font-size: 0.9rem;
    }

    .not-found {
        background: #ffebee;
        color: #c62828;
        padding: 20px;
        border-radius: 15px;
        margin-top: 20px;
        display: none;
    }

    .loading-spinner {
        display: none;
        margin-top: 20px;
        color: #1a237e;
    }

    /* سلايد شو القاموس القديم */
    .con {
        padding: 50px 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
        text-align: center;
    }

    .con h1 {
        font-size: 2.5rem;
        color: #1a237e;
        margin-bottom: 40px;
        position: relative;
        display: inline-block;
    }

    .con h1::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, #1a237e, #4caf50);
        border-radius: 2px;
    }

    .slideshow-container {
        position: relative;
        max-width: 800px;
        margin: auto;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .mySlides {
        display: none;
        animation: fade 1.5s ease;
    }

    .mySlides img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }

    @keyframes fade {
        from {opacity: 0.4}
        to {opacity: 1}
    }

    .dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        margin: 0 5px;
        background-color: #bbb;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .dot:hover {
        background-color: #1a237e;
        transform: scale(1.2);
    }

    .dot.active {
        background-color: #4caf50;
        width: 30px;
        border-radius: 6px;
    }

    .term-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
        padding: 20px;
        text-align: center;
    }

    .term-caption h3 {
        margin: 0;
        font-size: 1.5rem;
    }

    .term-caption p {
        margin: 5px 0 0;
        font-size: 0.9rem;
        opacity: 0.8;
    }

    @media (max-width: 768px) {
        .dictionary-container {
            margin: 0 20px;
            padding: 25px;
        }
        .mySlides img {
            height: 250px;
        }
    }

    /* ========== تنسيق قسم خارطة الطريق المطور ========== */
.roadmap-section {
    padding: 80px 20px;
    background: #0f172a; /* خلفية داكنة تناسب طابع الأمن السيبراني */
    color: #ffffff;
    font-family: 'Cairo', sans-serif;
    direction: rtl;
}

.roadmap-header {
    text-align: center;
    margin-bottom: 50px;
}

.roadmap-header h1 {
    font-size: 2.5rem;
    color: #38bdf8;
    margin-bottom: 15px;
}

.roadmap-header p {
    color: #94a3b8;
    font-size: 1.1rem;
}

/* صندوق النصيحة التفاعلي */
.guidance-container {
    max-width: 900px;
    margin: 0 auto 40px auto;
    background: #1e293b;
    border-radius: 12px;
    border: 1px solid #334155;
    overflow: hidden;
}

.guidance-toggle {
    width: 100%;
    padding: 20px;
    background: #1e293b;
    border: none;
    color: #f8fafc;
    font-size: 1.2rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: 'Cairo', sans-serif;
    transition: background 0.3s;
}

.guidance-toggle:hover {
    background: #334155;
}

.guidance-toggle .arrow-icon {
    transition: transform 0.3s ease;
}

.guidance-toggle.active .arrow-icon {
    transform: rotate(180deg);
}

.guidance-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
    background: #0f172a;
}

.guidance-inner {
    padding: 25px;
    border-top: 1px solid #334155;
    line-height: 1.8;
}

.guidance-inner h3 {
    color: #38bdf8;
    margin-bottom: 15px;
}

.guidance-inner ul {
    list-style: none;
    padding: 0;
}

.guidance-inner ul li {
    margin-bottom: 12px;
    position: relative;
    padding-right: 25px;
}

.guidance-inner ul li::before {
    content: "⚡";
    position: absolute;
    right: 0;
    top: 0;
}

/* أزرار اختيار المسار Tabs */
.interest-selection {
    text-align: center;
    margin-bottom: 50px;
}

.interest-selection h3 {
    margin-bottom: 20px;
    color: #e2e8f0;
}

.path-tabs {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}

.path-tab {
    padding: 14px 28px;
    background: #1e293b;
    border: 2px solid #334155;
    color: #94a3b8;
    border-radius: 50px;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.path-tab:hover {
    border-color: #38bdf8;
    color: #38bdf8;
}

.path-tab.active {
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    border-color: #38bdf8;
    color: #ffffff;
    box-shadow: 0 4px 20px rgba(56, 189, 248, 0.3);
}

/* هيكلية الشجرة التفاعلية Tree View CSS */
.tree-container {
    max-width: 1000px;
    margin: 0 auto;
}

.roadmap-tree {
    display: none;
    animation: fadeIn 0.5s ease-in-out forwards;
}

.roadmap-tree.active {
    display: block;
}

.tree-branch {
    position: relative;
    margin-bottom: 40px;
    padding-right: 30px;
    border-right: 3px dashed #334155;
}

.tree-branch::before {
    content: '';
    position: absolute;
    right: -8px;
    top: 0;
    width: 14px;
    height: 14px;
    background: #38bdf8;
    border-radius: 50%;
    box-shadow: 0 0 10px #38bdf8;
}

.node-root {
    background: #1e293b;
    padding: 12px 25px;
    border-radius: 8px;
    display: inline-block;
    font-weight: 700;
    color: #f8fafc;
    border: 1px solid #475569;
    margin-bottom: 20px;
}

.special-red { border-right: 5px solid #ef4444; color: #fca5a5; }
.special-blue { border-right: 5px solid #3b82f6; color: #93c5fd; }
.special-forensics { border-right: 5px solid #a855f7; color: #d8b4fe; }

.tree-children {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 15px;
}

.tree-node {
    background: #0f172a;
    border: 1px solid #334155;
    padding: 15px;
    border-radius: 8px;
    font-size: 0.95rem;
    color: #cbd5e1;
    position: relative;
    transition: all 0.3s ease;
}

.tree-node:hover {
    transform: translateY(-3px);
    background: #1e293b;
    border-color: #38bdf8;
    color: #ffffff;
}

.tree-node.leaves {
    border-right: 3px solid #10b981;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .path-tabs { flex-direction: column; width: 100%; }
    .tree-branch { border-right: none; padding-right: 0; text-align: center; }
    .tree-branch::before { display: none; }
    .tree-children { grid-template-columns: 1fr; }
}
/* تنسيقات بطاقة "هذا المسار يناسب من؟" */
.suitability-card {
    background: #1e293b;
    border-radius: 12px;
    padding: 20px 25px;
    margin-bottom: 35px;
    line-height: 1.8;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.suitability-card h4 {
    font-size: 1.2rem;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* الألوان المميزة لكل تخصص */
.special-red { border-right: 5px solid #ef4444; h4 { color: #f87171; } }
.special-blue { border-right: 5px solid #3b82f6; h4 { color: #60a5fa; } }
.special-purple { border-right: 5px solid #a855f7; h4 { color: #c084fc; } }
.special-orange { border-right: 5px solid #f97316; h4 { color: #fb923c; } }
.special-cyan { border-right: 5px solid #06b6d4; h4 { color: #22d3ee; } }
.special-green { border-right: 5px solid #10b981; h4 { color: #34d399; } }

/* تحسين مرونة الأزرار لتستوعب العدد الجديد من التخصصات */
.path-tabs {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
    max-width: 1100px;
    margin: 0 auto;
}

.path-tab {
    font-size: 0.95rem;
    padding: 12px 20px;
}
</style>
@endsection

@section('content')
<!-- القسم الرئيسي -->
<section>
    <div class="main" id="home">
        <div class="main-content">
            <div class="main-text">
                <h1>Cyber <br> <span>Security</span></h1>
                <p>Welcome to CyberEye, your ultimate guide to cybersecurity education. Learn everything from basics to advanced security techniques. Join thousands of students who have transformed their careers with our comprehensive courses and resources.</p>
            </div>

            <div class="main-image">
                <img src="{{ asset('cms/img/cyber.jpg') }}" alt="Cybersecurity">
            </div>
        </div>

        <div class="social-icon">
            <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#"><i class="fa-brands fa-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>

        <div class="button">
            <a href="{{ route('view.login', ['guard' => 'student']) }}">START NOW</a>
            <i class="fa-solid fa-chevron-right"></i>
        </div>
    </div>
</section>

<!-- ========== القاموس الذكي (البحث) ========== -->
<div class="dictionary-section" id="dictionary">
    <div class="dictionary-container">
        <h2 class="dictionary-title">📖 القاموس الذكي 🔍</h2>
        <p class="dictionary-subtitle">ابحث عن أي مصطلح أمني أو تقني</p>

        <div class="search-box">
            <input type="text" id="searchInput" class="search-input" placeholder="مثال: Phishing, Malware, Firewall, Laravel..." autocomplete="off">
            <i class="fas fa-search search-icon"></i>
        </div>

        <div class="loading-spinner" id="loadingSpinner">
            <i class="fas fa-spinner fa-spin"></i> جاري البحث...
        </div>

        <div class="result-card" id="resultCard">
            <div class="result-term" id="resultTerm"></div>
            <div class="result-definition" id="resultDefinition"></div>
            <div class="result-category" id="resultCategory"></div>
            <div class="result-example" id="resultExample"></div>
        </div>

        <div class="not-found" id="notFound">
            <i class="fas fa-times-circle"></i> <span id="notFoundMessage"></span>
        </div>
    </div>
</div>


<!-- Cybersecurity Roadmap -->
<div class="roadmap-section" id="roadmap" direction="rtl">
    <div class="roadmap-header">
        <h1>بوصلة مجالات الأمن السيبراني والـ Roadmap 🎯</h1>
        <p>اختر المجال الذي يثير اهتمامك واستكشف شجرته التعليمية، واعرف إن كان يناسب شخصيتك ومهاراتك</p>
    </div>

    <div class="guidance-container">
        <button class="guidance-toggle" id="guidanceToggle">
            <i class="fas fa-question-circle"></i> كيف أحدد مساري المناسب في الأمن السيبراني؟
            <i class="fas fa-chevron-down arrow-icon"></i>
        </button>
        <div class="guidance-content" id="guidanceContent">
            <div class="guidance-inner">
                <h3>💡 نصائح ذهبية لاختيار مسارك الصحيح:</h3>
                <ul>
                    <li><strong>ابدأ بالأساسيات أولاً:</strong> لا يمكنك حماية أو اختراق نظام لا تفهم كيف يعمل. تعلّم الشبكات (Networking) وأنظمة التشغيل (Linux & Windows) والبرمجة (Python/Bash) قبل اختيار تخصصك.</li>
                    <li><strong>هل تميل للهجوم أم الدفاع؟</strong> إذا كنت تستمتع بالبحث عن الثغرات والتفكير خارج الصندوق، فالمسار الأحمر <strong>(Red Team)</strong> هو لك. أما إذا كنت تفضل حماية الأنظمة وتتبع الأدلة الجنائية وتحليل الهجمات، فالمسار الأزرق <strong>(Blue Team)</strong> هو خيارك الأفضل.</li>
                    <li><strong>جرّب المنصات العملية:</strong> قم بإنشاء حسابات على موقع TryHackMe أو HackTheBox، وجرّب الغرف المخصصة للدفاع والغرف المخصصة للهجوم لمعرفة ما يثير شغفك أكثر.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="interest-selection">
        <h3>🔍 اختر مجال اهتمامك لرؤية التفاصيل والشجرة التعليمية:</h3>
        <div class="path-tabs">
            <button class="path-tab active" data-path="pentesting">
                <i class="fas fa-user-secret"></i> اختبار الاختراق (Penetration Testing)
            </button>
            <button class="path-tab" data-path="soc-analyst">
                <i class="fas fa-shield-alt"></i> التحليل الأمني (SOC Analyst)
            </button>
            <button class="path-tab" data-path="digital-forensics">
                <i class="fas fa-search-location"></i> الأدلة الجنائية الرقمية (Digital Forensics)
            </button>
            <button class="path-tab" data-path="malware-analysis">
                <i class="fas fa-virus-slash"></i> تحليل البرمجيات الخبيثة (Malware Analysis)
            </button>
            <button class="path-tab" data-path="cloud-security">
                <i class="fas fa-cloud-shield"></i> أمن الحوسبة السحابية (Cloud Security)
            </button>
            <button class="path-tab" data-path="grc">
                <i class="fas fa-file-contract"></i> الحوكمة والمخاطر والامتثال (GRC)
            </button>
        </div>
    </div>

    <div class="tree-container">

        <div class="roadmap-tree active" id="pentesting">
            <div class="suitability-card special-red">
                <h4><i class="fas fa-user-check"></i> هذا المسار يناسب مَن؟</h4>
                <p>يناسب الأشخاص الشغوفين بالاكتشاف والتفكير خارج الصندوق (Out of the box). إذا كنت تحب تفكيك الأشياء لمعرفة كيف تعمل، وتمتلك فضولاً لا ينتهي لكشف الثغرات ونقاط الضعف، ولديك صبر طويل على المحاولة والتكرار، فهذا هو مسارك الأحمر المثالي.</p>
            </div>
            
            <div class="tree-branch">
                <div class="node-root">المرحلة 1: الأساسيات المتينة</div>
                <div class="tree-children">
                    <div class="tree-node">الشبكات وبروتوكولاتها (TCP/IP, DNS, HTTP)</div>
                    <div class="tree-node">إدارة أنظمة Linux و سطر الأوامر (Bash)</div>
                    <div class="tree-node">أساسيات الويب (HTML, CSS, JavaScript, PHP)</div>
                </div>
            </div>
            <div class="tree-branch">
                <div class="node-root">المرحلة 2: الفحص وتقييم الثغرات</div>
                <div class="tree-children">
                    <div class="tree-node">جمع المعلومات والاستطلاع (OSINT)</div>
                    <div class="tree-node">أدوات الفحص والتحليل (Nmap, Burp Suite, Nessus)</div>
                    <div class="tree-node">برمجة السكربتات الخاصة بالأتمتة (Python)</div>
                </div>
            </div>
            <div class="tree-branch">
                <div class="node-root">المرحلة 3: الاستغلال والاحتراف</div>
                <div class="tree-children">
                    <div class="tree-node leaves">ثغرات تطبيقات الويب (OWASP Top 10)</div>
                    <div class="tree-node leaves">اختراق الشبكات الداخلية وبيئات الـ Active Directory</div>
                    <div class="tree-node leaves">كتابة التقارير الفنية وسد الثغرات</div>
                    <div class="tree-node leaves">الشهادات الموصى بها: eJPT ➔ PNPT ➔ OSCP</div>
                </div>
            </div>
        </div>

        <div class="roadmap-tree" id="soc-analyst">
            <div class="suitability-card special-blue">
                <h4><i class="fas fa-user-check"></i> هذا المسار يناسب مَن؟</h4>
                <p>يناسب الأشخاص المنظمين الذين يعشقون المراقبة والتحليل الدقيق وحماية الأنظمة. إذا كنت سريع البديهة في رصد الأنماط غير الطبيعية، وتحب العمل الجماعي تحت الضغط لإحباط الهجمات والتهديدات قبل وقوعها، فإن هندسة الدفاع والـ SOC هي خيارك.</p>
            </div>

            <div class="tree-branch">
                <div class="node-root">المرحلة 1: البنية التحتية والشبكات</div>
                <div class="tree-children">
                    <div class="tree-node">بنية الشبكات المتقدمة والأمنية (Firewalls, VPN, IDS/IPS)</div>
                    <div class="tree-node">إدارة خوادم (Windows Server & Linux Sysadmin)</div>
                </div>
            </div>
            <div class="tree-branch">
                <div class="node-root">المرحلة 2: المراقبة والتحليل</div>
                <div class="tree-children">
                    <div class="tree-node">تحليل حركة البيانات وحزم الشبكة (Wireshark)</div>
                    <div class="tree-node">فهم وتحليل سجلات النظام والأحداث (Log Analysis)</div>
                    <div class="tree-node">التعامل مع أنظمة إدارة الأحداث الأمنية (SIEM Tools: Splunk, ELK)</div>
                </div>
            </div>
            <div class="tree-branch">
                <div class="node-root">المرحلة 3: الاستجابة والهندسة الدفاعية</div>
                <div class="tree-children">
                    <div class="tree-node leaves">الاستجابة الفورية للحوادث السيبرانية (Incident Response)</div>
                    <div class="tree-node leaves">صيد التهديدات المتقدمة (Threat Hunting)</div>
                    <div class="tree-node leaves">الشهادات الموصى بها: Security+ ➔ CySA+ ➔ CCD (Certified Cyber Defense)</div>
                </div>
            </div>
        </div>

        <div class="roadmap-tree" id="digital-forensics">
            <div class="suitability-card special-purple">
                <h4><i class="fas fa-user-check"></i> هذا المسار يناسب مَن؟</h4>
                <p>يناسب عشاق التحقيق وحل الألغاز والجرائم (مثل المحقق كونان التقني). إذا كان لديك اهتمام بكيفية تتبع الأثر الرقمي، استرجاع البيانات المحذوفة، وجمع الأدلة الرقمية لتقديمها للعدالة مع دقة شديدة في التوثيق وكتابة التقارير القانونية.</p>
            </div>

            <div class="tree-branch">
                <div class="node-root">المرحلة 1: المعرفة العميقة بالأنظمة</div>
                <div class="tree-children">
                    <div class="tree-node">هندسة الحاسوب وكيفية تخزين البيانات في الذاكرة والقرص الصلب</div>
                    <div class="tree-node">دراسة متعمقة لأنظمة الملفات (NTFS, FAT32, ext4)</div>
                </div>
            </div>
            <div class="tree-branch">
                <div class="node-root">المرحلة 2: جمع وحفظ الأدلة</div>
                <div class="tree-children">
                    <div class="tree-node">الاستحواذ الجنائي على الأقراص وصنع الصور الرقمية (FTK Imager)</div>
                    <div class="tree-node">الحفاظ على سلامة الأدلة وحساب قيم الـ Hash للبيانات</div>
                </div>
            </div>
            <div class="tree-branch">
                <div class="node-root">المرحلة 3: التحليل الجنائي وكتابة التقارير</div>
                <div class="tree-children">
                    <div class="tree-node leaves">التحليل الجنائي للذاكرة العشوائية (Volatility)</div>
                    <div class="tree-node leaves">تحليل سجلات نظام التشغيل والملفات المؤقتة (Artifacts Analysis)</div>
                    <div class="tree-node leaves">التحقيق في اختراق الشبكات (Network Forensics)</div>
                    <div class="tree-node leaves">الشهادات الموصى بها: CHFI ➔ GCFE ➔ GCFA</div>
                </div>
            </div>
        </div>

        <div class="roadmap-tree" id="malware-analysis">
            <div class="suitability-card special-orange">
                <h4><i class="fas fa-user-check"></i> هذا المسار يناسب مَن؟</h4>
                <p>يناسب الأشخاص المهتمين بالبرمجة منخفضة المستوى (Low-Level Programming) والذين يمتلكون خلفية برمجية قوية جداً. إذا كنت ترغب في تشريح الفيروسات وفهم كيفية عمل برمجيات الفدية (Ransomware) في بيئات معزولة ومحمية لتطوير أدوات الدفاع وضدها.</p>
            </div>

            <div class="tree-branch">
                <div class="node-root">المرحلة 1: التأسيس البرمجي العالي</div>
                <div class="tree-children">
                    <div class="tree-node">إتقان لغات البرمجة (C / C++) و لغة التجميع (Assembly x86/x64)</div>
                    <div class="tree-node">فهم هندسة وتكوين الملفات التنفيذية (PE Files / ELF)</div>
                </div>
            </div>
            <div class="tree-branch">
                <div class="node-root">المرحلة 2: التحليل الساكن والديناميكي (Static & Dynamic)</div>
                <div class="tree-children">
                    <div class="tree-node">فحص الملفات بدون تشغيلها (مخرجات UPX، الـ Strings، وفحص الهياكل)</div>
                    <div class="tree-node">مراقبة سلوك الملف عند تشغيله في البيئات المعزولة (Sandboxing)</div>
                </div>
            </div>
            <div class="tree-branch">
                <div class="node-root">المرحلة 3: الهندسة العكسية المتقدمة</div>
                <div class="tree-children">
                    <div class="tree-node leaves">استخدام أدوات تفكيك الأكواد ومصححات الأخطاء (IDA Pro, Ghidra, x64dbg)</div>
                    <div class="tree-node leaves">تجاوز تقنيات الحماية ومكافحة الهندسة العكسية للفيروسات</div>
                    <div class="tree-node leaves">الشهادات الموصى بها: GCIH ➔ GREM</div>
                </div>
            </div>
        </div>

        <div class="roadmap-tree" id="cloud-security">
            <div class="suitability-card special-cyan">
                <h4><i class="fas fa-user-check"></i> هذا المسار يناسب مَن؟</h4>
                <p>يناسب المهتمين بالبنية التحتية الحديثة والخدمات السحابية. إذا كنت تملك اهتماماً بالعمل على منصات مثل AWS, Azure, أو Google Cloud وترغب في دمج معايير الأمان مع تقنيات الحاويات (Docker & Kubernetes) ومنهجيات الـ DevOps الحديثة لتأمين الشركات الضخمة.</p>
            </div>

            <div class="tree-branch">
                <div class="node-root">المرحلة 1: فهم البيئة السحابية والافتراضية</div>
                <div class="tree-children">
                    <div class="tree-node">أساسيات الأنظمة الافتراضية (Hypervisors, VMware, KVM)</div>
                    <div class="tree-node">فهم نماذج الخدمة السحابية (IaaS, PaaS, SaaS) على المنصات الكبرى</div>
                </div>
            </div>
            <div class="tree-branch">
                <div class="node-root">المرحلة 2: هندسة الأمان السحابي</div>
                <div class="tree-children">
                    <div class="tree-node">إدارة الهويات والوصول السحابي (Cloud IAM)</div>
                    <div class="tree-node">تأمين الشبكات السحابية، التشفير، وإدارة المفاتيح (KMS)</div>
                </div>
            </div>
            <div class="tree-branch">
                <div class="node-root">المرحلة 3: أمان الحاويات والـ DevSecOps</div>
                <div class="tree-children">
                    <div class="tree-node leaves">تأمين الحاويات والأوركسترا (Docker & Kubernetes Security)</div>
                    <div class="tree-node leaves">البنية التحتية ككود آمن (Secure Infrastructure as Code - Terraform)</div>
                    <div class="tree-node leaves">الشهادات الموصى بها: CCSP ➔ AWS Certified Security ➔ Azure Security Engineer</div>
                </div>
            </div>
        </div>

        <div class="roadmap-tree" id="grc">
            <div class="suitability-card special-green">
                <h4><i class="fas fa-user-check"></i> هذا المسار يناسب مَن؟</h4>
                <p>يناسب الأشخاص الذين يفضلون الجانب الإداري، التنظيمي والقانوني على الجانب التقني البحت والبرمجي. إذا كنت بارعاً في الإدارة، صياغة السياسات الأمنية، تحليل المخاطر الاستراتيجية للشركات، والتأكد من مطابقتها للمقاييس العالمية، فهذا مجالك المفيد جداً والمطلوب بشدة.</p>
            </div>

            <div class="tree-branch">
                <div class="node-root">المرحلة 1: فهم إدارة الأعمال والأمن</div>
                <div class="tree-children">
                    <div class="tree-node">فهم المفاهيم الأساسية للأمن السيبراني (مبدأ CIA Triad)</div>
                    <div class="tree-node">أساسيات إدارة المشاريع والعمليات داخل المؤسسات والشركات</div>
                </div>
            </div>
            <div class="tree-branch">
                <div class="node-root">المرحلة 2: المعايير والأطر العالمية</div>
                <div class="tree-children">
                    <div class="tree-node">دراسة أطر الأمن الشهيرة (NIST Cybersecurity Framework)</div>
                    <div class="tree-node">معايير الجودة والأمان الدولية (ISO/IEC 27001)</div>
                    <div class="tree-node">قوانين حماية البيانات والامتثال (GDPR, PCI-DSS)</div>
                </div>
            </div>
            <div class="tree-branch">
                <div class="node-root">المرحلة 3: إدارة المخاطر الاستراتيجية والتدقيق</div>
                <div class="tree-children">
                    <div class="tree-node leaves">تقييم المخاطر وتحليل الأثر على أعمال المنشأة (Risk Assessment)</div>
                    <div class="tree-node leaves">صياغة سياسات وإجراءات الأمن السيبراني للموظفين</div>
                    <div class="tree-node leaves">التدقيق الأمني الداخلي والخارجي (IT Auditing)</div>
                    <div class="tree-node leaves">الشهادات الموصى بها: CISA ➔ CRISC ➔ CISM</div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- <div class="roadmap-section" id="roadmap" direction="rtl">
    <div class="roadmap-header">
        <h1>خارطة طريق الأمن السيبراني 🎯</h1>
        <p>اكتشف مسارك المهني من البداية وحتى الاحتراف من خلال الخرائط الشجرية التفاعلية</p>
    </div>

    <div class="guidance-container">
        <button class="guidance-toggle" id="guidanceToggle">
            <i class="fas fa-question-circle"></i> كيف أحدد مساري المناسب في الأمن السيبراني؟
            <i class="fas fa-chevron-down arrow-icon"></i>
        </button>
        <div class="guidance-content" id="guidanceContent">
            <div class="guidance-inner">
                <h3>💡 نصائح ذهبية لاختيار مسارك الصحيح:</h3>
                <ul>
                    <li><strong>ابدأ بالأساسيات أولاً:</strong> لا يمكنك حماية أو اختراق نظام لا تفهم كيف يعمل. تعلّم الشبكات (Networking) وأنظمة التشغيل (Linux & Windows) والبرمجة (Python/Bash) قبل اختيار تخصصك.</li>
                    <li><strong>هل تميل للهجوم أم الدفاع؟</strong> إذا كنت تستمتع بالبحث عن الثغرات والتفكير خارج الصندوق، فالمسار الأحمر <strong>(Red Team)</strong> هو لك. أما إذا كنت تفضل حماية الأنظمة وتتبع الأدلة الجنائية وتحليل الهجمات، فالمسار الأزرق <strong>(Blue Team)</strong> هو خيارك الأفضل.</li>
                    <li><strong>جرّب المنصات العملية:</strong> قم بإنشاء حسابات على موقع TryHackMe أو HackTheBox، وجرّب الغرف المخصصة للدفاع والغرف المخصصة للهجوم لمعرفة ما يثير شغفك أكثر.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="interest-selection">
        <h3>🔍 اختر مجال اهتمامك لرؤية الخريطة الشجرية:</h3>
        <div class="path-tabs">
            <button class="path-tab active" data-path="red-team">
                <i class="fas fa-user-secret"></i> اختبار الاختراق والمسار الأحمر (Red Team)
            </button>
            <button class="path-tab" data-path="blue-team">
                <i class="fas fa-shield-alt"></i> التحليل الأمني والمسار الأزرق (Blue Team)
            </button>
            <button class="path-tab" data-path="forensics">
                <i class="fas fa-search-dollar"></i> الأدلة الجنائية الرقمية (Digital Forensics)
            </button>
        </div>
    </div>

    <div class="tree-container">
        
        <div class="roadmap-tree active" id="red-team">
            <div class="tree-branch core-branch">
                <div class="node-root">المرحلة 1: الأساسيات المشتركة</div>
                <div class="tree-children">
                    <div class="tree-node">الشبكات (CCNA/Network+)</div>
                    <div class="tree-node">أنظمة التشغيل (Linux/Windows Admin)</div>
                    <div class="tree-node">أساسيات البرمجة (Python / Bash)</div>
                </div>
            </div>
            <div class="tree-branch core-branch">
                <div class="node-root">المرحلة 2: أساسيات الأمن والأدوات</div>
                <div class="tree-children">
                    <div class="tree-node">مبادئ الأمن السيبراني (Security+)</div>
                    <div class="tree-node">أدوات الفحص والجمع (Nmap, Burp Suite)</div>
                </div>
            </div>
            <div class="tree-branch path-specific-branch">
                <div class="node-root special-red">المرحلة 3: الاحتراف والهجوم (Red Teaming)</div>
                <div class="tree-children">
                    <div class="tree-node leaves">اختبار اختراق الويب (OWASP Top 10)</div>
                    <div class="tree-node leaves">اختراق الشبكات والأنظمة (Active Directory)</div>
                    <div class="tree-node leaves">الهندسة الاجتماعية (Social Engineering)</div>
                    <div class="tree-node leaves">الشهادات المقترحة: (eJPT -> OSCP)</div>
                </div>
            </div>
        </div>

        <div class="roadmap-tree" id="blue-team">
            <div class="tree-branch core-branch">
                <div class="node-root">المرحلة 1: الأساسيات المشتركة</div>
                <div class="tree-children">
                    <div class="tree-node">الشبكات وأنظمة التشغيل المتطورة</div>
                    <div class="tree-node">فهم بنية خوادم الويب وقواعد البيانات</div>
                </div>
            </div>
            <div class="tree-branch core-branch">
                <div class="node-root">المرحلة 2: الدفاع وإدارة المخاطر</div>
                <div class="tree-children">
                    <div class="tree-node">إدارة الثغرات الأمنية والـ Patch Management</div>
                    <div class="tree-node">تجهيز جدران الحماية (Firewalls & IDS/IPS)</div>
                </div>
            </div>
            <div class="tree-branch path-specific-branch">
                <div class="node-root special-blue">المرحلة 3: إدارة العمليات الأمنية (SOC)</div>
                <div class="tree-children">
                    <div class="tree-node leaves">تحليل سجلات النظام (Log Analysis)</div>
                    <div class="tree-node leaves">التعامل مع أنظمة الـ SIEM (Splunk / ELK)</div>
                    <div class="tree-node leaves">الاستجابة للحوادث الرقمية (Incident Response)</div>
                    <div class="tree-node leaves">الشهادات المقترحة: (Sec+ -> CySA+ -> CCD)</div>
                </div>
            </div>
        </div>

        <div class="roadmap-tree" id="forensics">
            <div class="tree-branch core-branch">
                <div class="node-root">المرحلة 1: المعرفة التحتية</div>
                <div class="tree-children">
                    <div class="tree-node">فهم معمق لأنظمة الملفات (NTFS, ext4, FAT32)</div>
                    <div class="tree-node">هندسة الحاسوب وآلية عمل الذاكرة العشوائية (RAM)</div>
                </div>
            </div>
            <div class="tree-branch path-specific-branch">
                <div class="node-root special-forensics">المرحلة 2: التحقيق وتحليل الأدلة</div>
                <div class="tree-children">
                    <div class="tree-node leaves">تحليل الذاكرة المؤقتة (Volatility)</div>
                    <div class="tree-node leaves">جمع وتحليل الصور الرقمية للأقراص (FTK Imager / Autopsy)</div>
                    <div class="tree-node leaves">تحليل البرمجيات الخبيثة وتفكيكها (Malware Analysis)</div>
                    <div class="tree-node leaves">الشهادات المقترحة: (CHFI -> GCFE)</div>
                </div>
            </div>
        </div>

    </div>
</div> --}}
{{-- <div class="roadmap" id="roadmap">
    <h1>Cybersecurity Roadmap</h1>
    <div class="step">
        <div class="step-content">
            <h2>Operating Systems</h2>
            <p>Windows, Linux</p>
        </div>
        <div class="step-actions">
            <a href="../html/courses.html#windows" class="btn">Windows</a>
            <a href="../html/courses.html#linux" class="btn">Linux</a>
        </div>
    </div>

    <div class="step">
        <div class="step-content">
            <h2>Networking</h2>
            <p>IP Address, DNS, HTTP/HTTPS, TCP/UDP, Ports, Firewalls, VPN, OSI Model, Subnetting</p>
        </div>
        <div class="step-actions">
            <a href="../html/courses.html#network" class="btn">Networking</a>
        </div>
    </div>

    <div class="step">
        <div class="step-content">
            <h2>Programming</h2>
            <p>Java, Bash/Shell, JavaScript</p>
        </div>
        <div class="step-actions">
            <a href="../html/courses.html#java" class="btn">Java</a>
        </div>
    </div>

    <div class="step">
        <div class="step-content">
            <h2>Security Fundamentals</h2>
            <p>CIA, Malware, Phishing, Social Engineering, Encryption, Authentication, Authorization</p>
        </div>
        <div class="step-actions">
            <a href="../html/courses.html#security" class="btn">Security</a>
        </div>
    </div>

    <div class="step">
        <div class="step-content">
            <h2>Ethical Hacking</h2>
            <p>Install (Kali Linux), Tools (Nmap, Metasploit, Burp Suite, Wireshark)</p>
        </div>
        <div class="step-actions">
            <a href="../html/courses.html#ethical" class="btn">Ethical Hacking</a>
        </div>
    </div>

    <div class="step">
        <div class="step-content">
            <h2>Penetration Testing</h2>
            <p>Learn (SQL Injection, XSS, CSRF, File Upload Attacks, Brute Force) Practice on (TryHackMe, Hack The Box)</p>
        </div>
        <div class="step-actions">
            <a href="../html/courses.html#penetration" class="btn">Penetration Testing</a>
        </div>
    </div>

    <div class="step">
        <div class="step-content">
            <h2>Web Security</h2>
            <p>How websites work, Cookies & Sessions, APIs, Authentication Bugs</p>
        </div>
        <div class="step-actions">
            <a href="../html/courses.html#websec" class="btn">Web Security</a>
        </div>
    </div>

    <div class="step">
        <div class="step-content">
            <h2>Choose a Path</h2>
            <p>Red Team, Blue Team, Purple Team, SOC Analyst, Cloud Security, Digital Forensics, Malware Analysis</p>
        </div>
        <div class="step-actions">
            <a href="../html/courses.html" class="btn">Explore Paths</a>
        </div>
    </div>
</div> --}}

<!-- Resources -->
<div class="ex" id="resources">
    <h1>Resources</h1>
    <div class="external">
        <div class="card">
            <img src="{{ asset('cms/img/zero') }}" alt="Al Zero">
            <h3>Al Zero Academy</h3>
            <p>منصة عربية لتعلم البرمجة والتصميم والمجالات التقنية مجاناً.</p>
            <a href="https://academy.zer0s.com/" target="_blank">Visit</a>
        </div>

        <div class="card">
            <img src="{{ asset('cms/img/cisco.jpg') }}" alt="Cisco">
            <h3>Cisco Networking Academy</h3>
            <p>منصة عالمية لتعلم الشبكات والأمن السيبراني والشهادات المعتمدة.</p>
            <a href="https://www.netacad.com/" target="_blank">Visit</a>
        </div>

        <div class="card">
            <img src="{{ asset('cms/img/udemy.png') }}" alt="Udemy">
            <h3>Udemy</h3>
            <p>أكبر منصة تعليمية عالميًا بها آلاف الدورات في كل المجالات.</p>
            <a href="https://www.udemy.com/" target="_blank">Visit</a>
        </div>

        <div class="card">
            <img src="{{ asset('cms/img/coursera.png') }}" alt="Coursera">
            <h3>Coursera</h3>
            <p>منصة تعليمية عالمية بشهادات جامعية ودورات من أفضل الجامعات.</p>
            <a href="https://www.coursera.org/" target="_blank">Visit</a>
        </div>

        <div class="card">
            <img src="{{ asset('cms/img/khan.png') }}" alt="Khan Academy">
            <h3>Khan Academy</h3>
            <p>منصة تعليمية مجانية لتعلم العلوم والرياضيات والبرمجة لجميع الأعمار.</p>
            <a href="https://www.khanacademy.org/" target="_blank">Visit</a>
        </div>
    </div>
</div>

<!-- About Section -->
<div class="about" id="about">
    <h1>Web <span>About</span></h1>
    <div class="about-main">
        <div class="about-image">
            <div class="about-small-image">
               
                <img src="{{ asset('cms/admins/noor.jpg') }}" onclick="changeImage(this)" alt="Thumbnail 2">
                <img src="{{ asset('cms/admins/saja.jpg') }}" onclick="changeImage(this)" alt="Thumbnail 3">
                <img src="{{ asset('cms/admins/bassmah.jpg') }}" onclick="changeImage(this)" alt="Thumbnail 4">
                <img src="{{ asset('cms/admins/baraa.jpg') }}" onclick="changeImage(this)" alt="Thumbnail 1">
            </div>
            <div class="image-contaner">
                <img src="{{ asset('cms/img/NetSec.jpg') }}" id="imagbox" alt="Main Image">
            </div>
        </div>
        <div class="about-text">
            <p>CyberEye is a comprehensive cybersecurity learning platform designed to help individuals and professionals enhance their security skills. Our mission is to make cybersecurity education accessible to everyone through structured courses, practical exercises, and real-world scenarios.</p>
            <p>We offer a wide range of courses covering fundamental to advanced topics in cybersecurity. Our experienced instructors and up-to-date curriculum ensure that you gain the skills needed in today's digital world.</p>
            <p>Join our community of learners and start your journey towards becoming a cybersecurity expert today!</p>
        </div>
    </div>
</div>

<!-- Reviews -->
<div class="review" id="Review">
    <h1>Student's <span>Review</span></h1>
    <div class="review-box">
        @forelse($latestReviews as $review)
            <div class="review-card">
                <div class="card-top">
                    <div class="profile">
                        <div class="profile-image">
                            <img src="{{ $review->user->profile_image ? asset('storage/' . $review->user->profile_image) : asset('cms/img/student1.jpg') }}" alt="Student">
                        </div>
                        <div class="name">
                            <strong>{{ $review->user->username }}</strong>
                            <p style="font-size: 0.7rem; color: #666; margin: 0;">Course: {{ $review->course->course_name }}</p>
                            <div class="like">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                <div class="comment">
                    <p>"{{ Str::limit($review->comment, 150) }}"</p>
                </div>
            </div>
        @empty
            <p>No reviews available yet.</p>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('cms/js/index.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // السلايد شو
    let slideIndex = 0;
    let slides;
    let dots;

    function initSlideshow() {
        slides = document.getElementsByClassName("mySlides");
        dots = document.getElementsByClassName("dot");
        if (slides.length > 0) {
            showSlides(slideIndex);
            startAutoSlide();
        }
    }

    function showSlides(n) {
        if (!slides || slides.length === 0) return;
        if (n >= slides.length) slideIndex = 0;
        if (n < 0) slideIndex = slides.length - 1;
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
            if (dots[i]) dots[i].classList.remove('active');
        }
        slides[slideIndex].style.display = "block";
        if (dots[slideIndex]) dots[slideIndex].classList.add('active');
    }

    function currentSlide(n) {
        slideIndex = n;
        showSlides(slideIndex);
        resetAutoSlide();
    }

    let slideInterval;
    function startAutoSlide() {
        if (slideInterval) clearInterval(slideInterval);
        slideInterval = setInterval(function() {
            slideIndex++;
            if (slideIndex >= slides.length) slideIndex = 0;
            showSlides(slideIndex);
        }, 4000);
    }

    function resetAutoSlide() {
        if (slideInterval) clearInterval(slideInterval);
        startAutoSlide();
    }

    // تغيير الصورة الرئيسية في قسم About
    function changeImage(element) {
        document.getElementById('imagbox').src = element.src;
    }

    // ========== القاموس الذكي (AJAX Search) ==========
    let searchTimeout;

    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        let term = $(this).val();
        if (term.length < 2) {
            $('#resultCard').hide();
            $('#notFound').hide();
            $('#loadingSpinner').hide();
            return;
        }
        searchTimeout = setTimeout(() => performSearch(term), 500);
    });

    function performSearch(term) {
        $('#loadingSpinner').show();
        $('#resultCard').hide();
        $('#notFound').hide();

        $.ajax({
            url: '{{ route("dictionary.search") }}',
            type: 'POST',
            data: { term: term, _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#loadingSpinner').hide();
                if (response.found) {
                    $('#resultTerm').text(response.term);
                    $('#resultDefinition').text(response.definition);
                    $('#resultCategory').text(response.category || 'عام');
                    if (response.example) {
                        $('#resultExample').html('<i class="fas fa-lightbulb"></i> مثال: ' + response.example).show();
                    } else {
                        $('#resultExample').hide();
                    }
                    $('#resultCard').show();
                } else {
                    $('#notFoundMessage').text(response.message);
                    $('#notFound').show();
                }
            },
            error: function() {
                $('#loadingSpinner').hide();
                $('#notFoundMessage').text('حدث خطأ أثناء البحث، حاول مرة أخرى');
                $('#notFound').show();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initSlideshow();
    });

    document.addEventListener('DOMContentLoaded', function () {
    
    // --- 1. تفعيل فتح وإغلاق قسم النصيحة الاستشارية ---
    const guidanceToggle = document.getElementById('guidanceToggle');
    const guidanceContent = document.getElementById('guidanceContent');

    guidanceToggle.addEventListener('click', function () {
        this.classList.toggle('active');
        if (guidanceContent.style.maxHeight && guidanceContent.style.maxHeight !== '0px') {
            guidanceContent.style.maxHeight = '0px';
        } else {
            guidanceContent.style.maxHeight = guidanceContent.scrollHeight + "px";
        }
    });

    // --- 2. تفعيل التبديل بين الخرائط الشجرية (Paths) ---
    const tabs = document.querySelectorAll('.path-tab');
    const trees = document.querySelectorAll('.roadmap-tree');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            // إزالة الكلاس الفعال من الأزرار الأخرى
            tabs.forEach(t => t.classList.remove('active'));
            // إضافة الكلاس الفعال للزر الحالي
            this.classList.add('active');

            // إخفاء كل الخرائط الشجرية
            const targetPath = this.getAttribute('data-path');
            trees.forEach(tree => {
                tree.classList.remove('active');
                if (tree.id === targetPath) {
                    tree.classList.add('active');
                }
            });
        });
    });
});
</script>

@endsection