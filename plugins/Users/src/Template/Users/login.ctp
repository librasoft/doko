<?= $this->Flash->render('auth') ?>
<?= $this->Form->create() ?>
	<fieldset>
		<legend><?= __('Please enter your username and password') ?></legend>
		<?= $this->Form->input('email') ?>
		<?= $this->Form->input('password') ?>
		<?= $this->Form->input('save_user_login', [
			'type' => 'checkbox',
		]) ?>
	</fieldset>
<p><?= $this->Html->link(__d('Users', 'Registration'), [
	'action' => 'register',
]) ?></p>
<p><?= $this->Html->link(__d('Users', 'Forgot password?'), [
	'action' => 'forgot',
]) ?></p>
<?= $this->Form->button(__('Login')) ?>
<?= $this->Form->end() ?>
