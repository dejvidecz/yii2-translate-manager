<?php

namespace dlds\translatemanager\bundles;

/**
 * AllLanguageItem Plugin asset bundle
 *
 * @author Semenihin Maksim <semenihin.maksim@gmail.com>
 *
 * @since 1.0
 *
 * will include all active languages
 */
class AllLanguageItemPluginAsset extends LanguageItemPluginAsset
{
    public function init()
    {
        parent::init();
        $this->js = [];
        $this->sourcePath = \Yii::$app->getModule('translatemanager')->getLanguageItemsDirPath();

        $langs = \dlds\translatemanager\models\Language::findAll(['status' => \dlds\translatemanager\models\Language::STATUS_ACTIVE]);

        foreach ($langs as $key => $lang) {
            if (file_exists(\Yii::getAlias($this->sourcePath . $lang->language_id . '.js'))) {
                $this->js[] = $lang->language_id . '.js';
            }
        }
    }
}
