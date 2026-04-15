<?php

namespace Database\Seeders;

use App\Models\{FeeCategory, SchoolClass, Section, Setting, Subject, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default Settings
        $settings = [
            'site_name'         => 'Bright Future School',
            'site_tagline'      => 'Empowering Education Through Technology',
            'contact_email'     => 'info@brightfutureschool.com',
            'contact_phone'     => '+91 98765 43210',
            'contact_address'   => '123, Education Colony, New Delhi - 110001',
            'footer_text'       => '© ' . date('Y') . ' Bright Future School. All Rights Reserved.',
            'academic_year'     => date('Y'),
            'meta_title'        => 'Bright Future School - Best CBSE School',
            'meta_description'  => 'Bright Future School offers world-class education from Class 1 to 12. Apply online for admission.',
            'meta_keywords'     => 'bright future school, cbse school, english medium school, admission 2024',
            'payu_merchant_key' => 'your_payu_key',
            'payu_merchant_salt'=> 'your_payu_salt',
            'payu_mode'         => 'test',
            'feature_online_admission' => '1',
            'feature_fee_payment'      => '1',
            'feature_blog'             => '1',
            'feature_student_portal'   => '1',
            'feature_parent_portal'    => '1',
            'feature_attendance'       => '1',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name'     => 'System Administrator',
                'password' => Hash::make('admin@123'),
                'role'     => 'admin',
                'is_active'=> true,
            ]
        );

        // Classes 1-12
        for ($i = 1; $i <= 12; $i++) {
            $class = SchoolClass::updateOrCreate(
                ['numeric_value' => $i],
                ['name' => (string)$i, 'description' => 'Class ' . $i]
            );

            // Add sections A, B for each class
            foreach (['A', 'B'] as $sec) {
                Section::updateOrCreate(
                    ['class_id' => $class->id, 'name' => $sec],
                    ['capacity' => 40]
                );
            }

            // Add subjects for each class
            $subjects = [
                ['Mathematics', 'MATH'],
                ['English', 'ENG'],
                ['Science', 'SCI'],
                ['Social Studies', 'SST'],
                ['Hindi', 'HIN'],
            ];

            foreach ($subjects as [$name, $code]) {
                Subject::updateOrCreate(
                    ['code' => $code . $i],
                    [
                        'name'       => $name,
                        'class_id'   => $class->id,
                        'max_marks'  => 100,
                        'pass_marks' => 33,
                    ]
                );
            }
        }

        // Fee Categories
        $feeCategories = [
            ['name' => 'Tuition Fee', 'description' => 'Monthly tuition fee'],
            ['name' => 'Transport Fee', 'description' => 'School bus transport fee'],
            ['name' => 'Library Fee', 'description' => 'Annual library fee'],
            ['name' => 'Lab Fee', 'description' => 'Science/Computer lab fee'],
            ['name' => 'Sports Fee', 'description' => 'Annual sports fee'],
            ['name' => 'Examination Fee', 'description' => 'Examination and assessment fee'],
            ['name' => 'Admission Fee', 'description' => 'One-time admission fee'],
        ];

        foreach ($feeCategories as $cat) {
            FeeCategory::updateOrCreate(['name' => $cat['name']], $cat);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin Login: admin@school.com / admin@123');
    }
}
