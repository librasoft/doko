<?php

use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Routing\Router;

EventManager::instance()->on('Controller.hookComponents', function (Event $event) {
	$event->subject()->loadComponent('Auth', [
		'authenticate' => [
			'all' => [
				'fields' => [
                    'username' => 'email',
                ],
			],
			'Form',
		],
		'flash' => [
            'element' => 'default',
            'key' => 'auth',
            'params' => [
                'class' => 'error',
            ],
        ],
		'loginAction' => [
            'plugin' => 'Users',
            'controller' => 'Users',
            'action' => 'login',
        ],
		'loginRedirect' => '/',
		'logoutRedirect' => [
            'plugin' => 'Users',
            'controller' => 'Users',
            'action' => 'login',
        ],
	]);
	$event->subject()->loadComponent('Users.Login');
	$event->subject()->loadComponent('Users.ACL');

	// Allow all by default. We'll use Auth/ACL to check permissions.
	$event->subject()->Auth->allow();

	return true;
});

Router::scope('/', function($routes) {
	$routes->connect('/login', [
        'plugin' => 'Users',
        'controller' => 'Users',
        'action' => 'login',
    ]);
	$routes->connect('/logout', [
        'plugin' => 'Users',
        'controller' => 'Users',
        'action' => 'logout',
    ]);
	$routes->connect('/forgot-password', [
        'plugin' => 'Users',
        'controller' => 'Users',
        'action' => 'forgot',
    ]);
	$routes->connect('/reset-password/*', [
        'plugin' => 'Users',
        'controller' => 'Users',
        'action' => 'reset',
    ]);
	$routes->connect('/clear-logins', [
        'plugin' => 'Users',
        'controller' => 'Users',
        'action' => 'clear_logins',
    ]);
});

Cache::config('throttling', [
    'className' => 'File',
    'duration' => '+1 hours',
    'path' => CACHE . 'throttling' . DS,
]);