<?php
namespace App\View\Helper;

use Cake\Utility\Inflector;
use Cake\View\Helper\FormHelper as BaseFormHelper;
use Cake\View\View;

/**
 * Form helper
 */
class FormHelper extends BaseFormHelper
{
    use OptionsAwareTrait;

    public function __construct(View $View, array $config = [])
    {
        $this->_defaultConfig['templates'] = [
            'checkboxContainer' => '<div class="checkbox input-{{field}}{{required}}">{{content}}{{help}}</div>',
            'checkboxContainerError' => '<div class="checkbox input-{{field}}{{required}} has-error">{{content}}{{error}}{{help}}</div>',
            'error' => '<div class="help-block error-block">{{content}}</div>',
            'formGroup' => '{{label}}{{between}}{{input}}',
            'help' => '<p class="help-block">{{content}}</p>',
            'inputContainer' => '<div class="form-group {{type}}-form-group input-{{field}}{{required}}">{{content}}{{help}}</div>',
            'inputContainerError' => '<div class="form-group {{type}}-form-group input-{{field}}{{required}} has-error">{{content}}{{error}}{{help}}</div>',
            'nestingLabel' => '{{hidden}}{{input}}<label{{attrs}}>{{text}}</label>',
        ] + $this->_defaultConfig['templates'];
        parent::__construct($View, $config);
    }

    public function create($model = null, array $options = [])
    {
        return parent::create($model, $options + [
            'role' => 'form',
        ]);
    }

    public function input($fieldName, array $options = [])
    {
        $options += [
            'prepend' => null,
            'append' => null,
            'type' => null,
            'label' => null,
            'error' => null,
            'required' => null,
            'options' => null,
            'between' => null,
            'help' => null,
            'templates' => [],
        ];
        $options = $this->_parseOptions($fieldName, $options);
        $reset = $this->templates();

        switch ($options['type']) {
            case 'hidden':
                break;
            case 'checkbox':
            case 'radio':
                break;
            case 'select':
                if (isset($options['multiple']) && $options['multiple'] === 'checkbox') {
                    $this->templates(['checkboxWrapper' => '<div class="checkbox">{{label}}</div>']);
                    $options['type'] = 'multicheckbox';
                } else {
                    $options = $this->injectClasses('form-control', $options);
                }
                break;
            default:
                $options = $this->injectClasses('form-control', $options);
        }

        if ($options['help']) {
            $options['help'] = $this->templater()->format('help', [
                'content' => $options['help']
            ]);
        }

        $result = parent::input($fieldName, $options);
        $this->templates($reset);
        return $result;
    }

    protected function _getInput($fieldName, $options)
    {
        unset($options['help'], $options['between']);
        return parent::_getInput($fieldName, $options);
    }

    protected function _groupTemplate($options)
    {
        $groupTemplate = $options['options']['type'] . 'FormGroup';
        if (!$this->templater()->get($groupTemplate)) {
            $groupTemplate = 'formGroup';
        }
        return $this->templater()->format($groupTemplate, [
            'input' => $options['input'],
            'label' => $options['label'],
            'error' => $options['error'],
            'between' => $options['options']['between'],
            'help' => $options['options']['help'],
        ]);
    }

    protected function _inputContainerTemplate($options)
    {
        $inputContainerTemplate = $options['options']['type'] . 'Container' . $options['errorSuffix'];
        if (!$this->templater()->get($inputContainerTemplate)) {
            $inputContainerTemplate = 'inputContainer' . $options['errorSuffix'];
        }

        return $this->templater()->format($inputContainerTemplate, [
            'content' => $options['content'],
            'error' => $options['error'],
            'required' => $options['options']['required'] ? ' required' : '',
            'type' => $options['options']['type'],
            'field' => $options['options']['id'],
            'between' => $options['options']['between'],
            'help' => $options['options']['help'],
        ]);
    }

    protected function _getLabel($fieldName, $options)
    {
        if (is_string($options['label'])) {
            $options['label'] = [
                'text' => $options['label'],
            ];
        }
        if ($options['label'] === null) {
            $text = $fieldName;
            if (substr($text, -5) === '._ids') {
                $text = substr($text, 0, -5);
            }
            if (strpos($text, '.') !== false) {
                $fieldElements = explode('.', $text);
                $text = array_pop($fieldElements);
            }
            if (substr($text, -3) === '_id') {
                $text = substr($text, 0, -3);
            }
            $text = __(Inflector::humanize(Inflector::underscore($text)));
            $options['label'] = [
                'text' => $text,
            ];
        }
        if (is_array($options['label'])) {
            $options['label'] = $this->injectClasses('control-label', $options['label']);

            if (!empty($options['required'])) {
                $options['label']['text'] = '<i>*</i> ' . $options['label']['text'];
                $options['label']['escape'] = false;
            }
        }

        return parent::_getLabel($fieldName, $options);
    }

}
