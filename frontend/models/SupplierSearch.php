<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SupplierSearch extends Supplier
{
    public function rules()
    {
        // 只有在 rules() 函数中声明的字段才可以搜索
        return [
            [['id'], 'integer'],
            [['name'], 'string'],
            [['code'], 'string'],
        ];
    }

    public function scenarios()
    {
        // 旁路在父类中实现的 scenarios() 函数
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Supplier::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // 从参数的数据中加载过滤条件，并验证
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // 增加过滤条件来调整查询对象
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->title])
              ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}

