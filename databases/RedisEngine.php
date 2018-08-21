<?php
namespace deluxcms\sqldebug\databases;

use Yii;

class RedisEngine extends CommonEngine implements EngineInterface
{
    const DEBUG_KEY = 'sql_debug_list';
    protected $saveEngine;

    public function __construct($params = [])
    {
        if (empty($params['saveEngine'])) {
            $params['saveEngine'] = '\deluxcms\sqldebug\databases\MysqlEngine';
        }
        $this->saveEngine = Yii::$container->get($params['saveEngine']);
    }

    public function save($routeId, $data)
    {
        Yii::$app->redis->lpush(self::DEBUG_KEY, serialize([
            'routeId' => $routeId,
            'data' => $data
        ]));

        return true;
    }

    /**
     * 监听处理Mysql数据
     * @param Clouser $callBack 回调数据(状态，接口，相关数据)
    */
    public function listen($callBack = null)
    {
        while (true) {
            $this->reDbLink();   //防止SQL监听超时
            $data = Yii::$app->redis->rpop(self::DEBUG_KEY);
            if (!$data) {
                sleep(1);
                continue;
            }
            $data = unserialize($data);
            if (isset($data['routeId']) && isset($data['data'])) {
                $status = $this->saveEngine->save($data['routeId'], $data['data']);
                if (is_callable($callBack)) {
                    call_user_func_array($callBack, [
                        $status,
                        $data['routeId'],
                        $data['data']
                    ]);
                }
            }
        }
    }
}