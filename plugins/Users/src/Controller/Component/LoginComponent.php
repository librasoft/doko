<?php

namespace Users\Controller\Component;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Cache\Cache;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

/**
 * - Prevents login throttling.
 * - Handles "Remember me" cookie.
 */
class LoginComponent extends Component
{

    protected $_defaultConfig = [
        'cookie_name' => 'urmc',
        'restore_interval' => '1 month',
    ];

    /**
     * Other components utilized by AuthComponent
     *
     * @var array
     */
    public $components = ['Auth', 'Cookie', 'Flash'];

    /**
     * Events supported by this component.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Controller.initialize' => 'beforeFilter',
            'Controller.userLoginSuccess' => 'loginSuccess',
            'Controller.userLoginFailure' => 'loginFailure',
            'Controller.userLogoutAfter' => 'logoutAfter',
            'Controller.beforeRender' => 'beforeRender',
        ];
    }

    public function beforeFilter(Event $event)
    {
        if (!$this->Auth->user() && $this->Cookie->check($this->_config['cookie_name'])) {
            $this->_restoreLogin();
        }
        if ($this->Auth->user()) {
            date_default_timezone_set($this->Auth->user('timezone'));
            ini_set('intl.default_locale', $this->Auth->user('language'));
        }
    }

    public function beforeRender(Event $event)
    {
        $event->subject()->set('authUser', $this->Auth->user());
    }

    /**
     * Called after a login success.
     *
     * @param Event $event
     */
    public function loginSuccess(Event $event, $user)
    {
        $this->clearThrottling($event->subject()->request->data['email']);
        date_default_timezone_set($user['timezone']);
        ini_set('intl.default_locale', $user['language']);

        if (array_key_exists('save_user_login', $event->subject()->request->data) && $event->subject()->request->data['save_user_login']) {
            $this->_saveLogin($user['id']);
        }
    }

    /**
     * Called after a login attempt failed.
     *
     * @param Event $event
     */
    public function loginFailure(Event $event)
    {
        $controller = $event->subject();

        if (!empty($controller->request->data['email'])) {
            $identifier = $controller->request->data['email'];
        } else {
            $identifier = 'ip_' . ip2long($controller->request->clientIp());
        }

        $this->preventThrottling($identifier);
    }

    /**
     * Called after user logout.
     *
     * @param Event $event
     */
    public function logoutAfter(Event $event)
    {
        $this->_clearSavedLogins();
    }

    /**
     * Generates a random token.
     *
     * @return string
     */
    protected function _generateToken()
    {
        return hash('sha256', php_uname() . microtime(true));
    }

    /**
     * If the visitor is not logged in, check for remember me cookie
     * and restore the session if cookie tokens and db tokens matches.
     *
     * @link http://fishbowl.pastiche.org/2004/01/19/persistent_login_cookie_best_practice/
     * @return void
     */
    protected function _restoreLogin()
    {
        $this->Cookie->configKey($this->_config['cookie_name'], [
            'expires' => '+' . $this->_config['restore_interval'],
            'httpOnly' => true
        ]);
        $remember_me_cookie = $this->Cookie->read($this->_config['cookie_name']);

        if (empty($remember_me_cookie['user_id']) || empty($remember_me_cookie['token'])) {
            $this->log('_restoreLogin malformed cookie:' . print_r($remember_me_cookie, true), 'debug');
            $this->Cookie->delete($this->_config['cookie_name']);
            return;
        }

        $saved_logins = TableRegistry::get('Users.UsersSavedLogins');

        // Prune outdated sessions for this user.
        $saved_logins->prune($remember_me_cookie['user_id'], '-' . $this->_config['restore_interval']);

        $saved_logins_query = $saved_logins->find('all')
            ->where([
                'user_id' => $remember_me_cookie['user_id'],
            ])
            ->order([
                'modified' => 'DESC',
            ]);

        $password_hasher = new DefaultPasswordHasher();

        foreach ($saved_logins_query as $saved_login) {
            if (!$password_hasher->check($remember_me_cookie['token'], $saved_login->token)) {
                continue;
            }

            // Now we can generate a new token and restore the login.
            $user = TableRegistry::get('Users')->get($saved_login->user_id)->toArray();
            $token = $this->_generateToken();
            $saved_login->user_agent = env('HTTP_USER_AGENT');
            $saved_login->token = $token;

            if ($saved_logins->save($saved_login)) {
                $this->Cookie->write($this->_config['cookie_name'], [
                    'user_id' => $saved_login->user_id,
                    'token' => $token,
                ]);
            }

            $user['login_restored'] = true;
            $this->Auth->setUser($user);
            return;
        }

        $this->log('_restoreLogin cookie with expired/wrong token: ' . print_r($remember_me_cookie, true), 'debug');
        $this->Cookie->delete($this->_config['cookie_name']);
    }

    /**
     * Saves the current login in a cookie and in the database with a secure token.
     *
     * @param integer $user_id
     */
    protected function _saveLogin($user_id)
    {
        $this->_clearSavedLogins();

        $token = $this->_generateToken();
        $saved_logins = TableRegistry::get('Users.UsersSavedLogins');
        $saved_login = $saved_logins->newEntity([
            'user_id' => $user_id,
            'user_agent' => env('HTTP_USER_AGENT'),
            'token' => $token,
        ]);

        if (!$saved_logins->save($saved_login)) {
            return;
        }

        $this->Cookie->configKey($this->_config['cookie_name'], [
            'expires' => '+' . $this->_config['restore_interval'],
            'httpOnly' => true
        ]);
        $this->Cookie->write($this->_config['cookie_name'], [
            'user_id' => $saved_login->user_id,
            'token' => $token,
        ]);
    }

    /**
     * Deletes "remember me" cookie and the relative database entities.
     */
    protected function _clearSavedLogins()
    {
        if (!$this->Cookie->check($this->_config['cookie_name'])) {
            return;
        }

        $this->Cookie->configKey($this->_config['cookie_name'], [
            'expires' => '+' . $this->_config['restore_interval'],
            'httpOnly' => true
        ]);
        $remember_me_cookie = $this->Cookie->read($this->_config['cookie_name']);

        if (empty($remember_me_cookie['user_id']) || empty($remember_me_cookie['token'])) {
            $this->Cookie->delete($this->_config['cookie_name']);
            return;
        }

        $saved_logins = TableRegistry::get('Users.UsersSavedLogins');

        // Prune outdated sessions for this user.
        $saved_logins->prune($remember_me_cookie['user_id'], '-' . $this->_config['restore_interval']);

        $saved_logins_query = $saved_logins->find('all')
            ->where([
                'user_id' => $remember_me_cookie['user_id'],
            ]);

        $password_hasher = new DefaultPasswordHasher();

        foreach ($saved_logins_query as $saved_login) {
            if (!$password_hasher->check($remember_me_cookie['token'], $saved_login->token)) {
                continue;
            }

            $saved_logins->delete($saved_login);
            break;
        }

        $this->Cookie->delete($this->_config['cookie_name']);
    }

    /**
     * Prevents brute force attack by sleeping after a failed login.
     *
     * @param string $identifier
     */
    public function preventThrottling($identifier)
    {
        $cache_key = 'login_wait_' . Inflector::slug($identifier, '_');
        $seconds_to_wait = ((int) Cache::read($cache_key, 'throttling')) * 2 ?: 1;

        Cache::write($cache_key, $seconds_to_wait, 'throttling');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            set_time_limit((int) ini_get('max_execution_time') + $seconds_to_wait);
        }
        sleep($seconds_to_wait);
    }

    /**
     * Clears the throttling cache for the given identifier
     *
     * @param string $identifier
     */
    public function clearThrottling($identifier)
    {
        $cache_key = 'login_wait_' . Inflector::slug($identifier, '_');
        Cache::delete($cache_key, 'throttling');
    }

}
