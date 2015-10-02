<?php

namespace App\I18n;

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
    public static $ui;

    public static function init($frontend, $backend, $request = null)
    {
		//Step 0: Get all available languages for the site.
		$url = env('REQUEST_URI');
		$location = preg_match('#\/admin\/#', $url) ? 'backend' : 'frontend';

		self::$frontend = $frontend;
		self::$backend = $backend;
        self::$languages = self::$frontend;

		self::$multilanguage_frontend = (count(self::$frontend) > 1);
		self::$multilanguage_backend = (count(self::$backend) > 1);
		self::$multilanguage = self::$multilanguage_frontend;

        $frontend_haystack = array_flip(self::$frontend);
        $backend_haystack = array_flip(self::$backend);

		//Check for url param.
		preg_match('#\/(' . implode('|', self::$frontend) . ')\/#', $url, $url_lang);
		if (!empty($url_lang[1])) {
            self::$stack['URL'] = $url_lang[1];
		}

		//Check for user preference.
        if ($request) {
            $user_lang = $request->session()->read(self::$sessionAuthKey . '.language');
            if ($user_lang) {
                self::$stack['User'] = $user_lang;
            }
        }

		//Check for site settings.
		if (!empty(self::$frontend)) {
            self::$stack['Frontend'] = current(self::$frontend);
		}
		if (!empty(self::$backend)) {
            self::$stack['Backend'] = current(self::$backend);
		}

        if ($location === 'frontend') {
            //Check for request preference.
            $http_accept_language = self::parseAcceptWithQualifier(env('HTTP_ACCEPT_LANGUAGE'));
            $found = false;
            foreach ($http_accept_language as $http_part) {
                foreach ($http_part as $request_lang) {
                    if (isset($frontend_haystack[$request_lang])) {
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
                        if (isset($frontend_haystack[$request_lang])) {
                            self::$stack['Request'] = $request_lang;
                            break 2;
                        }
                    }
                }
            }

            //Set the Suggested language
            if (!empty(self::$stack['URL'])) {
                foreach (['User', 'Request'] as $param) {
                    if (!empty(self::$stack[$param]) && isset($frontend_haystack[self::$stack[$param]])) {
                        if (self::$stack[$param] !== self::$stack['URL']) {
                            self::$stack['Suggested'] = self::$stack[$param];
                        }
                        break;
                    }
                }
            }

            //Set Current language
            foreach (['URL', 'User', 'Request', 'Frontend'] as $param) {
                if (!empty(self::$stack[$param]) && isset($frontend_haystack[self::$stack[$param]])) {
                    self::$current = self::$ui = self::$stack[$param];
                    return;
                }
            }
        } else { //Backend
            //Set Current languages
            foreach (['URL', 'User', 'Frontend'] as $param) {
                if (!empty(self::$stack[$param]) && isset($frontend_haystack[self::$stack[$param]])) {
                    self::$current = self::$stack[$param];
                    break;
                }
            }
            foreach (['User', 'Backend'] as $param) {
                if (!empty(self::$stack[$param]) && isset($backend_haystack[self::$stack[$param]])) {
                    self::$ui = self::$stack[$param];
                    return;
                }
            }
        }
    }

    public static function parseAcceptWithQualifier($header)
    {
        $accept = [];
        $header = array_filter(explode(',', $header));
        foreach ($header as $value) {
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
