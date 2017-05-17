<?php

namespace kriss\modules\auth\models;

use yii\data\ActiveDataProvider;

class AuthRoleSearch extends AuthRole
{
    public function rules() {
        return [
            [['id'], 'integer'],
            [['name', 'description', 'operation_list'], 'safe'],
        ];
    }

    public function search($params) {
        $query = AuthRole::find();

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
