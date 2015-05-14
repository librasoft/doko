<div class="users form">
<?= $this->Form->create($user, ['type' => 'file']); ?>
	<fieldset>
		<legend><?= __('Edit User'); ?></legend>
	<?php
		echo $this->Form->input('role_id', ['options' => $aclRoles]);
		echo $this->Form->input('status');
		echo $this->Form->input('email');
		echo $this->Form->input('password', ['value' => '']);
		echo $this->Form->input('photo_path', ['type' => 'file']);
		if (!empty($user->photo_path) && is_string($user->photo_path)) {
			echo '<img src="' . $this->Url->webroot($user->photo_path) . '" width="100" height="100" alt="">';
		}
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
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # %s?', $user->id)]); ?></li>
		<li><?= $this->Html->link(__('List Users'), ['action' => 'index']); ?></li>
		<li><?= $this->Html->link(__('List AclRoles'), ['controller' => 'AclRoles', 'action' => 'index']); ?> </li>
		<li><?= $this->Html->link(__('New Acl Role'), ['controller' => 'AclRoles', 'action' => 'add']); ?> </li>
	</ul>
</div>
