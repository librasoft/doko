<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use HTMLPurifier;
use HTMLPurifier_Config;
use Psr\Log\LogLevel;

/**
 * Form component
 */
class FormComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function implementedEvents()
    {
        return [
            'Controller.initialize' => 'beforeFilter',
        ];
    }

    public function beforeFilter(Event $event)
    {
        if (empty($this->request->data)) {
            return true;
        }
        if (isset($this->request->data['_timestamp'])) {
            $treshold_min = 3; // in seconds
            $treshold_max = 60 * 60 * 24 * 7;
            $form_timestamp = (int) $this->request->data['_timestamp'];
            $current_timestamp = (int) time();

            if ($current_timestamp - $form_timestamp <= $treshold_min) {
                $this->log(print_r($this->request->data + [
                    'ip' => env('REMOTE_ADDR'),
                    'agent' => env('HTTP_USER_AGENT'),
                    'referer' => env('HTTP_REFERER'),
                ], true), LogLevel::WARNING);
                sleep(10); // flood prevention
                throw new BadRequestException(__d('Doko', 'You spent too little time to complete the form.'));
            } elseif ($current_timestamp - $form_timestamp >= $treshold_max) {
                $this->log(print_r($this->request->data + [
                    'ip' => env('REMOTE_ADDR'),
                    'agent' => env('HTTP_USER_AGENT'),
                    'referer' => env('HTTP_REFERER'),
                ], true), LogLevel::WARNING);
                sleep(10); // flood prevention
                throw new BadRequestException(__d('Doko', 'You spent too much time to complete the form.'));
            }
        }
        if (isset($this->request->data['_honeypot'])) {
            $honeypot = json_decode($this->request->data['_honeypot'], true);
            $honeypot_value = $this->request->data($honeypot['name']);

            if (!empty($honeypot_value)) {
                $this->log(print_r($this->request->data + [
                    'ip' => env('REMOTE_ADDR'),
                    'agent' => env('HTTP_USER_AGENT'),
                    'referer' => env('HTTP_REFERER'),
                ], true), LOG_WARNING);
                sleep(10); // flood prevention
                throw new BadRequestException(__d('Doko', 'Leave empty the specified field.'));
            }
        }

        $this->request->data = $this->_clean($this->request->data);
        return true;
    }

    protected function _clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key] = $this->_clean($val);
            }

            return $data;
        }

        if (is_string($data)) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('Cache.SerializerPath', CACHE);
            $purifier = new HTMLPurifier($config);
            $data = $purifier->purify($data);
        }

        return $data;
    }
}
