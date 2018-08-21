<?php

namespace deluxcms\sqldebug\models;

use yii\db\ActiveRecord;

/**
表结构
CREATE TABLE `xg_debug_api_sql` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`debug_api_id` int(11) DEFAULT NULL COMMENT '关联api',
`info` text COMMENT '执行语句',
`category` varchar(255) NOT NULL COMMENT '执行类型',
`duration_time` int(11) DEFAULT NULL COMMENT '消耗时间',
`memory_diff` int(11) DEFAULT NULL COMMENT '消耗内存',
`trace` text COMMENT '调试内容',
PRIMARY KEY (`id`),
KEY `idx_api` (`debug_api_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='DEBUG关联SQL';

*/
class DebugApiSql extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%debug_api_sql}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['debug_api_id', 'integer', 'message' => '请选择关联api'],
            [['category', 'trace', 'info'], 'string'],
            [['duration_time', 'memory_diff'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'debug_api_id' => 'DEBUG访问接口',
            'category' => '执行类型',
            'duration_time' => '消耗时间',
            'memory_diff' => '内存消耗',
            'trace' => '调式足迹',
        ];
    }
}