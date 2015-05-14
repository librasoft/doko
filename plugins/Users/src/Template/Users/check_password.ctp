<?= $this->Flash->render('auth') ?>
<?= $this->Form->create() ?>
	<fieldset>
		<legend><?= __('Please enter your actual password to perform this action') ?></legend>
		<?= $this->Form->input('check_user_password', [
			'type' => 'password',
		]) ?>
	</fieldset>
<?= $this->Form->button(__('Send')) ?>
<?= $this->Form->end() ?>
