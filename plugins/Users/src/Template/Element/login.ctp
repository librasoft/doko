<?= $this->Flash->render('auth') ?>
<?= $this->Form->create(null, [
    'url' => [
        'plugin' => 'Users',
        'controller' => 'Users',
        'action' => 'login',
    ],
    'class' => 'login-form',
]) ?>
    <?= $this->Form->input('email', [
        'type' => 'email',
        'label' => __d('Users', 'Email'),
        'autofocus' => !empty($autofocus),
        'tabindex' => 1,
        'data-mailcheck' => __d('Users', 'Do you mean {{suggestion}}?'),
    ]) ?>
    <?= $this->Form->input('password', [
        'type' => 'password',
        'label' => __d('Users', 'Password'),
        'value' => '',
        'tabindex' => 2,
		'between' => $this->Html->tag('p', $this->Html->link(__d('Users', 'Can’t remember'), [
			'plugin' => 'Users',
			'controller' => 'Users',
			'action' => 'forgot',
		], [
			'tabindex' => 5,
		]), [
			'class' => 'form-control-actions',
		]),
    ]) ?>
    <?= $this->Form->input('save_user_login', [
		'type' => 'checkbox',
		'label' => __d('Users', 'Remember me on this device'),
		'tabindex' => 3,
		'checked' => true,
    ]) ?>
    <div class="form-actions">
        <?= $this->Form->button(__d('Users', 'Enter'), [
			'type' => 'submit',
			'class' => 'btn btn-primary btn-submit',
			'tabindex' => 4,
        ]) ?>
    </div>
<?= $this->Form->end() ?>
