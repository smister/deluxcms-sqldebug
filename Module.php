<?php

namespace deluxcms\sqldebug;

/**
 * post module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'deluxcms\sqldebug\controllers';

    public $defaultRoute = 'sql-debug';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
