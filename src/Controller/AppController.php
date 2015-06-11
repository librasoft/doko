<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Routing\Router;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Network\Response;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize()
    {
        if (!$this->theme) {
            $this->loadTheme(Configure::read('Doko.Frontend.theme'));
        }

        $this->loadComponent('Languages');
        $this->loadComponent('Form');
        $this->loadComponent('Csrf');
        $this->loadComponent('Security');
        $this->loadComponent('Flash');
        $this->loadComponent('RequestHandler');
        $this->dispatchEvent('Controller.hookComponents');
    }

    /**
     * Loads the chosen theme.
     */
    public function loadTheme($theme) {
        if ($this->theme === $theme) {
            return;
        }

        Plugin::load($theme, [
            'autoload' => true,
            'path' => ROOT . DS . 'themes' . DS,
            'bootstrap' => true,
            'routes' => true,
            'ignoreMissing' => true,
        ]);
        $this->theme = $theme;
    }

    public function beforeRedirect(Event $event, $url, Response $response)
    {
        $response->location(Router::url($url, true));
        return $response;
    }

}
