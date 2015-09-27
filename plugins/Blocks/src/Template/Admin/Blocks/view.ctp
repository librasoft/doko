<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Block'), ['action' => 'edit', $block->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Block'), ['action' => 'delete', $block->id], ['confirm' => __('Are you sure you want to delete # {0}?', $block->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Blocks'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Block'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Parent Blocks'), ['controller' => 'Blocks', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Parent Block'), ['controller' => 'Blocks', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="blocks view large-9 medium-8 columns content">
    <h3><?= h($block->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Language') ?></th>
            <td><?= h($block->language) ?></td>
        </tr>
        <tr>
            <th><?= __('Region') ?></th>
            <td><?= h($block->region) ?></td>
        </tr>
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($block->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Element') ?></th>
            <td><?= h($block->element) ?></td>
        </tr>
        <tr>
            <th><?= __('Css Class') ?></th>
            <td><?= h($block->css_class) ?></td>
        </tr>
        <tr>
            <th><?= __('Acl Token') ?></th>
            <td><?= h($block->acl_token) ?></td>
        </tr>
        <tr>
            <th><?= __('Parent Block') ?></th>
            <td><?= $block->has('parent_block') ? $this->Html->link($block->parent_block->title, ['controller' => 'Blocks', 'action' => 'view', $block->parent_block->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($block->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($block->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Level') ?></th>
            <td><?= $this->Number->format($block->level) ?></td>
        </tr>
        <tr>
            <th><?= __('Lft') ?></th>
            <td><?= $this->Number->format($block->lft) ?></td>
        </tr>
        <tr>
            <th><?= __('Rght') ?></th>
            <td><?= $this->Number->format($block->rght) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($block->modified) ?></tr>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($block->created) ?></tr>
        </tr>
        <tr>
            <th><?= __('Show Title') ?></th>
            <td><?= $block->show_title ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
    <div class="row">
        <h4><?= __('Body') ?></h4>
        <?= $this->Text->autoParagraph(h($block->body)); ?>
    </div>
    <div class="row">
        <h4><?= __('Element Options') ?></h4>
        <?= $this->Text->autoParagraph(h($block->element_options)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Blocks') ?></h4>
        <?php if (!empty($block->child_blocks)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Language') ?></th>
                <th><?= __('Region') ?></th>
                <th><?= __('Title') ?></th>
                <th><?= __('Body') ?></th>
                <th><?= __('Element') ?></th>
                <th><?= __('Element Options') ?></th>
                <th><?= __('Css Class') ?></th>
                <th><?= __('Show Title') ?></th>
                <th><?= __('Acl Token') ?></th>
                <th><?= __('Parent Id') ?></th>
                <th><?= __('Level') ?></th>
                <th><?= __('Lft') ?></th>
                <th><?= __('Rght') ?></th>
                <th><?= __('Modified') ?></th>
                <th><?= __('Created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($block->child_blocks as $childBlocks): ?>
            <tr>
                <td><?= h($childBlocks->id) ?></td>
                <td><?= h($childBlocks->status) ?></td>
                <td><?= h($childBlocks->language) ?></td>
                <td><?= h($childBlocks->region) ?></td>
                <td><?= h($childBlocks->title) ?></td>
                <td><?= h($childBlocks->body) ?></td>
                <td><?= h($childBlocks->element) ?></td>
                <td><?= h($childBlocks->element_options) ?></td>
                <td><?= h($childBlocks->css_class) ?></td>
                <td><?= h($childBlocks->show_title) ?></td>
                <td><?= h($childBlocks->acl_token) ?></td>
                <td><?= h($childBlocks->parent_id) ?></td>
                <td><?= h($childBlocks->level) ?></td>
                <td><?= h($childBlocks->lft) ?></td>
                <td><?= h($childBlocks->rght) ?></td>
                <td><?= h($childBlocks->modified) ?></td>
                <td><?= h($childBlocks->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Blocks', 'action' => 'view', $childBlocks->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Blocks', 'action' => 'edit', $childBlocks->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Blocks', 'action' => 'delete', $childBlocks->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childBlocks->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
