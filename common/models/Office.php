<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "office".
 *
 * @property int $row_id
 * @property string $office_code
 * @property int|null $create_at
 * @property int|null $update_at
 *
 * @property Rate[] $rates
 */
class Office extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'office';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['office_code'], 'required'],
            [['create_at', 'update_at'], 'integer'],
            [['office_code'], 'string', 'max' => 255],
            [['office_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'row_id' => 'Row ID',
            'office_code' => 'Office Code',
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
        return $this->hasMany(Rate::class, ['office_id' => 'row_id']);
    }

    /**
     * @param $code
     * @return false|int|string|null
     */
    public static function getIdByCode($code)
    {
        return self::find()
            ->select('row_id')
            ->where(['office_code' => $code])
            ->scalar();
    }
}