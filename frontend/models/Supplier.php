<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class Supplier extends ActiveRecord
{
    public static function tableName() {
        return 'supplier';
    }

    public function rules()
    {
        return [
            [['id'], 'string'],
            [['name', 'code'], 'trim'],
            [['t_status'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = self::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'p',
                'pageSizeParam' => 'pageSize',
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
                'attributes' => [
                    'id', 'name', 'code', 't_status'
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $provider;
        } 
        // fix id传空bug
        if (!empty($this->id)) {
            $query->andFilterWhere([$this->id, 'id', 10]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'code', $this->code])
              ->andFilterWhere(['t_status' => $this->t_status]);

        return $provider;
    }

    public function searchAll($params)
    {
        $query = self::find()->select('id');

        if (!($this->load($params) && $this->validate())) {
            return $query->asArray()->all();
        } 
        // fix id传空bug
        if (!empty($this->id)) {
            $query->andFilterWhere([$this->id, 'id', 10]);
        }

        if (!empty($this->name)) {
            $query->andFilterWhere(['like', 'name', $this->name]);
        }
        if (!empty($this->code)) {
            $query->andFilterWhere(['like', 'code', $this->code]);
        } 
        if (!empty($this->t_status)) {
            $query->andFilterWhere(['t_status' => $this->t_status]);
        }

        //var_dump($query->createCommand()->getRawSql());
        return $query->asArray()->all();
    }

}
