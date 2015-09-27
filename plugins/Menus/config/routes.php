<?php
use Cake\Routing\Router;

Router::plugin('Menus', function ($routes) {
    $routes->fallbacks('DashedRoute');
});
