<?php
namespace deluxcms\sqldebug\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 表结构
CREATE TABLE `debug_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api` varchar(255) NOT NULL COMMENT '接口名称',
  `duration_time` int(11) DEFAULT NULL COMMENT '消耗时间',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='DEBUG访问接口';
 */
class DebugApi extends ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%debug_api}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['api', 'required', 'message' => 'Api不能为空'],
            [['duration_time', 'created_at', 'updated_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'api' => '接口名称',
            'duration_time' => '消耗时间',
            'updated_at' => '更新时间',
            'created_at' => '创建时间',
        ];
    }

    public function getSqls()
    {
        return self::hasMany(DebugApiSql::className(), ['debug_api_id' => 'id']);
    }
}