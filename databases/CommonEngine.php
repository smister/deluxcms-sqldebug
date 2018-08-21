<?php
namespace deluxcms\sqldebug\databases;

use Yii;

class CommonEngine
{
    protected $sqlSign = [
        'yii\db\command'
    ];

    protected $error = '';

    public function __construct($params = [])
    {

    }

    /**
     * 解析数据
     * @param array $data
    */
    protected function parseData($data)
    {
        $retData = [];
        foreach ($data as $value) {
            if (isset($value['category']) && ($category = $this->getCategory($value['category'])) !== false) {
                $retData[] = [
                    'info' => $value['info'],
                    'category' => $category,
                    'trace' => $value['trace'],
                    'duration_time' => $this->getDuration($value['duration']),
                    'memory_diff' => $value['memoryDiff']
                ];
            } else {
                continue;
            }
        }
        return $retData;
    }

    /**
     * 获取执行类型
    */
    protected function getCategory($category)
    {
        $textArr = explode(':', $category);
        if (count($textArr) > 2) {
            if (in_array(strtolower($textArr[0]), $this->sqlSign)) {
                return $textArr[1];
            }
        }
        return false;
    }

    /**
     * 读取时间MS
    */
    protected function getDuration($duration)
    {
        return $duration * 1000;
    }

    /**
     * 读取总消耗时间
    */
    protected function getTotalDuration($data)
    {
        $duration = 0;
        foreach ($data as $value) {
            $duration += isset($value['duration_time']) ? $value['duration_time'] : $value['duration'];
        }
        return $duration;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * 检测db异常重连
     */
    protected function reDbLink()
    {
        try{
            Yii::$app->db->createCommand('SELECT 1')->queryOne();
        }catch (\yii\db\Exception $e)
        {
            Yii::$app->db->close();
            Yii::$app->db->open();
        }
    }
}