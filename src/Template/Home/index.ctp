<?php

use Cake\Core\Configure;

$this->Layout->setCanonical(Configure::read('Frontend.home-url'));
$this->Layout->setCss('doko-home');
$this->set('skip-breadcrumb', true);

//echo $this->element('home-hero');

$this->assign('middle-append', $this->Regions->sidebar('home', array(
    'offcanvas-button' => false,
	'title-tag' => 'h2',
)));
