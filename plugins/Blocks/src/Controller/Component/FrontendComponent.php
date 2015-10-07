<?php
namespace Blocks\Controller\Component;

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
        $blocks = Cache::remember('dokoBlocks-' . LanguageRegistry::$current, function () use ($event) {
            $event->subject()->loadModel('Blocks.Blocks');

            return $event->subject()->Blocks
                ->find('threaded')
                ->where([
                    'language' => LanguageRegistry::$current,
                    'status' => BLOCKS_STATUS_ACTIVE,
                ])
                ->order([
                    'region' => 'ASC',
                    'lft' => 'ASC',
                ])
                ->combine('id', function ($entity) { return $entity; }, 'region')
                ->toArray();
        });

        $event->subject()->set('dokoBlocks', $blocks);
    }

}
