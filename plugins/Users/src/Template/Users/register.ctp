<?= $this->Flash->render('auth') ?>
<?= $this->Form->create() ?>
	<fieldset>
		<legend><?= __('Please enter your informations') ?></legend>
		<?= $this->Form->input('name') ?>
		<?= $this->Form->input('email') ?>
		<?= $this->Form->input('password') ?>
		<?= $this->Form->input('timezone', [
			'type' => 'hidden',
		]) ?>
		<?= $this->Form->input('language_frontend', [
			'type' => 'hidden',
		]) ?>
	</fieldset>
<p><?= $this->Html->link(__d('Users', 'Already registered?'), [
	'action' => 'login',
]) ?></p>
<?= $this->Form->button(__('Register')) ?>
<?= $this->Form->end() ?>
