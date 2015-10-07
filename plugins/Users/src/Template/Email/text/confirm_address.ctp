<?= __d('Users', 'Hi {0},', $user->name); ?>

<?= __d('Users', 'please confirm your email address by following this link:'); ?>


<?= $this->Url->build([
    'prefix' => false,
    'plugin' => 'Users',
    'controller' => 'Users',
    'action' => 'confirm',
    $user->id,
    $security_token,
    1, // to save login
]);
