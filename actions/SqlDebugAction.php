<?php
namespace deluxcms\sqldebug\actions;

use Yii;
use yii\base\Action;

class SqlDebugAction extends Action
{
    /**
     * 处理存储在redis的数据
    */
    public function run()
    {
        Yii::$app->sqlDebug->listen();
    }
}