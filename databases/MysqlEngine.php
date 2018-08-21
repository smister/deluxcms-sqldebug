<?php
namespace deluxcms\sqldebug\databases;

use deluxcms\sqldebug\models\DebugApiSql;
use Yii;
use deluxcms\sqldebug\models\DebugApi;
use yii\db\Exception;

class MysqlEngine extends CommonEngine implements EngineInterface
{
    public function save($routeId, $data)
    {
        $data = $this->parseData($data);
        if ($data) {
            $debugApi = new DebugApi();
            $debugApi->setAttributes([
                'api' => $routeId,
                'duration_time' => (int)$this->getTotalDuration($data)
            ]);

            $tran = Yii::$app->db->beginTransaction();
            try {
                if (!$debugApi->save()) {
                    throw new Exception("添加Debug接口失败，失败信息" . print_r($debugApi->getErrors(), true));
                }
                foreach ($data as $value) {
                    $debugApiSql = new DebugApiSql();
                    $debugApiSql->setAttributes([
                        'debug_api_id' => $debugApi->id,
                        'info' => $value['info'],
                        'category' => $value['category'],
                        'duration_time' => (int)$value['duration_time'],
                        'memory_diff' => $value['memory_diff'],
                        'trace' => is_array($value['trace']) ? serialize($value) : $value['trace'],
                    ]);
                    if (!$debugApiSql->save()) {
                        throw new Exception("添加DebugSql失败，失败信息" . print_r($debugApiSql->getErrors()));
                    }
                }
                $tran->commit();
                return true;
            } catch (Exception $e) {
                $tran->rollBack();
                $this->setError($e->getMessage());
                var_dump($e->getMessage());
                Yii::error($e->getMessage());
                return false;
            }
        }
        return true;
    }

    public function listen()
    {
    }

}