<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Menu'), ['action' => 'edit', $menu->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Menu'), ['action' => 'delete', $menu->id], ['confirm' => __('Are you sure you want to delete # {0}?', $menu->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Menus'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Menu'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Links'), ['controller' => 'MenusLinks', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Link'), ['controller' => 'MenusLinks', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="menus view large-9 medium-8 columns content">
    <h3><?= h($menu->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Language') ?></th>
            <td><?= h($menu->language) ?></td>
        </tr>
        <tr>
            <th><?= __('Alias') ?></th>
            <td><?= h($menu->alias) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($menu->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($menu->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($menu->modified) ?></tr>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($menu->created) ?></tr>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Menus Links') ?></h4>
        <?php if (!empty($menu->links)): ?>
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
            <?php foreach ($menu->links as $links): ?>
            <tr>
                <td><?= h($links->id) ?></td>
                <td><?= h($links->status) ?></td>
                <td><?= h($links->menu_id) ?></td>
                <td><?= h($links->parent_id) ?></td>
                <td><?= h($links->title) ?></td>
                <td><?= h($links->url) ?></td>
                <td><?= h($links->css_class) ?></td>
                <td><?= h($links->rel) ?></td>
                <td><?= h($links->target_blank) ?></td>
                <td><?= h($links->icon) ?></td>
                <td><?= h($links->element) ?></td>
                <td><?= h($links->element_options) ?></td>
                <td><?= h($links->acl_token) ?></td>
                <td><?= h($links->level) ?></td>
                <td><?= h($links->lft) ?></td>
                <td><?= h($links->rght) ?></td>
                <td><?= h($links->modified) ?></td>
                <td><?= h($links->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'MenusLinks', 'action' => 'view', $links->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'MenusLinks', 'action' => 'edit', $links->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'MenusLinks', 'action' => 'delete', $links->id], ['confirm' => __('Are you sure you want to delete # {0}?', $links->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
