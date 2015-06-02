<?php
namespace App\Controller\Component;

use App\I18n\LanguageRegistry;
use Cake\Controller\Component;
use Cake\Event\Event;

/**
 * Languages component
 */
class LanguagesComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];


    public function implementedEvents()
    {
        return [
            'Controller.initialize' => 'beforeFilter',
        ];
    }

    public function beforeFilter(Event $event)
    {
        if (LanguageRegistry::$multilanguage) {
            if (!$this->request->param('language')) {
                return $this->_registry->getController()->redirect([
                    'language' => LanguageRegistry::$current,
                ]);
            }
        }
    }

}
