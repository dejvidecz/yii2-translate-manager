<?php

/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */

use dlds\translatemanager\helpers\Language;
use dlds\translatemanager\models\Language as Lang;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $code string */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel dlds\translatemanager\models\searches\LanguageSourceSearch */

$this->title = Yii::t('language', 'Translation into {code}', ['code' => $code]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('language', 'Languages'), 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::hiddenInput('code', $code, ['id' => 'code', 'data-url' => Yii::$app->urlManager->createAbsoluteUrl('/translatemanager/language/save')]); ?>
<div id="translates" class="<?= $code ?>">
    <?php
    Pjax::begin([
        'id' => 'translates',
    ]);
    $form = ActiveForm::begin([
        'method' => 'get',
        'id' => 'search-form',
        'enableAjaxValidation' => FALSE,
        'enableClientValidation' => FALSE,
    ]);
    echo $form->field($searchModel, 'source')->dropDownList(['' => Yii::t('language', 'Original')] + Lang::getLanguageNames(TRUE))->label(Yii::t('language', 'Source language'));
    ActiveForm::end();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'raw',
                'filter' => Language::getCategories(),
                'attribute' => 'category',
                'filterInputOptions' => ['class' => 'form-control', 'id' => 'category'],
            ],
            [
                'format' => 'raw',
                'attribute' => 'message',
                'filterInputOptions' => ['class' => 'form-control', 'id' => 'message'],
                'label' => Yii::t('language', 'Source'),
                'content' => function ($data) {
                    return Html::textarea('LanguageSource[' . $data->id . ']', $data->source, ['class' => 'form-control source', 'readonly' => 'readonly']);
                },
            ],
            [
                'format' => 'raw',
                'attribute' => 'translation',
                'filterInputOptions' => ['class' => 'form-control', 'id' => 'translation'],
                'label' => Yii::t('language', 'Translation'),
                'content' => function ($data) {
                    return Html::textarea('LanguageTranslate[' . $data->id . ']', $data->translation, ['class' => 'form-control translation', 'data-id' => $data->id, 'tabindex' => $data->id]);
                },
            ],
            [
                'format' => 'raw',
                'attribute' => 'type',
                'filter' => ['scan' => 'Auto', 'manual' => 'Manual'],
                'label' => Yii::t('language', 'Type'),
                'content' => function ($data) {
                    return Html::dropDownList('LanguageType[' . $data->id . ']', $data->type, ['scan' => 'Auto', 'manual' => 'Manual'], ['class' => 'form-control type', 'data-id' => $data->id, 'tabindex' => $data->id]);
                },
            ],
            [
                'format' => 'raw',
                'attribute' => 'app',
                'filter' => ['all' => 'All', 'web' => 'Web', 'mobile' => 'Mobile'],
                'label' => Yii::t('language', 'App'),
                'content' => function ($data) {
                    return Html::dropDownList('LanguageApp[' . $data->id . ']', $data->app, ['all' => 'All', 'web' => 'Web', 'mobile' => 'Mobile'], ['class' => 'form-control app', 'data-id' => $data->id, 'tabindex' => $data->id]);
                },
            ],
            [
                'format' => 'raw',
                'attribute' => 'v_start',
                'options' => ['style' => 'width:60px;text-align:center;padding:0;'],
                'filterInputOptions' => ['class' => 'form-control', 'id' => 'vstart', 'style' => 'width:60px;text-align:center;padding:0;'],
                'label' => Yii::t('language', 'V Start'),
                'content' => function ($data) {
                    return Html::textInput('LanguageVStart' . $data->id . ']', $data->v_start, ['class' => 'form-control vstart', 'data-id' => $data->id, 'tabindex' => $data->id]);
                },
            ],
            [
                'format' => 'raw',
                'attribute' => 'v_end',
                'options' => ['style' => 'width:60px;text-align:center;padding:0;'],
                'filterInputOptions' => ['class' => 'form-control', 'id' => 'vend', 'style' => 'width:60px'],
                'label' => Yii::t('language', 'V End'),
                'content' => function ($data) {
                    return Html::textInput('LanguageVEnd' . $data->id . ']', $data->v_end, ['class' => 'form-control vend', 'data-id' => $data->id, 'tabindex' => $data->id]);
                },
            ],
            [
                'format' => 'raw',
                'label' => Yii::t('language', 'Action'),
                'content' => function ($data) {
                    return Html::button(Yii::t('language', 'Save'), ['type' => 'button', 'data-id' => $data->id, 'class' => 'btn btn-sm btn-fluid btn-success']);
                },
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>