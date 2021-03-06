<?php

use kartik\icons\Icon;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;

/* @var $this         yii\web\View */
/* @var $searchModel  setrun\sys\forms\search\DomainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('setrun/sys/domain', 'Domains');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pjaxID']        = 'domain-index';

Icon::map($this, Icon::FI);

?>
<div class="domain-index">
    <div class="box">
        <div class="box-body">
            <p>
                <a href="<?= Url::to(['create']) ?>" class="btn btn-success">
                    <i class="fa fa-fw fa-plus"></i> <?= Yii::t('setrun/sys/domain', 'Create Domain') ?>
                </a>
                <a href="javascript:void(0)" class="btn btn-default" id="clear-filter">
                    <?= Icon::show('times', [], Icon::FA) ?>
                    <?= Yii::t('setrun/backend', 'Clear filters') ?>
                </a>
            </p>
            <hr />
            <?php Pjax::begin([
                'id'      => $this->params['pjaxID'],
                'timeout' => 3000
            ]);
            if (isset($_GET['_pjax'])) $_GET['_pjax'] = ''; ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-striped table-hover table-bordered'
                ],
                'columns' => [
                    ['class' => 'yii\grid\CheckboxColumn'],
                    'name' => [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function($model, $index, $key){
                            $url     = ['edit', 'id' => $model->id];
                            $options = ['class' => '', 'data-pjax' => 0];
                            return Html::a($model->name, $url, $options);
                        }
                    ],
                    'url',
                    'updated_at' => [
                        'attribute' => 'updated_at',
                        'format'    => 'raw',
                        'value' => function($model, $index, $widget){
                            return $model->timeAgo;
                        },
                        'filter' => DateRangePicker::widget([
                            'model'         => $searchModel,
                            'attribute'     => 'updated_at',
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'locale' => ['format' => 'yy-m-d'],
                                'opens'  => 'left'
                            ],
                            'hideInput'      => false,
                            'presetDropdown' => true,
                            'options' => [
                                'class' => 'form-control input-sm font-size-11'
                            ]
                        ])
                    ],
                    ['class' => 'setrun\backend\over\grid\ActionColumn'],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
