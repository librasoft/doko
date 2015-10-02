<?php

namespace Users\Mailer;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{

    public function confirmEmail($user, $security_token)
    {
        $this
            ->transport('default')
            ->from(Configure::read('Doko.Owner.email'))
            ->subject(__d('Users', '{0}, confirm your email address', $user->name))
            ->to($user->email)
            ->layout('default')
            ->template('Users.confirm_address')
            ->set(compact('user', 'security_token'));
    }

    public function resetPassword($user, $security_token)
    {
        $this
            ->transport('default')
            ->from(Configure::read('Doko.Owner.email'))
            ->subject(__d('Users', '{0}, reset your password', $user->name))
            ->to($user->email)
            ->layout('default')
            ->template('Users.reset_password')
            ->set(compact('user', 'security_token'));
    }

}
