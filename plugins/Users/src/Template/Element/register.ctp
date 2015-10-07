<?php
use Cake\Core\Configure;
?>
<?= $this->Form->create($user, [
    'honeypot' => true,
    'url' => [
        'plugin' => 'Users',
        'controller' => 'Users',
        'action' => 'register',
    ],
    'class' => 'register-form',
]) ?>
    <?= $this->Flash->render() ?>
    <?= $this->Flash->render('auth') ?>
    <p class="help-block"><?= __d('Users', 'The symbol <i>*</i> indicates that the field is required.') ?></p>
    <?= $this->Form->input('name', [
        'type' => 'text',
        'label' => __d('Users', 'Name'),
        'autofocus' => !empty($autofocus),
    ]) ?>
    <?= $this->Form->input('email', [
        'type' => 'email',
        'label' => __d('Users', 'Email'),
        'data-mailcheck' => __d('Users', 'Do you mean {{suggestion}}?'),
    ]) ?>
    <?= $this->Form->input('password', [
        'type' => 'password',
        'label' => __d('Users', 'Password'),
        'value' => '',
        'class' => 'strength-password',
        'help' => __d('Users', 'Minimum {0} characters', Configure::read('Doko.Profile.password-min-length')),
        'pattern' => sprintf('.{%s,}', Configure::read('Doko.Profile.password-min-length')),
        'title' => __d('Users', 'Minimum {0} characters', Configure::read('Doko.Profile.password-min-length')),
        'data-strength-factor' => Configure::read('Doko.Profile.password-strength-factor'),
        'data-min-length' => Configure::read('Doko.Profile.password-min-length'),
        'data-weak-label' => __d('Users', 'Weak'),
        'data-normal-label' => __d('Users', 'Medium'),
        'data-strong-label' => __d('Users', 'Strong'),
    ]) ?>
    <?= $this->Form->input('timezone', [
        'type' => 'hidden',
        'mutable' => true,
    ]) ?>
    <div class="form-actions">
        <?= $this->Form->button(__d('Users', 'Submit'), [
            'type' => 'submit',
            'class' => 'btn btn-primary btn-submit',
        ]) ?>
    </div>
<?= $this->Form->end() ?>
