<?php
namespace Menus\Controller\Component;

use App\I18n\LanguageRegistry;
use Cake\Cache\Cache;
use Cake\Controller\Component;
use Cake\Event\Event;

/**
 * Frontend component
 */
class FrontendComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Events supported by this component.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Controller.beforeRender' => 'beforeRender',
        ];
    }

    public function beforeRender(Event $event)
    {
        $menus = Cache::remember('dokoMenus-' . LanguageRegistry::$current, function () use ($event) {
            $event->subject()->loadModel('Menus.Menus');

            return $event->subject()->Menus
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
                    'language' => LanguageRegistry::$current,
                    'status' => MENUS_STATUS_ACTIVE,
                ])
                ->combine('alias', function ($entity) { return $entity; })
                ->toArray();
        });

        $event->subject()->set('dokoMenus', $menus);
    }

}
