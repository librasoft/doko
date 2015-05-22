<?php

namespace Users\Controller\Component;

use Users\Auth\ACL;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\Event\EventManagerTrait;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Request;
use Cake\ORM\TableRegistry;

class ACLComponent extends Component
{

    use EventManagerTrait;

    /**
     * Other components utilized by AuthComponent
     *
     * @var array
     */
	public $components = ['Auth', 'Security', 'Users.Login'];

    /**
     * Instance of the Controller object
     *
     * @return void
     */
    public $controller;

    /**
     * Request object
     *
     * @var Request
     */
    public $request;

    /**
     * Instance of the Session object
     *
     * @return void
     */
    public $session;

    /**
     * ACL object
     *
     * @var ACL
     */
    protected $_acl;

    /**
     * Events supported by this component.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Controller.startup' => 'startup',
        ];
    }

    /**
     * Initializes this Component for use in the controller
     *
     * @param array $config The configuration settings provided to this component.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->controller = $this->_registry->getController();
        $this->eventManager($this->controller->eventManager());
        $this->session = $this->request->session();
    }

    public function startup(Event $event)
    {
        if ($this->request->is('post') && !empty($this->request->data['check_user_password']) && $this->Auth->user('login_restored')) {
            $entered_password = $this->request->data['check_user_password'];
            unset($this->request->data['check_user_password']);
            $this->_checkUserPassword($entered_password);
        }
    }

    /**
     * Checks if the current user can do a certain operation.
     *
     * If the given operation needs a password to be performed,
     * checks if the user session has been restored:
     * if so, asks the user his password without loose the submitted data.
     *
     * @param type $operation
     * @throws ForbiddenException
     */
    public function checkPermission($operation)
    {
        if (!$this->acl()->can($operation) || (!$this->Auth->user('id') && $this->acl()->requiresAuth($operation))) {
            if (!$this->Auth->user('id')) {
                $this->Auth->flash(__d('Users', 'You must first login to access this page.'));
                $this->Auth->redirectUrl($this->request->here(false));

                $this->controller->redirect($this->Auth->config('loginAction'));
                $this->controller->response->sendHeaders();
                $this->controller->response->stop();
            }

            throw new ForbiddenException(__d('Users', 'You do not have permission to access this page.'));
        }

        if ($this->acl()->requiresAuth($operation, $this->Auth->user('login_restored'))) {
            // We needs the user password!
            if (empty($this->request->data['check_user_password'])) {
                if (!empty($this->request->data)) {
                    $this->session->write('BeforeAuth.requestData', $this->request->data);
                }

                $response = $this->controller->render('Users.Users/check_password');
                $response->send();
                $response->stop();
            }
        }
    }

    /**
     * Checks if the given password match the logged user's password.
     * If not, asks him.
     *
     * @param string $entered_password
     */
    protected function _checkUserPassword($entered_password)
    {
        $users = TableRegistry::get('Users.Users');

        if (!(new DefaultPasswordHasher)->check($entered_password, $users->get($this->Auth->user('id'))->password)) {
            if (!$this->session->check('BeforeAuth.requestData')) {
                $this->session->write('BeforeAuth.requestData', $this->request->data);
            }

            $this->Auth->flash(__d('Users', 'Wrong password.'));
            $this->Login->preventThrottling($this->Auth->user('email'));

            $response = $this->controller->render('Users.Users/check_password');
            $response->send();
            $response->stop();
        }

        $this->Login->clearThrottling($this->Auth->user('email'));
        $this->session->delete($this->Auth->sessionKey . '.login_restored');

        if ($this->session->check('BeforeAuth.requestData')) {
            $this->Security->config(['validatePost' => false]); //post has already been validated
            $this->request->data = $this->session->read('BeforeAuth.requestData');
            $this->session->delete('BeforeAuth.requestData');
        }
    }

    /**
     * Gets an instance of ACL object
     *
     * @return App\Auth\ACL
     */
    public function acl()
    {
        if ($this->_acl) {
            return $this->_acl;
        }

        return $this->_acl = new ACL($this->Auth->user('role'));
    }

}
