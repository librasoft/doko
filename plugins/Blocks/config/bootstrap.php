<?php

use Cake\Event\Event;
use Cake\Event\EventManager;

define('BLOCKS_STATUS_ACTIVE', 1);
define('BLOCKS_STATUS_INACTIVE', 0);

EventManager::instance()->on('Controller.hook', function (Event $event) {
    if (!$event->subject()->request->param('prefix')) {
        $event->subject()->loadComponent('BlocksLoader', ['className' => 'Blocks.Frontend']);
    }
    $event->subject()->viewBuilder()->helpers(['Blocks.Regions']);

    return true;
});
