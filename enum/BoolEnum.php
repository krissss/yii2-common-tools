<?php

namespace kriss\enum;

class BoolEnum extends BaseEnum
{
    const YES = 1;
    const NO = 0;

    public static function getViewItems()
    {
        return [
            self::YES => 'Y',
            self::NO => 'N',
        ];
    }
}
