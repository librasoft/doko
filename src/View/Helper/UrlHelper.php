<?php

namespace App\View\Helper;

use App\Routing\Router;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Utility\Inflector;
use Cake\View\Helper\UrlHelper as BaseUrlHelper;

/**
 * UrlHelper class for generating urls.
 */
class UrlHelper extends BaseUrlHelper
{

    /**
     * {@inheritDoc}
     */
    public function build($url = null, $full = true)
    {
        return h(Router::url($url, $full));
    }

    /**
     * {@inheritDoc}
     */
    public function webroot($file)
    {
        $asset = explode('?', $file);
        $asset[1] = isset($asset[1]) ? '?' . $asset[1] : null;
        $webPath = $this->request->webroot . $asset[0];
        $file = $asset[0];

        if (!empty($this->theme)) {
            $file = trim($file, '/');

            if (DS === '\\') {
                $file = str_replace('/', '\\', $file);
            }

            if (file_exists(Configure::read('App.wwwRoot') . 'theme' . DS . $this->theme . DS . $file)) {
                $webPath = $this->request->webroot . 'theme/' . $this->theme . '/' . $asset[0];
            } else {
                $themePath = Plugin::path($this->theme);
                $path = $themePath . 'webroot' . DS . $file;
                if (file_exists($path)) {
                    $webPath = $this->request->webroot . Inflector::underscore($this->theme) . '/' . $asset[0];
                }
            }
        }
        if (strpos($webPath, '//') !== false) {
            return str_replace('//', '/', $webPath . $asset[1]);
        }
        return $webPath . $asset[1];
    }

}
