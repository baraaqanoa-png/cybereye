<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User1;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // قائمة الأدمن المراد إضافتهم
        $admins = [
            [
                'username' => 'super_admin',
                'email' => 'admin@cybereye.com',
                'password' => 'password123',
            ],
            [
                'username' => 'cyber_admin',
                'email' => 'cyber@cybereye.com',
                'password' => 'admin123',
            ],
            [
                'username' => 'security_admin',
                'email' => 'security@cybereye.com',
                'password' => 'security123',
            ],
        ];
        
        foreach ($admins as $adminData) {
            // إنشاء سجل في جدول admins
            $adminRecord = Admin::create([]);
            
            // إنشاء المستخدم مع الربط
            User1::create([
                'username' => $adminData['username'],
                'email' => $adminData['email'],
                'password' => Hash::make($adminData['password']),
                'role' => 'admin',
                'actor_id' => $adminRecord->id,
                'actor_type' => 'App\Models\Admin',
            ]);
            
            $this->command->info(" تم إنشاء الأدمن: {$adminData['email']}");
        }
        
        $this->command->info("\nتم إضافة " . count($admins) . " أدمن بنجاح!");
    }
}