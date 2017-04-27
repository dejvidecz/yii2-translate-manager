<?php

namespace dlds\translatemanager\controllers\actions;

use Yii;
use yii\web\Response;
use dlds\translatemanager\services\Generator;
use dlds\translatemanager\models\LanguageTranslate;

/**
 * Class for saving translations.
 *
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */
class SaveAction extends \yii\base\Action
{
    /**
     * Saving translated language elements.
     *
     * @return array
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id', 0);
        $languageId = Yii::$app->request->post('code', Yii::$app->language);

        $languageTranslate = LanguageTranslate::findOne(['id' => $id, 'language' => $languageId]) ?:
            new LanguageTranslate(['id' => $id, 'language' => $languageId]);

        $languageTranslate->translation = Yii::$app->request->post('translation', '');
        if ($languageTranslate->validate() && $languageTranslate->save()) {
            $generator = new Generator($this->controller->module, $languageId);

            $generator->run();
        }

        return $languageTranslate->getErrors();
    }
}
