<?php
namespace deluxcms\sqldebug\components;

use Yii;
use yii\base\Component;

class SqlDebug extends Component
{
    public $engine;
    public $type;
    public $engineConfig = [];
    protected $engines = [
        'redis' => '\deluxcms\sqldebug\databases\RedisEngine',
        'mysql' => '\deluxcms\sqldebug\databases\MysqlEngine',
    ];

    public function init()
    {
        if (!isset($this->type) || !isset($this->engines[$this->type])) {
            $this->type = 'mysql';
        }

        $this->engine = new $this->engines[$this->type]($this->engineConfig);
        parent::init();
    }

    /**
     * 触发Sql Debug数据保存
    */
    public function triggerSave($controller, $action)
    {
        if (YII_DEBUG && Yii::$app->has('log')) {
            $log = Yii::$app->log->getLogger();
            $messages = $log->messages;
            if (!empty($messages)) {
                $data = $log->calculateTimings($messages);
                $routeId = $this->geRouteId($controller, $action);
                return $this->engine->save($routeId, $data);
            }
        }
        return true;
    }


    public function listen()
    {
        $this->engine->listen();
    }

    /**
     * 读取接口的唯一id
    */
    protected function geRouteId($controller, $action)
    {
        $route = Yii::$app->id . '_';

        $module = $controller->module;
        $appendRoute = '';
        while(true){
            if (Yii::$app->id != $module->id) $appendRoute = $module->id .'_' . $appendRoute;
            if (!isset($module->module)) break;
            $module = $module->module;
        }

        $route .= $appendRoute . $controller->id . '_' . $action->id;
        return $route;
    }

}