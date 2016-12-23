<?php

namespace app\models;

use yii\base\Component;
use yii\base\Event;

class Foor extends Component
{
    const EVENT_HELLO = 'hello';

    public function bar()
    {
        $this->trigger(self::EVENT_HELLO);
    }
}