<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Block'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="blocks index large-9 medium-8 columns content">
    <h3><?= __('Blocks') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('language') ?></th>
                <th><?= $this->Paginator->sort('region') ?></th>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th><?= $this->Paginator->sort('element') ?></th>
                <th><?= $this->Paginator->sort('css_class') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($blocks as $block): ?>
            <tr>
                <td><?= $this->Number->format($block->id) ?></td>
                <td><?= $this->Number->format($block->status) ?></td>
                <td><?= h($block->language) ?></td>
                <td><?= h($block->region) ?></td>
                <td><?= h($block->title) ?></td>
                <td><?= h($block->element) ?></td>
                <td><?= h($block->css_class) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $block->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $block->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $block->id], ['confirm' => __('Are you sure you want to delete # {0}?', $block->id)]) ?>
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
