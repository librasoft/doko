<?= $this->Flash->render('auth') ?>
<?= $this->Form->create() ?>
	<fieldset>
		<legend><?= __('Please enter your email') ?></legend>
		<?= $this->Form->input('email') ?>
	</fieldset>
<?= $this->Form->button(__('Send me instructions')); ?>
<?= $this->Form->end() ?>
