<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "url".
 *
 * @property int $id
 * @property string $original_url
 * @property string $short_code
 * @property string|null $created_at
 * @property int|null $clicks
 */
class Url extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'url';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clicks'], 'default', 'value' => 0],
            [['original_url', 'short_code'], 'required'],
            [['original_url'], 'string'],
            [['created_at'], 'safe'],
            [['clicks'], 'integer'],
            [['short_code'], 'string', 'max' => 16],
            [['short_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'original_url' => 'Original Url',
            'short_code' => 'Short Code',
            'created_at' => 'Created At',
            'clicks' => 'Clicks',
        ];
    }

}
