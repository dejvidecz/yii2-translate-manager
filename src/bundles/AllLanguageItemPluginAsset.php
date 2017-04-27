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

        $langs = \dlds\translatemanager\models\Language::findAll(['status_translation' => \dlds\translatemanager\models\Language::STATUS_ACTIVE]);

        foreach ($langs as $key => $lang) {
            if (file_exists(\Yii::getAlias($this->sourcePath . $lang->code . '.js'))) {
                $this->js[] = $lang->code . '.js';
            }
        }
    }
}
