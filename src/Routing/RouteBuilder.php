<?php

namespace App\Routing;

use App\I18n\LanguageRegistry;
use Cake\Routing\RouteBuilder as BaseRouteBuilder;

class RouteBuilder extends BaseRouteBuilder
{

    public function connect($route, array $defaults = [], array $options = [])
    {
        if (LanguageRegistry::$multilanguage) {
            if (empty($options['language'])) {
                $options['language'] = implode('|', LanguageRegistry::$languages);
            }
        }
        parent::connect($route, $defaults, $options);
    }

}
