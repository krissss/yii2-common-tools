<?php

namespace common\components;

use Yii;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;

class TimeUserBehavior extends Behavior
{
    public $createdAtAttribute = 'created_at';
    public $createdByAttribute = 'created_by';

    public $updatedAtAttribute = 'updated_at';
    public $updatedByAttribute = 'updated_by';

    public $useTime = true;
    public $useUser = true;

    private $attributes;
    private $timeValue;
    private $userValue;

    public function init()
    {
        parent::init();

        // 运行 console 时不执行以下代码
        if (php_sapi_name() != 'cli') {
            if ($this->useTime) {
                $this->timeValue = time();
            }

            if ($this->useUser) {
                $this->userValue = Yii::$app->user->getId();
            }

            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [
                    'time' => [$this->createdAtAttribute, $this->updatedAtAttribute],
                    'user' => [$this->createdByAttribute, $this->updatedByAttribute],
                ],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => [
                    'time' => [$this->updatedAtAttribute],
                    'user' => [$this->updatedByAttribute],
                ]
            ];
        }

    }

    public function events()
    {
        if (php_sapi_name() == 'cli') { // 运行console时
            return parent::events();
        } else {
            return array_fill_keys(array_keys($this->attributes), 'generateValue');
        }
    }

    public function generateValue($event)
    {
        if (!empty($this->attributes[$event->name])) {
            if ($this->useTime) {
                $timeAttributes = (array)$this->attributes[$event->name]['time'];
                foreach ($timeAttributes as $attribute) {
                    $this->owner->$attribute = $this->timeValue;
                }
            }
            if ($this->useUser) {
                $userAttributes = (array)$this->attributes[$event->name]['user'];
                foreach ($userAttributes as $attribute) {
                    $this->owner->$attribute = $this->userValue;
                }
            }
        }
    }
}