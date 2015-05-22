<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Configure paths required to find CakePHP + general filepath
 * constants
 */
require __DIR__ . '/paths.php';

// Use composer to load the autoloader.
require ROOT . DS . 'vendor' . DS . 'autoload.php';

/**
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

// You can remove this if you are confident you have intl installed.
if (!extension_loaded('intl')) {
    trigger_error('You must enable the intl extension to use CakePHP.', E_USER_ERROR);
}

use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Log\Log;
use Cake\Network\Email\Email;
use Cake\Network\Request;
use Cake\Routing\DispatcherFactory;
use Cake\Utility\Hash;
use Cake\Utility\Security;

/**
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('settings/cake', 'default', false);
    mb_internal_encoding(Configure::read('App.encoding'));
    Configure::load('settings/doko', 'default', false);
} catch (\Exception $e) {
    die($e->getMessage() . "\n");
}

if (!Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+1 years');
    Configure::write('Cache._cake_core_.duration', '+1 years');
}

/**
 * Register application error and exception handlers.
 */
$isCli = php_sapi_name() === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
    require __DIR__ . '/bootstrap_cli.php';
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

Cache::config(Configure::consume('Cache'));
ConnectionManager::config(Configure::consume('Datasources'));
Log::config(Configure::consume('Log'));
Security::salt(Configure::consume('Security.salt'));

//TODO: get current languages
$current_language = 'en';
ini_set('intl.default_locale', $current_language);
date_default_timezone_set(Configure::read('Doko.i18n.default-timezone'));

if (Configure::read('Doko.i18n.' . $current_language)) {
    Configure::write('Doko', Hash::merge(Configure::read('Doko'), Configure::read('Doko.i18n.' . $current_language)));
}

if (Configure::read('Doko.Owner.email')) {
    Configure::write('Email.default.from', [
        Configure::read('Doko.Owner.email') => Configure::read('Doko.Frontend.title'),
    ]);
}

Email::configTransport(Configure::consume('EmailTransport'));
Email::config(Configure::consume('Email'));

/**
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
if (!Configure::read('App.fullBaseUrl')) {
    if (Configure::read('Doko.Frontend.url_base')) {
        Configure::write('App.fullBaseUrl', Configure::read('Doko.Frontend.url_base'));
    } else {
        $httpHost = env('HTTP_HOST');
        if ($httpHost) {
            Configure::write('App.fullBaseUrl', 'http' . (env('HTTPS') ? 's' : null) . '://' . $httpHost);
        }
        unset($httpHost);
    }
}

/**
 * Setup detectors for mobile and tablet.
 */
Request::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isMobile();
});
Request::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isTablet();
});

/**
 * Load Plugins
 */
$plugins = array_merge([
    'Migrations',
    'Crud',
], Configure::read('Doko.Plugins'));

foreach ($plugins as $plugin) {
    Plugin::load($plugin, [
        'autoload' => true,
        'bootstrap' => true,
        'routes' => true,
        'ignoreMissing' => true,
    ]);
}

/**
 * Connect middleware/dispatcher filters.
 */
DispatcherFactory::add('Asset');
DispatcherFactory::add('Routing');
DispatcherFactory::add('ControllerFactory');
