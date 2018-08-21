<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel deluxcms\crontab\models\CrontabsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sql-Debug';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .danger {
        color:red;
    }
    .green {
        color:green;
    }
</style>
<div class="crontabs-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'api',
            [
                'attribute' => 'duration_time',
                'content' => function ($model) {
                    if ($model->duration_time > 3000) {
                        return '<span class="danger">' . $model->duration_time . ' MS</span>';
                    } else {
                        return '<span class="green">' . $model->duration_time . ' MS</span>';
                    }

                }
            ],
            [
                'attribute' => 'created_at',
                'content' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
