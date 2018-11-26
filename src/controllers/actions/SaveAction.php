<?php

namespace dlds\translatemanager\controllers\actions;

use dlds\translatemanager\models\LanguageSource;
use dlds\translatemanager\models\LanguageTranslate;
use dlds\translatemanager\services\Generator;
use Yii;
use yii\web\Response;

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

        $lSource = LanguageSource::findOne($id);
        $type = Yii::$app->request->post('type', NULL);

        if (!is_null($type)) {
            $lSource->type = $type;
        }

        $app = Yii::$app->request->post('app', NULL);
        if (!is_null($app)) {
            $lSource->app = $app;
        }
        $vstart = Yii::$app->request->post('v_start', NULL);
        if (!is_null($vstart)) {
            if (!$vstart) {
                $vstart = NULL;
            }
            $lSource->v_start = $vstart;
        }

        $vend = Yii::$app->request->post('v_end', NULL);
        if (!is_null($vend)) {
            if (!$vend) {
                $vend = NULL;
            }
            $lSource->v_end = $vend;
        }

        $lSource->save();

        if ($languageTranslate->validate() && $languageTranslate->save()) {
            $generator = new Generator($this->controller->module, $languageId);

            $generator->run();
        }

        return $languageTranslate->getErrors();
    }
}
