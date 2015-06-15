<?php
$class = 'alert';
if (!empty($params['class'])) {
    $class .= ' alert-' . str_replace(' ', ' alert-', $params['class']);
}
?>
<div class="<?= h($class) ?>"><?= h($message) ?></div>
