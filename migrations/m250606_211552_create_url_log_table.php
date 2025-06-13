<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%url_log}}`.
 */
class m250606_211552_create_url_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%url_log}}', [
            'id' => $this->primaryKey(),
            'url_id' => $this->integer()->notNull(),
            'ip_address' => $this->string(45),
            'visited_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-url_log-url_id',
            '{{%url_log}}',
            'url_id',
            '{{%url}}',
            'id',
            'CASCADE'
        );
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%url_log}}');
    }
}
