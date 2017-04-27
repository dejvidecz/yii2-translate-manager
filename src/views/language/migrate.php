<?php

use dlds\translatemanager\models\ImportForm;
use dlds\translatemanager\models\Language;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \dlds\translatemanager\models\MigrateForm */

$this->title = Yii::t('language', 'Migrate');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if ($model->isProcessed() && $model->getResult()): ?>

    <div id="w2-info" class="alert-success alert fade in">
        <?= Yii::t('language', '{n, plural, =0{No new entries} =1{One new entry} other{# new entries}} were added!', ['n' => $model->getResult()]) ?>
    </div>

<?php endif; ?>

<?php if ($model->isProcessed() && $model->getMissing()): ?>

    <div id="w2-danger" class="alert-danger alert fade in">
        <?= Yii::t('language', '{n, plural, =0{No entries} =1{One entry} other{# entries}} missing.', ['n' => $model->getMissing()]) ?>
    </div>

<?php endif; ?>

<?php if ($model->isProcessed() && !$model->getResult()): ?>

    <div id="w2-danger" class="alert-info alert fade in">
        <?= Yii::t('language', 'There are no translations to migrate') ?>
    </div>

<?php endif; ?>

<div class="language-migrate col-sm-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'language')->dropDownList(Language::getLanguageNames(true), ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('language', 'Migrate'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>