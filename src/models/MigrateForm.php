<?php

namespace dlds\translatemanager\models;

use dlds\translatemanager\services\Migrator;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Import Form.
 *
 * @author rhertogh <>
 *
 * @since 1.5.0
 */
class MigrateForm extends Model
{
    /**
     * @var UploadedFile The file to import (json or xml)
     */
    public $language;

    /**
     * @var integer
     */
    private $_result = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language'], 'required'],
            [['language'], 'exist', 'targetClass' => '\dlds\translatemanager\models\Language', 'targetAttribute' => 'code', 'skipOnEmpty' => false],
        ];
    }

    /**
     * Import the uploaded file. Existing languages and translations will be updated, new ones will be created.
     * Source messages won't be updated, only created if they not exist.
     *
     * @return array
     *
     * @throws BadRequestHttpException
     * @throws Exception
     */
    public function migrate()
    {
        if (!$this->validate()) {
            return false;
        }

        $migrator = new Migrator();
        $this->_result = $migrator->run($this->language);
    }

    /**
     * All possible langauges
     * @return array
     */
    public static function languages()
    {
        return ArrayHelper::map(Language::getLanguageNames(true));
    }

    /**
     * Retrieves migration result
     * @return int
     */
    public function isProcessed()
    {
        return $this->_result !== null;
    }

    /**
     * Retrieves migration result
     * @return int
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * Retrieves missing translations count
     * @return int
     */
    public function getMissing()
    {
        $migrator = new Migrator();

        return $migrator->countMissingTranslations($this->language);
    }
}
