<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Blocks'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Parent Blocks'), ['controller' => 'Blocks', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Parent Block'), ['controller' => 'Blocks', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="blocks form large-9 medium-8 columns content">
    <?= $this->Form->create($block) ?>
    <fieldset>
        <legend><?= __('Add Block') ?></legend>
        <?php
            echo $this->Form->input('status');
            echo $this->Form->input('language');
            echo $this->Form->input('region');
            echo $this->Form->input('title');
            echo $this->Form->input('body');
            echo $this->Form->input('element');
            echo $this->Form->input('element_options');
            echo $this->Form->input('css_class');
            echo $this->Form->input('show_title');
            echo $this->Form->input('acl_token');
            echo $this->Form->input('parent_id', ['options' => $parentBlocks, 'empty' => true]);
            echo $this->Form->input('level');
            echo $this->Form->input('lft');
            echo $this->Form->input('rght');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
