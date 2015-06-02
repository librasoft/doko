<?php

namespace App\I18n;

use Cake\Network\Session;

class LanguageRegistry
{

    public static $sessionAuthKey = 'Auth.User';
	public static $frontend;
	public static $backend;
	public static $languages;
	public static $multilanguage_frontend;
	public static $multilanguage_backend;
	public static $multilanguage;
    public static $stack = [];
    public static $current;
    public static $current_frontend;

    public static function init($frontend, $backend)
    {
		//Step 0: Get all available languages for the site.
		$url = env('REQUEST_URI');
		$location = preg_match('#\/admin\/#', $url) ? 'backend' : 'frontend';

		self::$frontend = $frontend;
		self::$backend = $backend;
        self::$languages = self::${$location};

		self::$multilanguage_frontend = (count(self::$frontend) > 1);
		self::$multilanguage_backend = (count(self::$backend) > 1);
		self::$multilanguage = (count(self::$languages) > 1);

        $haystack = array_flip(self::$languages);

		//Step 1: Check for url param.
		preg_match('#\/(' . implode('|', self::$languages) . ')\/#', $url, $url_lang);
		if (!empty($url_lang[1])) {
            self::$stack['URL'] = $url_lang[1];
		}

		//Step 2: Check for user preference.
        $user_lang = (new Session())->read(self::$sessionAuthKey . '.language');
		if ($user_lang) {
			if (isset($haystack[$user_lang])) {
				self::$stack['User'] = $user_lang;
			}
		}

		//Step 3: Check for request preference.
		$http_accept_language = self::parseAcceptWithQualifier(env('HTTP_ACCEPT_LANGUAGE'));
        $found = false;
        foreach ($http_accept_language as $http_part) {
            foreach ($http_part as $request_lang) {
                if (isset($haystack[$request_lang])) {
                    self::$stack['Request'] = $request_lang;
                    $found = true;
                    break 2;
                }
            }
        }
        if (!$found) {
            foreach ($http_accept_language as $http_part) {
                foreach ($http_part as $request_lang) {
                    $request_lang = substr($request_lang, 0, 2);
                    if (isset($haystack[$request_lang])) {
                        self::$stack['Request'] = $request_lang;
                        break 2;
                    }
                }
            }
        }

		//Step 4: Check for site settings.
		if (!empty(self::$languages)) {
            self::$stack['Site'] = current(self::$languages);
		}

		//Now set the Suggested language
		if (!empty(self::$stack['URL'])) {
			foreach (['User', 'Request'] as $param) {
                if (!empty(self::$stack[$param])) {
                    if (self::$stack[$param] !== self::$stack['URL']) {
                        self::$stack['Suggested'] = self::$stack[$param];
                    }
                    break;
                }
			}
		}

		//At the end set Current language
		foreach (['URL', 'User', 'Request', 'Site'] as $param) {
			if (!empty(self::$stack[$param])) {
                self::$current = self::$stack[$param];
                self::$current_frontend = in_array(self::$stack[$param], self::$frontend) ? self::$stack[$param] : current(self::$frontend);
				return;
			}
		}
    }

    public static function parseAcceptWithQualifier($header)
    {
        $accept = [];
        $header = explode(',', $header);
        foreach (array_filter($header) as $value) {
            $prefValue = '1.0';
            $value = trim($value);

            $semiPos = strpos($value, ';');
            if ($semiPos !== false) {
                $params = explode(';', $value);
                $value = trim($params[0]);
                foreach ($params as $param) {
                    $qPos = strpos($param, 'q=');
                    if ($qPos !== false) {
                        $prefValue = substr($param, $qPos + 2);
                    }
                }
            }

            if (!isset($accept[$prefValue])) {
                $accept[$prefValue] = [];
            }
            if ($prefValue) {
				if (strpos($value, '_')) {
					$value = str_replace('_', '-', $value);
				}
                $accept[$prefValue][] = strtolower($value);
            }
        }
        krsort($accept);
        return $accept;
    }

}
