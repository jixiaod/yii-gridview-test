<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

use app\models\Supplier;
use app\models\SupplierSearch;

/**
 * Supplier controller
 */
class SupplierController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'list' => ['get'],
                    'all-list' => ['get'],
                    'export' => ['get'],
                ],
            ],
        ];
    }

     /**
     * Implement a simple Supplier List Page
     *
     * @return mixed
     */
    public function actionList()
    {
        $supplier = new Supplier();
        $provider = $supplier->search(Yii::$app->request->get());

        return $this->render('supplier-list', [
            'model' => $supplier,
            'provider' => $provider,
        ]);
    }

    public function actionAllList()
    {
        $supplier = new Supplier();
        $supplierList = $supplier->searchAll(Yii::$app->request->get());
        $idz = array_column($supplierList, 'id');

        return json_encode($idz);
    }

    public function actionExport()
    {
        $get = Yii::$app->request->get();
        $idz = explode(',', $get['idz']);
        if (!isset($get['idz']) || empty($get['idz']) || empty($idz)) {
            return '传入参数不正确';
        }
        $data= Supplier::find()->where(['id' => $idz])->asArray()->all();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="supplier.csv"');
        header('Cache-Control: max-age=0');

        $fp = fopen('php://output', 'a');
        $headList = ["ID", "名称", "编码", "状态"];
        foreach ($headList as $key => $value) {
            $headlist[$key] = iconv('utf-8', 'gbk', $value);
        }

        fputcsv($fp, $headlist);

        foreach ($data as $k => $v) {
            $row = $data[$k];
            foreach ($row as $key => $value) {
                $row[$key] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $row);
        }

        exit;
    }
}

