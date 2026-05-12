<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== الأدوار =====
        DB::table('roles')->insert([
            ['name' => 'admin',   'name_ar' => 'مدير النظام',    'created_at' => now(), 'updated_at' => now()],
            ['name' => 'editor',  'name_ar' => 'محرر المحتوى',   'created_at' => now(), 'updated_at' => now()],
            ['name' => 'sales',   'name_ar' => 'موظف مبيعات',    'created_at' => now(), 'updated_at' => now()],
            ['name' => 'support', 'name_ar' => 'موظف دعم فني',   'created_at' => now(), 'updated_at' => now()],
        ]);

        // ===== مستخدم المدير =====
        DB::table('users')->insert([
            'name'       => 'مدير النظام',
            'email'      => 'admin@ebda3soft.com',
            'password'   => Hash::make('Admin@123456'),
            'role_id'    => 1,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ===== الفروع =====
        $branches = [
            ['name_ar' => 'المقر الرئيسي - صنعاء',   'name_en' => 'Head Office - Sana\'a',  'type' => 'branch', 'city' => 'صنعاء',    'governorate' => 'أمانة العاصمة', 'phone' => '0133375710', 'is_active' => true],
            ['name_ar' => 'فرع عدن',                  'name_en' => 'Aden Branch',             'type' => 'branch', 'city' => 'عدن',      'governorate' => 'عدن',            'phone' => '0221234567', 'is_active' => true],
            ['name_ar' => 'فرع حضرموت - المكلا',      'name_en' => 'Hadramaut Branch',        'type' => 'branch', 'city' => 'المكلا',   'governorate' => 'حضرموت',         'phone' => '0521234567', 'is_active' => true],
            ['name_ar' => 'مكتب سيئون',               'name_en' => 'Say\'un Office',          'type' => 'branch', 'city' => 'سيئون',    'governorate' => 'حضرموت',         'phone' => '0531234567', 'is_active' => true],
            ['name_ar' => 'فرع الحديدة',              'name_en' => 'Hudaydah Branch',         'type' => 'branch', 'city' => 'الحديدة',  'governorate' => 'الحديدة',        'phone' => '0391234567', 'is_active' => true],
            ['name_ar' => 'وكيل تعز',                 'name_en' => 'Taiz Agent',              'type' => 'agent',  'city' => 'تعز',      'governorate' => 'تعز',            'phone' => '0421234567', 'is_active' => true],
            ['name_ar' => 'وكيل إب',                  'name_en' => 'Ibb Agent',               'type' => 'agent',  'city' => 'إب',       'governorate' => 'إب',             'phone' => '0461234567', 'is_active' => true],
        ];
        foreach ($branches as $b) {
            DB::table('branches')->insert(array_merge($b, ['created_at' => now(), 'updated_at' => now()]));
        }

        // ===== تصنيفات الأنظمة =====
        $categories = [
            ['name_ar' => 'الأنظمة المحاسبية والمالية',  'name_en' => 'Accounting & Finance',   'slug' => 'accounting-finance',   'icon' => 'calculator', 'sort_order' => 1],
            ['name_ar' => 'أنظمة القطاعات المتخصصة',     'name_en' => 'Specialized Sectors',    'slug' => 'specialized-sectors',  'icon' => 'building',   'sort_order' => 2],
            ['name_ar' => 'خدمات البنية التحتية',         'name_en' => 'Infrastructure Services','slug' => 'infrastructure',       'icon' => 'server',     'sort_order' => 3],
        ];
        foreach ($categories as $c) {
            DB::table('categories')->insert(array_merge($c, ['created_at' => now(), 'updated_at' => now()]));
        }

        // ===== الأنظمة/المنتجات =====
        $products = [
            // --- أنظمة محاسبية (category_id=1)
            [
                'name_ar'       => 'نظام دوت إكس برو (Dotx Pro)',
                'slug'          => 'dotx-pro',
                'category_id'   => 1,
                'short_desc_ar' => 'نظام محاسبي وإداري متكامل يغطي جميع احتياجات الشركة من المحاسبة وإدارة المخزون والمبيعات',
                'is_featured'   => true,
                'is_active'     => true,
                'sort_order'    => 1,
            ],
            [
                'name_ar'       => 'نظام مال المحاسبي',
                'slug'          => 'mal-accounting',
                'category_id'   => 1,
                'short_desc_ar' => 'نظام محاسبة شامل للمؤسسات الصغيرة والمتوسطة يوفر قوائم مالية احترافية',
                'is_featured'   => true,
                'is_active'     => true,
                'sort_order'    => 2,
            ],
            [
                'name_ar'       => 'نظام التاجر المطوّر',
                'slug'          => 'merchant-pro',
                'category_id'   => 1,
                'short_desc_ar' => 'نظام نقاط بيع (POS) وإدارة تجارية متكامل للمتاجر والمحلات التجارية',
                'is_featured'   => false,
                'is_active'     => true,
                'sort_order'    => 3,
            ],
            [
                'name_ar'       => 'نظام الأصول الثابتة (Fixed Assets)',
                'slug'          => 'fixed-assets',
                'category_id'   => 1,
                'short_desc_ar' => 'إدارة وجرد الأصول الثابتة مع حساب الاهلاك التلقائي وتقارير شاملة',
                'is_featured'   => false,
                'is_active'     => true,
                'sort_order'    => 4,
            ],
            [
                'name_ar'       => 'نظام شركات الصرافة المتقدم',
                'slug'          => 'exchange-advanced',
                'category_id'   => 1,
                'short_desc_ar' => 'حل متكامل لإدارة شركات الصرافة مع دعم العملات المتعددة والتحويلات',
                'is_featured'   => false,
                'is_active'     => true,
                'sort_order'    => 5,
            ],
            [
                'name_ar'       => 'نظام الصراف المتقدم',
                'slug'          => 'teller-advanced',
                'category_id'   => 1,
                'short_desc_ar' => 'نظام صرافة متطور للبنوك والمؤسسات المالية مع إدارة العمليات اليومية',
                'is_featured'   => false,
                'is_active'     => true,
                'sort_order'    => 6,
            ],
            [
                'name_ar'       => 'نظام الصراف بلاس',
                'slug'          => 'teller-plus',
                'category_id'   => 1,
                'short_desc_ar' => 'نظام صرافة مبسط ومتكامل لمكاتب الصرافة الصغيرة',
                'is_featured'   => false,
                'is_active'     => true,
                'sort_order'    => 7,
            ],
            // --- أنظمة قطاعية (category_id=2)
            [
                'name_ar'       => 'نظام المطاعم',
                'slug'          => 'restaurant-system',
                'category_id'   => 2,
                'short_desc_ar' => 'إدارة متكاملة للمطاعم من إدارة الطلبات والطاولات إلى المحاسبة والمخزون',
                'is_featured'   => true,
                'is_active'     => true,
                'sort_order'    => 8,
            ],
            [
                'name_ar'       => 'نظام محطات الوقود',
                'slug'          => 'fuel-station',
                'category_id'   => 2,
                'short_desc_ar' => 'إدارة محطات الوقود مع ضبط المبيعات والمخزون وتقارير يومية وشهرية',
                'is_featured'   => false,
                'is_active'     => true,
                'sort_order'    => 9,
            ],
            [
                'name_ar'       => 'نظام الموارد البشرية (HR)',
                'slug'          => 'hr-system',
                'category_id'   => 2,
                'short_desc_ar' => 'إدارة الموظفين والرواتب والإجازات والحضور والانصراف بشكل متكامل',
                'is_featured'   => true,
                'is_active'     => true,
                'sort_order'    => 10,
            ],
            [
                'name_ar'       => 'نظام المدارس نون',
                'slug'          => 'noon-schools',
                'category_id'   => 2,
                'short_desc_ar' => 'نظام إدارة تعليمي شامل للمدارس يشمل التسجيل والرسوم والجداول والنتائج',
                'is_featured'   => true,
                'is_active'     => true,
                'sort_order'    => 11,
            ],
            [
                'name_ar'       => 'نظام المستشفيات',
                'slug'          => 'hospital-system',
                'category_id'   => 2,
                'short_desc_ar' => 'إدارة متكاملة للمستشفيات تشمل الحجز والسجلات الطبية والمحاسبة والصيدلة',
                'is_featured'   => true,
                'is_active'     => true,
                'sort_order'    => 12,
            ],
            [
                'name_ar'       => 'نظام محطات الكهرباء',
                'slug'          => 'electricity-station',
                'category_id'   => 2,
                'short_desc_ar' => 'إدارة محطات الكهرباء مع إصدار الفواتير وإدارة الاشتراكات والتقارير',
                'is_featured'   => false,
                'is_active'     => true,
                'sort_order'    => 13,
            ],
            [
                'name_ar'       => 'نظام مشاريع المياه',
                'slug'          => 'water-projects',
                'category_id'   => 2,
                'short_desc_ar' => 'إدارة مشاريع المياه والصرف الصحي مع نظام فواتير الاشتراكات والتحصيل',
                'is_featured'   => false,
                'is_active'     => true,
                'sort_order'    => 14,
            ],
            [
                'name_ar'       => 'نظام معارض السيارات',
                'slug'          => 'car-showroom',
                'category_id'   => 2,
                'short_desc_ar' => 'إدارة معارض السيارات من المخزون والمبيعات إلى التقسيط والمحاسبة',
                'is_featured'   => false,
                'is_active'     => true,
                'sort_order'    => 15,
            ],
            [
                'name_ar'       => 'نظام الفنادق',
                'slug'          => 'hotel-system',
                'category_id'   => 2,
                'short_desc_ar' => 'إدارة الفنادق من الحجز والغرف إلى خدمات المطعم والمحاسبة',
                'is_featured'   => false,
                'is_active'     => true,
                'sort_order'    => 16,
            ],
        ];

        foreach ($products as $p) {
            DB::table('products')->insert(array_merge($p, [
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ===== مميزات نظام Dotx Pro =====
        $dotxFeatures = [
            'محاسبة مالية شاملة (General Ledger)',
            'إدارة المخزون والمستودعات',
            'نقطة بيع احترافية (POS)',
            'إدارة المشتريات والموردين',
            'إدارة الذمم المدينة والدائنة',
            'تقارير مالية احترافية',
            'دعم متعدد الفروع',
            'واجهة عربية كاملة',
        ];
        foreach ($dotxFeatures as $i => $f) {
            DB::table('product_features')->insert(['product_id' => 1, 'feature_ar' => $f, 'sort_order' => $i]);
        }

        // ===== الاستفسارات المتكررة =====
        $faqs = [
            ['question_ar' => 'ما هي أنظمة إبداع سوفت؟',           'answer_ar' => 'إبداع سوفت تقدم مجموعة متكاملة من الأنظمة المحاسبية والإدارية للشركات والمؤسسات في اليمن وخارجها.'],
            ['question_ar' => 'كيف يمكنني طلب نظام؟',              'answer_ar' => 'يمكنك التواصل معنا عبر واتساب أو ملء نموذج طلب النظام في الموقع وسيتواصل معك فريق المبيعات.'],
            ['question_ar' => 'هل يوجد دعم فني بعد الشراء؟',       'answer_ar' => 'نعم، نوفر دعماً فنياً متكاملاً لعملائنا عبر الهاتف والواتساب والزيارات الميدانية.'],
            ['question_ar' => 'هل يمكن تخصيص النظام حسب احتياجاتي؟','answer_ar' => 'نعم، توفر إبداع سوفت حلولاً مخصصة تتناسب مع احتياج كل عميل على حده.'],
            ['question_ar' => 'ما هي تكلفة الأنظمة؟',              'answer_ar' => 'تختلف التكلفة حسب النظام وحجم المؤسسة، يرجى التواصل معنا للحصول على عرض سعر مخصص.'],
            ['question_ar' => 'هل يعمل النظام بدون إنترنت؟',       'answer_ar' => 'نعم، معظم أنظمتنا تعمل على الشبكة المحلية (LAN) بدون الحاجة لاتصال دائم بالإنترنت.'],
        ];
        foreach ($faqs as $i => $f) {
            DB::table('faqs')->insert(array_merge($f, ['sort_order' => $i, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]));
        }

        // ===== إعدادات الموقع =====
        $settings = [
            ['key' => 'site_name_ar',       'value' => 'إبداع سوفت للأنظمة الخاصة',  'type' => 'text'],
            ['key' => 'site_name_en',       'value' => 'Ebda3 Soft Systems',            'type' => 'text'],
            ['key' => 'site_phone',         'value' => '+967 01 337571',                'type' => 'text'],
            ['key' => 'site_email',         'value' => 'info@ebda3soft.com',            'type' => 'text'],
            ['key' => 'site_address_ar',    'value' => 'صنعاء - شارع مأرب - عمارة الأكوع - الدور الرابع', 'type' => 'text'],
            ['key' => 'whatsapp',           'value' => '+967776400070',                 'type' => 'text'],
            ['key' => 'facebook',           'value' => 'https://facebook.com/EBDA3SOFTNET', 'type' => 'text'],
            ['key' => 'years_experience',   'value' => '12',                            'type' => 'text'],
            ['key' => 'clients_count',      'value' => '1500+',                         'type' => 'text'],
            ['key' => 'branches_count',     'value' => '15+',                           'type' => 'text'],
            ['key' => 'trainees_count',     'value' => '5000+',                         'type' => 'text'],
        ];
        foreach ($settings as $s) {
            DB::table('settings')->insert($s);
        }
    }
}
