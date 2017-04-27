<?php

namespace dlds\translatemanager\services;

use dlds\translatemanager\models\LanguageTranslate;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use dlds\translatemanager\models\LanguageSource;
use yii\i18n\I18N;

/**
 * Scanner class for scanning project, detecting new language elements
 *
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */
class Migrator
{
    /**
     * @var array for storing missing translations
     */
    private $_missingTranslations = [];

    /**
     * @var array for storing migrated translations
     */
    private $_readyToMigrateTranslations = [];

    /**
     * Scanning project for text not stored in database.
     *
     * @return int The number of new language elements.
     */
    public function run($language)
    {
        $migratorLimit = Yii::$app->getModule('translatemanager')->migratorLimit;

        $this->_initMissingTranslations($language, $migratorLimit);

        $this->_translateMissings($language);

        $command = \Yii::$app->db->createCommand()->batchInsert(LanguageTranslate::tableName(), LanguageTranslate::getTableSchema()->columnNames, $this->_readyToMigrateTranslations);

        return $command->execute();
    }

    /**
     * Retrieves missing translations count
     * @return int
     */
    public function countMissingTranslations($language)
    {
        return static::qyMissings($language, false)->count();
    }

    /**
     * Retrieves missing translations query
     * @param $language
     * @param $limit
     * @return $this
     */
    protected static function qyMissings($language, $limit)
    {
        $onCondition = sprintf('(%s.id = %s.id AND %s.language = "%s")', LanguageSource::tableName(), LanguageTranslate::tableName(), LanguageTranslate::tableName(), $language);

        return LanguageSource::find()
            ->leftJoin(LanguageTranslate::tableName(), $onCondition)
            ->where(['translation' => null])
            ->limit($limit);
    }

    /**
     * Finds all missing tranlsations for current language
     */
    private function _initMissingTranslations($language, $limit)
    {
        $query = static::qyMissings($language, $limit);

        $this->_missingTranslations = $query->all();
    }

    /**
     * Translate missings
     */
    private function _translateMissings($language)
    {
        $migratorRules = Yii::$app->getModule('translatemanager')->migratorRules;

        $i18n = new I18N(['translations' => ArrayHelper::getValue($migratorRules, 'translations', [])]);

        $lngAlias = ArrayHelper::getValue($migratorRules, sprintf('languages.%s', $language), $language);

        foreach ($this->_missingTranslations as $source) {

            $translation = $i18n->translate($source->category, $source->message, [], $lngAlias);

            if ($translation == $source->message) {
                $translation = $i18n->translate('global', $source->message, [], $lngAlias);
            }

            if ($translation == $source->message) {
                $translation = sprintf('@%s', $translation);
            }

            $this->_readyToMigrateTranslations[] = [
                'id' => $source->id,
                'language' => $language,
                'translation' => $translation,
            ];
        }
    }
}
