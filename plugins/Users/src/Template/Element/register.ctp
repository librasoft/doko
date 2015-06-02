<?php
use Cake\Core\Configure;
?>
<?= $this->Flash->render('auth') ?>
<?= $this->Form->create($user, [
    'url' => [
        'plugin' => 'Users',
        'controller' => 'Users',
        'action' => 'login',
    ],
    'class' => 'login-form',
]) ?>
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
		'help' => __d('Users', 'Minimum {0} characters', Configure::read('Doko.Profile.password_min_length')),
		'pattern' => sprintf('.{%s,}', Configure::read('Doko.Profile.password_min_length')),
		'title' => __d('Users', 'Minimum {0} characters', Configure::read('Doko.Profile.password_min_length')),
		'data-strength-factor' => Configure::read('Doko.Profile.password_strength_factor'),
		'data-min-length' => Configure::read('Doko.Profile.password_min_length'),
		'data-weak-label' => __d('Users', 'Weak'),
		'data-normal-label' => __d('Users', 'Medium'),
		'data-strong-label' => __d('Users', 'Strong'),
    ]) ?>
    <?= $this->Form->input('timezone', [
		'type' => 'hidden',
		'secure' => false, //Updated via javascript
    ]) ?>
    <div class="form-actions">
        <?= $this->Form->button(__d('Users', 'Submit'), [
			'type' => 'submit',
			'class' => 'btn btn-primary btn-submit',
        ]) ?>
    </div>
<?= $this->Form->end() ?>
