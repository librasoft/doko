<?php
namespace Blocks\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * Regions helper
 */
class RegionsHelper extends Helper
{

    public $helpers = ['Layout'];

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function display($alias, $options = [])
    {
        $defaults = [
            'mode' => 'div', // possible values: ul, div, dropdown
            'class' => 'blocks blocks-' . strtolower($alias),
            'title-tag' => 'h2',
        ];

        $options += $defaults;

        $blocks = $this->_View->get('dokoBlocks');

        if (empty($blocks[$alias])) {
            return '';
        }
        return $this->Layout->nestedList($this->normalize($blocks[$alias], $options), $options);
    }

    public function sidebar($alias, $options = [])
    {
        $defaults = [
            'offcanvas-button' => true,
            'button-label' => __d('Blocks', 'Sidebar'),
        ];

        $options += $defaults;

        $blocks = $this->display($alias, $options);

        if (!$blocks) {
            return '';
        }

        $this->Layout->setCss('layout-has-sidebar');

        $sidebar = '';
        if ($options['offcanvas-button']) {
            $sidebar .= '<button class="btn btn-offcanvas-sidebar" data-toggle="offcanvas" data-offcanvas-dir="right" aria-hidden="true">' . $options['button-label'] . '</button>';
        }
        $sidebar .= '<aside id="complementary" class="sidebar" role="complementary">';
        $sidebar .= $blocks;
        $sidebar .= '</aside>';

        return $sidebar;
    }

    public function normalize($items, $options)
    {
        $list = [];
        foreach ($items as $item) {
            $list[] = [
                'element' => [
                    'name' => 'Blocks.block',
                    'data' => [
                        'block' => $item,
                        'options' => $options,
                    ],
                ],
                'acl_token' => $item->acl_token,
            ];
        }
        return $list;
    }

}
