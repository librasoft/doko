<?php
namespace App\Controller\Component;

use App\I18n\LanguageRegistry;
use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;

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
        if (LanguageRegistry::$multilanguage_frontend) {
            if (!$this->request->param('language') && empty($this->request->data)) {
                if ($this->request->is('ajax')) {
                    throw new BadRequestException('No language specified');
                }
                return $this->_registry->getController()->redirect([
                    'language' => LanguageRegistry::$current,
                ]);
            }
        }
    }

}
