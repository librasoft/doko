<?php

namespace App\Routing;

use App\I18n\LanguageRegistry;
use App\Routing\RouteBuilder;
use Cake\Routing\Router as BaseRouter;

class Router extends BaseRouter
{

    public static function scope($path, $params = [], $callback = null)
    {
        if ($callback === null) {
            $callback = $params;
            $params = [];
        }
        if (!is_callable($callback)) {
            $msg = 'Need a callable function/object to connect routes.';
            throw new \InvalidArgumentException($msg);
        }

        $builder = new RouteBuilder(static::$_collection, '/', [], [
            'routeClass' => static::defaultRouteClass(),
            'extensions' => static::$_defaultExtensions
        ]);

        if (LanguageRegistry::$multilanguage_frontend) {
            $builder->scope('/:language' . $path, $params, $callback);
        }

        $builder->scope($path, $params, $callback);
    }

    public static function url($url = null, $full = false)
    {
        if (LanguageRegistry::$multilanguage) {
            if (is_array($url)) {
                if (!array_key_exists('language', $url)) {
                    $url['language'] = LanguageRegistry::$current;
                }
            } elseif ($url === '/') {
                $url = '/' . LanguageRegistry::$current . '/';
            }
        }

        $url = parent::url($url, $full);

        if (!preg_match('@(\/\?|(\.[a-zA-Z0-9]|\/)$)@', $url)) {
            $url = strpos($url, '?') ? str_replace('?', '/?', $url) : $url . '/';
        }

        return $url;
    }

}
