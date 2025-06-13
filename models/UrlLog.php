<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "url_log".
 *
 * @property int $id
 * @property int $url_id
 * @property string|null $ip_address
 * @property string|null $visited_at
 *
 * @property Url $url
 */
class UrlLog extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'url_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip_address'], 'default', 'value' => null],
            [['url_id'], 'required'],
            [['url_id'], 'integer'],
            [['visited_at'], 'safe'],
            [['ip_address'], 'string', 'max' => 45],
            [['url_id'], 'exist', 'skipOnError' => true, 'targetClass' => Url::class, 'targetAttribute' => ['url_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url_id' => 'Url ID',
            'ip_address' => 'Ip Address',
            'visited_at' => 'Visited At',
        ];
    }

    /**
     * Gets query for [[Url]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUrl()
    {
        return $this->hasOne(Url::class, ['id' => 'url_id']);
    }

}
