<?php

namespace app\components;

use yii\base\Widget;

class NewsWidget extends Widget
{
    public $provider;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function run()
    {
        return $this->render('newsAll',[
            'provider' => $this->provider,
        ]);
    }
}