<?php
$defaults = [
    'title-tag' => 'h2',
];
$options = isset($options) ? $options + $defaults : $defaults;
?>
<div class="block <?= $block->css_class ?>">
    <?php
    if ($block->show_title) {
        echo $this->Html->tag($options['title-tag'], $block->title, [
            'class' => 'block-title',
        ]);
    }
    ?>
    <div class="block-body"><?php
    echo $block->body;

    if ($block->element) {
        echo $this->element($block->element, [
            'options' => $block->element_options ? json_decode($block->element_options, true) : [],
        ]);
    }
    ?></div>
</div>
