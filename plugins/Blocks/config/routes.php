<?php
use Cake\Routing\Router;

Router::plugin('Blocks', function ($routes) {
    $routes->fallbacks('DashedRoute');
});
