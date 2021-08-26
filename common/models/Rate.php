<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "rates".
 *
 * @property int $row_id
 * @property int $currency_id
 * @property float $buy
 * @property float $sell
 * @property int $begins_at
 * @property int $office_id
 * @property int|null $created_at
 *
 * @property Office $office
 */
class Rate extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['currency_id', 'buy', 'sell', 'begins_at'], 'required'],
            [['buy', 'sell'], 'number'],
            [['currency_id', 'begins_at', 'office_id', 'created_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'row_id' => 'Row ID',
            'currency_id' => 'Currency ID',
            'buy' => 'Buy',
            'sell' => 'Sell',
            'begins_at' => 'Begins At',
            'office_id' => 'Office ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Office]].
     *
     * @return ActiveQuery
     */
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['row_id' => 'office_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['row_id' => 'currency_id']);
    }

    /**
     * @param $officeId
     * @param $atDate
     * @return array|Rate[]|ActiveRecord[]
     */
    public static function getRatesByOffice($officeId, $atDate)
    {

        return self::find()->alias('rate')
            ->select([
                'currency' => 'cur.currency_code',
                'buy' => 'rate.buy',
                'sell' => 'rate.sell',
                'begins_at' => new Expression('DATE_FORMAT(FROM_UNIXTIME(begins_at), "%d.%m.%Y %H:%i:%s")'),
            ])
            ->leftJoin(['cur' => Currency::tableName()], [
                'cur.row_id' => new Expression('rate.currency_id'),
            ])
            ->where(new Expression('(currency_id, begins_at) in
                              (SELECT currency_id, max(begins_at)
                               FROM rates
                               WHERE
                                   begins_at <= :date 
                                   AND
                                   (office_id = :officeId OR office_id IS NULL)
                               GROUP BY currency_id)'), [':date' => $atDate, ':officeId' => $officeId]
            )
            ->asArray()->all();
    }
}
