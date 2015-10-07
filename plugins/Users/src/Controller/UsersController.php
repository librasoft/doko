<?php

namespace Users\Controller;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\Mailer\MailerAwareTrait;
use Cake\Network\Exception\BadRequestException;
use Crud\Controller\ControllerTrait;
use Users\Controller\AppController;
use Users\Model\Entity\User;
use Users\Model\Table\UsersTable;

/**
 * Users Controller
 *
 * @property UsersTable $Users
 */
class UsersController extends AppController
{

    use ControllerTrait;
    use MailerAwareTrait;

    public function initialize()
    {
        $this->loadTheme(Configure::read('Doko.Profile.theme'));

        parent::initialize();

        $this->loadComponent('Crud.Crud', [
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
            ],
        ]);
    }

    public function implementedEvents()
    {
        return parent::implementedEvents() + [
            'Crud.beforeRender' => '_crud_beforeRender',
            'Crud.beforeSave' => '_crud_beforeSave',
            'Crud.afterSave' => '_crud_afterSave',
        ];
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Crud->mapAction('register', 'Crud.Add');
        $this->Crud->mapAction('profile', 'Crud.Edit');
    }

    public function login()
    {
        $is_ajax = $this->request->is('ajax');
        if ($is_ajax && !$this->request->is('post')) {
            $this->set('response', __d('Users', 'Invalid request'));
            $this->set('_serialize', 'response');
            return;
        }
        if (!$is_ajax && $this->Auth->user()) {
            return $this->redirect($this->Auth->config('loginRedirect'));
        }
        if ($this->request->is('post')) {
            $event_before = $this->dispatchEvent('Controller.userLoginBefore');

            if ($event_before->isStopped()) {
                $this->Flash->error(__d('Users', 'Invalid email or password, try again'));
                return;
            }

            $user = $this->Auth->identify();

            if ($user) {
                if ($user['row_status'] === 0) {
                    $this->dispatchEvent('Controller.userLoginFailure');
                    $this->Flash->error(__d('Users', 'Validate your email address before you can login. Check your inbox.'));

                    $entity = new User($user);
                    $this->security_token = $entity->generateToken();
                    $this->Users->save($entity);
                    $email = new Email('default');
                    $email
                        ->to($entity->email)
                        ->subject(__d('Users', 'Confirm email address'))
                        ->template('Users.register')
                        ->viewVars([
                            'item' => $entity,
                            'token' => $this->security_token,
                        ])
                        ->helpers($this->helpers)
                        ->send();
                    return;
                }

                $event_success = $this->dispatchEvent('Controller.userLoginSuccess', ['user' => $user]);

                if (!empty($event_success->result['user'])) {
                    $user = $event_success->result['user'];
                }

                $this->Auth->setUser($user);

                if ($this->Auth->authenticationProvider()->needsPasswordRehash()) {
                    $user = $this->Users->get($this->Auth->user('id'));
                    $user->password = $this->request->data('password');
                    $this->Users->save($user);
                }

                if ($this->request->is('ajax')) {
                    $this->set('response', $this->Auth->user());
                    $this->set('_serialize', 'response');
                    return;
                }
                return $this->redirect($this->Auth->redirectUrl());
            }

            $this->dispatchEvent('Controller.userLoginFailure');
            if ($this->request->is('ajax')) {
                $this->set('response', __d('Users', 'Invalid email or password, try again'));
                $this->set('_serialize', 'response');
                return;
            }
            $this->Flash->error(__d('Users', 'Invalid email or password, try again'));
        }
    }

    public function logout()
    {
        $this->dispatchEvent('Controller.userLogoutBefore');
        $logout_redirect = $this->Auth->logout();
        $this->dispatchEvent('Controller.userLogoutAfter');

        return $this->redirect($this->referer($logout_redirect));
    }

    public function forgot()
    {
        if ($this->Auth->user()) {
            return $this->redirect($this->referer($this->Auth->config('loginRedirect')));
        }
        if ($this->request->is('post')) {
            list(, $modelClass) = pluginSplit($this->modelClass);

            $item = $this->{$modelClass}->find()
                ->where([
                    'email' => $this->request->data['email'],
                ])
                ->first();

            if (!$item) {
                $this->Flash->error(__d('Users', 'Email not found'));
                return;
            }

            $token = $item->generateToken();

            if ($this->{$modelClass}->save($item)) {
                $this->getMailer('Users.User')->send('resetPassword', [
                    $item,
                    $token,
                ]);

                $this->Flash->success(__d('Users', 'Check your email and follow instructions'));
                return $this->redirect($this->Auth->config('loginAction'));
            }

            $this->Flash->error(__d('Users', 'An error occurred.'));
        }
    }

    public function reset($id, $token)
    {
        if ($this->Auth->user()) {
            return $this->redirect($this->referer($this->Auth->config('loginRedirect')));
        }

        list(, $modelClass) = pluginSplit($this->modelClass);

        $item = $this->{$modelClass}->get($id);

        if (!$item->checkToken($token)) {
            throw new BadRequestException();
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $item->password = $this->request->data['password'];

            if ($this->{$modelClass}->save($item)) {
                $this->Flash->success(__d('Users', 'Your new password has been saved'));

                $item->security_token = '';
                $this->{$modelClass}->save($item);

                $this->request->data['email'] = $this->email;

                $event_before = $this->dispatchEvent('Controller.userLoginBefore');

                if ($event_before->isStopped()) {
                    $this->Flash->error(__d('Users', 'Invalid email or password, try again'));
                    return;
                }

                $user = $item->toArray();

                $event_success = $this->dispatchEvent('Controller.userLoginSuccess', ['user' => $user]);

                if (!empty($event_success->result['user'])) {
                    $user = $event_success->result['user'];
                }

                $this->Auth->setUser($user);

                return $this->redirect($this->Auth->redirectUrl());
            }
        }

        $this->set(compact('item'));
    }

    public function register()
    {
        if ($this->Auth->user()) {
            return $this->redirect($this->referer($this->Auth->config('loginRedirect')));
        }

        return $this->Crud->execute();
    }

    public function confirm($id, $token, $save_login = 0)
    {
        if ($this->Auth->user()) {
            return $this->redirect($this->referer($this->Auth->config('loginRedirect')));
        }

        list(, $modelClass) = pluginSplit($this->modelClass);

        $item = $this->{$modelClass}->get($id);

        if (!$item->checkToken($token)) {
            throw new BadRequestException();
        }

        $item->status = 1;
        $item->security_token = '';

        if (!$this->{$modelClass}->save($item)) {
            $this->Flash->error(__d('Users', 'An error occurred.'));
            return $this->redirect($this->Auth->config('loginAction'));
        }

        $this->request->data['email'] = $this->email;
        $this->request->data['save_user_login'] = $save_login;

        $event_before = $this->dispatchEvent('Controller.userLoginBefore');

        if ($event_before->isStopped()) {
            return $this->redirect($this->Auth->config('loginAction'));
        }

        $user = $item->toArray();

        $event_success = $this->dispatchEvent('Controller.userLoginSuccess', ['user' => $user]);

        if (!empty($event_success->result['user'])) {
            $user = $event_success->result['user'];
        }

        $this->Auth->setUser($user);

        return $this->redirect($this->Auth->redirectUrl());
    }

    public function profile()
    {
        if (!$this->Auth->user()) {
            return $this->redirect($this->Auth->config('loginAction'));
        }

        return $this->Crud->execute(null, [$this->Auth->user('id')]);
    }

    public function clear_logins()
    {
        if (!$this->Auth->user()) {
            return $this->redirect($this->Auth->config('loginAction'));
        }
        if (!$this->request->is('post')) {
            throw new BadRequestException();
        }

        $this->loadComponent('Cookie');
        $this->Cookie->configKey($this->Login->config('cookie_name'), [
            'expires' => '+' . $this->Login->config('restore_interval'),
            'httpOnly' => true
        ]);
        $remember_me_cookie = $this->Cookie->read($this->Login->config('cookie_name'));

        $this->SavedLogins = $this->loadModel('Users.UsersSavedLogins');

        if (empty($remember_me_cookie['token'])) {
            $this->Cookie->delete($this->Login->config('cookie_name'));
            $this->SavedLogins->prune($this->Auth->user('id'));

            return $this->redirect($this->referer($this->Auth->config('loginRedirect')));
        }

        $logins = $this->SavedLogins->find()
            ->where([
            'user_id' => $this->Auth->user('id'),
        ]);

        if (!empty($logins)) {
            $password_hasher = new DefaultPasswordHasher();

            foreach ($logins as $login) {
                if ($password_hasher->check($remember_me_cookie['token'], $login->token)) {
                    continue;
                }

                $this->SavedLogins->delete($login);
            }
        }

        $this->Flash->success(__d('Users', 'Other logins are deleted.'));
        return $this->redirect($this->referer($this->Auth->config('loginRedirect')));
    }

    /*
     * CRUD events for register and profile actions
     */

    public function _crud_beforeRender(Event $event)
    {
        if ($event->subject()->entity->isNew()) {
            if (empty($this->request->data['timezone'])) {
                $this->request->data['timezone'] = date_default_timezone_get();
            }
            if (empty($this->request->data['language'])) {
                $this->request->data['language'] = ini_get('intl.default_locale');
            }
        }
    }

    public function _crud_beforeSave(Event $event)
    {
        if (
            !$event->subject()->entity->isNew()
            && ($event->subject()->entity->dirty('email') || $this->request->data('password'))
        ) {
            $this->ACL->checkPermission('Users.Profile.ChangeCredentials');
        }
        if ($event->subject()->entity->isNew() || $event->subject()->entity->dirty('email')) {
            $event->subject()->entity->status = 0;
            $this->security_token = $event->subject()->entity->generateToken();
        }
    }

    public function _crud_afterSave(Event $event)
    {
        if ($event->subject()->success && $this->security_token) {
            if ($event->subject()->entity->language !== ini_get('intl.default_locale')) {
                I18n::locale($event->subject()->entity->language);
            }

            $this->getMailer('Users.User')->send('confirmEmail', [
                $event->subject()->entity,
                $this->security_token,
            ]);
        }

        if ($event->subject()->success && !$event->subject()->created) {
            $user = $event->subject()->entity->toArray();

            if ($this->security_token) {
                $user['login_restored'] = true;
            }

            $this->Auth->setUser($user);
        }

        if ($event->subject()->created) {
            $this->request->data['redirect_url'] = $this->Auth->config('loginAction');
        } else {
            $this->request->data['redirect_url'] = ['action' => 'profile'];
        }
    }

}
