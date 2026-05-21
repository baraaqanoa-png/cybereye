<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>شهادة تقدير - CYBEReye</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', 'Tajawal', 'Segoe UI', serif;
            background: #4a6b3c;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .certificate-wrapper {
            max-width: 950px;
            width: 100%;
            background: #fdf8e7;
            padding: 30px 25px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
            position: relative;
            border: 1px solid #d4c9a6;
            margin: 0 auto;
        }

        /* إطار ذهبي كلاسيكي */
        .certificate-wrapper::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 2px solid #c9a53b;
            pointer-events: none;
        }

        /* زوايا مزخرفة */
        .corner-decoration {
            position: absolute;
            width: 30px;
            height: 30px;
            border-color: #c9a53b;
            border-style: solid;
            border-width: 0;
        }

        .corner-tl {
            top: 15px;
            left: 15px;
            border-top-width: 3px;
            border-left-width: 3px;
        }

        .corner-tr {
            top: 15px;
            right: 15px;
            border-top-width: 3px;
            border-right-width: 3px;
        }

        .corner-bl {
            bottom: 15px;
            left: 15px;
            border-bottom-width: 3px;
            border-left-width: 3px;
        }

        .corner-br {
            bottom: 15px;
            right: 15px;
            border-bottom-width: 3px;
            border-right-width: 3px;
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header-title {
            font-size: 12px;
            letter-spacing: 3px;
            color: #8b7a4b;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .certificate-title {
            font-size: 32px;
            font-weight: 800;
            color: #2c2b26;
            letter-spacing: 3px;
            margin: 8px 0;
        }

        .certificate-subtitle {
            font-size: 11px;
            color: #6b5a3a;
            letter-spacing: 2px;
            border-bottom: 1px solid #d4c9a6;
            display: inline-block;
            padding-bottom: 5px;
        }

        .award-text {
            font-size: 14px;
            color: #3a3524;
            margin: 20px 0 10px;
            line-height: 1.6;
            text-align: center;
        }

        .student-section {
            text-align: center;
            margin: 10px 0;
        }

        .student-name {
            font-size: 28px;
            font-weight: 700;
            color: #1e3a2f;
            letter-spacing: 1px;
            font-family: 'Times New Roman', serif;
            border-bottom: 1px dashed #c9a53b;
            display: inline-block;
            padding-bottom: 6px;
            word-break: break-word;
            max-width: 100%;
        }

        .course-section {
            text-align: center;
            margin: 20px 0 10px;
        }

        .course-label {
            font-size: 13px;
            color: #5e5538;
            margin-bottom: 6px;
            letter-spacing: 1px;
        }

        .course-name-box {
            display: inline-block;
            background: linear-gradient(135deg, #1e3a2f 0%, #2c5a4a 100%);
            color: #fdf8e7;
            padding: 10px 20px;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            border: 1px solid #c9a53b;
            word-break: break-word;
            max-width: 100%;
        }

        .completion-text {
            font-size: 13px;
            color: #4a4532;
            margin: 15px 0 5px;
            line-height: 1.6;
            text-align: center;
            padding: 0 10px;
        }

        .gratitude-text {
            font-size: 12px;
            color: #7a6b42;
            margin: 15px 0 10px;
            font-style: italic;
            font-weight: 500;
            text-align: center;
            padding: 0 10px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 15px;
            margin: 30px 0 20px;
            text-align: center;
            flex-wrap: wrap;
        }

        .signature-item {
            flex: 1;
            min-width: 120px;
        }

        .signature-line {
            font-family: 'Dancing Script', cursive;
            font-size: 18px;
            font-weight: 500;
            color: #2c3e2f;
            margin-bottom: 8px;
            border-bottom: 1px solid #c9a53b;
            display: inline-block;
            padding-bottom: 5px;
            min-width: 120px;
            word-break: keep-all;
        }

        .signature-title {
            color: #7a6b42;
            font-size: 10px;
            letter-spacing: 1px;
        }

        /* الختم السيبراني */
        .stamp-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .cyber-stamp {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            background: linear-gradient(145deg, #0a0f1a, #06090f);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            position: relative;
        }

        .cyber-stamp::before {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            border-radius: 50%;
            border: 1px solid rgba(0, 255, 204, 0.4);
            animation: pulse 2s ease-out infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.6;
            }
            70% {
                transform: scale(1.12);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 0;
            }
        }

        .cyber-stamp i {
            font-size: 24px;
            color: #00ffcc;
            margin-bottom: 3px;
            filter: drop-shadow(0 0 5px #00ffcc);
        }

        .cyber-stamp span {
            font-size: 7px;
            color: #00ffcc;
            text-align: center;
            font-weight: 700;
            letter-spacing: 1px;
            font-family: monospace;
        }

        .stamp-text {
            font-size: 9px;
            color: #00aa99;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .verified-badge {
            margin-top: 5px;
            font-size: 8px;
            background: rgba(0, 255, 204, 0.1);
            padding: 2px 6px;
            border-radius: 20px;
            font-family: monospace;
        }

        .certificate-meta {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #e0d5b5;
            text-align: center;
            font-size: 9px;
            color: #8b7a4b;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .cert-number {
            font-family: monospace;
            background: #f0ebd8;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .btn-print {
            margin-top: 20px;
            background: #2c3e2f;
            border: none;
            color: #fdf8e7;
            padding: 8px 24px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 12px;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-print:hover {
            background: #1e2a1f;
        }

        /* ========== MEDIA QUERIES للجوال ========== */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .certificate-wrapper {
                padding: 20px 15px;
            }
            
            .certificate-wrapper::before {
                top: 8px;
                left: 8px;
                right: 8px;
                bottom: 8px;
            }
            
            .corner-decoration {
                width: 20px;
                height: 20px;
            }
            
            .corner-tl, .corner-tr, .corner-bl, .corner-br {
                top: 10px;
                left: 10px;
                right: 10px;
                bottom: 10px;
            }
            
            .certificate-title {
                font-size: 24px;
            }
            
            .student-name {
                font-size: 20px;
            }
            
            .course-name-box {
                font-size: 13px;
                padding: 8px 16px;
            }
            
            .signature-line {
                font-size: 14px;
                min-width: 80px;
            }
            
            .cyber-stamp {
                width: 60px;
                height: 60px;
            }
            
            .cyber-stamp i {
                font-size: 20px;
            }
            
            .certificate-meta {
                flex-direction: column;
                gap: 8px;
            }
        }
        
        /* شاشات صغيرة جداً */
        @media (max-width: 480px) {
            .signatures {
                flex-direction: column;
                align-items: center;
                gap: 20px;
            }
            
            .signature-item {
                width: 100%;
            }
            
            .signature-line {
                min-width: 150px;
            }
            
            .certificate-title {
                font-size: 20px;
            }
            
            .header-title {
                font-size: 10px;
            }
            
            .award-text, .completion-text, .gratitude-text {
                font-size: 11px;
            }
        }
        
        /* شاشات تابلت */
        @media (min-width: 769px) and (max-width: 1024px) {
            .certificate-wrapper {
                padding: 25px 35px;
            }
            
            .student-name {
                font-size: 28px;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .btn-print {
                display: none;
            }
            .certificate-wrapper {
                box-shadow: none;
                padding: 30px;
            }
            .cyber-stamp {
                border-color: #2c5a4a;
                background: #f0ebd8;
            }
            .cyber-stamp i, .cyber-stamp span {
                color: #2c5a4a;
            }
            .cyber-stamp::before {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="certificate-wrapper">
    <div class="corner-decoration corner-tl"></div>
    <div class="corner-decoration corner-tr"></div>
    <div class="corner-decoration corner-bl"></div>
    <div class="corner-decoration corner-br"></div>

    <div class="certificate-header">
        <div class="header-title">شهادة تقدير</div>
        <h1 class="certificate-title">CERTIFICATE</h1>
        <div class="certificate-subtitle">OF ACHIEVEMENT</div>
    </div>

    <div class="award-text">
        تُمنح هذه الشهادة إلى / This certificate is awarded to
    </div>

    <div class="student-section">
        <div class="student-name">
            {{ $student->user1->username ?? 'Juliana Silva' }}
        </div>
    </div>

    <div class="course-section">
        <div class="course-label">لإتمامها بنجاح الدورة التدريبية</div>
        <div class="course-name-box">
            <i class="fas fa-microchip" style="margin-left: 8px;"></i>
            {{ $course->course_name ?? 'Cyber Security Fundamentals' }}
        </div>
    </div>

    <div class="completion-text">
        وقد أظهرت التزاماً مميزاً ومهارات عالية خلال فترة التدريب.
    </div>

    <div class="gratitude-text">
        "نقدم هذه الشهادة بكل تقدير، على أمل أن تكون حافزاً لتحقيق المزيد من النجاحات"
    </div>

    <div class="signatures">
        <div class="signature-item">
            <div class="signature-line">
                {{ $course->instructor->user1->username ?? 'ادارة المنصة' }}
            </div>
            <div class="signature-title">instructor</div>
        </div>

        <!-- الختم السيبراني المحسن -->
        <div class="signature-item stamp-container">
            <div class="cyber-stamp">
                <i class="fas fa-fingerprint"></i>
                <span>CYBEReye</span>
                <span style="font-size: 6px;">SECURE v2.0</span>
            </div>
            <div class="stamp-text">ختم موثق رقمياً</div>
            <div class="verified-badge">
                <i class="fas fa-check-circle"></i> VERIFIED
            </div>
        </div>

        <div class="signature-item">
            <div class="signature-line">
               CYBEReye
            </div>
            <div class="signature-title">General Manager</div>
        </div>
    </div>

    <div class="certificate-meta">
        <div class="cert-number">
            <i class="fas fa-hashtag"></i> رقم الشهادة: {{ $certificate->certificate_number ?? 'CY-' . rand(10000, 99999) }}
        </div>
        <div>
            <i class="fas fa-qrcode"></i> بصمة رقمية: 0x{{ substr(md5(rand()), 0, 8) }}
        </div>
        <div>
            <i class="fas fa-calendar-alt"></i> التاريخ: {{ now()->format('Y/m/d') }}
        </div>
    </div>

    <div style="text-align: center;">
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> طباعة الشهادة
        </button>
    </div>
</div>

</body>
</html>