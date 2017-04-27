<?php

namespace dlds\translatemanager\controllers\actions;

use dlds\translatemanager\models\MigrateForm;
use yii\data\ArrayDataProvider;
use dlds\translatemanager\services\Scanner;
use dlds\translatemanager\models\LanguageSource;
use dlds\translatemanager\bundles\ScanPluginAsset;

/**
 * Class for detecting language elements.
 *
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */
class MigrateAction extends \yii\base\Action
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        ScanPluginAsset::register($this->controller->view);
        parent::init();
    }

    /**
     * Detecting new language elements.
     *
     * @return string
     */
    public function run()
    {
        $model = new MigrateForm();

        if ($model->load(\Yii::$app->request->post())) {
            $model->migrate();
        }

        return $this->controller->render('migrate', [
            'model' => $model,
        ]);
    }

    /**
     * Returns an ArrayDataProvider consisting of language elements.
     *
     * @param array $languageSourceIds
     *
     * @return ArrayDataProvider
     */
    private function _createLanguageSourceDataProvider($languageSourceIds)
    {
        $languageSources = LanguageSource::find()->with('languageTranslates')->where(['id' => $languageSourceIds])->all();

        $data = [];
        foreach ($languageSources as $languageSource) {
            $languages = [];
            if ($languageSource->languageTranslates) {
                foreach ($languageSource->languageTranslates as $languageTranslate) {
                    $languages[] = $languageTranslate->language;
                }
            }

            $data[] = [
                'id' => $languageSource->id,
                'category' => $languageSource->category,
                'message' => $languageSource->message,
                'languages' => implode(', ', $languages),
            ];
        }

        return new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);
    }
}
