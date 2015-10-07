<?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Please enter your informations') ?></legend>
        <?= $this->Form->input('name') ?>
        <?= $this->Form->input('email') ?>
        <?= $this->Form->input('password', [
            'label' => __d('Users', 'Your new password'),
            'value' => '',
        ]) ?>
        <?= $this->Form->input('organization') ?>
        <?= $this->Form->input('job') ?>
        <?= $this->Form->input('bio') ?>
    </fieldset>
<p><?= $this->Html->link(__d('Users', 'Already registered?'), [
    'action' => 'login',
]) ?></p>
<?= $this->Form->button(__('Register')) ?>
<?= $this->Form->end() ?>
<?= $this->cell('Users.SavedLogins', [
    'user_id' => $authUser['id'],
]) ?>