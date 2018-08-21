# deluxcms-sqldebug
 主要在调试模式下，将运行的sql语句存储到数据库中
 
 # 安装
 1、下载deluxcms-sqldebug组件
 ---------------------------
 ```
 composer require deluxcms/deluxcms-sqldebug
 ```
 
2、创建数据表
--------------------------
```
CREATE TABLE `debug_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api` varchar(255) NOT NULL COMMENT '接口名称',
  `duration_time` int(11) DEFAULT NULL COMMENT '消耗时间',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='DEBUG访问接口';

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='DEBUG关联SQL';
```

3、配置sqldebug组件,在config/main-local.php中添加
--------------------------
```
这里需要配置好mysql，如果开启了redis则需要保证组件存在Yii::$app->redis
[
    'components' => [
        ...
            'sqldebug' => [
                'class' => 'deluxcms\sqldebug\components\SqlDebug',
                'type' => 'redis', //目前支持redis,mysql
                'engineConfig' => [//配置异步存储redis的引擎
                    'saveEngine' => 'deluxcms\sqldebug\databases\MysqlEngine',
                ],
            ],
        ...
    ]
]
```
4、开启DEBUG模式跟log模块
--------------------------
```
1) 在入口文件web/index.php中,将YII_DEBUG设置为true
defined('YII_DEBUG') or define('YII_DEBUG', true);

2) 需要引入debug模块
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
}
```

5、开启采集Debug数据
--------------------------
```
写公共收集控制器，其他控制器继承使用即可
class CommonController extends \yii\web\Controller 
{
     public function afterAction($action, $result)
     {
         Yii::$app->sqldebug->triggerSave($this, $action);
         return parent::afterAction($action, $result);
     }
}
```

6、如果type为redis则需要异步将数据写入mysql中（type=mysql忽略）
--------------------------
```
1) 在console/controller中创建SqlDebugController.php
<?php

namespace console\controllers;

use yii\console\Controller;

class SqlDebugController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'deluxcms\sqldebug\actions\SqlDebugAction'
            ]
        ];
    }
}

2) 执行监听
php yii sql-debuig/index
```

7、配置config/main-local.php文件,添加sqldebug模块查看数据
--------------------------
```
'modules' => [
       'sqldebug' => [//管理模块
           'class' => 'deluxcms\sqldebug\Module',
       ],
],

查看数据
http://域名/模块   --> http://smister.com/sqldebug
```