<?php
namespace Menus\View\Helper;

use Cake\View\Helper;
use InvalidArgumentException;
use Menus\Builder\PluggableMenu\PluggableMenu;

/**
 * Menu helper
 */
class MenuHelper extends Helper
{

    public $helpers = ['Layout'];

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function implementedEvents()
    {
        return [];
    }

    public function display($alias, $options = [], $type = 'table')
    {
        $defaults = [
            'mode' => 'dropdown', // possible values: ul, div, dropdown
            'class' => 'nav nav-menu nav-menu-' . strtolower($alias),
        ];

        $options += $defaults;

        switch ($type) {
            case 'table':
                $menus = $this->_View->get('dokoMenus');
                if (empty($menus[$alias])) {
                    return '';
                }
                return $this->Layout->nestedList($this->normalize($menus[$alias]->links), $options);

            case 'pluggable':
                return $this->Layout->nestedList(PluggableMenu::items($alias), $options);
        }
        throw new InvalidArgumentException(sprintf('Type %s not valid for MenuHelper::display', $type));
    }

    public function normalize($items)
    {
        $list = [];
        for ($i = 0, $n = count($items); $i < $n; $i++) {
			$list[] = [
				'link' => [
					'title' => $items[$i]->title,
					'url' => is_string($items[$i]->url) && $items[$i]->url[0] === '{' ? json_decode($items[$i]->url, true) : $items[$i]->url,
					'options' => [
						'rel' => $items[$i]->rel,
						'target' => $items[$i]->target_blank ? '_blank' : null,
					],
				],
                'element' => $items[$i]->element ? [
                    'name' => $items[$i]->element,
                    'data' => $items[$i]->element_options ? json_decode($items[$i]->element_options, true) : null,
                ] : null,
                'acl_token' => $items[$i]->acl_token,
				'options' => [
					'class' => $items[$i]->css_class,
				],
				'children' => $items[$i]->children ? $this->_normalize($items[$i]->children) : [],
			];
        }
        return $list;
    }

}
