<?php

namespace kriss\widgets;

use kartik\base\Config;
use kartik\datecontrol\Module;
use yii\base\InvalidConfigException;

class DateControl extends \kartik\datecontrol\DateControl
{
    public function initConfig()
    {
        $this->_module = Config::initModule(Module::classname());
        if (!isset($this->autoWidget)) {
            $this->autoWidget = $this->_module->autoWidget;
        }
        if (!$this->autoWidget && !empty($this->widgetClass) && !class_exists($this->widgetClass)) {
            throw new InvalidConfigException("The widgetClass '{$this->widgetClass}' entered is invalid.");
        }
        if ($this->autoWidget === null) {
            $this->autoWidget = true;
        }
        $this->_widgetSettings = $this->_module->widgetSettings;
        if (empty($this->displayFormat)) {
            $this->displayFormat = $this->_module->getDisplayFormat($this->type);
        } else {
            $this->displayFormat = Module::parseFormat($this->displayFormat, $this->type);
        }
        if (empty($this->saveFormat)) {
            $this->saveFormat = $this->_module->getSaveFormat($this->type);
        } else {
            $this->saveFormat = Module::parseFormat($this->saveFormat, $this->type);
        }
        if (empty($this->displayTimezone)) {
            $this->displayTimezone = $this->_module->getDisplayTimezone();
        }
        if (empty($this->saveTimezone)) {
            $this->saveTimezone = $this->_module->getSaveTimezone();
        }
        // 去除此处判断
        // skip timezone validations when using date only inputs
        /*if ($this->type === self::FORMAT_DATE) {
            $this->displayTimezone = $this->saveTimezone = null;
        }*/
        if ($this->autoWidget) {
            $this->_widgetSettings = [
                self::FORMAT_DATE => ['class' => '\kartik\date\DatePicker'],
                self::FORMAT_DATETIME => ['class' => '\kartik\datetime\DateTimePicker'],
                self::FORMAT_TIME => ['class' => '\kartik\time\TimePicker'],
            ];
            Config::validateInputWidget(
                $this->_widgetSettings[$this->type]['class'],
                "for DateControl '{$this->type}' format"
            );
            foreach ($this->_widgetSettings as $type => $setting) {
                $this->_widgetSettings[$type]['options'] = $this->_module->autoWidgetSettings[$type];
                $this->_widgetSettings[$type]['disabled'] = $this->disabled;
                $this->_widgetSettings[$type]['readonly'] = $this->readonly;
            }
        }
        if (empty($this->widgetClass) && !empty($this->_widgetSettings[$this->type]['class'])) {
            $this->widgetClass = $this->_widgetSettings[$this->type]['class'];
        }
    }
}
