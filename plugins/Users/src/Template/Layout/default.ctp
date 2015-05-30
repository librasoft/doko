<?php
use Cake\Core\Configure;
?>
<!doctype html>
<?= $this->Layout->htmlStart(); ?>
<head>
    <?php
    Configure::write('Asset.timestamp', 'force');

    echo $this->Html->charset();
    echo $this->Layout->title();
    echo $this->Layout->viewport();
    echo $this->Layout->documentMeta();
    echo $this->Html->css([
        'app.css',
    ]);
    echo $this->fetch('css');
    echo $this->Html->script([
        'modernizr.js',
    ]);
    echo $this->Layout->initJsVars();
    echo $this->fetch('script');
    echo $this->Layout->oldIE();

    Configure::write('Asset.timestamp', true);
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

<?php exit; ?>
<nav class="wrapper wrapper-breadcrumbs">
    <div class="breadcrumbs">
        <h2 class="breadcrumbs-title sr-only"><?= __d('Doko', 'Percorso di navigazione'); ?></h2>
        <p class="breadcrumbs-prefix"><?= __d('Doko', 'Ti trovi qui:'); ?></p>
        <?= $this->Layout->crumbsShow('>', 'Home', Configure::read('Doko.Frontend.home-url'), ['lang' => 'en']); ?>
    </div>
</nav>

<div class="wrapper wrapper-middle">
    <div class="middle">
        <main id="main" class="main" role="main">
            <h2 class="main-title"><?php echo $title_for_layout; ?></h2>
            <?php
            echo $this->Layout->sessionFlash();
            echo $this->fetch('content');
            ?>
        </main>
    </div>
</div>

<footer class="wrapper wrapper-footer" role="contentinfo">
    <div class="footer"><?php
        if (Configure::read('Page.base_url')) {
            echo '<p>' . sprintf('%s · %s', $this->Html->link(__d('Doko', 'Termini di Servizio'), Configure::read('Page.base_url') + array(
                'action' => 'view',
                'slug' => __d('Doko', 'termini-di-servizio'),
            )), $this->Html->link(__d('Doko', 'Privacy'), Configure::read('Page.base_url') + array(
                'action' => 'view',
                'slug' => __d('Doko', 'privacy'),
            ))) . '</p>';
        }
        //Do not remove.
        echo '<p class="powered-by">' . $this->Html->link(__d('Doko', 'Doko di Librasoft'), 'http://www.librasoftsnc.it', array(
            'target'	=> '_blank',
        )) . '</p>';
        //--
    ?></div>
</footer>

<?php if (DokoACL::can('Backend.Access')): ?>
<a class="user-shortcut to-admin" href="<?php echo $this->Url->build(Configure::read('Url.admin')) ?>" title="<?php echo __d('Doko', 'Vai al Pannello di Amministrazione'); ?>"></a>
<?php endif; ?>
<?php echo $this->fetch('UserShortcuts'); ?>

<?php
Configure::write('Asset.timestamp', 'force');
?>
<?php echo $this->fetch('scriptBeforeJquery'); ?>
<script src="<?php echo $this->Url->assetUrl('js/jquery.js'); ?>"></script>
<?php echo $this->fetch('scriptAfterJquery'); ?>
<script src="<?php echo $this->Url->assetUrl('js/vendor.js'); ?>"></script>
<?php echo $this->fetch('scriptAfterVendor'); ?>
<script src="<?php echo $this->Url->assetUrl('js/app.js'); ?>"></script>
<?php echo $this->fetch('scriptAfterApp'); ?>

<?php
if (!DokoACL::can('Analytics.DoNotTrack')) {
    $this->Layout->analyticsTrackPage();
}
?>
</body>
</html>
