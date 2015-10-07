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

<ul class="sr-only">
    <li><a href="#navigation" class="sr-only sr-only-focusable"><?= __d('Doko', 'Skip to main navigation') ?></a></li>
    <li><a href="#main" class="sr-only sr-only-focusable"><?= __d('Doko', 'Skip to main content') ?></a></li>
</ul>

<!--[if lt IE 9]>
    <div class="browsehappy">
        <p><?= __d('Doko', '<strong>Heads Up!</strong> Unfortunately, your browser in not supported.<br><a class="btn btn-sm btn-primary" href="http://browsehappy.com/">Upgrade it free</a>') ?></p>
    </div>
<![endif]-->

<header class="wrapper wrapper-header">
    <div class="logo" role="banner" itemscope itemtype="http://schema.org/Organization">
        <a class="logo-link" href="<?= $this->Url->build(Configure::read('Doko.Frontend.home-url')) ?>" accesskey="1" itemprop="url">
            <h1 class="logo-title">
                <?= $this->Html->image('logo.png', [
                    'alt' => Configure::read('Doko.Frontend.title'),
                    'width' => Configure::read('Doko.Frontend.logo-width'),
                    'height' => Configure::read('Doko.Frontend.logo-height'),
                    'itemprop' => 'logo name',
                    'class' => 'logo-img',
                ]) ?>
            </h1>
        </a>
    </div>
    <button class="btn btn-toggle-navigation btn-offcanvas" data-offcanvas-dir="left" aria-hidden="true"><?= __d('Doko', 'Menu') ?></button>
    <nav id="navigation" class="navigation" role="navigation">
        <h2 class="navigation-title sr-only"><?= __d('Doko', 'Main Navigation') ?></h2>
        <a href="#main" class="sr-only sr-only-focusable" accesskey="2"><?= __d('Doko', 'Skip to main content') ?></a>
        <?= $this->Menu->display('main') ?>
    </nav>
</header>

<?php if (!$this->get('skip-breadcrumbs')): ?>
<nav class="wrapper wrapper-breadcrumbs">
    <div class="breadcrumbs">
        <h2 class="breadcrumbs-title sr-only"><?= __d('Doko', 'Navigation path') ?></h2>
        <p class="breadcrumbs-prefix"><?= __d('Doko', 'You are here:') ?></p>
        <?= $this->Html->getCrumbs('>', [
            'text' => 'Home',
            'url' => Configure::read('Doko.Frontend.home-url'),
            'lang' => 'en',
        ]) ?>
    </div>
</nav>
<?php endif ?>

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

<?php
$footer_before = $this->Regions->display('footer-before');
if ($footer_before):
?>
<aside class="wrapper wrapper-footer-before">
    <div class="footer-before" role="complementary">
        <?= $footer_before ?>
    </div>
</aside>
<?php
endif;

$footer = $this->Regions->display('footer');
if ($footer):
?>
<footer class="wrapper wrapper-footer" role="contentinfo">
    <div class="footer">
        <?= $footer ?>
    </div>
</footer>
<?php
endif;
?>

<footer class="wrapper wrapper-legal" role="contentinfo">
    <div class="legal">
        <p><?= sprintf('Copyright © %s %s', date('Y'), Configure::read('Doko.Owner.copyright')) ?></p>
        <p><?= sprintf('%s · %s · P.I. %s', Configure::read('Doko.Owner.legal-name'), Configure::read('Doko.Owner.legal-address'), Configure::read('Doko.Owner.vat-code')) ?></p>
        <p><?= sprintf('%s · %s · %s · %s', '<a href="http://www.ProgettoKuma.it">Progetto Kuma Siti Web</a>', $this->Html->link(__d('Doko', 'Credits'), [
            'action' => 'view',
            'slug' => __d('Doko', 'credits'),
        ]), $this->Html->link(__d('Doko', 'Terms of Services'), [
            'action' => 'view',
            'slug' => __d('Doko', 'terms-of-services'),
        ]), $this->Html->link(__d('Doko', 'Privacy/Cookie'), [
            'action' => 'view',
            'slug' => __d('Doko', 'privacy'),
        ])) ?></p>
    </div>
</footer>

<?php if ($this->ACL->can('Backend.Access')): ?>
<a class="user-shortcut to-admin" href="<?= $this->Url->build(Configure::read('Doko.Backend.home-url')) ?>" title="<?= __d('Doko', 'To Admin Panel') ?>"></a>
<?php elseif ($this->ACL->can('Users.Profile')): ?>
<a class="user-shortcut to-profile" href="<?= $this->Url->build([
    'prefix' => false,
    'plugin' => 'Users',
    'controller' => 'Users',
    'action' => 'profile',
]) ?>" title="<?= __d('Doko', 'To Your Account') ?>"></a>
<?php endif ?>
<?= $this->fetch('UserShortcuts') ?>

<?= $this->element('cookie-alert') ?>

<?= $this->fetch('scriptBeforeJquery') ?>
<script src="<?= $this->Url->assetUrl('js/jquery' . $asset_suffix . '.js') ?>"></script>
<?= $this->fetch('scriptAfterJquery') ?>
<script src="<?= $this->Url->assetUrl('js/vendor' . $asset_suffix . '.js') ?>"></script>
<?= $this->fetch('scriptAfterVendor') ?>
<script src="<?= $this->Url->assetUrl('js/app' . $asset_suffix . '.js') ?>"></script>
<?= $this->fetch('scriptAfterApp') ?>

<?php
if (!$this->ACL->can('Analytics.DoNotTrack')) {
    $this->Layout->analyticsTrackPage();
}
?>
</body>
</html>
