<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
     
        $instructors = Instructor::all();
        $categories = Category::all();

        if ($instructors->isEmpty()) {
            $this->command->error(' لا يوجد مدربين! قم بتشغيل InstructorSeeder أولاً');
            return;
        }

        if ($categories->isEmpty()) {
            $this->command->error(' لا يوجد تصنيفات! قم بتشغيل CategorySeeder أولاً');
            return;
        }

        $courses = [
            // دورة أساسيات الأمن السيبراني
            [
                'course_name' => 'أساسيات الأمن السيبراني',
                'short_description' => 'تعلم المفاهيم الأساسية للأمن السيبراني وحماية البيانات',
                'no_hours' => 20,
                'level' => 'beginner',
                'rating' => 4.5,
                'status' => 'active',
                'course_image' => 'courses/cyber_basics.jpg',
                'category_id' => $categories->where('title', 'أساسيات الأمن السيبراني')->first()?->id ?? 1,
                'instructor_id' => $instructors->first()?->id ?? 1,
            ],
            [
                'course_name' => 'أمن الشبكات المتقدم',
                'short_description' => 'حماية الشبكات من الاختراقات والهجمات المتقدمة',
                'no_hours' => 35,
                'level' => 'intermediate',
                'rating' => 4.8,
                'status' => 'active',
                'course_image' => 'courses/network_security.jpg',
                'category_id' => $categories->where('title', 'أمن الشبكات')->first()?->id ?? 1,
                'instructor_id' => $instructors->first()?->id ?? 1,
            ],
            [
                'course_name' => 'الاختراق الأخلاقي',
                'short_description' => 'تعلم تقنيات الاختراق الأخلاقي والقانوني',
                'no_hours' => 45,
                'level' => 'advanced',
                'rating' => 4.9,
                'status' => 'active',
                'course_image' => 'courses/ethical_hacking.jpg',
                'category_id' => $categories->where('title', 'الاختراق الأخلاقي')->first()?->id ?? 1,
                'instructor_id' => $instructors->first()?->id ?? 1,
            ],
            [
                'course_name' => 'أمن تطبيقات الويب',
                'short_description' => 'حماية تطبيقات الويب من الثغرات والهجمات',
                'no_hours' => 30,
                'level' => 'intermediate',
                'rating' => 4.7,
                'status' => 'active',
                'course_image' => 'courses/web_security.jpg',
                'category_id' => $categories->where('title', 'أمن التطبيقات')->first()?->id ?? 1,
                'instructor_id' => $instructors->first()?->id ?? 1,
            ],
            [
                'course_name' => 'تحليل البرمجيات الخبيثة',
                'short_description' => 'تعلم تحليل وفهم سلوك البرمجيات الخبيثة',
                'no_hours' => 40,
                'level' => 'advanced',
                'rating' => 4.6,
                'status' => 'active',
                'course_image' => 'courses/malware_analysis.jpg',
                'category_id' => $categories->where('title', 'الاستجابة للحوادث')->first()?->id ?? 1,
                'instructor_id' => $instructors->first()?->id ?? 1,
            ],
            [
                'course_name' => 'أمن قواعد البيانات',
                'short_description' => 'حماية قواعد البيانات من الاختراق والثغرات',
                'no_hours' => 25,
                'level' => 'intermediate',
                'rating' => 4.4,
                'status' => 'active',
                'course_image' => 'courses/database_security.jpg',
                'category_id' => $categories->where('title', 'أمن قواعد البيانات')->first()?->id ?? 1,
                'instructor_id' => $instructors->first()?->id ?? 1,
            ],
            [
                'course_name' => 'أمن السحابة (Cloud Security)',
                'short_description' => 'تأمين البنية التحتية السحابية على AWS و Azure',
                'no_hours' => 38,
                'level' => 'advanced',
                'rating' => 4.8,
                'status' => 'active',
                'course_image' => 'courses/cloud_security.jpg',
                'category_id' => $categories->where('title', 'أمن السحابة')->first()?->id ?? 1,
                'instructor_id' => $instructors->first()?->id ?? 1,
            ],
            [
                'course_name' => 'الهندسة الاجتماعية',
                'short_description' => 'فهم أساليب الهندسة الاجتماعية وكيفية الحماية منها',
                'no_hours' => 15,
                'level' => 'beginner',
                'rating' => 4.3,
                'status' => 'active',
                'course_image' => 'courses/social_engineering.jpg',
                'category_id' => $categories->where('title', 'الهندسة الاجتماعية')->first()?->id ?? 1,
                'instructor_id' => $instructors->first()?->id ?? 1,
            ],
            [
                'course_name' => 'التشفير (Cryptography)',
                'short_description' => 'تعلم أساسيات التشفير والخوارزميات لحماية البيانات',
                'no_hours' => 28,
                'level' => 'intermediate',
                'rating' => 4.7,
                'status' => 'active',
                'course_image' => 'courses/cryptography.jpg',
                'category_id' => $categories->where('title', 'التشفير')->first()?->id ?? 1,
                'instructor_id' => $instructors->first()?->id ?? 1,
            ],
            [
                'course_name' => 'اختبار الاختراق العملي',
                'short_description' => 'تطبيق عملي لاختبار الاختراق على أنظمة حقيقية',
                'no_hours' => 50,
                'level' => 'advanced',
                'rating' => 4.9,
                'status' => 'active',
                'course_image' => 'courses/penetration_testing.jpg',
                'category_id' => $categories->where('title', 'اختبار الاختراق')->first()?->id ?? 1,
                'instructor_id' => $instructors->first()?->id ?? 1,
            ],
            
                [
                    'course_name' => 'تدريب ميداني: أمن الشبكات',
                    'short_description' => 'برنامج تدريب عملي مكثف لتأمين الشبكات في بيئات حقيقية',
                    'no_hours' => 100,
                    'level' => 'advanced',
                    'rating' => 5.0,
                    'status' => 'active',
                    'course_image' => 'courses/internship_network.jpg',
                    'category_id' => $categories->where('title', 'أمن الشبكات')->first()?->id ?? 1,
                    'instructor_id' => $instructors->first()?->id ?? 1,
                ],
                [
                    'course_name' => 'مسار التدريب الميداني في الاختراق الأخلاقي',
                    'short_description' => 'تطبيق عملي متقدم لاكتشاف الثغرات في أنظمة المؤسسات',
                    'no_hours' => 120,
                    'level' => 'advanced',
                    'rating' => 5.0,
                    'status' => 'active',
                    'course_image' => 'courses/internship_hacking.jpg',
                    'category_id' => $categories->where('title', 'الاختراق الأخلاقي')->first()?->id ?? 1,
                    'instructor_id' => $instructors->first()?->id ?? 1,
                ],
                
                    [
                        'course_name' => 'تدريب ميداني: الاستجابة للحوادث السيبرانية',
                        'short_description' => 'التعامل العملي مع الهجمات السيبرانية واحتواء التهديدات فور وقوعها',
                        'no_hours' => 80,
                        'level' => 'advanced',
                        'rating' => 5.0,
                        'status' => 'active',
                        'course_image' => 'courses/internship_incident.jpg',
                        'category_id' => $categories->where('title', 'الاستجابة للحوادث')->first()?->id ?? 1,
                        'instructor_id' => $instructors->first()?->id ?? 1,
                    ],
                    [
                        'course_name' => 'مسار ميداني: التحقيق الجنائي الرقمي',
                        'short_description' => 'استخراج الأدلة الرقمية وتحليلها من الأجهزة المخترقة بطريقة قانونية',
                        'no_hours' => 90,
                        'level' => 'advanced',
                        'rating' => 5.0,
                        'status' => 'active',
                        'course_image' => 'courses/internship_forensics.jpg',
                        'category_id' => $categories->where('title', 'تحليل البرمجيات الخبيثة')->first()?->id ?? 1,
                        'instructor_id' => $instructors->first()?->id ?? 1,
                    ],
                    [
                        'course_name' => 'تدريب ميداني: أمن السحابة المؤسسي',
                        'short_description' => 'إدارة وتأمين البيئات السحابية الضخمة (Enterprise Cloud Security)',
                        'no_hours' => 110,
                        'level' => 'advanced',
                        'rating' => 5.0,
                        'status' => 'active',
                        'course_image' => 'courses/internship_cloud.jpg',
                        'category_id' => $categories->where('title', 'أمن السحابة')->first()?->id ?? 1,
                        'instructor_id' => $instructors->first()?->id ?? 1,
                    ]
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }

        $this->command->info('تم إضافة ' . count($courses) . ' كورس بنجاح');
    }
}