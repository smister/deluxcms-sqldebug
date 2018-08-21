<?php

namespace deluxcms\sqldebug\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deluxcms\sqldebug\models\DebugApi;

/**
 * CrontabsSearch represents the model behind the search form about `\deluxcms\crontab\models\Crontabs`.
 */
class DebugApiSearch extends DebugApi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['api', 'duration_time'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DebugApi::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'api', $this->api])
            ->andFilterWhere(['>=', 'duration_time', $this->duration_time])->orderBy('id DESC');

        return $dataProvider;
    }
}
