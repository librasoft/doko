<?= __d('Users', 'Hi {0},', $user->name); ?>

<?= __d('Users', 'you can reset your password by following this link:'); ?>


<?= $this->Url->build([
    'prefix' => false,
    'plugin' => 'Users',
    'controller' => 'Users',
    'action' => 'reset',
    $user->id,
    $security_token,
]);
