<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rates}}`.
 */
class m210824_211426_create_rates_table extends Migration
{
    private $currency = 'currency';
    private $office = 'office';
    private $rates = 'rates';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (Yii::$app->db->getTableSchema($this->currency) === null) {
            $this->createTable($this->currency, [
                'row_id' => $this->primaryKey(),
                'currency_code' => $this->string()->notNull()->unique(),
                'created_at' => $this->integer(10),
                'updated_at' => $this->integer(10),
            ]);
        }

        if (Yii::$app->db->getTableSchema($this->office) === null) {
            $this->createTable($this->office, [
                'row_id' => $this->primaryKey(),
                'office_code' => $this->string()->notNull()->unique(),
                'created_at' => $this->integer(10),
                'updated_at' => $this->integer(10),
            ]);
        }

        if (Yii::$app->db->getTableSchema($this->rates) === null) {
            $this->createTable($this->rates, [
                'row_id' => $this->primaryKey(),
                'currency_id' => $this->integer()->notNull(),
                'buy' => $this->double()->notNull(),
                'sell' =>  $this->double()->notNull(),
                'begins_at' =>  $this->integer(10)->notNull(),
                'office_id' => $this->integer(),
                'created_at' => $this->integer(10),
            ]);

            $this->addForeignKey('FK_' . $this->rates . '_' . $this->currency, $this->rates, 'currency_id', $this->currency, 'row_id', 'RESTRICT', 'CASCADE');
            $this->addForeignKey('FK_' . $this->rates . '_' . $this->office, $this->rates, 'office_id', $this->office, 'row_id', 'RESTRICT', 'CASCADE');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (Yii::$app->db->getTableSchema($this->rates) !== null) {
            $this->dropTable($this->rates);
        }

        if (Yii::$app->db->getTableSchema($this->office) !== null) {
            $this->dropTable($this->office);
        }

        if (Yii::$app->db->getTableSchema($this->currency) !== null) {
            $this->dropTable($this->office);
        }
    }
}
