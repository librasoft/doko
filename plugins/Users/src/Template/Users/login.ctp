<?php
$title = __d('Users', 'Login');
$this->set('title', $title);

$canonical = [
    'action' => 'login',
];
$this->Layout->setCanonical($canonical);
$this->Html->addCrumb($title, $canonical);
?>
<ul class="nav nav-tabs">
    <li class="active"><a href="<?= $this->Url->build([
        'action' => 'login',
    ]); ?>"><?= __d('Users', 'Login'); ?></a></li>
    <?php if ($this->ACL->can('Users.Register')): ?>
    <li><a href="<?= $this->Url->build([
        'action' => 'register',
    ]); ?>"><?= __d('Users', 'Register'); ?></a></li>
    <?php endif; ?>
</ul>

<?= $this->element('login', [
    'autofocus' => true,
]); ?>
