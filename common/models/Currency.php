<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "currency".
 *
 * @property int $row_id
 * @property string $currency_code
 * @property int|null $create_at
 * @property int|null $update_at
 *
 * @property Rate[] $rates
 */
class Currency extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['currency_code'], 'required'],
            [['create_at', 'update_at'], 'integer'],
            [['currency_code'], 'string', 'max' => 255],
            [['currency_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'row_id' => 'Row ID',
            'currency_code' => 'Currency Code',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * Gets query for [[Rates]].
     *
     * @return ActiveQuery
     */
    public function getRates()
    {
        return $this->hasMany(Rate::class, ['currency_id' => 'row_id']);
    }

    /**
     * @param $code
     * @return false|int|string|null
     */
    public static function getIdByCode($code)
    {
        return self::find()
            ->select('row_id')
            ->where(['currency_code' => $code])
            ->scalar();
    }

}