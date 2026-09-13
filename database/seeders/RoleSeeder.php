<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // استخدام CASCADE لتفريغ الجداول المرتبطة ببعضها في PostgreSQL بدون أخطاء
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('model_has_permissions')->delete();
        Permission::query()->delete();
        Role::query()->delete();

        $superAdminRole = Role::create(['name' => 'super admin', 'guard_name' => 'admin']);
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        $instructorRole = Role::create(['name' => 'instructor', 'guard_name' => 'instructor']);
        $studentRole = Role::create(['name' => 'student', 'guard_name' => 'student']);

        $adminPerms = [
            'create-admin', 'index-admin', 'edit-admin', 'delete-admin',
            'create-student', 'index-student', 'edit-student', 'delete-student',
            'create-instructor', 'index-instructor', 'edit-instructor', 'delete-instructor',
            'create-category', 'index-category', 'edit-category', 'delete-category',
            'index-quiz', 'index-question', 'index-material', 'index-video', 'index-course',
            'index-role', 'create-role', 'edit-role', 'delete-role',
            'index-permission', 'create-permission', 'edit-permission', 'delete-permission',
        ];

        foreach ($adminPerms as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'admin']);
        }

        // صلاحيات الطالب
        $studentPerms = [
            'index-quiz', 'index-question', 'index-material', 'index-video', 'index-course',
            'index-certificate', 'view-student-dashboard', 'view-certificates',
        ];

        foreach ($studentPerms as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'student']);
        }

        // صلاحيات المدرب
        $instructorPerms = [
            'create-quiz', 'index-quiz', 'edit-quiz', 'delete-quiz',
            'create-question', 'index-question', 'edit-question', 'delete-question',
            'create-material', 'index-material', 'edit-material', 'delete-material',
            'create-video', 'index-video', 'edit-video', 'delete-video',
            'create-course', 'index-course', 'edit-course', 'delete-course',
            'create-category', 'index-category', 'edit-category', 'delete-category',
        ];

        foreach ($instructorPerms as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'instructor']);
        }

        // تعيين الصلاحيات للأدوار
        $superAdminRole->syncPermissions(
            Permission::where('guard_name', 'admin')->get()
        );
        $adminRole->syncPermissions(Permission::where('guard_name', 'admin')->get());
        $instructorRole->syncPermissions(Permission::where('guard_name', 'instructor')->get());
        $studentRole->syncPermissions(Permission::where('guard_name', 'student')->get());

        echo " Roles and permissions seeded successfully!\n";
    }
}
