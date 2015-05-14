<?= $this->Flash->render('auth') ?>
<?= $this->Form->create() ?>
	<fieldset>
		<legend><?= __('Please enter your new password') ?></legend>
		<p><?= $item->name ?></p>
		<?= $this->Form->input('password') ?>
		<?= $this->Form->input('save_user_login', [
			'type' => 'checkbox',
		]) ?>
	</fieldset>
<?= $this->Form->button(__('Save new password and log me in.')) ?>
<?= $this->Form->end() ?>
