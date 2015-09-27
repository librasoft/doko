<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Menus Links'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Menus'), ['controller' => 'Menus', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Menu'), ['controller' => 'Menus', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Parent Menus Links'), ['controller' => 'MenusLinks', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Parent Menus Link'), ['controller' => 'MenusLinks', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="menusLinks form large-9 medium-8 columns content">
    <?= $this->Form->create($menusLink) ?>
    <fieldset>
        <legend><?= __('Add Menus Link') ?></legend>
        <?php
            echo $this->Form->input('status');
            echo $this->Form->input('menu_id', ['options' => $menus]);
            echo $this->Form->input('parent_id', ['options' => $parentMenusLinks, 'empty' => true]);
            echo $this->Form->input('title');
            echo $this->Form->input('url');
            echo $this->Form->input('css_class');
            echo $this->Form->input('rel');
            echo $this->Form->input('target_blank');
            echo $this->Form->input('icon');
            echo $this->Form->input('element');
            echo $this->Form->input('element_options');
            echo $this->Form->input('acl_token');
            echo $this->Form->input('level');
            echo $this->Form->input('lft');
            echo $this->Form->input('rght');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
