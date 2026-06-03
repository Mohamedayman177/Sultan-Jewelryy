<?php

return [
    'item_categories' => [
        'jewelry' => ['ar' => 'مجوهرات', 'en' => 'Jewelry'],
        'gemstone' => ['ar' => 'أحجار كريمة', 'en' => 'Gemstones'],
    ],

    'jewelry' => [
        'piece_types' => [
            'ring' => ['ar' => 'خاتم', 'en' => 'Ring'],
            'necklace' => ['ar' => 'عقد', 'en' => 'Necklace'],
            'bracelet' => ['ar' => 'سوار', 'en' => 'Bracelet'],
            'earrings' => ['ar' => 'أقراط', 'en' => 'Earrings'],
            'watch' => ['ar' => 'ساعة', 'en' => 'Watch'],
            'set' => ['ar' => 'طقم مجوهرات', 'en' => 'Jewelry set'],
            'other' => ['ar' => 'أخرى', 'en' => 'Other'],
        ],
        'metal_types' => [
            'gold' => ['ar' => 'ذهب', 'en' => 'Gold'],
            'platinum' => ['ar' => 'بلاتين', 'en' => 'Platinum'],
            'silver' => ['ar' => 'فضة', 'en' => 'Silver'],
            'mixed' => ['ar' => 'معدن مختلط', 'en' => 'Mixed metal'],
            'unknown' => ['ar' => 'غير معروف', 'en' => 'Unknown'],
        ],
        'piece_conditions' => [
            'new' => ['ar' => 'جديدة', 'en' => 'New'],
            'used' => ['ar' => 'مستعملة', 'en' => 'Used'],
            'needs_maintenance' => ['ar' => 'تحتاج صيانة', 'en' => 'Needs maintenance'],
        ],
        'yes_no_unknown' => [
            'yes' => ['ar' => 'نعم', 'en' => 'Yes'],
            'no' => ['ar' => 'لا', 'en' => 'No'],
            'unknown' => ['ar' => 'غير معروف', 'en' => 'Unknown'],
        ],
        'evaluation_purposes' => [
            'sell' => ['ar' => 'بيع', 'en' => 'Sell'],
            'buy' => ['ar' => 'شراء', 'en' => 'Buy'],
            'insurance' => ['ar' => 'تأمين', 'en' => 'Insurance'],
            'inheritance' => ['ar' => 'ورثة', 'en' => 'Inheritance'],
            'market_value' => ['ar' => 'معرفة القيمة السوقية', 'en' => 'Market value'],
            'documentation' => ['ar' => 'توثيق', 'en' => 'Documentation'],
        ],
    ],

    'gemstone' => [
        'gemstone_types' => [
            'diamond' => ['ar' => 'ألماس', 'en' => 'Diamond'],
            'emerald' => ['ar' => 'زمرد', 'en' => 'Emerald'],
            'ruby' => ['ar' => 'ياقوت', 'en' => 'Ruby'],
            'sapphire' => ['ar' => 'زفير', 'en' => 'Sapphire'],
            'pearl' => ['ar' => 'لؤلؤ', 'en' => 'Pearl'],
            'semi_precious' => ['ar' => 'أحجار شبه كريمة', 'en' => 'Semi-precious'],
            'colored' => ['ar' => 'أحجار ملونة', 'en' => 'Colored stones'],
            'other' => ['ar' => 'أخرى', 'en' => 'Other'],
        ],
        'shapes' => [
            'round' => ['ar' => 'دائري', 'en' => 'Round'],
            'oval' => ['ar' => 'بيضاوي', 'en' => 'Oval'],
            'pear' => ['ar' => 'كمثري', 'en' => 'Pear'],
            'cushion' => ['ar' => 'وسادة', 'en' => 'Cushion'],
            'princess' => ['ar' => 'أميرة', 'en' => 'Princess'],
            'emerald_cut' => ['ar' => 'زمردي', 'en' => 'Emerald cut'],
            'other' => ['ar' => 'أخرى', 'en' => 'Other'],
        ],
        'origin_types' => [
            'natural' => ['ar' => 'طبيعي', 'en' => 'Natural'],
            'lab' => ['ar' => 'مصنع', 'en' => 'Lab-grown'],
            'unknown' => ['ar' => 'غير معروف', 'en' => 'Unknown'],
        ],
        'stone_conditions' => [
            'excellent' => ['ar' => 'ممتازة', 'en' => 'Excellent'],
            'good' => ['ar' => 'جيدة', 'en' => 'Good'],
            'fair' => ['ar' => 'مقبولة', 'en' => 'Fair'],
            'needs_attention' => ['ar' => 'تحتاج عناية', 'en' => 'Needs attention'],
        ],
        'yes_no_unknown' => [
            'yes' => ['ar' => 'نعم', 'en' => 'Yes'],
            'no' => ['ar' => 'لا', 'en' => 'No'],
            'unknown' => ['ar' => 'غير معروف', 'en' => 'Unknown'],
        ],
        'evaluation_purposes' => [
            'sell' => ['ar' => 'بيع', 'en' => 'Sell'],
            'buy' => ['ar' => 'شراء', 'en' => 'Buy'],
            'investment' => ['ar' => 'استثمار', 'en' => 'Investment'],
            'insurance' => ['ar' => 'تأمين', 'en' => 'Insurance'],
            'inheritance' => ['ar' => 'ورثة', 'en' => 'Inheritance'],
            'documentation' => ['ar' => 'توثيق', 'en' => 'Documentation'],
            'market_value' => ['ar' => 'معرفة القيمة السوقية', 'en' => 'Market value'],
        ],
    ],

    'referral_sources' => [
        'instagram' => ['ar' => 'إنستغرام', 'en' => 'Instagram'],
        'tiktok' => ['ar' => 'تيك توك', 'en' => 'TikTok'],
        'google' => ['ar' => 'بحث جوجل', 'en' => 'Google search'],
        'friend' => ['ar' => 'صديق / معارف', 'en' => 'Friend / referral'],
        'other' => ['ar' => 'أخرى', 'en' => 'Other'],
    ],

    'upload' => [
        'max_photos' => 8,
        'max_certificates' => 5,
        'max_file_kb' => 5120,
        'mimes' => 'jpg,jpeg,png,webp,pdf',
    ],
];
