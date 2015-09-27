<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Menus Link'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Menus'), ['controller' => 'Menus', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Menu'), ['controller' => 'Menus', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="menusLinks index large-9 medium-8 columns content">
    <h3><?= __('Menus Links') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('menu_id') ?></th>
                <th><?= $this->Paginator->sort('parent_id') ?></th>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th><?= $this->Paginator->sort('url') ?></th>
                <th><?= $this->Paginator->sort('css_class') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menusLinks as $menusLink): ?>
            <tr>
                <td><?= $this->Number->format($menusLink->id) ?></td>
                <td><?= $this->Number->format($menusLink->status) ?></td>
                <td><?= $menusLink->has('menu') ? $this->Html->link($menusLink->menu->id, ['controller' => 'Menus', 'action' => 'view', $menusLink->menu->id]) : '' ?></td>
                <td><?= $menusLink->has('parent_menus_link') ? $this->Html->link($menusLink->parent_menus_link->title, ['controller' => 'MenusLinks', 'action' => 'view', $menusLink->parent_menus_link->id]) : '' ?></td>
                <td><?= h($menusLink->title) ?></td>
                <td><?= h($menusLink->url) ?></td>
                <td><?= h($menusLink->css_class) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $menusLink->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $menusLink->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $menusLink->id], ['confirm' => __('Are you sure you want to delete # {0}?', $menusLink->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
