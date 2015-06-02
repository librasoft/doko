<?php
use Cake\Core\Configure;
?>
<!doctype html>
<?= $this->Layout->htmlStart(); ?>
<head>
    <?php
    echo $this->Html->charset();
    echo $this->Layout->title();
    echo $this->Layout->viewport();
    echo $this->Layout->documentMeta();
    echo $this->Html->css([
        'app.css',
    ]);
    echo $this->fetch('css');
    echo $this->Html->script([
        'modernizr.min.js',
    ]);
    echo $this->Layout->initJsVars();
    echo $this->fetch('script');
    echo $this->Layout->oldIE();
    ?>
</head>
<?= $this->Layout->bodyStart(); ?>

<!--[if lt IE 9]>
    <div class="browsehappy">
        <p><?= __d('Doko', '<strong>Heads Up:</strong> Your browser is outdated and <strong>not supported</strong>.<br><a class="btn btn-sm btn-primary" href="http://browsehappy.com/">Upgrade it free</a>'); ?></p>
    </div>
<![endif]-->

<header class="wrapper wrapper-header">
    <div class="header">
        <h1 class="header-title"><?= Configure::read('Doko.Frontend.title'); ?></h1>
        <a class="back-home" href="<?= $this->Url->build(Configure::read('Doko.Frontend.home-url')); ?>"><?= __d('Doko', 'Back to Home'); ?></a>
        <?= $this->element('i18n-languages-navigation'); ?>
    </div>
</header>

<nav class="wrapper wrapper-breadcrumbs">
    <div class="breadcrumbs">
        <h2 class="breadcrumbs-title sr-only"><?= __d('Doko', 'Navigation path'); ?></h2>
        <p class="breadcrumbs-prefix"><?= __d('Doko', 'You are here:'); ?></p>
        <?= $this->Html->getCrumbs('>', [
            'text' => 'Home',
            'url' => Configure::read('Doko.Frontend.home-url'),
            'lang' => 'en',
        ]); ?>
    </div>
</nav>

<div class="wrapper wrapper-middle">
    <div class="middle">
        <main id="main" class="main" role="main">
            <h2 class="main-title"><?= $this->fetch('title'); ?></h2>
            <?= $this->Flash->render(); ?>
            <?= $this->fetch('content'); ?>
        </main>
    </div>
</div>

<footer class="wrapper wrapper-footer" role="contentinfo">
    <div class="footer">
        <p class="powered-by"><?= $this->Html->link(__d('Doko', 'Doko di Librasoft'), 'http://www.librasoftsnc.it', [
            'target' => '_blank',
        ]); ?></p>
    </div>
</footer>

<?php if ($this->ACL->can('Backend.Access')): ?>
<a class="user-shortcut to-admin" href="<?= $this->Url->build(Configure::read('Doko.Backend.home-url')) ?>" title="<?= __d('Doko', 'Go to Admin Panel'); ?>"></a>
<?php endif; ?>
<?= $this->fetch('UserShortcuts'); ?>

<?= $this->fetch('scriptBeforeJquery'); ?>
<script src="<?= $this->Url->assetUrl('js/jquery.min.js'); ?>"></script>
<?= $this->fetch('scriptAfterJquery'); ?>
<script src="<?= $this->Url->assetUrl('js/vendor.min.js'); ?>"></script>
<?= $this->fetch('scriptAfterVendor'); ?>
<script src="<?= $this->Url->assetUrl('js/app.min.js'); ?>"></script>
<?= $this->fetch('scriptAfterApp'); ?>

<?= $this->Layout->analyticsTrackPage(); ?>
</body>
</html>
