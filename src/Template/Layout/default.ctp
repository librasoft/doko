<?php

use Cake\Core\Configure;

//Layout options
$asset_suffix = Configure::read('debug') ? '.full' : '.min';
?>
<!doctype html>
<?php echo $this->Layout->htmlStart(); ?>
<head>
    <?php
    echo $this->Html->charset();
    echo $this->Layout->title();
    echo $this->Layout->viewport();
    echo $this->Layout->documentMeta();
    echo $this->Html->css(array(
        'app' . $asset_suffix . '.css',
    ));
    echo $this->fetch('css');
    echo $this->Html->script(array(
        'modernizr' . $asset_suffix . '.js',
    ));
    echo $this->Layout->initJsVars();
    echo $this->fetch('script');
    echo $this->Layout->oldIE();
    ?>
</head>
<?php echo $this->Layout->bodyStart(); ?>

<ul class="sr-only">
    <li><a href="#navigation" class="sr-only sr-only-focusable"><?php echo __d('Doko', 'Skip to main navigation'); ?></a></li>
    <li><a href="#main" class="sr-only sr-only-focusable"><?php echo __d('Doko', 'Skip to main content'); ?></a></li>
</ul>

<!--[if lt IE 9]>
    <div class="browsehappy">
        <p><?php echo __d('Doko', '<strong>Heads Up!</strong> Unfortunately, your browser in not supported.<br><a class="btn btn-sm btn-primary" href="http://browsehappy.com/">Upgrade it free</a>'); ?></p>
    </div>
<![endif]-->

<header class="wrapper wrapper-header">
    <div class="logo" role="banner" itemscope itemtype="http://schema.org/Organization">
        <a class="logo-link" href="<?php echo $this->Url->build(Configure::read('Frontend.home-url')); ?>" accesskey="1" itemprop="url">
            <h1 class="logo-title">
                <?php
                echo $this->Html->image('logo.png', array(
                    'alt' => Configure::read('Frontend.title'),
                    'width' => Configure::read('Frontend.logo-width'),
                    'height' => Configure::read('Frontend.logo-height'),
                    'itemprop' => 'logo',
                    'class' => 'logo-img',
                ));
                ?>
            </h1>
        </a>
    </div>
    <button class="btn btn-toggle-navigation btn-offcanvas" data-offcanvas-dir="left" aria-hidden="true"><?php echo __d('Doko', 'Menu'); ?></button>
    <nav id="navigation" class="navigation" role="navigation">
        <h2 class="navigation-title sr-only"><?php echo __d('Doko', 'Main Navigation'); ?></h2>
        <a href="#main" class="sr-only sr-only-focusable" accesskey="2"><?php echo __d('Doko', 'Skip to main content'); ?></a>
        <?php
        echo $this->Layout->nestedList($this->Layout->menu('main'), array(
            'class' => 'nav nav-menu nav-menu-main',
            'dropdown' => true,
        ));
        ?>
    </nav>
</header>

<?php if (!$this->get('no-breadcrumbs')): ?>
<nav class="wrapper wrapper-breadcrumbs">
    <div class="breadcrumbs">
        <h2 class="breadcrumbs-title sr-only"><?php echo __d('Doko', 'Navigation path'); ?></h2>
        <p class="breadcrumbs-prefix"><?php echo __d('Doko', 'You are here:'); ?></p>
        <?php echo $this->Layout->crumbsShow('>', 'Home', Configure::read('Frontend.home-url'), array('lang' => 'en')); ?>
    </div>
</nav>
<?php endif; ?>

<div class="wrapper wrapper-middle">
    <div class="middle">
        <?php echo $this->fetch('middle-prepend'); ?>

        <main id="main" class="main" role="main">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </main>

        <?php echo $this->fetch('middle-append'); ?>
    </div>
</div>

<?php
$footer_before = $this->Layout->blocks('footer-before');
if ($footer_before):
?>
<aside class="wrapper wrapper-footer-before">
    <div class="footer-before" role="complementary">
        <?php echo $footer_before; ?>
    </div>
</aside>
<?php
endif;
?>

<footer class="wrapper wrapper-footer" role="contentinfo">
    <div class="footer">
        <?php
        echo $this->Layout->blocks('footer');
        ?>
    </div>
</footer>

<footer class="wrapper wrapper-legal" role="contentinfo">
    <div class="legal">
        <p><?php
            echo sprintf('Copyright © %s %s', date('Y'), Configure::read('Owner.copyright'));
        ?></p>
        <p><?php
            echo sprintf('%s · %s · P.I. %s', Configure::read('Owner.legal-name'), Configure::read('Owner.legal-address'), Configure::read('Owner.vat-code'));
        ?></p>
        <p><?php
            echo sprintf('%s · %s · %s · %s', '<a href="http://www.ProgettoKuma.it">Progetto Kuma Siti Web</a>', $this->Html->link(__d('Doko', 'Credits'), array(
                'action' => 'view',
                'slug' => __d('Doko', 'credits'),
            )), $this->Html->link(__d('Doko', 'Terms of Services'), array(
                'action' => 'view',
                'slug' => __d('Doko', 'terms-of-services'),
            )), $this->Html->link(__d('Doko', 'Privacy/Cookie'), array(
                'action' => 'view',
                'slug' => __d('Doko', 'privacy'),
            )));
        ?></p>
    </div>
</footer>

<?php if ($this->ACL->can('Backend.Access')): ?>
<a class="user-shortcut to-admin" href="<?php echo $this->Url->build(Configure::read('Backend.home-url')) ?>" title="<?php echo __d('Doko', 'To Admin Panel'); ?>"></a>
<?php elseif ($this->ACL->can('Users.Profile')): ?>
<a class="user-shortcut to-profile" href="<?php
    echo $this->Url->build(array(
        'prefix' => false,
        'plugin' => 'Users',
        'controller' => 'Users',
        'action' => 'profile',
    ));
?>" title="<?php echo __d('Doko', 'To Your Account'); ?>"></a>
<?php endif; ?>
<?php echo $this->fetch('UserShortcuts'); ?>

<?php echo $this->element('cookie-alert'); ?>

<?php echo $this->fetch('scriptBeforeJquery'); ?>
<script src="<?php echo $this->Url->assetUrl('js/jquery' . $asset_suffix . '.js'); ?>"></script>
<?php echo $this->fetch('scriptAfterJquery'); ?>
<script src="<?php echo $this->Url->assetUrl('js/vendor' . $asset_suffix . '.js'); ?>"></script>
<?php echo $this->fetch('scriptAfterVendor'); ?>
<script src="<?php echo $this->Url->assetUrl('js/app' . $asset_suffix . '.js'); ?>"></script>
<?php echo $this->fetch('scriptAfterApp'); ?>

<?php
if (!$this->ACL->can('Analytics.DoNotTrack')) {
    $this->Layout->analyticsTrackPage();
}
?>
</body>
</html>
