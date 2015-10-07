<?php

use Cake\Core\Configure;

$title = __d('Users', 'Reset Password');
$this->set('title', $title);

$canonical = null;
$this->Layout->setCanonical($canonical);
$this->Html->addCrumb($title, $canonical);
?>
<ul class="nav nav-tabs">
    <li><a href="<?= $this->Url->build([
        'action' => 'login',
    ]); ?>"><?= __d('Users', 'Login'); ?></a></li>
    <li class="active"><a href="<?= $this->Url->build($canonical); ?>"><?= $title; ?></a></li>
</ul>

<?= $this->Flash->render('auth') ?>
<?= $this->Form->create(null, [
    'class' => 'login-form',
]) ?>
    <p class="help-block"><?php echo __d('Users', '{0}, please enter your new password.', '<b>' . $item->name . '</b>'); ?></p>
    <?= $this->Form->input('password', [
        'type' => 'password',
        'autofocus' => true,
        'required' => true,
        'label' => __d('Users', 'New Password'),
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
    <?= $this->Form->input('save_user_login', [
        'type' => 'checkbox',
        'label' => __d('Users', 'Remember me on this device'),
        'checked' => true,
    ]) ?>
    <div class="form-actions">
        <?= $this->Form->button(__d('Users', 'Save and Enter'), [
            'type' => 'submit',
            'class' => 'btn btn-primary btn-submit',
        ]) ?>
    </div>
<?= $this->Form->end() ?>
