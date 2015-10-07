<div class="users form">
<?= $this->Form->create($user, ['type' => 'file']); ?>
    <fieldset>
        <legend><?= __('Add User'); ?></legend>
    <?php
        echo $this->Form->input('role_id', ['options' => $aclRoles]);
        echo $this->Form->input('status');
        echo $this->Form->input('email');
        echo $this->Form->input('password');
        echo $this->Form->input('photo_path', ['type' => 'file']);
        echo $this->Form->input('name');
        echo $this->Form->input('job');
        echo $this->Form->input('organization');
        echo $this->Form->input('website');
        echo $this->Form->input('bio');
    ?>
    </fieldset>
<?= $this->Form->button(__('Submit')); ?>
<?= $this->Form->end(); ?>
</div>
<div class="actions">
    <h3><?= __('Actions'); ?></h3>
    <ul>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']); ?></li>
        <li><?= $this->Html->link(__('List AclRoles'), ['controller' => 'AclRoles', 'action' => 'index']); ?> </li>
        <li><?= $this->Html->link(__('New Acl Role'), ['controller' => 'AclRoles', 'action' => 'add']); ?> </li>
    </ul>
</div>
