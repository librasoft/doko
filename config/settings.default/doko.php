<?php
$doko = [
    'Plugins' => [
//        'DebugKit',
        'Users',
    ],
    'Frontend' => [
        'title' => 'Doko CMS',
        'home-title' => 'Doko, il CMS umile',
        'home-description' => 'Descrizione in italiano',
        'home-url' => '/',
        'robots' => null,
        'theme' => 'DokoFrontend',
        'theme-color' => null,
        'logo' => [
            'width' => 270,
            'height' => 100,
        ],
        'status' => true,
        'languages' => [
            'en', 'it',
        ],
    ],
    'Owner' => [
        'email' => 'info@doko-cms.it',
        'copyright' => 'www.Doko-CMS.it',
        'legal_name' => 'Librasoft snc',
        'legal_address' => 'Via della Luna, 13 - 47034 Forlimpopoli (FC)',
        'phone' => '+39 0543 424612',
        'fax' => '+39 0543 424612',
        'vat_code' => '03961040403',
        'fiscal_code' => '03961040403',
    ],
    'Services' => [
        'analytics' => [
//            'GoogleAnalytics' => '',
        ],
    ],
    'Backend' => [
        'home-url' => '/admin/',
        'theme' => 'DokoAdmin',
        'languages' => [
            'it',
        ],
    ],
    'Profile' => [
        'theme' => 'DokoProfile',
        'password_min_length' => 8,
        'password_strength_factor' => 0.6,
    ],
    'I18n' => [
        'default-timezone' => 'Europe/Rome',
        'en' => [
            'Frontend' => [
                'title' => 'Doko CMS ENG',
                'home-title' => 'Doko, the humble CMS',
                'home-description' => 'Descrizione in inglese',
            ],
        ],
    ],
];

return [
    'Doko' => $doko,
];
