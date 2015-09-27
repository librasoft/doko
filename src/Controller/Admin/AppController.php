<?php

namespace App\Controller\Admin;

use App\Controller\AppController as BaseController;
use Cake\Core\Configure;
use Cake\Error\ForbiddenException;
use Cake\Event\Event;

class AppController extends BaseController
{

    public function initialize()
    {
        // deny access to ie < 10
        if (preg_match('/(?i)msie [1-9]\./', env('HTTP_USER_AGENT'))) {
            throw new ForbiddenException(__('You are using an outdated browser.'));
        }

        $this->viewBuilder()->theme(Configure::read('Backend.theme'));

        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->ACL->checkPermission('Backend.Access');
    }

}
