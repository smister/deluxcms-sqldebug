<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model deluxcms\crontab\models\Crontabs */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sql-Debug', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sqldebug-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <table class="table table-striped table-bordered detail-view">
        <tr><td><b>ID：</b><?=$model->id?></td></tr>
        <tr><td><b>接口：</b><?=$model->api?></td></tr>
        <tr><td><b>执行时间：</b><?=$model->duration_time . ' MS'?></td></tr>
        <tr><td><b>创建时间：</b><?=date('Y-m-d H:i:s', $model->created_at)?></td></tr>
        <tr>
            <td>
                <b> 详细SQL：</b><br/>
                <?php if (isset($model->sqls)):?>
                    <?php foreach($model->sqls as $sql):?>
                        <?=$sql['info'] . '<br/>'?>
                    <?php endforeach;;?>
                <?php endif;?>
            </td>
        </tr>
    </table>

</div>
