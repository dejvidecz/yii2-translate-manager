<?php

/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */

namespace dlds\translatemanager\models\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use dlds\translatemanager\models\Language;

/**
 * LanguageSearch represents the model behind the search form about `common\models\Language`.
 */
class LanguageSearch extends Language
{
    use SearchTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'language', 'code_country', 'title', 'title_ascii'], 'safe'],
            [['status_translation'], 'integer'],
        ];
    }

    /**
     * The name of the default scenario.
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param array $params Search conditions.
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Language::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status_translation' => $this->status_translation,
        ]);

        $query->andFilterWhere($this->createLikeExpression('code', $this->code))
            ->andFilterWhere($this->createLikeExpression('language', $this->language))
            ->andFilterWhere($this->createLikeExpression('code_country', $this->code_country))
            ->andFilterWhere($this->createLikeExpression('title', $this->title))
            ->andFilterWhere($this->createLikeExpression('title_ascii', $this->title_ascii));

        return $dataProvider;
    }
}
