<?php

namespace dlds\translatemanager\controllers\actions;

use Yii;
use yii\web\Response;
use dlds\translatemanager\models\Language;

/**
 * Class that modifies the state of a language.
 *
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */
class ChangeStatusAction extends \yii\base\Action
{
    /**
     * Modifying the state of language.
     *
     * @return array
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $language = Language::findOne(Yii::$app->request->post('code', ''));
        if ($language !== null) {
            $language->status_translation = Yii::$app->request->post('status_translation', Language::STATUS_BETA);
            if ($language->validate()) {
                $language->save();
            }
        }

        return $language->getErrors();
    }
}
