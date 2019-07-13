<?php
namespace app\modules\post\models;

use Yii;
use app\components\behaviors\SluggableBehavior;


class Category extends \app\modules\post\models\base\Category
{
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
            ],
        ];
    }

    public function beforeValidate()
    {
        $this->created_by = Yii::$app->user->id;
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }
}