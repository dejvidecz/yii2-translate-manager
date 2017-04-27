<?php
/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.3
 */

/* @var $this yii\web\View */
/* @var $model dlds\translatemanager\models\Language */

$this->title = Yii::t('language', 'Update {modelClass}: ', [
    'modelClass' => 'Language',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('language', 'Languages'), 'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->code]];
$this->params['breadcrumbs'][] = Yii::t('language', 'Update');
?>
<div class="language-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>