<?php

namespace App\Routing;

use App\I18n\LanguageRegistry;
use Cake\Routing\RouteBuilder as BaseRouteBuilder;

class RouteBuilder extends BaseRouteBuilder
{

    public function connect($route, array $defaults = [], array $options = [])
    {
        if (empty($options['language']) && LanguageRegistry::$multilanguage) {
            $options['language'] = implode('|', LanguageRegistry::$languages);
        }
        parent::connect($route, $defaults, $options);
    }

}
