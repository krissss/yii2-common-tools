<?php

namespace kriss\modules\auth\models;

use kriss\modules\auth\Module;
use yii\data\ActiveDataProvider;

class AuthRoleSearch extends AuthRole
{
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['name', 'description', 'operation_list'], 'safe'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuery() {
        return (Module::getAuthRoleClass())::find();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = $this->getQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'operation_list', $this->operation_list]);

        return $dataProvider;
    }
}
