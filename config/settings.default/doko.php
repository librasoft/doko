<?php
$doko = [
    'Plugins' => [
//        'DebugKit',
        'Users',
        'Menus',
        'Blocks',
    ],
    'Frontend' => [
        'title' => 'Doko CMS',
        'home-title' => 'Doko, il CMS umile',
        'home-description' => 'Descrizione in italiano',
        'home-url' => '/',
        'robots' => null,
        'theme' => 'DokoFrontend',
        'theme-color' => null,
        'logo-width' => 270,
        'logo-height' => 100,
        'status' => true,
        'languages' => [
            'en', 'it',
        ],
    ],
    'Owner' => [
        'email' => 'info@doko-cms.it',
        'copyright' => 'www.Doko-CMS.it',
        'legal-name' => 'Librasoft snc',
        'legal-address' => 'Via della Luna, 13 - 47034 Forlimpopoli (FC)',
        'phone' => '+39 0543 424612',
        'fax' => '+39 0543 424612',
        'vat-code' => '03961040403',
        'fiscal-code' => '03961040403',
    ],
    'Services' => [
        'tinypng' => '',
        'analytics' => [
//            'GoogleAnalytics' => '',
        ],
    ],
    'Backend' => [
        'home-url' => '/admin/',
        'theme' => 'DokoBackend',
        'languages' => [
            'it',
        ],
    ],
    'Profile' => [
        'theme' => 'DokoProfile',
        'password-min-length' => 8,
        'password-strength-factor' => 0.6,
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
