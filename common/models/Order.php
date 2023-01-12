<?php
namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\log;
use yii\log\Logger;
use yii\db\ActiveRecord;
use \DateTime;

/**
 * @property int $id
 * @property string $client
 * @property string $email
 * @property string $mobile_phone
 * @property string $phone
 * @property string $city
 * @property string $address
 * @property string $comment
 * @property string $organization
 * @property string $inn
 * @property float $total
 * @property string $date
 * @property string $ip
 * @property string $http_user_agent
 */


class Order extends ActiveRecord
{

    public $status;

    const TABLE_NAME = 'orders';

    public static function tableName()
    {
        return self::TABLE_NAME;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['total'], 'number'],
            [['client','mobile_phone','phone', 'email', 'city', 'address', 'date', 'organization', 'inn'], 'string'],
            ['id, client, mobile_phone, phone, email, city, address, date, total, organization, inn', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client' => 'ФИО',
            'email' => 'Email',
            'mobile_phone' => 'Мобильный',
            'phone' => 'Телефон',
            'city' => 'Город',
            'address' => 'Адрес',
            'organization' => 'Организация',
            'inn' => 'ИНН',
            'total' => 'Итого',
            'comment' => 'Коментарий',
            'date' => 'Дата',
            'status' => 'Статус',
        ];
    }

    /**
     * Получение данных таблицы OrdersItems
     */

    public function getProducts()
    {
        return $this->hasMany(OrdersItems::class, ['id_order' => 'id']);
    }


    /**
     * Получение данных таблицы OrdersStatus
     */

    public function getStatuses()
    {
        return $this->hasMany(OrdersStatus::class, ['id_order' => 'id'])->orderBy(['date' => SORT_DESC]);
    }


    /**
     * Method for adding an order
     *
     * @static
     * @access public
     * @param array $contents,
     * @return int order's id
     */
    public static function add($contents)
    {
        $order = new self;

        $order->date = date('Y-m-d H:i:s');

        $order->client = $contents['client'];
        $order->mobile_phone = $contents['mobile_phone'];
        $order->phone = $contents['phone'];
        $order->city = $contents['city'];
        $order->address = $contents['address'];
        $order->email = $contents['email'];
        $order->comment = $contents['comment'];
        $order->organization = $contents['organization'];
        $order->inn = $contents['inn'];
        $order->comment = $contents['comment'];

        $order->ip = $contents['ip'];
        $order->http_user_agent = $contents['http_user_agent'];

        $total = 0;

        $order->total = $total;

        $order->save();

        if (!empty($contents['items']) && is_array($contents['items'])) {

            foreach ($contents['items'] as $item) {

                $orderItem = new OrdersItems();
                $orderItem->id_order = $order->id;
                $orderItem->id_item = $item['id'];
                $orderItem->name = $item['name'];
                $orderItem->price = $item['price'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->amount = $item['amount'];

                $orderItem->save();

                $total += $item['amount'];
            }
        }

        $order->total = $total;

        $order->save();

        $orderStatus = new OrdersStatus();
        $orderStatus->id_order = $order->id;
        $orderStatus->status = OrdersStatus::STATUS_NEW;
        $orderStatus->date = date('Y-m-d H:i:s');
        $orderStatus->save();

        return $order->id;
    }


    public static function sendEmail($emailTo, $orderNumber, $orderContents)
    {

        $emailFrom = Settings::get('config_email');

        Yii::$app->mailer->htmlLayout = "@common/mail/layouts/html";

        Yii::$app->mailer
            ->compose('@common/mail/order', ['orderNumber' => $orderNumber, 'orderContents' => $orderContents])
            ->setFrom([$emailFrom => Yii::$app->params['nameRu']])
            ->setTo($emailTo)
            ->setSubject('Ваш заказ № ' . $orderNumber .  ' принят на сайте ' . Yii::$app->params['nameRu'] )
            ->send();
    }


    public function search()
    {

        $query = Order::find();

        $this->load(Yii::$app->request->queryParams);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'client', (string)$this->client]);
        $query->andFilterWhere(['like', 'mobile_phone', (string)$this->mobile_phone]);
        $query->andFilterWhere(['like', 'email', (string)$this->email]);
        $query->andFilterWhere(['like', 'phone', (string)$this->phone]);
        $query->andFilterWhere(['like', 'city', (string)$this->city]);
        $query->andFilterWhere(['like', 'address', (string)$this->address]);
        $query->andFilterWhere(['like', 'organization', (string)$this->organization]);
        $query->andFilterWhere(['like', 'inn', (string)$this->inn]);

        if (!empty($this->total)) {
            $query->andFilterWhere(['total' => (float)$this->total]);
        }

        $query->orderBy(['id' => SORT_DESC]);

        return $dataProvider;
    }

}
