<div class="users index">
    <h2><?= __('Users'); ?></h2>
    <table cellpadding="0" cellspacing="0">
    <tr>
        <th><?= $this->Paginator->sort('name'); ?></th>
        <th><?= $this->Paginator->sort('role_id'); ?></th>
        <th><?= $this->Paginator->sort('status'); ?></th>
        <th><?= $this->Paginator->sort('email'); ?></th>
        <th><?= $this->Paginator->sort('photo_path'); ?></th>
        <th><?= $this->Paginator->sort('job'); ?></th>
        <th><?= $this->Paginator->sort('organization'); ?></th>
        <th><?= $this->Paginator->sort('modified'); ?></th>
        <th><?= $this->Paginator->sort('created'); ?></th>
        <th class="actions"><?= __('Actions'); ?></th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= h($user->name); ?>&nbsp;</td>
        <td>
            <?= $user->has('role_id') ? $this->Html->link($aclRoles[$user->role_id], ['controller' => 'AclRoles', 'action' => 'view', $user->role_id]) : ''; ?>
        </td>
        <td><?= h($user->status); ?>&nbsp;</td>
        <td><?= h($user->email); ?>&nbsp;</td>
        <td><?= h($user->photo_path); ?>&nbsp;</td>
        <td><?= h($user->job); ?>&nbsp;</td>
        <td><?= h($user->organization); ?>&nbsp;</td>
        <td><?= h($user->modified); ?>&nbsp;</td>
        <td><?= h($user->created); ?>&nbsp;</td>
        <td class="actions">
            <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]); ?>
            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]); ?>
            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]); ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
    <p><?= $this->Paginator->counter(); ?></p>
    <ul class="pagination">
    <?php
        echo $this->Paginator->prev('< ' . __('previous'));
        echo $this->Paginator->numbers();
        echo $this->Paginator->next(__('next') . ' >');
    ?>
    </ul>
</div>
<div class="actions">
    <h3><?= __('Actions'); ?></h3>
    <ul>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']); ?></li>
        <li><?= $this->Html->link(__('List AclRoles'), ['controller' => 'AclRoles', 'action' => 'index']); ?> </li>
        <li><?= $this->Html->link(__('New Acl Role'), ['controller' => 'AclRoles', 'action' => 'add']); ?> </li>
    </ul>
</div>
