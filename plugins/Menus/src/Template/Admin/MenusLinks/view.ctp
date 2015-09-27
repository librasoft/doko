<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Menus Link'), ['action' => 'edit', $menusLink->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Menus Link'), ['action' => 'delete', $menusLink->id], ['confirm' => __('Are you sure you want to delete # {0}?', $menusLink->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Menus Links'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Menus Link'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Menus'), ['controller' => 'Menus', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Menu'), ['controller' => 'Menus', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Parent Menus Links'), ['controller' => 'MenusLinks', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Parent Menus Link'), ['controller' => 'MenusLinks', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="menusLinks view large-9 medium-8 columns content">
    <h3><?= h($menusLink->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Menu') ?></th>
            <td><?= $menusLink->has('menu') ? $this->Html->link($menusLink->menu->id, ['controller' => 'Menus', 'action' => 'view', $menusLink->menu->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Parent Menus Link') ?></th>
            <td><?= $menusLink->has('parent_menus_link') ? $this->Html->link($menusLink->parent_menus_link->title, ['controller' => 'MenusLinks', 'action' => 'view', $menusLink->parent_menus_link->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($menusLink->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Url') ?></th>
            <td><?= h($menusLink->url) ?></td>
        </tr>
        <tr>
            <th><?= __('Css Class') ?></th>
            <td><?= h($menusLink->css_class) ?></td>
        </tr>
        <tr>
            <th><?= __('Rel') ?></th>
            <td><?= h($menusLink->rel) ?></td>
        </tr>
        <tr>
            <th><?= __('Icon') ?></th>
            <td><?= h($menusLink->icon) ?></td>
        </tr>
        <tr>
            <th><?= __('Element') ?></th>
            <td><?= h($menusLink->element) ?></td>
        </tr>
        <tr>
            <th><?= __('Acl Token') ?></th>
            <td><?= h($menusLink->acl_token) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($menusLink->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($menusLink->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Level') ?></th>
            <td><?= $this->Number->format($menusLink->level) ?></td>
        </tr>
        <tr>
            <th><?= __('Lft') ?></th>
            <td><?= $this->Number->format($menusLink->lft) ?></td>
        </tr>
        <tr>
            <th><?= __('Rght') ?></th>
            <td><?= $this->Number->format($menusLink->rght) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($menusLink->modified) ?></tr>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($menusLink->created) ?></tr>
        </tr>
        <tr>
            <th><?= __('Target Blank') ?></th>
            <td><?= $menusLink->target_blank ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
    <div class="row">
        <h4><?= __('Element Options') ?></h4>
        <?= $this->Text->autoParagraph(h($menusLink->element_options)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Menus Links') ?></h4>
        <?php if (!empty($menusLink->child_menus_links)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Menu Id') ?></th>
                <th><?= __('Parent Id') ?></th>
                <th><?= __('Title') ?></th>
                <th><?= __('Url') ?></th>
                <th><?= __('Css Class') ?></th>
                <th><?= __('Rel') ?></th>
                <th><?= __('Target Blank') ?></th>
                <th><?= __('Icon') ?></th>
                <th><?= __('Element') ?></th>
                <th><?= __('Element Options') ?></th>
                <th><?= __('Acl Token') ?></th>
                <th><?= __('Level') ?></th>
                <th><?= __('Lft') ?></th>
                <th><?= __('Rght') ?></th>
                <th><?= __('Modified') ?></th>
                <th><?= __('Created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($menusLink->child_menus_links as $childMenusLinks): ?>
            <tr>
                <td><?= h($childMenusLinks->id) ?></td>
                <td><?= h($childMenusLinks->status) ?></td>
                <td><?= h($childMenusLinks->menu_id) ?></td>
                <td><?= h($childMenusLinks->parent_id) ?></td>
                <td><?= h($childMenusLinks->title) ?></td>
                <td><?= h($childMenusLinks->url) ?></td>
                <td><?= h($childMenusLinks->css_class) ?></td>
                <td><?= h($childMenusLinks->rel) ?></td>
                <td><?= h($childMenusLinks->target_blank) ?></td>
                <td><?= h($childMenusLinks->icon) ?></td>
                <td><?= h($childMenusLinks->element) ?></td>
                <td><?= h($childMenusLinks->element_options) ?></td>
                <td><?= h($childMenusLinks->acl_token) ?></td>
                <td><?= h($childMenusLinks->level) ?></td>
                <td><?= h($childMenusLinks->lft) ?></td>
                <td><?= h($childMenusLinks->rght) ?></td>
                <td><?= h($childMenusLinks->modified) ?></td>
                <td><?= h($childMenusLinks->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'MenusLinks', 'action' => 'view', $childMenusLinks->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'MenusLinks', 'action' => 'edit', $childMenusLinks->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'MenusLinks', 'action' => 'delete', $childMenusLinks->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childMenusLinks->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
