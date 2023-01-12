<?php

namespace backend\controllers;


use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Manufacturer;
use common\models\Order;
use common\models\OrdersStatus;
use common\models\Settings;

/**
 * OrderController implements the CRUD actions for order model.
 */
class OrderController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Lists all Brand models.
     * @return mixed
     */
    public function actionIndex()
    {

        $filterModel = new Order();

        return $this->render('index',  [
            'dataProvider' => $filterModel->search(),
            'filterModel' => $filterModel,
        ]);

    }

    public function actionForm($id) {

        $model = Order::find()->with(['products'])->where(['id' => $id])->one();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['order/index']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model
            ]);
        } else {
            return $this->render('_form', [
                'model' => $model
            ]);
        }
    }

    public function actionStatus($id) {

        $modelOrder = Order::find()->with(['products'])->where(['id' => $id])->one();

        if (Yii::$app->request->isPost) {

            $order_data = Yii::$app->request->post('Order');

            if (!empty($order_data['status'])) {

                $orderStatus = new OrdersStatus();
                $orderStatus->id_order = $id;
                $orderStatus->status = $order_data['status'];

                $orderStatus->date = date('Y-m-d H:i:s');

                if ($orderStatus->save(false)) {

                    $emailTo = $modelOrder->email;
                    $emailFrom = Settings::get('config_email');

                    $subject = html_entity_decode(Settings::get('config_store') . '. Изменен статус заказа ' . $id . '.');

                    $html = '<div>Изменен статус заказа <b>' . $id . '</b>.</div><div>Новый статус: <b>' . OrdersStatus::getLabel($order_data['status']) . '</b></div>';

                    if (!empty($modelOrder->products) && is_array($modelOrder->products)) {

                        $html .= '<h2 style="text-align: center;">Товары</h2>';

                        $html .= '<table class="order-products">';

                        $html .= '<tr><th>Название</th><th>Модель</th><th>Количество</th></tr>';

                        foreach ($modelOrder->products as $product) {

                            $html .= '<tr>';

                            $html .= '<td>' . $product->name . '</td>';
                            $html .= '<td>' . $product->quantity . '</td>';

                            $html .= '</tr>';


                        }

                        $html .= '</table>';

                    }
                    
                    Yii::$app->mailer
                        ->compose()
                        ->setFrom($emailFrom)
                        ->setTo($emailTo)
                        ->setHtmlBody($html)
                        ->setSubject($subject)
                        ->send();

                }


            }

        }

        $orderHistory = OrdersStatus::find()->where(['id_order' => $id])->orderBy(['date' => SORT_DESC])->all();

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form_status', [
                'modelOrder' => $modelOrder,
                'orderHistory' => $orderHistory,
                'statuses' => OrdersStatus::$statuses
            ]);
        } else {
            return $this->render('_form_status', [
                'modelOrder' => $modelOrder,
                'orderHistory' => $orderHistory,
                'statuses' => OrdersStatus::$statuses
            ]);
        }
    }


}
