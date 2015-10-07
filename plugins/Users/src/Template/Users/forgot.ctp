<?php
$title = __d('Users', 'Recover Password');
$this->set('title', $title);

$canonical = [
    'action' => 'forgot',
];
$this->Layout->setCanonical($canonical);
$this->Html->addCrumb($title, $canonical);
?>
<ul class="nav nav-tabs">
    <li><a href="<?= $this->Url->build([
        'action' => 'login',
    ]); ?>"><?= __d('Users', 'Login'); ?></a></li>
    <li class="active"><a href="<?= $this->Url->build([
        'action' => 'forgot',
    ]); ?>"><?= $title; ?></a></li>
</ul>

<?= $this->Flash->render('auth') ?>
<?= $this->Form->create(null, [
    'class' => 'login-form',
]) ?>
    <p class="help-block"><?php echo __d('Users', 'Please enter your email: we will send you the instructions to choose a new password.'); ?></p>
    <?= $this->Form->input('email', [
        'type' => 'email',
        'label' => __d('Users', 'Email'),
        'autofocus' => true,
        'required' => true,
        'data-mailcheck' => __d('Users', 'Do you mean {{suggestion}}?'),
    ]) ?>
    <div class="form-actions">
        <?= $this->Form->button(__d('Users', 'Submit'), [
            'type' => 'submit',
            'class' => 'btn btn-primary btn-submit',
        ]) ?>
    </div>
<?= $this->Form->end() ?>
