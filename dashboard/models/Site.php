<?php

namespace dashboard\models;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property string $id
 * @property string $name
 * @property string $size
 * @property string $upload_date
 */
class Site extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%user}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['old_password','new_password','repeat_password'], 'required'],
            [['old_password','new_password','repeat_password'], 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [];
    }
}
