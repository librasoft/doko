<?php
namespace Menus\View\Cell;

use App\I18n\LanguageRegistry;
use Cake\View\Cell;

/**
 * Menu cell
 */
class MenuCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($alias, $language = null, $options = [])
    {
        if (empty($alias)) {
            throw new \InvalidArgumentException('Please specify an alias for MenuCell display.');
        }
        if (empty($language)) {
            $language = LanguageRegistry::$current;
        } elseif (!in_array($language, LanguageRegistry::$languages)) {
            throw new \InvalidArgumentException('Language not installed.');
        }

        $defaults = [
            'mode' => 'dropdown', // possible values: ul, div, dropdown
            'class' => 'nav nav-menu nav-menu-' . strtolower($alias),
        ];

        $options += $defaults;

        $this->loadModel('Menus.Menus');
        $menu = $this->Menus
            ->find()
            ->contain([
                'Links' => function ($q) {
                    return $q
                        ->find('threaded')
                        ->where([
                            'status' => MENUS_STATUS_ACTIVE,
                        ])
                        ->order([
                            'lft' => 'ASC',
                        ]);
                },
            ])
            ->where([
                'alias' => $alias,
                'language' => $language,
                'status' => MENUS_STATUS_ACTIVE,
            ])
            ->first();

        $this->set('alias', $alias);
        $this->set('menu', $menu);
        $this->set('options', $options);
    }

}
