<?php

use Cake\Event\Event;
use Cake\Event\EventManager;

define('MENUS_STATUS_ACTIVE', 1);
define('MENUS_STATUS_INACTIVE', 0);

EventManager::instance()->on('Controller.hook', function (Event $event) {
    if (!$event->subject()->request->param('prefix')) {
        $event->subject()->loadComponent('MenusLoader', ['className' => 'Menus.Frontend']);
    }
    $event->subject()->viewBuilder()->helpers(['Menus.Menu']);

	return true;
});
