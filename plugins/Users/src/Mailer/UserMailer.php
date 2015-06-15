<?php

namespace Users\Mailer;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{

    public function confirmEmail($user, $security_token)
    {
        $this->transport = 'default';
        $this->from = Configure::read('Doko.Owner.email');
        $this->subject = __d('Users', '{0}, confirm your email address', $user->name);
        $this->to = $user->email;
        $this->layout = 'default';
        $this->template = 'Users.confirm_address';
        $this->set(compact('user', 'security_token'));
    }

    public function resetPassword($user)
    {
        $this->subject = 'Reset password';
        $this->to = $user->email;
        $this->set(['token' => $user->token]);
    }

}
