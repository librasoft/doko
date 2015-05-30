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

        if (LanguageRegistry::$multilanguage) {
            $i18n_path = '/:language' . $path;
            $i18n_params = $params;
            $builder->scope($i18n_path, $i18n_params, $callback);
        }

        $builder->scope($path, $params, $callback);
    }

    public static function url($url = null, $full = false)
    {
        if (empty($url)) {
            $url = parent::url($url, false);
            if ($full) {
                $url = static::fullBaseUrl() . $url;
            }
        } elseif (is_array($url)) {
            $url = parent::url($url, $full);
        } else {
            $plainString = (
                strpos($url, 'javascript:') === 0 ||
                strpos($url, 'mailto:') === 0 ||
                strpos($url, 'tel:') === 0 ||
                strpos($url, 'sms:') === 0 ||
                strpos($url, '#') === 0 ||
                strpos($url, '?') === 0 ||
                strpos($url, '//') === 0 ||
                strpos($url, '://') !== false
            );

            if ($plainString) {
                return $url;
            }
        }

        if (!preg_match('@(\/\?|(\.[a-zA-Z0-9]|\/)$)@', $url)) {
            $url = strpos($url, '?') ? str_replace('?', '/?', $url) : $url . '/';
        }

        return $url;
    }

}
