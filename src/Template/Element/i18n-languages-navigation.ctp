<?php

use App\I18n\LanguageRegistry;

$_defaults = [
    'label' => '',
    'class' => '',
    'force' => false,
    'mode' => 'dropdown', //possible values: dropdown, plain, plain-no-current
    'titles' => 'normal', //possible values: normal, 2-letters, 3-letters
];

$options = isset($options) ? $options + $_defaults : $_defaults;

if (!$options['force'] && !LanguageRegistry::$multilanguage) {
    return;
}

$languages_links = $this->Layout->getLanguagesLinks();
$languages_nav = [];

foreach ($languages_links as $language => $link) {
    $is_current = $language === LanguageRegistry::$current;
    if ($is_current && $options['mode'] !== 'plain') {
        continue;
    }
    $title = mb_convert_case(locale_get_display_language($language, $language), MB_CASE_TITLE, 'utf-8');

    switch ($options['titles']) {
        case '2-letters':
            $title = substr($title, 0, 2);
            break;
        case '3-letters':
            $title = substr($title, 0, 3);
            break;
    }

    if ($options['mode'] === 'dropdown') {
        $title .= sprintf(' <small>(%s)</small>', mb_convert_case(locale_get_display_language($language), MB_CASE_TITLE, 'utf-8'));
    }

    $languages_nav[] = [
        'link' => [
            'title' => $title,
            'url' => $link['url'],
            'options' => [
                'lang' => $language,
                'hreflang' => $language,
            ],
        ],
        'options' => [
            'class' => $is_current ? 'link-' . $language . ' active' : 'link-' . $language,
        ],
    ];
}

if ($options['mode'] === 'dropdown') {
    $language = LanguageRegistry::$current;
    $title = mb_convert_case(locale_get_display_language($language), MB_CASE_TITLE, 'utf-8');

    switch ($options['titles']) {
        case '2-letters':
            $title = substr($title, 0, 2);
            break;
        case '3-letters':
            $title = substr($title, 0, 3);
            break;
    }

    $languages_nav = [
        [
            'link' => [
                'title' => $title,
                'url' => null,
                'options' => [
                    'lang' => $language,
                    'hreflang' => $language,
                ],
            ],
            'options' => [
                'class' => 'link-' . $language,
            ],
            'children' => $languages_nav,
        ],
    ];
}

if (!empty($options['label'])) {
    echo $options['label'];
}
echo $this->Layout->nestedList($languages_nav, [
    'mode' => 'dropdown',
    'class' => 'nav nav-languages' . ($options['class'] ? ' ' . $options['class'] : ''),
]);
