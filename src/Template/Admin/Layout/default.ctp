<?php

use Cake\Core\Configure;

//Layout options
$asset_suffix = Configure::read('debug') ? '.full' : '.min';
?>
<!doctype html>
<?= $this->Layout->htmlStart() ?>
<head>
    <?php
    echo $this->Html->charset();
    echo $this->Layout->title();
    echo $this->Layout->viewport();
    echo $this->Layout->documentMeta();
    echo $this->Html->css([
        'app' . $asset_suffix . '.css',
    ]);
    echo $this->fetch('css');
    echo $this->Html->script([
        'modernizr' . $asset_suffix . '.js',
    ]);
    echo $this->Layout->initJsVars();
    echo $this->fetch('script');
    echo $this->Layout->oldIE();
    ?>
</head>
<?= $this->Layout->bodyStart() ?>

<div class="wrapper wrapper-middle">
    <div class="middle">
        <?= $this->fetch('middle-prepend') ?>

        <main id="main" class="main" role="main">
            <?= $this->Flash->render() ?>

            <?= $this->fetch('main-prepend') ?>
            <?= $this->fetch('content') ?>
            <?= $this->fetch('main-append') ?>
        </main>

        <?= $this->fetch('middle-append') ?>
    </div>
</div>

</body>
</html>
