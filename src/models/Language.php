<?php

/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */

namespace dlds\translatemanager\models;

use Yii;

/**
 * This is the model class for table "language".
 *
 * @property string $code
 * @property string $code_lng
 * @property string $code_country
 * @property string $title
 * @property string $title_ascii
 * @property int $status_translation
 * @property LanguageTranslate $languageTranslate
 * @property LanguageSource[] $languageSources
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * Status of inactive language.
     */
    const STATUS_INACTIVE = 0;

    /**
     * Status of active language.
     */
    const STATUS_ACTIVE = 1;

    /**
     * Status of ‘beta’ language.
     */
    const STATUS_BETA = 2;

    /**
     * Array containing possible states.
     *
     * @var array
     * @translate
     */
    private static $_CONDITIONS = [
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_BETA => 'Beta',
    ];

    /**
     * @inheritdoc
     */
    public static function getDb()
    {
        return Yii::$app->get(Yii::$app->getModule('translatemanager')->connection);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->getModule('translatemanager') ?
            Yii::$app->getModule('translatemanager')->languageTable : '{{%language}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'code_lng', 'code_country', 'title', 'title_ascii', 'status_translation'], 'required'],
            [['code'], 'string', 'max' => 5],
            [['code'], 'unique'],
            [['code'], 'match', 'pattern' => '/^([a-z]{2}[_-][A-Z]{2}|[a-z]{2})$/'],
            [['code_lng', 'code_country'], 'string', 'max' => 2],
            [['code_lng', 'code_country'], 'match', 'pattern' => '/^[a-z]{2}$/i'],
            [['title', 'title_ascii'], 'string', 'max' => 32],
            [['status_translation'], 'integer'],
            [['status_translation'], 'in', 'range' => array_keys(self::$_CONDITIONS)],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('model', 'Language ID'),
            'code_lng' => Yii::t('model', 'Language'),
            'code_country' => Yii::t('model', 'Country'),
            'title' => Yii::t('model', 'Name'),
            'title_ascii' => Yii::t('model', 'Name Ascii'),
            'status_translation' => Yii::t('model', 'Status'),
        ];
    }

    /**
     * Returns the list of languages stored in the database in an array.
     *
     * @param bool $active True/False according to the status of the language.
     *
     * @return array
     *
     * @deprecated since version 1.5.2
     */
    public static function getLanguageNames($active = false)
    {
        $languageNames = [];
        foreach (self::getLanguages($active, true) as $language) {
            $languageNames[$language['code']] = $language['title'];
        }

        return $languageNames;
    }

    /**
     * Returns language objects.
     *
     * @param bool $active True/False according to the status of the language.
     * @param bool $asArray Return the languages as language object or as 'flat' array
     *
     * @return Language|array
     *
     * @deprecated since version 1.5.2
     */
    public static function getLanguages($active = true, $asArray = false)
    {
        if ($active) {
            return self::find()->where(['status_translation' => static::STATUS_ACTIVE])->asArray($asArray)->all();
        } else {
            return self::find()->asArray($asArray)->all();
        }
    }

    /**
     * Returns the state of the language (Active, Inactive or Beta) in the current language.
     *
     * @return string
     */
    public function getStatusName()
    {
        return Yii::t('array', self::$_CONDITIONS[$this->status_translation]);
    }

    /**
     * Returns the names of possible states in an associative array.
     *
     * @return array
     */
    public static function getStatusNames()
    {
        return \dlds\translatemanager\helpers\Language::a(self::$_CONDITIONS);
    }

    /**
     * Returns the completness of a given translation (language).
     *
     * @return int
     */
    public function getGridStatistic()
    {
        static $statistics;
        if (!$statistics) {
            $count = LanguageSource::find()->count();
            if ($count == 0) {
                return 0;
            }

            $languageTranslates = LanguageTranslate::find()
                ->select(['language', 'COUNT(*) AS cnt'])
                ->andWhere('translation IS NOT NULL')
                ->groupBy(['language'])
                ->all();

            foreach ($languageTranslates as $languageTranslate) {
                $statistics[$languageTranslate->language] = floor(($languageTranslate->cnt / $count) * 100);
            }
        }

        return isset($statistics[$this->code]) ? $statistics[$this->code] : 0;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageTranslate()
    {
        return $this->hasOne(LanguageTranslate::className(), ['language' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     *
     * @deprecated since version 1.4.5
     */
    public function getIds()
    {
        return $this->hasMany(LanguageSource::className(), ['id' => 'id'])
            ->viaTable(LanguageTranslate::tableName(), ['language' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageSources()
    {
        return $this->hasMany(LanguageSource::className(), ['id' => 'id'])
            ->viaTable(LanguageTranslate::tableName(), ['language' => 'code']);
    }
}
