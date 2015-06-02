<?php

namespace App\View\Helper;

use App\I18n\LanguageRegistry;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\View\Helper;

class LayoutHelper extends Helper
{

    /**
     * Other helpers used by FormHelper
     *
     * @var array
     */
    public $helpers = ['Url', 'Html'];

    /**
     * Returns current language for html attribute like lang=""
     *
     * @return string
     */
    public function lang()
    {
        return LanguageRegistry::$current;
    }


    /**
     * Sets a list of css classes for the current html document.
     *
     * @param string|array $classes
     * @return string
     */
    public function setCss($classes)
    {
        $this->_View->set('documentCss', array_unique(array_merge((array) $this->_View->get('documentCss'), (array) $classes)));
    }

    /**
     * Gets the list of css classes for the current html document.
     *
     * @param array $url_params
     * @return string
     */
    protected function _getCss($url_params = null)
    {
        $classes = (array) $this->_View->get('documentCss');

        if ($url_params) {
            unset($url_params['prefix']);

            foreach ($url_params as $key => $value) {
                $classes[] = strtolower('doko-' . $key[0] . '-' . str_replace('_', '-', $value));
            }
        }

        return implode(' ', $classes);
    }

    /**
     * Sets a list of executable javascript functions to call in the current html document.
     * http://www.viget.com/inspire/extending-paul-irishs-comprehensive-dom-ready-execution/
     *
     * @param string|array $functions
     * @return string
     */
    public function setJs($functions = null)
    {
        if (is_string($functions)) {
            $functions = [
                $functions => 'init',
            ];
        }
        $doc_js = (array) $this->_View->get('documentJs');

        foreach ((array) $functions as $controller => $action) {
            $doc_js[$controller] = isset($doc_js[$controller]) ? array_unique(array_merge((array) $doc_js[$controller], (array) $action)) : (array) $action;
        }

        $this->_View->set('documentJs', $doc_js);
    }

    /**
     * Gets the list of executable javascript functions to call in the current web page.
     * http://www.viget.com/inspire/extending-paul-irishs-comprehensive-dom-ready-execution/
     *
     * @param array $url_params
     * @return string
     */
    protected function _getJs($url_params = null)
    {
        $functions = (array) $this->_View->get('documentJs');

        if ($url_params) {
            $functions[$url_params['controller']] = isset($functions[$url_params['controller']]) ? array_unique(array_merge((array) $functions[$url_params['controller']], (array) $url_params['action'])) : (array) $url_params['action'];
        }

        return h(json_encode($functions));
    }

    /**
     * Retrieve usefull url params from request's params.
     *
     * @param Request|array $params The params array or
     *     Cake\Network\Request object that needs to be reversed.
     * @return array
     */
    protected function _parseUrlParams($request)
    {
        $params = $request->params;
        $pass = isset($params['pass']) ? $params['pass'] : [];

        unset(
            $params['pass'],
            $params['_Token'], $params['_csrfToken'], $params['isAjax'], $params['_ext']
        );

        return array_merge($params, $pass);
    }

    /**
     * Create the opening <html> element, with:
     * - html5 data-* attributes for automatic jQuery DOM-ready execution.
     *   http://www.viget.com/inspire/extending-paul-irishs-comprehensive-dom-ready-execution/
     * - css depending on current url to simplify styling
     *
     * @return string
     */
    public function htmlStart()
    {
        $url_params = $this->_parseUrlParams($this->request);

        return '<html'
            . ' lang="' . $this->lang() . '"'
            . ' class="' . $this->_getCss($url_params) . '"'
            . ' data-js="' . $this->_getJs($url_params) . '"'
            . '>' . "\n";
    }

    /**
     * The <title></title> tag with the current title for layout and site title.
     *
     * @param string $sep the char separating page and site title.
     */
    public function title($sep = '|')
    {
        $title = strip_tags($this->_View->fetch('title'));

        if (!$title) {
            $title = Configure::read('Doko.Frontend.title');
        } elseif ($sep) {
            $title .= ' ' . $sep . ' ' . Configure::read('Doko.Frontend.title');
        }

        echo "\n\t" . $this->Html->tag('title', $title);
    }

    /**
     * Meta tag for page viewport.
     *
     * @param type $viewport The value of the viewport to use.
     */
    public function viewport($viewport = 'width=device-width, initial-scale=1')
    {
        echo "\n\t" . $this->Html->meta([
            'name' => 'viewport',
            'content' => $viewport,
        ]);
    }

    /**
     * Sets the canonical URL meta tag.
     *
     * @param mixed $url
     */
    public function setCanonical($url = null)
    {
        $this->_View->set('canonical', $this->Url->build($url, true));
    }

    /**
     * Set a meta for the current html document.
     *
     * Available types:
     * - meta		<meta name="$meta" content="$content">
     * - link		<link rel="$meta" href="$content">
     * - property	<meta property="$meta" content="$content">
     * - lang		<link rel="alternate" hreflang="$meta" href="$content">
     *
     * @param string|array $meta
     * @param string $content
     * @param string $type
     */
    public function setMeta($meta, $content = null, $type = 'meta')
    {
        $available_types = [
            'meta', 'link', 'property', 'lang',
        ];

        if (!in_array($type, $available_types)) {
            throw new \InvalidArgumentException('Type not allowed.');
        }

        if (is_string($meta)) {
            $meta = [
                $meta => $content,
            ];
        }

        $meta = Hash::merge((array) $this->_View->get('documentMeta'), [
            $type => $meta,
        ]);

        $this->_View->set('documentMeta', $meta);
    }

    /**
     * Gets all the document meta to be displayed in the <head>
     *
     * @return string
     */
    public function documentMeta()
    {
        $types = [
            'meta' => [
                'name',
                'content',
            ],
            'link' => [
                'rel',
                'link',
            ],
            'property' => [
                'property',
                'content',
            ],
            'lang' => [
                'rel', // alternate
                'hreflang', // it, en, ...
                'link', // www.example.com/it, www.example.com/en, ...
            ],
        ];

        $metas = $this->_initMeta((array) $this->_View->get('documentMeta'));
        $output = '';

        foreach ($types as $type => $attributes) {
            if (!empty($metas[$type])) {
                foreach ($metas[$type] as $key => $value) {
                    $values = ($type !== 'lang') ? [$key, $value] : ['alternate', $key, $value];
                    $output .= "\n\t" . $this->Html->meta(array_combine($attributes, $values));
                }
            }
        }

        return $output;
    }

    /**
     * Inits and returns meta for the current html document with doko's default metas.
     *
     * @param array $meta
     * @return array
     */
    protected function _initMeta($meta)
    {
        // Type meta
        if (empty($meta['meta'])) {
            $meta['meta'] = [];
        }

        $meta['meta'] = Hash::filter([
            'generator' => 'Doko di Librasoft — www.LibrasoftSnc.it',
        ] + $meta['meta'] + [
            'robots' => Configure::read('Doko.Frontend.robots'),
            'theme-color' => Configure::read('Doko.Frontend.theme-color'),
            'format-detection' => 'telephone=no',
        ]);

        // Type link
        if (empty($meta['link'])) {
            $meta['link'] = [];
        }

        $meta['link'] = Hash::filter($meta['link'] + [
            'canonical' => $this->_View->get('canonical'),
            'apple-touch-icon' => Router::fullBaseUrl() . $this->Url->webroot(Configure::read('App.imageBaseUrl') . 'touch-icon.png'),
        ]);

        // Type property: open graph and twitter
        if (empty($meta['property'])) {
            $meta['property'] = [];
        }

        $meta['property'] = Hash::filter($meta['property'] + [
            // Open Graph (Facebook)
            'og:title' => strip_tags($this->_View->get('title')) ?: Configure::read('Site.title'),
            'og:url' => $this->_View->get('canonical') ? $this->Url->build($this->_View->get('canonical'), true) : $this->Url->build(null, true),
            'og:image' => Router::fullBaseUrl() . $this->Url->webroot(Configure::read('App.imageBaseUrl') . 'og-icon.png'),
            'og:type' => 'website',
            'og:site_name' => Configure::read('Site.title'),
            'og:locale' => LanguageRegistry::$current,
            // Twitter Card
//			'twitter:card' => 'summary',
//			'twitter:site' => !empty($contacts['socials']['twitter'][0]) ? preg_replace('#https?://(www.)?twitter.com/#', '@', $contacts['socials']['twitter'][0]) : false,
        ]);

        // Type lang
        // http://support.google.com/webmasters/bin/answer.py?hl=en&answer=189077
        if (LanguageRegistry::$multilanguage) {
            if (empty($meta['lang'])) {
                $meta['lang'] = [];
            }

            $meta['lang'] = Hash::filter($meta['lang'] + $this->getLanguagesLinks());

            if (count($meta['lang']) === 1) {
                $meta['lang'] = [];
            }
            foreach ($meta['lang'] as $language => $link) {
                if (!empty($link['matching'])) {
                    $meta['lang'][$language] = $this->Url->build($link['url']);
                }
            }
        }

        return $meta;
    }

    /**
     * Returns all the links of the available languages
     * for the current page location.
     *
     * Examples
     * ita => www.example.com/it/
     * eng => www.example.com/en/
     *
     * @return array
     */
    public function getLanguagesLinks()
    {
        $languages_links = (array) $this->_View->get('languages_links');

        if (!$languages_links) {
            $languages = LanguageRegistry::$languages;

            foreach ($languages as $language) {
                $languages_links[$language] = [
                    'url' => [
                        'language' => $language,
                    ],
                    'matching' => true,
                ];
            }

            $this->_View->set('languages_links', $languages_links);
        }

        return $languages_links;
    }

    /**
     * Sets one or more variable of the global javascript object
     * used in doko frontend for the current web page.
     *
     * @param array|string $vars
     * @param mixed $value
     */
    public function setJsVars($vars, $value = null)
    {
        if (is_string($vars)) {
            $vars = [
                $vars => $value,
            ];
        }

        $vars = Hash::merge((array) $this->_View->get('JsVars'), $vars);
        $this->_View->set('JsVars', $vars);
    }

    /**
     * Inits the global doko javascript object with the setted js variables.
     *
     * Base js variables:
     * - applications's webroot
     * - debug mode
     *
     * @return string
     */
    public function initJsVars()
    {
        $url_params = $this->_parseUrlParams($this->request);
        unset($url_params['prefix'], $url_params['plugin'], $url_params['controller'], $url_params['action']);

        $js = [
            'debug' => Configure::read('debug'),
            'webroot' => rtrim($this->request->webroot, '/'),
            'themeroot' => $this->request->webroot . 'theme/' . $this->theme . '/',
            'imgroot' => $this->request->webroot . 'theme/' . $this->theme . '/img/',
            'params'	=> $url_params,
        ] + (array) $this->_View->get('JsVars');

        return "\n\t" . $this->Html->tag('script', 'var doko={"vars":' . json_encode($js) . '}');
    }

    /**
     * Returns javascript libs for old versions of Internet Explorer.
     *
     * @param integer $ie_version
     * @param array other libs to call
     * @return string
     */
    public function oldIE($ie_version = 8, $libs = [])
    {
        $default = [
            '//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.min.js',
        ];
        $libs = array_merge($default, $libs);

        return "\n\t" . '<!--[if lte IE ' . $ie_version . ']>' . $this->Html->script($libs) . "\t" . '<![endif]-->' . "\n";
    }

    /**
     * Sets html5 microdata Type for the current html document.
     *
     * @param string $type
     * @param string $base_url
     * @return string
     */
    public function setMicrodata($type, $base_url = 'http://schema.org/')
    {
        $this->_View->set('documentMicrodata', $base_url . $type);
    }

    /**
     * Gets html5 microdata Type for the current html document.
     *
     * @param string $type
     * @param string $base_url
     * @return string
     */
    protected function _getMicrodata()
    {
        if ($this->_View->get('documentMicrodata')) {
            return ' itemscope itemtype="' . $this->_View->get('documentMicrodata') . '"';
        }

        return '';
    }

    /**
     * Create the opening <body> element with html5 microdata (when defined).
     *
     * @return string
     */
    public function bodyStart()
    {
        return '<body' . $this->_getMicrodata() . '>' . "\n";
    }

    /**
     * Returns a nested html list of items with a standard structure.
     * $item's structure:
     * 		'element'	=> render element with given:
     * 			'name'		=> element name
     * 			'data'		=> element data
     * 			'options'	=> element options
     * 		'link'		=> render link with given:
     * 			'title'		=> link title
     * 			'url'		=> link url
     * 			'options'	=> link options
     * 			'confirm'	=> link confirm alert
     * 		'options'	=> options for the current item
     * 		'children'	=> item's children
     *
     * @param array $items
     * @param array $options
     * @param integer $level
     * @return string
     */
    public function nestedList($items = [], $options = [], $level = 0)
    {
        $_options = [
            'mode' => 'ul', // possible values: ul, div, dropdown
            'class' => '',
            'attrs' => [],
        ];
        $options += $_options;

        $output = '';
        $options['class'] .= ' level-' . ($level + 1);

        foreach ($items as $item) {
            $item_output = null;

            $item['options'] = !empty($item['options']) ? $item['options'] : [];
            $item['options']['class'] = !empty($item['options']['class']) ? $item['options']['class'] : '';
            $item['children'] = !empty($item['children']) ? $item['children'] : [];

            if (!empty($item['element'])) {
                if (empty($item['element']['name'])) {
                    continue;
                }
                $item['element']['data'] = !empty($item['element']['data']) ? $item['element']['data'] : [];
                $item['element']['options'] = !empty($item['element']['options']) ? $item['element']['options'] : [];

                $item_output = $this->_View->element($item['element']['name'], $item['element']['data'], $item['element']['options']);
            } elseif (!empty($item['link'])) {
                $item['link']['title'] = !empty($item['link']['title']) ? $item['link']['title'] : '';
                $item['link']['options'] = !empty($item['link']['options']) ? $item['link']['options'] : [];
                $item['link']['options']['class'] = !empty($item['link']['options']['class']) ? $item['link']['options']['class'] : '';

                if ($options['mode'] === 'dropdown' && !empty($item['children'])) {
                    $item['link']['title'] .= ' <span class="caret"></span>';
                    $item['link']['options']['class'] .= ' dropdown-toggle';
                    $item['link']['options']['data-toggle'] = 'dropdown';
                }

                $item['options']['class'] .= ' ' . $this->activeClass($item['link']['url'], $item['children']);

                $item_output = $this->Html->link($item['link']['title'], $this->Url->build($item['link']['url']), $item['link']['options'] + [
                    'escapeTitle' => false,
                ]);
            } elseif ($item['options']['class'] === 'divider') {
                $item_output = '';
            }

            //Render item's children
            if (!empty($item['children'])) {
                $children_options = $options;

                if ($options['mode'] === 'dropdown') {
                    $item['options']['class'] .= ' dropdown';
                    $children_options['class'] = 'dropdown-menu';
                }

                $item_output .= $this->nestedList($item['children'], $children_options, $level + 1);
            }

            if ($item_output !== null) {
                if ($options['mode'] === 'div') {
                    $output .= $item_output;
                } else {
                    $output .= $this->Html->tag('li', $item_output, $item['options']);
                }
            }
        }

        if ($output) {
            return $this->Html->tag($options['mode'] === 'div' ? 'div' : 'ul', $output, [
                'class' => $options['class'],
            ] + $options['attrs']);
        }

        return '';
    }

    /**
     * Given an item's url and a list of the item's children,
     * returns the "active" css class relative to the current request url.
     *
     * @param type $url
     * @param type $children
     * @return string
     */
    public function activeClass($url, $children = [])
    {
        if ($this->_View->get('canonical') && $this->Url->build($url, true) === $this->_View->get('canonical')) {
            return 'active';
        }
        if ($this->Html->inCrumb($url)) {
            return 'active active-parent';
        }
        foreach ($children as $child) {
            if (!empty($child['link']) && $this->activeClass($child['link']['url'], !empty($child['children']) ? $child['children'] : [])) {
                return 'active active-parent';
            }
        }

        return '';
    }

    /**
     * Analytics Snippet to track the page view.
     */
    public function analyticsTrackPage()
    {
        $output = '';
        $analytics = (array)Configure::read('Services.analytics');

        foreach ($analytics as $provider => $key) {
            if ($key) {
                $output .= $this->_View->element('Analytics/' . $provider . '/trackPage', compact('key'));
            }
        }

        return $output;
    }

}
