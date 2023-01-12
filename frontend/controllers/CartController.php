<?php

namespace frontend\controllers;

use common\models\Product;
use common\models\Settings;
use Yii;
use yii\web\Controller;
use yii\base\Module;
use common\models\Order;
use yii\helpers\Url;


class CartController extends Controller
{
    /**
     * Class constructor
     *
     * @access public
     * @param string $id id of this controller
     * @param Module $module the module that this controller belongs to. This parameter
     * @return CartController
     */
    public function __construct($id, Module $module = null)
    {
        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);
        parent::__construct($id, $module);
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $newQuantities = Yii::$app->request->post('quantity', []);

        if (!empty($newQuantities) && is_array($newQuantities)) {
            foreach ( Yii::$app->cart->getPositions() as $position) {
                if (array_key_exists($position->getId(), $newQuantities)) {
                    Yii::$app->cart->update($position, $newQuantities[$position->getId()]);
                } else {
                    Yii::$app->cart->removeById($position->getId());
                }
            }

            $this->redirect(Url::to(['cart/index']));
        }


        return $this->render('index', []);
    }

    public function actionPut($id)
    {

        $quantity = Yii::$app->request->get('quantity', 1);

        $product = Product::findOne(['id' => $id]);

        if ($product) {
            Yii::$app->cart->put($product, $quantity);
        }

        if (!Yii::$app->request->getIsAjax()) {
            $this->redirect(['cart/index']);
        }
    }

    public function actionTotals()
    {
        return $this->renderPartial('totals');
    }


    public function actionRemove($id)
    {
        if (Yii::$app->cart->hasPosition($id)) {
            Yii::$app->cart->removeById($id);
        }
        $this->redirect(Url::to(['cart/index']));;
    }

    public function actionEmpty()
    {
        Yii::$app->cart->removeAll();

        $this->redirect(Url::to(['cart/index']));
    }

    public function actionOrder()
    {

        // Проверяем состав заказа, если заказ пуст, то прерываем отправку заказа и выкидываем ошибку
        if (Yii::$app->cart->getIsEmpty()) {
            Yii::$app->session->setFlash('danger', 'Ошибка при оформлении заказа - Ваша корзина пуста. Вероятнее всего, это произошло из-за того, что Вы пауза между заполнением корзины и оформлением заказа оказалась слишком велика и Ваша корзина была автоматически очищена. Приносим свои искренние извинения за доставленные неудобства.');
            $this->redirect(['cart/index']);
        }

        self::__setOrderStepParams('profile');

        $params = self::__getOrderStepParams('profile');

        return $this->render('order', array(
                'data' => (!empty($params) ? $params : null),
            )
        );

    }

    public function actionEnd()
    {

        // Проверяем состав заказа, если заказ пуст, то прерываем отправку заказа и выкидываем ошибку
        if (Yii::$app->cart->getIsEmpty()) {
            Yii::$app->session->setFlash('danger', 'Ошибка при оформлении заказа - Ваша корзина пуста. Вероятнее всего, это произошло из-за того, что Вы пауза между заполнением корзины и оформлением заказа оказалась слишком велика и Ваша корзина была автоматически очищена. Приносим свои искренние извинения за доставленные неудобства.');
            $this->redirect(['cart/index']);
        }

        self::__setOrderStepParams('profile');

        $profile = self::__getOrderStepParams('profile');

        if (!empty($profile) && is_array($profile) && 0 < count($profile)) {

            $orderContents = array(
                'client' => strip_tags((!empty($profile['lastname']) ? $profile['lastname'] . ' ' : '') . (!empty($profile['firstname']) ? $profile['firstname'] . ' ' : '') . (!empty($profile['fathersname']) ? $profile['fathersname'] : '')),
                'mobile_phone' => strip_tags(!empty($profile['mobile_code']) && !empty($profile['mobile_phone']) ? '+7(' . $profile['mobile_code'] . ')-' . $profile['mobile_phone'] : ''),
                'phone' => strip_tags(!empty($profile['phone']) ? $profile['phone'] : ''),
                'city' => strip_tags(!empty($profile['city']) ? $profile['city'] : ''),
                'address' => strip_tags(!empty($profile['address']) ? $profile['address'] : ''),
                'email' => strip_tags(!empty($profile['email']) ? $profile['email'] : ''),
                'comment' => strip_tags(!empty($profile['comment']) ? $profile['comment'] : ''),
                'organization' => strip_tags(!empty($profile['organization']) ? $profile['organization'] : ''),
                'inn' => strip_tags(!empty($profile['inn']) ? $profile['inn'] : ''),
                'ip' => Yii::$app->request->getUserIP(),
                'http_user_agent' => Yii::$app->request->userAgent,
                'items' => array(),
            );

            foreach (Yii::$app->cart->getPositions() as $position) {
                /** @var IECartPosition $position */

                $sum_price = $position->getCost();
                $price = $position->getPrice();

                $orderContents['items'][] = array(
                    'id' => $position->getId(),
                    'name' => $position->name,
                    'price' => $price,
                    'quantity' => $position->getQuantity(),
                    'amount' => $sum_price,
                );

            }

            $orderNumber = Order::add($orderContents);

            if (!empty($profile['email'])) {
                Order::sendEmail($profile['email'], $orderNumber, $orderContents );
            }

            $emailSelfTo = Settings::get('config_email');

            Order::sendEmail($emailSelfTo, $orderNumber, $orderContents );

            Yii::$app->request->enableCsrfValidation = false;

            return $this->render('end', [
                    'order' => $orderNumber,
                    'profile' => $profile,
                ]);
        }

    }

    private function __initOrderSession() {
        if (empty(Yii::$app->session['order_key'])) {
            Yii::$app->session->set('order_key', md5(time() . session_id()));
        }
        if (empty(Yii::$app->session['order'])) {
            Yii::$app->session->set('order', [Yii::$app->session['order_key'] => []]);
        }
    }

    private function __setOrderStepParams($stepName, $yandex = false) {

        self::__initOrderSession();

        $order = Yii::$app->session->get('order');

        $key = Yii::$app->session->get('order_key');

        $params = Yii::$app->request->post($stepName);

        if (!empty($params) && is_array($params)) {
            $order[$key][$stepName] = $params;
        }

        Yii::$app->session->set('order', $order);
    }

    private function __getOrderStepParams($stepName) {

        self::__initOrderSession();

        $order = Yii::$app->session->get('order');

        $key = Yii::$app->session->get('order_key');

        return (!empty($order[$key][$stepName]) ? $order[$key][$stepName] : false);
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = (!in_array($action->id, ['order']));
        return parent::beforeAction($action);
    }

}
